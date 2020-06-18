<?php


namespace App\Controller;


use App\Entity\UserCommands;
use App\Repository\LostCartRepository;
use App\Repository\ProductRepository;
use App\Repository\UserAddressRepository;
use App\Repository\UserCommandsRepository;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Stripe\Exception\ApiErrorException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
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
     * @param UserAddressRepository $userAddressRepository
     * @param ProductRepository $productRepository
     * @return Response
     * @throws ApiErrorException
     * @throws NonUniqueResultException
     */
    public function paymentWithCheckout(
        UserCommandsRepository $userCommandsRepository,
        UserAddressRepository $userAddressRepository,
        ProductRepository $productRepository
    ): Response
    {
        $user = $this->getUser();

        //vérifier que l'utilisateur a bien defini ses 2 adresses
        $oldCommandAddresses = $userAddressRepository->findByUserAndCommandWithArray($user);
        if($oldCommandAddresses === null){
            $this->addFlash(
                'danger',
                "Vous n'avez pas configurer votre adresse de livraison."
            );
            return $this->redirectToRoute('validation');
        }


        $oldBillingAddresses = $userAddressRepository->findByUserAndBillingWithArray($user);
        if($oldBillingAddresses === null){
            $this->addFlash(
                'danger',
                "Vous n'avez pas configurer votre adresse de facturation."
            );
            return $this->redirectToRoute('validation');
        }


        //mettre la commande sur validate
        $userCommand = $userCommandsRepository->findByUserNoValidateNoPaid($user);
        $userCommand->setValidate(true); //A REMETTRE une fois les tests terminés
        $this->em->flush();

        //a enlever une fois les tests terminés
        //$userCommand = $userCommandsRepository->findByUserValidateNoPaid($user);


        $products = $userCommand->getProducts();
        $lineItems = [];

        foreach ($products['product'] as $product){
            if($product['priceTTCWithPromo'] === 0 || empty($product['priceTTCWithPromo'])){
                $finalPrice = round($product['priceTTC'] * 100, 0);
            }else{
                $finalPrice = round($product['priceTTCWithPromo'] * 100, 0);
            }

            //recupére le produit dans productRepository pour l'image
            $productImage = $productRepository->findByName($product['reference'])->getProductImage();
            if($productImage !== null){
                $urlImage = 'http://localhost:8000/produits/image' . $productImage->getUrlImage();
            }else{
                $urlImage = 'http://localhost:8000/assets/produits/default.png';
            }
//            dump($productImage->getProductImage()->getUrlImage());

            $lineItems[] = array(
                'name' => $product['reference'],
                'amount' => (int)$finalPrice, //probléme avec les entiers faire une fonction pour stripe
                'currency' => 'eur',
                'quantity' => $product['quantity'],
                'images' => [$urlImage],
            );
        }

        $publicKEY = $_ENV['STRIPE_PUBLIC_KEY'];
        $secretKEY = $_ENV['STRIPE_SECRET_KEY'];

        \Stripe\Stripe::setApiKey($secretKEY);

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => 'http://localhost:8000/success?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => 'http://localhost:8000/cancel',
        ]);

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
     * @param LostCartRepository $lostCartRepository
     * @param SessionInterface $session
     * @param ProductRepository $productRepository
     * @return Response
     * @throws NonUniqueResultException
     */
    public function success(
        CartService $cartService,
        UserCommandsRepository $userCommandsRepository,
        LostCartRepository $lostCartRepository,
        SessionInterface $session,
        ProductRepository $productRepository
    ): Response
    {
        $user = $this->getUser();

        //créer la facture au format pdf

        //enlever du stock en recupérant la commande sur la session
        if($session->has('command')){
            $command = $session->get('command')->getProducts();
            foreach ($command['product'] as $k => $item){
                //dump($k);
                //dump($item['quantity']);
                $product = $productRepository->find($k);
                $quantityBefore = $product->getQuantity();
                $finalQuantity = $quantityBefore - $item['quantity'];
                //dump($finalQuantity);
                if($finalQuantity <= 0){
                    $product->setQuantity(0);
                }else{
                    $product->setQuantity($finalQuantity);
                }
                $this->em->flush();
            }
        }

        //vider la session du le panier et de la commande
        $cartService->emptyCartAndCommand();

        //catch userCommand
        $userCommand = $userCommandsRepository->findByUserValidateNoPaid($user);

        //récupérer la commande et faire la facture dans un dossier avec id user


        //set paid to true in userCommand
        $userCommand->setPaid(true);
        $this->em->flush();


        //supprimer le lostCart si présent
        //dump($lostCartRepository->findByUser($user));
        $userLostCart = $lostCartRepository->findByUser($user);
        if($userLostCart !== null){
            //dump($userLostCart);
            $this->em->remove($userLostCart);
            $this->em->flush();
        }

        //dump($userLostCart);
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
