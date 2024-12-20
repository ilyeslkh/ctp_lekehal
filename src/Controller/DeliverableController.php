<?php

namespace App\Controller;

use App\Entity\Deliverable;
use App\Form\DeliverableType;
use App\Repository\DeliverableRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/deliverable')]
class DeliverableController extends AbstractController
{
    #[Route('/', name: 'deliverable_index', methods: ['GET'])]
    public function index(DeliverableRepository $deliverableRepository): Response
    {
        return $this->render('deliverable/index.html.twig', [
            'deliverables' => $deliverableRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'deliverable_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $deliverable = new Deliverable();
        $form = $this->createForm(DeliverableType::class, $deliverable);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($deliverable);
            $entityManager->flush();

            return $this->redirectToRoute('deliverable_index');
        }

        return $this->render('deliverable/new.html.twig', [
            'deliverable' => $deliverable,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'deliverable_show', methods: ['GET'])]
    public function show(Deliverable $deliverable): Response
    {
        return $this->render('deliverable/show.html.twig', [
            'deliverable' => $deliverable,
        ]);
    }

    #[Route('/{id}/edit', name: 'deliverable_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Deliverable $deliverable, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DeliverableType::class, $deliverable);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('deliverable_index');
        }

        return $this->render('deliverable/edit.html.twig', [
            'deliverable' => $deliverable,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'deliverable_delete', methods: ['POST'])]
    public function delete(Request $request, Deliverable $deliverable, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$deliverable->getId(), $request->request->get('_token'))) {
            $entityManager->remove($deliverable);
            $entityManager->flush();
        }

        return $this->redirectToRoute('deliverable_index');
    }
}
