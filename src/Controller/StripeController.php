<?php


namespace App\Controller;


use App\Entity\UserCommands;
use App\Repository\UserCommandsRepository;
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
        $userCommand->setValidate(true);
        $this->em->flush();



        //récupérer le tableau de la userCommand pour l'injecter dans la session stripe
        dump($userCommand);
        dump($userCommand->getProducts());//mettre les produits dans le tableau line_items
        die();

        $publicKEY = $_ENV['STRIPE_PUBLIC_KEY'];
        $secretKEY = $_ENV['STRIPE_SECRET_KEY'];

        \Stripe\Stripe::setApiKey($secretKEY);

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'name' => 'book',
                    'amount' => 700,
                    'currency' => 'eur',
                    'quantity' => 1,
                ],
                [
                    'name' => 'dvd',
                    'amount' => 500,
                    'currency' => 'eur',
                    'quantity' => 2,
                ]
            ],
            'mode' => 'payment',
            'success_url' => 'http://localhost:8000/success?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => 'http://localhost:8000/cancel',
        ]);

        //dump($session);

        return $this->render('payment/purchase-sca.html.twig', [
            'id' => $session->id,
            'pk' => $publicKEY
        ]);

    }

    /**
     * @Route("/success", name="paiement_success")
     * @Security("is_granted('ROLE_USER')")
     * @return Response
     */
    public function success(): Response
    {
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
