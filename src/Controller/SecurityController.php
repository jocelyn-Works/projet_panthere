<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/inscription', name: 'inscription')]
    public function index(Request $request, EntityManagerInterface $em,
     UserPasswordHasherInterface $passwordHasher): Response
    {
        $newUser = new User();  // on crée un utilisateur
        $userForm = $this->createForm(UserType::class, $newUser);  // on récupére le formulaire user pour un nouvelle utilisateur
        $userForm->handleRequest($request);  // vlidation des donné du formulaire

        if($userForm->isSubmitted() && $userForm->isValid()) {  // si le formulaire est valid et soumis alors ->

            $hash = $passwordHasher->hashPassword($newUser,  $newUser->getPassword()); // hashage du mot de pass
            $newUser->setPassword($hash);  // on applique le hashage

            $em->persist($newUser);  // on prépare un nouveu utilisateur a etre en BDD
            $em->flush();  // on enregistre en BDD

            return $this->redirectToRoute('home'); // une fois fini on redirige lutilisateurs vers "home"
        }
        return $this->render('security/inscription.html.twig', ['form' => $userForm->createView()]);
        // cette fonction utilise le HTML dans le dossier security -> inscription.html.twig , on crée la vue du formulaire pour s'inscrire
    }

    #[Route('/connexion', name: 'connexion')]
    public function connexion(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError(); // erreur lors de lauthentification de l'utilisateur
        $username = $authenticationUtils->getLastUsername();  // on récupére le dernier identifiant saisi lors de l'authentification

        return $this->render('security/connexion.html.twig', [
        'error' => $error,  // retourner les erreur
        'username' => $username]);  // username = l'authentifiant utilisateur définit dans Entity getUserIdentifier
        
    }

    #[Route('/logout', name: 'logout')]
    public function logout() // function pour se déconecter défini dans service.yaml
        {}
   
}

