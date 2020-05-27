<?php


namespace App\Controller;


use App\Entity\User;
use App\Entity\UserAddress;
use App\Form\UserAddressType;
use App\Repository\UserAddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AccountController
 * @package App\Controller
 * @Route("/user/account")
 * rajoute des éléments pour le compte de l'utilisateur que le profil ne rajoute pas
 */
class AccountController extends AbstractController
{
    public $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * page de la liste des adresses de l'utilisateur
     * @Route("/mes-adresses", name="address")
     * @param Request $request
     * @param UserAddressRepository $userAddressRepository
     * @return Response
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function address(Request $request, UserAddressRepository $userAddressRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();

//        dd($user);
//        dd($userAddressRepository->findByUser($user));

        $newUserAddress = new UserAddress();
        $form = $this->createForm(UserAddressType::class, $newUserAddress);
        $form->handleRequest($request);

        if($form->isSubmitted()){
//            vérifier si le user n'a pas 5 adresses (si c'est déjà le cas, renvoyer message d'erreur dans le form)
            $countAddresses = $userAddressRepository->findByUserAndCount($user);
            if($countAddresses >= 5){
                $form->addError(new FormError('Vous ne pouvez pas avoir plus de 5 adresses.'));
            }
        }

        if($form->isSubmitted() && $form->isValid()){

            //vérifier si le user n'a pas d'autres adresses déjà checkées et enléve le check
            if($form['for_command']->getData() === true){
                $oldAddresses = $userAddressRepository->findByUserAndCheck($user);
                foreach ($oldAddresses as $oldAddress){
                    if($oldAddress->getForCommand() === true){
                        $oldAddress->setForCommand(false);
                        $this->em->flush();
                    }
                }
            }

            //3 champs à compléter automatiquement
            $newUserAddress->setFirstname($user->getFirstname());
            $newUserAddress->setLastname($user->getLastname());
            $newUserAddress->setUser($user);
            //persister et flush la nouvelle adresse
            $this->em->persist($newUserAddress);
            $this->em->flush();

            $this->addFlash('success',
                "L'adresse a bien été enregistrée !"
            );
        }

        return $this->render('user/address.html.twig',[
            'userAddresses' => $userAddresses = $userAddressRepository->findByUser($user),
            'form' => $form->createView()
        ]);
    }

    /**
     * @return Response
     * @Route("/modifier-adresse/{id}", name="modify_address")
     */
    public function modifyAddress($id): Response
    {
        return $this->render('user/modifyAddress.html.twig');
    }

    /**
     * page de la liste des factures de l'utilisateur
     * @Route("/mes-factures", name="invoices")
     * @return Response
     */
    public function invoices(): Response
    {
        return $this->render('user/invoices.html.twig',[]);
    }
}
