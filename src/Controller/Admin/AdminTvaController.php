<?php

namespace App\Controller\Admin;

use App\Repository\TvaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminTvaController
 * @package App\Controller\Admin
 * @Route("compo-admin/administration/tva")
 */
class AdminTvaController extends AbstractController
{
    public $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/", name="dashboard-tva")
     * @param TvaRepository $tvaRepository
     * @return Response
     */
    public function dashboard(TvaRepository $tvaRepository): Response
    {
        $tvas = $tvaRepository->findAll();

        return $this->render('admin/tva/dashboard-tva.html.twig',[
            'tvas' => $tvas
        ]);
    }

    /**
     * @Route("/new-tva", name="tva_create")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response
    {
        return $this->render('admin/tva/new.html.twig',[

        ]);
    }
}
