<?php

namespace App\Controller;


use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SiteController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param CategoryRepository $categoryRepository
     * @param ProductRepository $productRepository
     * @return Response
     * @throws \Exception
     */
    public function home(CategoryRepository $categoryRepository, ProductRepository $productRepository): Response
    {

        return $this->render('site/home.html.twig',[
            'categories' => $categoryRepository->findAll(),
            'products' => $productRepository->findAllRecent(),
            'latestProducts' => $productRepository->findLatest()
        ]);
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
     * @Route("/categorie/{slug}", name="products_category")
     * @param $slug
     * @param CategoryRepository $categoryRepository
     * @return Response
     * @throws NonUniqueResultException
     */
    public function productCategory($slug, CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->findCategoryWithSlug($slug);
//        dd($category->getProducts());
        return $this->render('site/products-category.html.twig',[
            'slug' => $slug,
            'products' => $category->getProducts()
        ]);
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
