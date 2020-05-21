<?php


namespace App\Controller;


use App\Service\CartService;
use Doctrine\ORM\NonUniqueResultException;
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
     * sur tout le site
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

    /**
     * sur la page du panier
     * @Route("panier/add-tab/{productName}", name="cart_add_tab")
     * @param $productName
     * @param Request $request
     * @return Response
     * @throws NonUniqueResultException
     */
    public function addToTab($productName, Request $request): Response
    {
        if($request->isXmlHttpRequest()){
            $quantity = (int)$request->get('quantity');
            $this->cartService->changeQuantity($productName, $quantity);

            $total = $this->cartService->getTotalPrice();
            $quantityProducts = $this->cartService->getQuantity();

            return new JsonResponse([$quantity, $quantityProducts, $total]);
        }
    }

    /**
     * sur la page du panier
     * @Route("panier/delete-tab/{slug}", name="cart_delete_tab")
     * @param $slug
     * @param Request $request
     * @return Response
     * @throws NonUniqueResultException
     */
    public function deleteToTab($slug, Request $request): Response
    {
        if($request->isXmlHttpRequest()){
            $this->cartService->remove($slug);
            $total = $this->cartService->getTotalPrice();
            $quantityProducts = $this->cartService->getQuantity();

            return new JsonResponse([$slug, $quantityProducts, $total]);
        }
    }
}
