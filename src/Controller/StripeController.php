<?php


namespace App\Controller;


use App\Entity\UserCommands;
use App\Repository\UserCommandsRepository;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Stripe\Exception\ApiErrorException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StripeController extends AbstractController
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/paiement", name="paiement")
     * @Security("is_granted('ROLE_USER')")
     * @param UserCommandsRepository $userCommandsRepository
     * @return Response
     * @throws ApiErrorException
     * @throws NonUniqueResultException
     */
    public function paymentWithCheckout(UserCommandsRepository $userCommandsRepository): Response
    {
        $user = $this->getUser();

        //mettre la commande sur validate
        $userCommand = $userCommandsRepository->findByUserNoValidateNoPaid($user);
        $userCommand->setValidate(true); //A REMETTRE une fois les tests terminés
        $this->em->flush();

        //a enlever une fois les tests terminés
        //$userCommand = $userCommandsRepository->findByUserValidateNoPaid($user);

        //récupérer le tableau de la userCommand pour l'injecter dans la session stripe
        //dump($userCommand);
        //dump($userCommand->getProducts());//mettre les produits dans le tableau line_items

        $products = $userCommand->getProducts();
        $lineItems = [];

        foreach ($products['product'] as $product){
            dump($product);
            //manipuler le prix pour que ça corresponde avec stripe
            /*$decimals = explode(".", $product['priceTTC']);
            dump($decimals[0]);
            dump($decimals[1]);
            $finalPrice = (int)$decimals[0] . ',' . (int)$decimals[1];*/
            if($product['priceTTCWithPromo'] === 0 || empty($product['priceTTCWithPromo'])){
                $finalPrice = round($product['priceTTC'] * 100, 0);
            }else{
                $finalPrice = round($product['priceTTCWithPromo'] * 100, 0);
            }
            dump((int)$finalPrice);

            //pour le prix, il faut rajouter le prix avec la promo !!!!

            $lineItems[] = array(
                'name' => $product['reference'],
                'amount' => (int)$finalPrice, //probléme avec les entiers faire une fonction pour stripe
                'currency' => 'eur',
                'quantity' => $product['quantity'],
            );
        }

        dump($lineItems);
        //die();

        $publicKEY = $_ENV['STRIPE_PUBLIC_KEY'];
        $secretKEY = $_ENV['STRIPE_SECRET_KEY'];

        \Stripe\Stripe::setApiKey($secretKEY);

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
//            'line_items' => [
//                [
//                    'name' => 'book',
//                    'amount' => 700,
//                    'currency' => 'eur',
//                    'quantity' => 1,
//                ],
//                [
//                    'name' => 'dvd',
//                    'amount' => 500,
//                    'currency' => 'eur',
//                    'quantity' => 2,
//                ]
//            ],
            'mode' => 'payment',
            'success_url' => 'http://localhost:8000/success?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => 'http://localhost:8000/cancel',
        ]);

        //dump($session);
        //die();

        return $this->render('payment/purchase-sca.html.twig', [
            'id' => $session->id,
            'pk' => $publicKEY
        ]);

    }

    /**
     * @Route("/success", name="paiement_success")
     * @Security("is_granted('ROLE_USER')")
     * @param CartService $cartService
     * @param UserCommandsRepository $userCommandsRepository
     * @return Response
     * @throws NonUniqueResultException
     */
    public function success(CartService $cartService, UserCommandsRepository $userCommandsRepository): Response
    {
        //vider la session du le panier et de la commande
        $cartService->emptyCartAndCommand();

        //set paid to true in userCommand
        $user = $this->getUser();
        $userCommand = $userCommandsRepository->findByUserValidateNoPaid($user);
        $userCommand->setPaid(true);
        $this->em->flush();

        $this->addFlash(
            'success',
            "Votre commande a bien été validée."
        );

        return $this->render('payment/purchase-success.html.twig', []);
    }

    /**
     * @Route("/cancel", name="paiement_cancel")
     * @param UserCommandsRepository $userCommandsRepository
     * @return Response
     * @throws NonUniqueResultException
     */
    public function cancel(UserCommandsRepository $userCommandsRepository): Response
    {
        $user = $this->getUser();

        //remets le validate sur false
        $userCommand = $userCommandsRepository->findByUserValidateNoPaid($user);
        $userCommand->setValidate(false);
        $this->em->flush();

        $this->addFlash(
            'danger',
            "Vous avez annulé votre commande. Vous pouvez modifier votre panier si besoin."
        );

        return $this->redirectToRoute('cart');
    }

}
