<?php


namespace App\Controller\Admin;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
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
    protected $em;

    /**
     * AdminBlogController constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

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
     * @param Request $request
     * @return Response
     */
    public function createPost(Request $request): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $post->setPostCreatedAt(new \DateTime('now'));

            $this->em->persist($post);
            $this->em->flush();

            $this->addFlash('success',
                "L'article <strong>{$post->getTitle()}</strong> a bien été enregistrée !"
            );
            return $this->redirectToRoute('dashboard-blog', [
                'slug' => $post->getSlug()
            ]);
        }

        return $this->render('admin/blog/create-post.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit-post/{slug}", name="edit_post")
     * @param Request $request
     * @param Post $post
     * @param $slug
     * @return Response
     */
    public function editPost(Request $request, Post $post, $slug): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setPostModifiedAt(new \DateTime('now'));
            $this->em->persist($post);
            $this->em->flush();

            $this->addFlash('success',
                "L'article <strong>{$post->getTitle()}</strong> a bien été modifié !"
            );
            return $this->redirectToRoute('dashboard-blog');
        }

        return $this->render('admin/blog/edit-post.html.twig',[
            'form' => $form->createView(),
            'title' => $slug
        ]);
    }

    /**
     * @Route("/delete-post/{slug}", name="delete_post")
     * @param Post $post
     * @return Response
     */
    public function deletePost(Post $post): Response
    {
        $this->em->remove($post);
        $this->em->flush();

        $this->addFlash(
            'success',
            "L'article <strong>{$post->getTitle()}</strong> a  bien été supprimé !"
        );

        return $this->redirectToRoute('dashboard-blog');
    }
}
