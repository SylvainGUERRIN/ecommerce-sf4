<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SiteController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @return Response
     */
    public function home(): Response
    {
        return $this->render('site/home.html.twig',[]);
    }

    /**
     * page de la liste de produits
     * @Route("/produits", name="products")
     * @return Response
     */
    public function products(): Response
    {
        return $this->render('site/products.html.twig',[]);
    }

    /**
     * page de la liste d'une catégorie de produits
     * @Route("/categorie-de-produits", name="products_category")
     * @return Response
     */
    public function productCategory(): Response
    {
        return $this->render('site/products-category.html.twig',[]);
    }

    /**
     * page d'un produit dans le détail
     * @Route("/nom-du-produit", name="single-product")
     * @return Response
     */
    public function singleProduct(): Response
    {
        return $this->render('site/single-product.html.twig',[]);
    }

    /**
     * page contact
     * @Route("/contact", name="contact")
     * @return Response
     */
    public function contact(): Response
    {
        return $this->render('site/contact.html.twig',[]);
    }

    /**
     * mentions
     * @Route("/mentions-legales", name="mentions")
     * @return Response
     */
    public function mentions(): Response
    {
        return $this->render('site/mentions-legales.html.twig',[]);
    }
}
