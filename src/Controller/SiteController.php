<?php

namespace App\Controller;


use App\Data\SearchData;
use App\Form\ContactType;
use App\Form\SearchType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Service\CartService;
use Doctrine\ORM\NonUniqueResultException;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class SiteController extends AbstractController
{
    protected $quantityProducts;

    /**
     * SiteController constructor.
     * @param CartService $cartService
     * @throws NonUniqueResultException
     */
    public function __construct(CartService $cartService)
    {
        $this->quantityProducts = $cartService->getQuantity();
    }

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
            'latestProducts' => $productRepository->findLatestWithLimit(6),
            'quantityProducts' => $this->quantityProducts
        ]);
    }

    /**
     * page de la liste de produits
     * @Route("/produits", name="products")
     * @param ProductRepository $productRepository
     * @param Request $request
     * @return Response
     */
    public function products(ProductRepository $productRepository, Request $request): Response
    {
        $data = new SearchData();
        $data->page = $request->get('page', 1);
        $form = $this->createForm(SearchType::class, $data);
        $form->handleRequest($request);
        [$min, $max] = $productRepository->findMinMax($data);
//        dd($data);

        $products = $productRepository->findSearch($data);
        //dd($products);

        return $this->render('site/products.html.twig',[
            'quantityProducts' => $this->quantityProducts,
            'products' => $products,
            'form' => $form->createView(),
            'min' => $min,
            'max' => $max
        ]);
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
            'products' => $products,
            'quantityProducts' => $this->quantityProducts
        ]);
    }

    /**
     * page d'un produit dans le détail
     * @Route("/produit/{slug}", name="single-product")
     * @param $slug
     * @param ProductRepository $productRepository
     * @param CategoryRepository $categoryRepository
     * @param SessionInterface $session
     * @return Response
     * @throws NonUniqueResultException
     */
    public function singleProduct(
        $slug,
        ProductRepository $productRepository,
        CategoryRepository $categoryRepository,
        SessionInterface $session
    ): Response
    {
//        $product = $productRepository->findProductWithSlug($slug);
//        dd($productRepository->findProductWithSlug($slug));
        //dd($session->get('panier')); //just for test remove session interface after
        return $this->render('site/single-product.html.twig',[
            'slug' => $slug,
            'product' => $productRepository->findProductWithSlug($slug),
            'quantityProducts' => $this->quantityProducts
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
            'form' => $form->createView(),
            'quantityProducts' => $this->quantityProducts
        ]);
    }

    /**
     * mentions
     * @Route("/mentions-legales", name="mentions")
     * @return Response
     */
    public function mentions(): Response
    {
        return $this->render('site/mentions-legales.html.twig',[
            'quantityProducts' => $this->quantityProducts
        ]);
    }
}
