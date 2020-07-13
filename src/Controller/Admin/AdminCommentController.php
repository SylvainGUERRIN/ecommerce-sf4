<?php


namespace App\Controller\Admin;


use App\Entity\Comment;
use App\Entity\Post;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminCommentController
 * @package App\Controller\Admin
 * @Route("compo-admin/administration/commentaires")
 */
class AdminCommentController extends AbstractController
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
     * @Route("/", name="dashboard-comments")
     * @param PaginatorInterface $paginator
     * @param CommentRepository $commentRepository
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function dashboard(PaginatorInterface $paginator, CommentRepository $commentRepository, Request $request): Response
    {
        $comments = $paginator->paginate(
            $commentRepository->findAllRecent(),
            $request->query->getInt('page',1),
            10
        );

        return $this->render('admin/comments/dashboard-comments.html.twig', [
            'comments' => $comments
        ]);
    }

    /**
     * @Route("/delete-comment/{id}", name="delete_comment")
     * @param Comment $comment
     * @return Response
     */
    public function deletePost(Comment $comment): Response
    {
        $this->em->remove($comment);
        $this->em->flush();

        $this->addFlash(
            'success',
            "Le commentaire de <strong>{$comment->getMail()}</strong> a  bien été supprimé !"
        );

        return $this->redirectToRoute('dashboard-comments');
    }
}
