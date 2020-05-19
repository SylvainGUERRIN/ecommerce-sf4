<?php


namespace App\Controller;


use App\Service\CartService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PaymentController extends AbstractController
{
    protected $cartService;

    /**
     * PaymentController constructor.
     * @param CartService $cartService
     */
    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * @Route("/panier", name="cart")
     * @return Response
     */
    public function cart(): Response
    {
        $panierWithData = $this->cartService->getFullCart();

        $total = $this->cartService->getTotalPrice();
        $quantityProducts = $this->cartService->getQuantity();

        return $this->render('payment/cart.html.twig',[
            'quantityProducts' => $quantityProducts,
            'items' => $panierWithData,
            'total' => $total
        ]);
    }

    /**
     * @Route("/livraison", name="delivery")
     * @Security("is_granted('ROLE_USER')")
     * @return Response
     */
    public function delivery(): Response
    {
        return $this->render('payment/delivery.html.twig',[]);
    }

    /**
     * @Route("/validation", name="validation")
     * @Security("is_granted('ROLE_USER')")
     * @return Response
     */
    public function validation(): Response
    {
        return $this->render('payment/validation.html.twig',[]);
    }
}
