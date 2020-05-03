<?php


namespace App\Controller\Admin;


use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminProductsCategoryController
 * @package App\Controller\Admin
 * @Route("compo-admin/administration/products/categories")
 */
class AdminCategoriesController extends AbstractController
{
    public $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/", name="dashboard-categories")
     * @param CategoryRepository $categoryRepository
     * @return Response
     */
    public function dashboard(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();
//        $products = $paginator->paginate(
//            $productRepository->findAllRecent(),
//            $request->query->getInt('page',1),
//            10
//        );

        return $this->render('admin/categories/dashboard-categories.html.twig',[
            'categories' => $categories
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/new-category", name="category_create")
     */
    public function create(Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $this->em->persist($category);
            $this->em->flush();

            $this->addFlash(
                'success',
                "La catégorie {$category->getName()} a bien été créée !"
            );

            return $this->redirectToRoute('dashboard-categories');
        }

        return $this->render('admin/categories/new.html.twig',[
            'form' => $form->createView(),
        ]);
    }
}
