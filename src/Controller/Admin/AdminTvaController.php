<?php

namespace App\Controller\Admin;

use App\Entity\Tva;
use App\Form\TvaType;
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
        $tva = new Tva();
        $form = $this->createForm(TvaType::class, $tva);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $this->em->persist($tva);
            $this->em->flush();

            $this->addFlash(
                'success',
                "La TVA {$tva->getName()} a bien été créée !"
            );

            return $this->redirectToRoute('dashboard-tva');
        }

        return $this->render('admin/tva/new.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit-tva/{id}", name="tva_edit")
     * @param Tva $tva
     * @param Request $request
     * @return Response
     */
    public function edit(Tva $tva, Request $request): Response
    {
        $form = $this->createForm(TvaType::class, $tva);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->em->persist($tva);
            $this->em->flush();

            $this->addFlash('success',
                "La TVA <strong>{$tva->getName()}</strong> a bien été modifiée !"
            );
            return $this->redirectToRoute('dashboard-tva');
        }

        return $this->render('admin/tva/edit.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Tva $tva
     * @return Response
     * @Route("/delete-category/{id}", name="tva_delete")
     */
    public function delete(Tva $tva): Response
    {
        $this->em->remove($tva);
        $this->em->flush();

        $this->addFlash(
            'success',
            "La TVA <strong>{$tva->getName()}</strong> a  bien été supprimée !"
        );
        return $this->redirectToRoute('dashboard-tva');
    }
}
