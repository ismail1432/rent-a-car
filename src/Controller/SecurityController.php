<?php

namespace App\Controller;

use App\Entity\Token;
use App\Entity\User;
use App\Form\RegistrationType;
use App\Security\LoginFormAuthenticator;
use App\Services\TokenSendler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/registration", name="registration")
     *
     */
    public function registration(
        Request $request,
        EntityManagerInterface $manager,
        GuardAuthenticatorHandler $guardAuthenticatorHandler,
        LoginFormAuthenticator $loginFormAuthenticator,
        UserPasswordEncoderInterface $passwordEncoder,
        TokenSendler $tokenSendler
    ) {
        $user = new User;

        $form = $this->createForm(RegistrationType::class, $user);

        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $passwordEncoded = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($passwordEncoded);
            $user->setRoles(['ROLE_ADMIN']);

            $token = new Token($user);
            $manager->persist($token);
            $manager->flush();

            $tokenSendler->sendToken($user, $token);

            $this->addFlash(
                'notice',
                "Un email de confirmation vous a été envoyé, veuillez cliquer sur le lien présent dans l'email"
            );

            return $this->redirectToRoute('home');
        }

        return $this->render('security/registration.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/confirmation/{value}", name="token_validation")
     *
     */
    public function validateToken(Token $token, EntityManagerInterface $manager, GuardAuthenticatorHandler $guardAuthenticatorHandler, LoginFormAuthenticator $loginFormAuthenticator, Request $request)
    {
        $user = $token->getUser();

        if ($user->getEnable()) {
            $this->addFlash(
                'notice',
                "Ce token est déjà validé !"
            );

            return $this->redirectToRoute('home');
        }

        if ($token->isValid()) {
            $user->setEnable(true);
            $manager->flush();

            return $guardAuthenticatorHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $loginFormAuthenticator,
                'main'
            );
        }

        $manager->remove($token);
        $manager->flush();

        $this->addFlash(
            'notice',
            "Le token est expiré, Inscrivez-vous à nouveau"
        );

        return $this->redirectToRoute('registration');
    }
}
