<?php


namespace App\Service;


use App\Repository\ProductRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    protected $session;
    protected $productRepository;

    public function __construct(SessionInterface $session, ProductRepository $productRepository)
    {
        $this->session = $session;
        $this->productRepository = $productRepository;
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
     * @return float|int
     */
    public function getTotalPrice()
    {
        $total = 0;
        $panierWithData = $this->getFullCart();
//        dd($panierWithData);
        foreach ($panierWithData as $product) {
            //retravailler dessus pour choper les prix des produits
            $totalProduct = $product['product']->getPrice() * $product['quantity'];
            $total += $totalProduct;
        }
        return $total;
    }

    public function empty(): bool
    {
        // le but est de vider le panier
        $this->session->remove('panier');
        return true;
    }
}
