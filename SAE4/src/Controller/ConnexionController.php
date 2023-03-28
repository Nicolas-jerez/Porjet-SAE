<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;


class ConnexionController extends AbstractController
{
    #[Route('/connexion', name: 'connexion')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('connexion/index.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }
    #[Route('/deconnexion', name: 'deconnexion',methods: ["GET"])]
    public function logout(): void{
        // controller can be blank: it will never be called!
    }

    #[Route('/inscription', name: 'inscription',methods:['GET','POST'])]
    public function inscription(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainTextPassword = $passwordHasher->hashPassword(
                $user,
                $user->getPassword()
            );
            $user->setPassword($plainTextPassword);
            $userRepository->save($user, true);
        }
        return $this->render('inscription/index.html.twig', [
            'form' => $form,
        ]);
    }
}
