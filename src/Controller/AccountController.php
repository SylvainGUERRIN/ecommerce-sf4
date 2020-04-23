<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AccountController
 * @package App\Controller
 * rajoute des éléments pour le compte de l'utilisateur que le profil ne rajoute pas
 */
class AccountController extends AbstractController
{
    /**
     * page de la liste des adresses de l'utilisateur
     * @Route("/mes-adresses", name="address")
     * @return Response
     */
    public function address(): Response
    {
        return $this->render('user/address.html.twig',[]);
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
