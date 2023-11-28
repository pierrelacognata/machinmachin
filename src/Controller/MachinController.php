<?php

namespace App\Controller;

use App\Entity\Machin;
use App\Form\MachinType;
use App\Repository\MachinRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/machin')]
class MachinController extends AbstractController
{
    #[Route('/', name: 'app_machin_index', methods: ['GET'])]
    public function index(MachinRepository $machinRepository): Response
    {
        return $this->render('machin/index.html.twig', [
            'machins' => $machinRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_machin_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $machin = new Machin();
        $form = $this->createForm(MachinType::class, $machin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($machin);
            $entityManager->flush();

            return $this->redirectToRoute('app_machin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('machin/new.html.twig', [
            'machin' => $machin,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_machin_show', methods: ['GET'])]
    public function show(Machin $machin): Response
    {
        return $this->render('machin/show.html.twig', [
            'machin' => $machin,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_machin_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Machin $machin, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MachinType::class, $machin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_machin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('machin/edit.html.twig', [
            'machin' => $machin,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_machin_delete', methods: ['POST'])]
    public function delete(Request $request, Machin $machin, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$machin->getId(), $request->request->get('_token'))) {
            $entityManager->remove($machin);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_machin_index', [], Response::HTTP_SEE_OTHER);
    }
}
