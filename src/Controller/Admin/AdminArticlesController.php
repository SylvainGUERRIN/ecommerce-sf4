<?php


namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminArticlesController
 * @package App\Controller
 *
 * @Route("compo-admin/administration")
 */
class AdminArticlesController extends AbstractController
{
    /**
     * @Route("/", name="dashboard")
     * @return Response
     */
    public function dashboard(): Response
    {

        return $this->render('admin/articles/dashboard.html.twig');
    }


}
