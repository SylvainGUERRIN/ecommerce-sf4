<?php

namespace App\Controller;


use App\Form\ContactType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\NonUniqueResultException;
use Knp\Component\Pager\PaginatorInterface;
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
            'products' => $productRepository->findAllWithLimit(12),
            'latestProducts' => $productRepository->findLatestWithLimit(6)
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
     * @param PaginatorInterface $paginator
     * @param ProductRepository $productRepository
     * @param Request $request
     * @return Response
     * @throws NonUniqueResultException
     * @throws \Exception
     */
    public function productCategory(
        $slug,
        CategoryRepository $categoryRepository,
        PaginatorInterface $paginator,
        ProductRepository $productRepository,
        Request $request
    ): Response
    {
        $category = $categoryRepository->findCategoryWithSlug($slug);
        $products = $paginator->paginate(
            $productRepository->findAllRecentWithCategory($category),
            $request->query->getInt('page',1),
            8
        );
        return $this->render('site/products-category.html.twig',[
            'slug' => $slug,
            'slideProducts' => $category->getProducts(),
            'products' => $products
        ]);
    }

    /**
     * page d'un produit dans le détail
     * @Route("/produit/{slug}", name="single-product")
     * @param $slug
     * @param ProductRepository $productRepository
     * @param CategoryRepository $categoryRepository
     * @return Response
     * @throws NonUniqueResultException
     */
    public function singleProduct($slug, ProductRepository $productRepository, CategoryRepository $categoryRepository): Response
    {
//        $product = $productRepository->findProductWithSlug($slug);
//        dd($productRepository->findProductWithSlug($slug));
        return $this->render('site/single-product.html.twig',[
            'slug' => $slug,
            'product' => $productRepository->findProductWithSlug($slug)
        ]);
    }

    /**
     * page contact
     * @Route("/contact", name="contact")
     * @param Request $request
     * @return Response
     */
    public function contact(Request $request): Response
    {
        $form = $this->createForm(ContactType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $nom = $form['nom']->getData();
            $email = $form['email']->getData();
            $sujet = $form['sujet']->getData();
            $description = $form['description']->getData();

            //envoyer un mail en créant un service pour les mails

            $this->addFlash(
                'success',
                "Votre demande a bien été prise en compte. Nous vous répondrons dans les plus bref délais."
            );

            return $this->redirectToRoute('home');
        }

        return $this->render('site/contact.html.twig',[
            'form' => $form->createView()
        ]);
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
