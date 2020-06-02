<?php


namespace App\Service;



use App\Entity\Product;
use App\Entity\User;
use App\Entity\UserAddress;
use App\Entity\UserCommands;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;

class CommandService
{
    protected $session;
    protected $em;
    protected $user;
    protected $cartService;

    /**
     * CommandService constructor.
     * @param SessionInterface $session
     * @param EntityManagerInterface $em
     * @param Security $security
     * @param CartService $cartService
     */
    public function __construct(SessionInterface $session, EntityManagerInterface $em, Security $security, CartService $cartService)
    {
        $this->session = $session;
        $this->em = $em;
        $this->user = $security->getUser();
        $this->cartService = $cartService;
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
        $amountHT = $this->cartService->getTotalPrice();
        $command = [];

        //trouver tous les produits du panier
        $products = $this->em->getRepository(Product::class)->findAllByName(array_keys($this->session->get('panier')));

        //pour faire un foreach et tout mettre dans le json en bdd

    }

    /**
     * @return Response
     * @throws NonUniqueResultException
     */
    public function prepareCommand(): Response
    {
        if(!$this->session->has('command')){
            $command = new UserCommands();
        }else{
            //regarder comment s'est enregistré en session
            $command = $this->em->getRepository(UserCommands::class)->find($this->session->get('command'));
        }

        //renvooi l'objet user ou null si pas authentifié
        $command->setUser($this->user);
//        $command->setUser($this->token_storage->getToken()->getUser());
        //$command->setUserAddress();
        $command->setCommandAt(new \DateTime('now'));
        $command->setValidate(false);
        //$command->setReference();
        $command->setProducts($this->facture());

        if(!$this->session->has('command')){
            $this->em->persist($command);
            $this->session->set('command', $command);
        }

        $this->em->flush();

        return new Response($command->getId());
    }
}
