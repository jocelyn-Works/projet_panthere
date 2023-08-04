<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(Security $security): Response
    {
        $user = $security->getUser();  // controlleur security pour récupere les utilisateurs
        return $this->render('home/index.html.twig', ['user' => $user]);
    }

   
}
