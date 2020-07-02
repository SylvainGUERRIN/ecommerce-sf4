<?php


namespace App\Controller\Admin;


use App\Entity\UserCommands;
use App\Form\SentUserCommandType;
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
     * @param UserCommands $command
     * @param Request $request
     * @return Response
     */
    public function commandDetail(UserCommands $command, Request $request): Response
    {
        //récupére la commande grâce à la reference dans l'url
        //dd($command);

        $form = $this->createForm(SentUserCommandType::class, $command);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $this->em->persist($command);
            $this->em->flush();

            $this->addFlash('success',
                "L'envoi de la commande a bien été enregistré !"
            );

            return $this->redirectToRoute('dashboard-products');
        }

        return $this->render('admin/commands/command-detail.html.twig',[
            'command' => $command,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/command-archives", name="command_archives")
     * @param PaginatorInterface $paginator
     * @param UserCommandsRepository $userCommandsRepository
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function commandArchives(PaginatorInterface $paginator, UserCommandsRepository $userCommandsRepository, Request $request): Response
    {
        $commands = $paginator->paginate(
            $userCommandsRepository->findAllArchives(),
            $request->query->getInt('page',1),
            5
        );

        return $this->render('admin/commands/command-archives.html.twig',[
            'commands' => $commands
        ]);
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
