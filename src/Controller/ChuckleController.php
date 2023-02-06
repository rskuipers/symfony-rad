<?php

namespace App\Controller;

use App\Entity\Chuckle;
use App\Form\ChuckleType;
use App\Repository\ChuckleRepository;
use App\Repository\GiggleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ChuckleController extends AbstractController
{
    #[Route('/', name: 'app_chuckle_index', methods: ['GET'])]
    public function index(ChuckleRepository $chuckleRepository): Response
    {
        $form = $this->createForm(ChuckleType::class);

        return $this->render('chuckle/index.html.twig', [
            'chuckles' => $chuckleRepository->getTimeline(),
            'form' => $form->createView()
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

        return $this->render('chuckle/new.html.twig', [
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

            return $this->redirectToRoute('app_chuckle_show', ['id' => $chuckle->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('chuckle/edit.html.twig', [
            'chuckle' => $chuckle,
            'form' => $form,
        ]);
    }

    #[Route('/chuckle/{id}', name: 'app_chuckle_delete', methods: ['DELETE'])]
    public function delete(Request $request, Chuckle $chuckle, ChuckleRepository $chuckleRepository): Response
    {
        if ($this->isCsrfTokenValid('turbo', $request->headers->get('X-CSRF-Token'))) {
            $chuckleRepository->remove($chuckle, true);
        }

        return $this->redirectToRoute('app_chuckle_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/chuckle/{id}/giggle', name: 'app_chuckle_giggle', methods: ['POST'])]
    public function giggle(Chuckle $chuckle, HubInterface $hub, GiggleRepository $giggleRepository): Response
    {
        $giggleRepository->toggleGiggle($this->getUser(), $chuckle);

        $hub->publish(new Update('chuckles', $this->renderView('stream/giggles.stream.html.twig', ['chuckle' => $chuckle])));

        return new Response();
    }
}
