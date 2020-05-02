<?php


namespace App\Controller\Admin;


use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminProductsController
 * @package App\Controller\Admin
 * @Route("compo-admin/administration/products")
 */
class AdminProductsController extends AbstractController
{
    public $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/", name="dashboard-products")
     * @param PaginatorInterface $paginator
     * @param ProductRepository $productRepository
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function dashboard(PaginatorInterface $paginator, ProductRepository $productRepository, Request $request): Response
    {
        $products = $paginator->paginate(
            $productRepository->findAllRecent(),
            $request->query->getInt('page',1),
            10
        );

        return $this->render('admin/products/dashboard-products.html.twig',[
            'products' => $products
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/new-product", name="product_create")
     */
    public function create(Request $request): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $product->setCreatedAt(new \DateTime('now'));

//            $this->em->persist();
//            $this->em->flush();
        }

        return$this->render('admin/products/new.html.twig',[
            'form' => $form->createView()
        ]);
    }
}
