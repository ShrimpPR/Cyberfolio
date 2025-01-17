<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProjectController extends AbstractController
{
    #[Route('/project', name: 'project_main', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $projects = $entityManager->getRepository(Project::class)->findAll();

        return $this->render('project/index.html.twig', [
            'controller_name' => 'ProjectController',
            'projects' => $projects,
        ]);
    }

    #[Route('/project/add', name: 'project_add', methods: ['GET', 'POST'])]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $screenshot = $form->get('screenshot')->getData();

            if ($screenshot) {
                $newFilename = uniqid().'.'.$screenshot->guessExtension();
                try {
                    $screenshot->move(
                        $this->getParameter('project_screenshot_directory'),
                        $newFilename
                    );
                    $project->setScreenshot($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'An error occurred while uploading the screenshot.');
                }
            }

            $entityManager->persist($project);
            $entityManager->flush();

            return $this->redirectToRoute('project_main');
        }

        return $this->render('project/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/project/{id}', name: 'project_info', methods: ['GET'])]
    public function show(int $id, EntityManagerInterface $entityManager): Response
    {
        $project = $entityManager->getRepository(Project::class)->find($id);

        if (!$project) {
            throw $this->createNotFoundException('The project does not exist');
        }

        return $this->render('project/info.html.twig', [
            'project' => $project,
        ]);
    }

    #[Route('/project/{id}/edit', name: 'project_edit', methods: ['GET', 'POST'])]
    public function edit(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $project = $entityManager->getRepository(Project::class)->find($id);

        if (!$project) {
            throw $this->createNotFoundException('The project does not exist.');
        }

        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $screenshot = $form->get('screenshot')->getData();

            if ($screenshot) {
                $newFilename = uniqid().'.'.$screenshot->guessExtension();
                try {
                    $screenshot->move(
                        $this->getParameter('project_screenshot_directory'),
                        $newFilename
                    );
                    $project->setScreenshot($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'An error occurred while uploading the screenshot.');
                }
            }

            $entityManager->persist($project);
            $entityManager->flush();

            return $this->redirectToRoute('project_main');
        }

        return $this->render('project/edit.html.twig', [
            'form' => $form->createView(),
            'project' => $project,
        ]);
    }

    #[Route('/project/{id}', name: 'project_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(int $id, EntityManagerInterface $entityManager): Response
    {
        $project = $entityManager->getRepository(Project::class)->find($id);

        if (!$project) {
            throw $this->createNotFoundException('The project does not exist.');
        }

        $entityManager->remove($project);
        $entityManager->flush();

        return $this->redirectToRoute('project_main');
    }
}
