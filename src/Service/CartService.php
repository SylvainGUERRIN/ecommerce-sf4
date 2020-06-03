<?php


namespace App\Service;


use App\Repository\ProductRepository;
use App\Repository\PromoRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    protected $session;
    protected $productRepository;
    protected $promoRepository;

    /**
     * CartService constructor.
     * @param SessionInterface $session
     * @param ProductRepository $productRepository
     * @param PromoRepository $promoRepository
     */
    public function __construct(SessionInterface $session, ProductRepository $productRepository, PromoRepository $promoRepository)
    {
        $this->session = $session;
        $this->productRepository = $productRepository;
        $this->promoRepository = $promoRepository;
    }

    /**
     * @return array
     * @throws NonUniqueResultException
     */
    public function getFullCart(): array
    {
        $panier = $this->session->get('panier', []);
        $panierWithData = [];
        foreach ($panier as $productName => $quantity){
            $panierWithData[] = [
                'product' => $this->productRepository->findByName($productName),
                'quantity' => $quantity
            ];
        }
        return $panierWithData;
    }

    /**
     * @param $productName
     * @return mixed
     */
    public function addToCart($productName)
    {
        $panier = $this->session->get('panier',[]);
        if(!empty($panier[$productName])){
            $panier[$productName]++;
        }else{
            $panier[$productName] = 1;
        }
        $this->session->set('panier', $panier);
        return $panier;
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function lessToCart(int $id)
    {
        $panier = $this->session->get('panier', []);
        if (!empty($panier[$id])) {
            if ($panier[$id] === 1) {
                unset($panier[$id]);
            }else{
                $panier[$id]--;
            }
            $this->session->set('panier', $panier);
        }
        return $panier;
    }

    /**
     * @return mixed|null
     * @throws NonUniqueResultException
     */
    public function getQuantity()
    {
        $quantityProducts = null;
        $panierWithData = $this->getFullCart();

        foreach ($panierWithData as $item){
            $quantityProducts += $item['quantity'];
        }
        return $quantityProducts;
    }

    /**
     * @param $productName
     * @return null
     * @throws NonUniqueResultException
     */
    public function getQuantityOfOneProduct($productName)
    {
        $quantityOfOneProduct = null;
        $panierWithData = $this->getFullCart();

        foreach ($panierWithData as $oneProduct){
            if($oneProduct['product']->getName() === $productName){
                $quantityOfOneProduct = $oneProduct['quantity'];
            }
        }
        return $quantityOfOneProduct;
    }

    /**
     * @param $productName
     * @param $quantity
     * @return mixed|null
     */
    public function changeQuantity($productName, $quantity): void
    {

        $panier = $this->session->get('panier',[]);
        if(!empty($panier[$productName])){
            $panier[$productName] = (int)$quantity;
        }
        $this->session->set('panier', $panier);
    }

    /**
     * @return float|int
     * @throws NonUniqueResultException
     */
    public function getTotalPrice()
    {
        $total = 0;
        $panierWithData = $this->getFullCart();
        foreach ($panierWithData as  $product) {
            $item = $this->productRepository->findByName($product['product']->getName());
            $originalPrice = $product['product']->getPrice();
            //si le produit a une tva
            if($item->getTva() !== null){
                $multiplicate = $item->getTva()->getMultiplicate();
                $priceWithTva = round($originalPrice + ($originalPrice * $multiplicate / 100), 2);
            }else{
                $priceWithTva = $originalPrice;
            }
            //si le produit est en promo
            if($item->getPromo() !== null){
                $percent = $this->promoRepository->find($item->getPromo())->getPercent();
                //dd($percent);
                $priceWithTva = round($priceWithTva - ($priceWithTva * $percent / 100), 2);
            }
            //retravailler dessus pour choper les prix des produits
            $totalProduct = $priceWithTva * $product['quantity'];
            $total += $totalProduct;
        }
        return $total;
    }

    /**
     * @param $productSlug
     * @return mixed
     * @throws NonUniqueResultException
     */
    public function remove($productSlug)
    {
        $productName = $this->productRepository->findBySlug($productSlug)->getName();
        $panier = $this->session->get('panier', []);
        if (!empty($panier[$productName])) {
            unset($panier[$productName]);
        }
        $this->session->set('panier', $panier);
        return $panier;
    }

    public function empty(): bool
    {
        // le but est de vider le panier
        $this->session->remove('panier');
        return true;
    }
}
