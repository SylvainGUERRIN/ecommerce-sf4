<?php


namespace App\Service;



use App\Entity\Product;
use App\Entity\User;
use App\Entity\UserAddress;
use App\Entity\UserCommands;
use App\Repository\UserCommandsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;
use Symfony\Component\Validator\Constraints\Date;

class CommandService
{
    protected $session;
    protected $em;
    protected $user;
    protected $cartService;
    protected $userCommandsRepository;
    protected $helpers;

    /**
     * CommandService constructor.
     * @param SessionInterface $session
     * @param EntityManagerInterface $em
     * @param Security $security
     * @param CartService $cartService
     * @param UserCommandsRepository $userCommandsRepository
     * @param Helpers $helpers
     */
    public function __construct(
        SessionInterface $session,
        EntityManagerInterface $em,
        Security $security,
        CartService $cartService,
        UserCommandsRepository $userCommandsRepository,
        Helpers $helpers
    )
    {
        $this->session = $session;
        $this->em = $em;
        $this->user = $security->getUser();
        $this->cartService = $cartService;
        $this->userCommandsRepository = $userCommandsRepository;
        $this->helpers = $helpers;
    }

    /**
     * @throws NonUniqueResultException
     */
    public function facture()
    {
        //generer un token find method in service
//        $generator = $this->container->get('security.secure_random');
        $panier = $this->session->get('panier');
        $user = $this->em->getRepository(User::class)->findByMail($this->user->getUsername());
        $deliveryAddress = $this->em->getRepository(UserAddress::class)->findByUserAndCommand($user);
        $billingAddress = $this->em->getRepository(UserAddress::class)->findByUserAndBilling($user);
        $command = [];

        //trouver tous les produits du panier
        $products = $this->em->getRepository(Product::class)->findAllByName(array_keys($this->session->get('panier')));

        $totalHT = 0;
        $totalTTC = 0;
        $totalTTCWithPromo = 0;
        //pour faire un foreach et tout mettre dans le json en bdd
        foreach ($products as $product){
            $quantityOfProduct = $this->cartService->getQuantityOfOneProduct($product->getName());
            $priceHT = $product->getPrice();

            //prix ttc
            if($product->getTva() !== null){
                $productTVA = $product->getTva()->getMultiplicate();
                if($productTVA > 0){
                    $priceTTC = $priceHT + (($priceHT * $productTVA) / 100);
//                    dd($priceTTC);
                }else{
                    $priceTTC = $priceHT;
                }
            }else{
                $priceTTC = $priceHT;
                $productTVA = null;
            }

            if($product->getPromo() !== null){
                $priceTTCWithPromo = $priceTTC - (($priceTTC * $product->getPromo()->getPercent()) / 100);
            }else{
                $priceTTCWithPromo = 0;
            }

            $totalHT += round($priceHT * $quantityOfProduct, 2);
            $totalTTC += round($priceTTC * $quantityOfProduct, 2);
            $totalTTCWithPromo += round($priceTTCWithPromo * $quantityOfProduct, 2);

            $command['product'][$product->getId()] = array(
                'reference' => $product->getName(),
                'image' => $product->getProductImage()->getUrlImage(),
                'quantity' => $quantityOfProduct,
                'tva' => $productTVA,
                'priceHT' => round($priceHT, 2),
                'priceTTC' => round($priceTTC, 2),
                'priceTTCWithPromo' => round($priceTTCWithPromo, 2)
            );
            $command['delivery'] = array(
                'firstname' => $deliveryAddress->getFirstname(),
                'lastname' => $deliveryAddress->getLastname(),
                'phone' => $deliveryAddress->getPhone(),
                'address' => $deliveryAddress->getAddress(),
                'town' => $deliveryAddress->getTown(),
                'cp' => $deliveryAddress->getCp(),
                'country' => $deliveryAddress->getCountry(),
                'complement' => $deliveryAddress->getComplement()
            );
            $command['billing'] = array(
                'firstname' => $billingAddress->getFirstname(),
                'lastname' => $billingAddress->getLastname(),
                'phone' => $billingAddress->getPhone(),
                'address' => $billingAddress->getAddress(),
                'town' => $billingAddress->getTown(),
                'cp' => $billingAddress->getCp(),
                'country' => $billingAddress->getCountry(),
                'complement' => $billingAddress->getComplement()
            );
        }

        $command['amount'] = array(
            'totalHT' => round($totalHT, 2),
            'totalTTC' => round($totalTTC, 2),
            'totalTTCWithPromo' => round($totalTTCWithPromo, 2)
        );

        return $command;
    }

    /**
     * @return Response
     * @throws NonUniqueResultException
     */
    public function prepareCommand(): Response
    {
        $user = $this->em->getRepository(User::class)->findByMail($this->user->getUsername());
        //dump($this->em->getRepository(UserCommands::class)->find($this->session->get('command')));
        $oldCommand = $this->em->getRepository(UserCommands::class)->findByUserNoValidateNoPaid($user);

        if(!$this->session->has('command')){
            if(!empty($oldCommand)){
                $command = $oldCommand;
            }else{
                $command = new UserCommands();
            }
        }else{
            //regarder comment s'est enregistré en session
            if(!empty($oldCommand)){
                $command = $oldCommand;
            }else{
                $command = $this->em->getRepository(UserCommands::class)->find($this->session->get('command'));
            }
        }

        $date = new \DateTime('now');
        $result = $date->format('Y-m-d');
        //dump($user);
        $command->setUser($user);
        $command->setUserAddress($this->em->getRepository(UserAddress::class)->findByUserAndCommand($user));
        $command->setBillingAddress($this->em->getRepository(UserAddress::class)->findByUserAndBilling($user));
        $command->setCommandAt(new \DateTime('now'));
        $command->setValidate(false);
        $command->setReference($this->helpers->random_str(5).$user->getId().'-'.$result);
        $command->setProducts($this->facture());
        $command->setTotalAmount($this->cartService->getTotalPrice());
        $command->setPaid(false);

        if(!$this->session->has('command')){
            if(empty($oldCommand)){
                $this->em->persist($command);
            }
            //ajout de la commande à la session
            $this->session->set('command', $command);
        }

        $this->em->flush();

        return new Response($command->getId());
    }
}
