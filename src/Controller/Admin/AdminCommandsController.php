<?php


namespace App\Controller\Admin;


use App\Entity\UserCommands;
use App\Repository\ProductRepository;
use App\Repository\UserCommandsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminCommandsController
 * @package App\Controller\Admin
 * @Route("compo-admin/administration/commands")
 */
class AdminCommandsController extends AbstractController
{
    public $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/", name="dashboard-commands")
     * @param PaginatorInterface $paginator
     * @param UserCommandsRepository $userCommandsRepository
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function dashboard(PaginatorInterface $paginator, UserCommandsRepository $userCommandsRepository, Request $request): Response
    {
        $commands = $paginator->paginate(
            $userCommandsRepository->findAllRecent(),
            $request->query->getInt('page',1),
            5
        );

        return $this->render('admin/commands/dashboard-commands.html.twig',[
            'commands' => $commands
        ]);
    }

    /**
     * @Route("/command-detail/{reference}", name="command_detail")
     * @return Response
     */
    public function commandDetail(): Response
    {
        return $this->render('admin/commands/command-detail.html.twig',[]);
    }

    /**
     * @Route("/command-archives", name="command_archives")
     * @return Response
     */
    public function commandArchives(): Response
    {
        return $this->render('admin/commands/command-archives.html.twig',[]);
    }

    /**
     * @Route("/command-delete/{reference}", name="command_delete")
     * @param UserCommands $command
     * @return Response
     */
    public function commandDelete(UserCommands $command): Response
    {
        $this->em->remove($command);
        $this->em->flush();

        $this->addFlash(
            'success',
            "La commande <strong>{$command->getReference()}</strong> a  bien été supprimée !"
        );
        return $this->redirectToRoute('dashboard-commands');
    }
}
