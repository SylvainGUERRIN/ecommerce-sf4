<?php


namespace App\Controller\Admin;

use App\Repository\PostRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminArticlesController
 * @package App\Controller
 *
 * @Route("compo-admin/administration/blog")
 */
class AdminBlogController extends AbstractController
{
    /**
     * @Route("/", name="dashboard-blog")
     * @param PaginatorInterface $paginator
     * @param PostRepository $postRepository
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function dashboard(PaginatorInterface $paginator, PostRepository $postRepository, Request $request): Response
    {
        $posts = $paginator->paginate(
            $postRepository->findAllRecent(),
            $request->query->getInt('page',1),
            10
        );

        return $this->render('admin/blog/dashboard-blog.html.twig', [
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/create-post", name="create_post")
     * @return Response
     */
    public function createPost(): Response
    {

        return $this->render('admin/blog/create-post.html.twig');
    }

    /**
     * @Route("/edit-post", name="edit_post")
     * @return Response
     */
    public function editPost(): Response
    {

        return $this->render('admin/blog/edit-post.html.twig');
    }

    /**
     * @Route("/delete-post", name="delete_post")
     * @return Response
     */
    public function deletePost(): Response
    {

        return $this->redirectToRoute('dashboard-blog');
    }
}
