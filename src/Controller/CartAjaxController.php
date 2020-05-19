<?php


namespace App\Controller;


use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartAjaxController extends AbstractController
{
    protected $cartService;

    /**
     * CartAjaxController constructor.
     * @param CartService $cartService
     */
    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * @Route("/panier/add/{productName}", name="cart_add")
     * @param $productName
     * @param Request $request
     * @return Response
     */
    public function add($productName, Request $request): Response
    {
        if($request->isXmlHttpRequest()){
            $result = $this->cartService->addToCart($productName);
            $total = array_sum($result);
            return new JsonResponse($total);
        }
    }
}
