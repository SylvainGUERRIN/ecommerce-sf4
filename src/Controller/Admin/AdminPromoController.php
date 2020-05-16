<?php


namespace App\Controller\Admin;


use App\Entity\Promo;
use App\Form\PromoType;
use App\Repository\PromoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminPromoController
 * @package App\Controller\Admin
 * @Route("compo-admin/administration/promos")
 */
class AdminPromoController extends AbstractController
{
    public $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/", name="dashboard-promos")
     * @param PromoRepository $promoRepository
     * @return Response
     */
    public function dashboard(PromoRepository $promoRepository): Response
    {
        $promos = $promoRepository->findAll();

        return $this->render('admin/promos/dashboard-promos.html.twig',[
            'promos' => $promos
        ]);
    }

    /**
     * @Route("/new-promo", name="promo_create")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response
    {
        $promo = new Promo();
        $form = $this->createForm(PromoType::class, $promo);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $this->em->persist($promo);
            $this->em->flush();

            $this->addFlash(
                'success',
                "La promotion {$promo->getName()} a bien été créée !"
            );

            return $this->redirectToRoute('dashboard-promos');
        }

        return $this->render('admin/promos/new.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit-promo/{id}", name="promo_edit")
     * @param Promo $promo
     * @param Request $request
     * @return Response
     */
    public function edit(Promo $promo, Request $request): Response
    {
        $form = $this->createForm(PromoType::class, $promo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->em->persist($promo);
            $this->em->flush();

            $this->addFlash('success',
                "La promotion <strong>{$promo->getName()}</strong> a bien été modifiée !"
            );
            return $this->redirectToRoute('dashboard-promos');
        }

        return $this->render('admin/promos/edit.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Promo $promo
     * @return Response
     * @Route("/delete-promo/{id}", name="promo_delete")
     */
    public function delete(Promo $promo): Response
    {
        $this->em->remove($promo);
        $this->em->flush();

        $this->addFlash(
            'success',
            "La promotion <strong>{$promo->getName()}</strong> a  bien été supprimée !"
        );
        return $this->redirectToRoute('dashboard-promos');
    }
}
