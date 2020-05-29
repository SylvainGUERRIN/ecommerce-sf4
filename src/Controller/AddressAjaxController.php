<?php


namespace App\Controller;


use App\Entity\User;
use App\Repository\UserAddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AddressAjaxController extends AbstractController
{
    public $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param $addressID
     * @param Request $request
     * @param UserAddressRepository $userAddressRepository
     * @return JsonResponse
     * @Route("/address/add/check/{addressID}", name="add_check_to_address")
     */
    public function addCheckToAddress($addressID, Request $request, UserAddressRepository $userAddressRepository): ? JsonResponse
    {
        if($request->isXmlHttpRequest()){
            /** @var User $user */
            $user = $this->getUser();

            //vérifier si le user n'a pas d'autres adresses déjà checkées et enléve le check
            $oldAddresses = $userAddressRepository->findByUserAndCommand($user);
            foreach ($oldAddresses as $oldAddress){
                if($oldAddress->getForCommand() === true){
                    $oldAddress->setForCommand(false);
                    $this->em->flush();
                }
            }
//            $id = substr($addressID, -1);
            $id = preg_replace('~\D~', '', $addressID);

            $address = $userAddressRepository->find($id);

            $address->setForCommand(true);
            $this->em->flush();

            //dd($addressID);

            return new JsonResponse([
                'content' => $this->renderView('site/partials/address/_addresses.html.twig', [
                    'userAddresses' => $userAddresses = $userAddressRepository->findByUser($user)]),
            ]);
        }
    }
}
