<?php


namespace App\Controller;

use App\Entity\User;
use App\Form\InscriptionType;
use App\Form\AccountType;
use App\Form\PassRecupType;
use App\Form\PasswordUpdateType;
use App\Repository\UserRepository;
use App\Entity\PasswordUpdate;
use App\Entity\PasswordRecup;
use Symfony\Component\Form\FormError;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use \DateTime;

/**
 * Class SecurityController
 * @package App\Controller
 * @Route("/user/account")
 */
class SecurityController extends AbstractController
{

    /**
     * @Route("/connexion", name="user_connexion")
     *
     * @param AuthenticationUtils $helper
     * @param Security $security
     * @return Response
     */
    public function connexion(AuthenticationUtils $helper, Security $security): Response
    {
        if ($security->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('member_space');
        }

//        $error = $utils->getLastAuthenticationError();
//        $username = $utils->getLastUsername();
        return $this->render('user/account/login.html.twig', [
//            'hasError' => $error !== null,
//            'username' => $username
            // last username entered by the user (if any)
            'last_username' => $helper->getLastUsername(),
            // last authentication error (if any)
            'error' => $helper->getLastAuthenticationError(),
        ]);
    }

    /**
     * permet de se deconnecter
     * @Route("/deconnexion", name="user_deconnexion")
     * @return void
     */
    public function deconnexion()
    {
        //automatic redirection
    }

    /**
     * @Route("/inscription", name="user_inscription")
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
            $user->setRole('user');

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash(
                'success',
                'Votre compte a bien été créé ! Vous pouvez maintenant vous connecter !'
            );

            return $this->redirectToRoute('user_connexion');
        }

        return $this->render('user/account/inscription.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/profil", name="user_profil")
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

        return $this->render('user/account/profil.html.twig', [
            'controller_name' => 'AccountController',
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de modifier le mot de passe
     *
     * @Route("/profil/password-update", name="profil_password")
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

            return $this->redirectToRoute('member_space');
        }

        return $this->render('user/account/pass.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
