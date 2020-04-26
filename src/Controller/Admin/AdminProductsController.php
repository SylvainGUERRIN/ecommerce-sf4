<?php


namespace App\Controller\Admin;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminProductsController
 * @package App\Controller\Admin
 * @Route("compo-admin/administration/products")
 */
class AdminProductsController extends AbstractController
{
    /**
     * @Route("/", name="dashboard-blog")
     * @return Response
     */
    public function dashboard(): Response
    {

        return $this->render('admin/blog/dashboard.html.twig');
    }
}
