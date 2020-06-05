<?php


namespace App\Controller;


use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Repository\UserAddressRepository;
use App\Repository\UserCommandsRepository;
use App\Service\CartService;
use App\Service\CommandService;
use Doctrine\ORM\NonUniqueResultException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class PaymentController extends AbstractController
{
    protected $cartService;
    protected $quantityProducts;
    protected $session;

    /**
     * PaymentController constructor.
     * @param CartService $cartService
     * @param SessionInterface $session
     * @throws NonUniqueResultException
     */
    public function __construct(CartService $cartService, SessionInterface $session)
    {
        $this->cartService = $cartService;
        $this->quantityProducts = $cartService->getQuantity();
        $this->session = $session;
    }

    /**
     * @Route("/panier", name="cart")
     * @return Response
     * @throws NonUniqueResultException
     */
    public function cart(): Response
    {
        $panierWithData = $this->cartService->getFullCart();

        $total = $this->cartService->getTotalPrice();

        return $this->render('payment/cart.html.twig',[
            'quantityProducts' => $this->quantityProducts,
            'items' => $panierWithData,
            'total' => $total
        ]);
    }

//    /**
//     * @Route("/livraison", name="delivery")
//     * @Security("is_granted('ROLE_USER')")
//     * @return Response
//     */
//    public function delivery(): Response
//    {
//        return $this->render('payment/delivery.html.twig',[
//            'quantityProducts' => $this->quantityProducts,
//        ]);
//    }

    /**
     * @Route("/validation", name="validation")
     * @Security("is_granted('ROLE_USER')")
     * @param UserAddressRepository $userAddressRepository
     * @param CommandService $commandService
     * @param UserCommandsRepository $userCommandsRepository
     * @return Response
     * @throws NonUniqueResultException
     */
    public function validation(
        UserAddressRepository $userAddressRepository,
        CommandService $commandService,
        UserCommandsRepository $userCommandsRepository
    ): Response
    {
        $user = $this->getUser();

        if(empty($this->session->get('panier'))){
            $this->addFlash(
                'danger',
                "Il doit y avoir des produits dans votre panier pour le validé."
            );

            return $this->redirectToRoute('cart');
        }

        //dd($this->session->get('command'));
        //call prepare command with command service
        $commandID = $commandService->prepareCommand()->getContent();
        dump($userCommand = $userCommandsRepository->find($commandID));
        //dd($userCommand);

        $panierWithData = $this->cartService->getFullCart();

        $total = $this->cartService->getTotalPrice();

        return $this->render('payment/validation.html.twig',[
            'deliveryAddress' => $userAddressRepository->findByUserAndCommand($user),
            'billingAddress' => $userAddressRepository->findByUserAndBilling($user),
            'quantityProducts' => $this->quantityProducts,
            'items' => $panierWithData,
            'total' => $total
        ]);
    }
}
