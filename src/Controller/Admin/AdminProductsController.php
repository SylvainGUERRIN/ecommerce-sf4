<?php


namespace App\Controller\Admin;


use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
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

    public function __construct(EntityManagerInterface $em)
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

            $this->em->persist($product);
            $this->em->flush();

            $this->addFlash('success',
                "Le produit <strong>{$product->getName()}</strong> a bien été enregistré !"
            );

            return $this->redirectToRoute('dashboard-products');
        }

        return $this->render('admin/products/new.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Product $product
     * @param Request $request
     * @return Response
     * @Route("/edit-product/{slug}", name="product_edit")
     */
    public function edit(Product $product, Request $request): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setUpdatedAt(new \DateTime('now'));
            $this->em->persist($product);
            $this->em->flush();

            $this->addFlash('success',
                "Le produit <strong>{$product->getName()}</strong> a bien été modifié !"
            );
            return $this->redirectToRoute('dashboard-products');
        }

        return $this->render('admin/products/edit.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Product $product
     * @return RedirectResponse
     * @Route("/product/delete/{slug}", name="product_delete")
     */
    public function delete(Product $product): RedirectResponse
    {
        $this->em->remove($product);
        $this->em->flush();

        $this->addFlash(
            'success',
            "Le produit <strong>{$product->getName()}</strong> a  bien été supprimé !"
        );
        return $this->redirectToRoute('dashboard-products');
    }
}
