<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $users = $entityManager->getRepository(User::class)->findAll();

        return $this->render('home/index.html.twig', [
            'message' => 'Welcome to my Cyberfolio!',
            'text' => 'This project is a simple portfolio created in the context of my Symfony classes.',
            'users' => $users,
        ]);
    }
}
