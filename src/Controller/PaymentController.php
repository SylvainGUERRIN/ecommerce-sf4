<?php


namespace App\Controller;


use App\Repository\ProductRepository;
use App\Service\CartService;
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

    /**
     * PaymentController constructor.
     * @param CartService $cartService
     * @throws NonUniqueResultException
     */
    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
        $this->quantityProducts = $cartService->getQuantity();
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
     * @return Response
     */
    public function validation(): Response
    {
        //call prepare command with command service

        return $this->render('payment/validation.html.twig',[]);
    }
}
