<?php

namespace App\Controller\Admin;

use App\Entity\PasswordUpdate;
use App\Form\AccountType;
use App\Form\InscriptionType;
use App\Form\PasswordUpdateType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\User;

/**
 * Class AdminAccountController
 * @package App\Controller\Admin
 * @Route("/compo-admin/account")
 */
class AdminAccountController extends AbstractController
{
    /**
     * @Route("/admin-connexion", name="admin_connexion")
     * @param AuthenticationUtils $utils
     * @param Security $security
     * @return Response
     */
    public function adminConnec(AuthenticationUtils $utils, Security $security): Response
    {
        if ($security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('dashboard');
        }

        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();

        return $this->render('admin/account/adminLogin.html.twig', [
            'error' => $error !== null,
            'last_username' => $username
        ]);
    }

    /**
     * @Route("/admin-deconnexion", name="admin_deconnexion")
     */
    public function adminDeconnect(): void
    {

    }

    /**
     * @Route("/inscription", name="admin_inscription")
     *
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     * @throws \Exception
     */
    public function inscription(
        Request $request,
        UserPasswordEncoderInterface $encoder): Response
    {
        $user = new User();

        $form = $this->createForm(InscriptionType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashPass = $encoder->encodePassword($user, $user->getPassword());
            $user->setPass($hashPass);
//            change it after set user admin for next user
            $user->setRole('admin');

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash(
                'success',
                'Votre compte a bien été créé ! Vous pouvez maintenant vous connecter !'
            );

            return $this->redirectToRoute('admin_connexion');
        }

        return $this->render('admin/account/inscription.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/profil", name="admin_profil")
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function profil(Request $request): Response
    {
        $user = $this->getUser();
//        $oldImage = $user->getAvatarUrl();
//        $avatar = new Avatar();

        $form = $this->createForm(AccountType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

//            $user->setUpdatedAt(new DateTime('now'));

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash(
                'success',
                "Les données du profil ont bien étés modifiées."
            );
        }

        return $this->render('admin/account/adminProfil.html.twig', [
            'controller_name' => 'AccountController',
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de modifier le mot de passe
     *
     * @Route("/profil/password-update", name="adminProfil_password")
     *
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     */
    public function updatePassword(
        Request $request,
        UserPasswordEncoderInterface $encoder): Response
    {
        $passwordUpdate = new PasswordUpdate();

        $user = $this->getUser();

        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newPassword = $passwordUpdate->getNewPassword();
            $hash = $encoder->encodePassword($user, $newPassword);

            $user->setPass($hash);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash(
                'success',
                "Votre mot de passe a bien été modifié !"
            );

            return $this->redirectToRoute('dashboard');
        }

        return $this->render('admin/account/adminPass.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
