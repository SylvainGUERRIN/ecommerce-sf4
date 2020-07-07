<?php


namespace App\Controller\Admin;


use App\Repository\UserRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminInvoicesController
 * @package App\Controller\Admin
 * @Route("compo-admin/administration/invoices")
 */
class AdminInvoicesController extends AbstractController
{
    /**
     * @Route("/", name="dashboard-invoices")
     * @param UserRepository $userRepository
     * @return Response
     */
    public function InvoicesDashboard(UserRepository $userRepository): ?Response
    {
        return $this->render('admin/invoices/dashboard-invoices.html.twig',[
            'users' => $userRepository->findAll()
        ]);
    }
}
