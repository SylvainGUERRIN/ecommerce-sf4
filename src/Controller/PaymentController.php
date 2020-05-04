<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PaymentController extends AbstractController
{

    /**
     * @Route("/panier", name="cart")
     * @return Response
     */
    public function cart(): Response
    {
        return $this->render('payment/cart.html.twig',[]);
    }

    /**
     * @Route("/livraison", name="delivery")
     * @return Response
     */
    public function delivery(): Response
    {
        return $this->render('payment/delivery.html.twig',[]);
    }

    /**
     * @Route("/validation", name="validation")
     * @return Response
     */
    public function validation(): Response
    {
        return $this->render('payment/validation.html.twig',[]);
    }
}