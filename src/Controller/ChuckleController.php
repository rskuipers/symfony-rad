<?php

namespace App\Controller;

use App\Entity\Chuckle;
use App\Form\ChuckleType;
use App\Repository\ChuckleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ChuckleController extends AbstractController
{
    #[Route('/', name: 'app_chuckle_index', methods: ['GET'])]
    public function index(ChuckleRepository $chuckleRepository): Response
    {
        return $this->render('chuckle/index.html.twig', [
            'chuckles' => $chuckleRepository->findAll(),
        ]);
    }

    #[Route('/chuckle/new', name: 'app_chuckle_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function new(Request $request, ChuckleRepository $chuckleRepository): Response
    {
        $chuckle = new Chuckle($this->getUser());
        $form = $this->createForm(ChuckleType::class, $chuckle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $chuckleRepository->save($chuckle, true);

            return $this->redirectToRoute('app_chuckle_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('chuckle/new.html.twig', [
            'chuckle' => $chuckle,
            'form' => $form,
        ]);
    }

    #[Route('/chuckle/{id}', name: 'app_chuckle_show', methods: ['GET'])]
    public function show(Chuckle $chuckle): Response
    {
        return $this->render('chuckle/show.html.twig', [
            'chuckle' => $chuckle,
        ]);
    }

    #[Route('/chuckle/{id}/edit', name: 'app_chuckle_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Chuckle $chuckle, ChuckleRepository $chuckleRepository): Response
    {
        $form = $this->createForm(ChuckleType::class, $chuckle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $chuckleRepository->save($chuckle, true);

            return $this->redirectToRoute('app_chuckle_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('chuckle/edit.html.twig', [
            'chuckle' => $chuckle,
            'form' => $form,
        ]);
    }

    #[Route('/chuckle/{id}', name: 'app_chuckle_delete', methods: ['POST'])]
    public function delete(Request $request, Chuckle $chuckle, ChuckleRepository $chuckleRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$chuckle->getId(), $request->request->get('_token'))) {
            $chuckleRepository->remove($chuckle, true);
        }

        return $this->redirectToRoute('app_chuckle_index', [], Response::HTTP_SEE_OTHER);
    }
}
