<?php

namespace App\Controller;

use App\Entity\Technology;
use App\Form\TechnologyType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TechController extends AbstractController
{
    #[Route('/technology', name: 'technology_main', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $technologies = $entityManager->getRepository(Technology::class)->findAll();

        return $this->render('tech/index.html.twig', [
            'controller_name' => 'TechnologyController',
            'technologies' => $technologies,
        ]);
    }

    #[Route('/technology/add', name: 'technology_add', methods: ['GET', 'POST'])]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $technology = new Technology();

        $form = $this->createForm(TechnologyType::class, $technology);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($technology);
            $entityManager->flush();

            return $this->redirectToRoute('technology_main');
        }

        return $this->render('tech/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/technology/{id}', name: 'technology_info', methods: ['GET'])]
    public function info(int $id, EntityManagerInterface $entityManager): Response
    {
        $technology = $entityManager->getRepository(Technology::class)->find($id);

        if (!$technology) {
            throw $this->createNotFoundException('The technology does not exist');
        }

        return $this->render('tech/info.html.twig', [
            'technology' => $technology,
        ]);
    }

    #[Route('/technology/{id}/edit', name: 'technology_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $technology = $entityManager->getRepository(Technology::class)->find($id);

        if (!$technology) {
            throw $this->createNotFoundException('The technology does not exist.');
        }

        $form = $this->createForm(TechnologyType::class, $technology);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('technology_main');
        }

        return $this->render('tech/edit.html.twig', [
            'form' => $form->createView(),
            'technology' => $technology,
        ]);
    }

    #[Route('/technology/{id}', name: 'technology_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(int $id, EntityManagerInterface $entityManager): Response
    {
        $technology = $entityManager->getRepository(Technology::class)->find($id);

        if (!$technology) {
            throw $this->createNotFoundException('The technology does not exist.');
        }

        $entityManager->remove($technology);
        $entityManager->flush();

        return $this->redirectToRoute('technology_main');
    }
}
