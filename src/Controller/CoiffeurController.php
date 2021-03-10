<?php

namespace App\Controller;

use App\Entity\Coiffeur;
use App\Form\CoiffeurType;
use App\Repository\CoiffeurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/coiffeur")
 */
class CoiffeurController extends AbstractController
{
    /**
     * @Route("/coiffeurs/", name="coiffeurs", methods={"GET"})
     */
    public function index(CoiffeurRepository $coiffeurRepository): Response
    {
        return $this->render('coiffeur/index.html.twig', [
            'coiffeurs' => $coiffeurRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="coiffeur_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $coiffeur = new Coiffeur();
        $form = $this->createForm(Coiffeur1Type::class, $coiffeur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($coiffeur);
            $entityManager->flush();

            return $this->redirectToRoute('coiffeurs');
        }

        return $this->render('coiffeur/new.html.twig', [
            'coiffeur' => $coiffeur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="coiffeur_show", methods={"GET"})
     */
    public function show(Coiffeur $coiffeur): Response
    {
        return $this->render('coiffeur/show.html.twig', [
            'coiffeur' => $coiffeur,
        ]);
    }

    /**
     * @Route("/coiffeurs/modifier-{slug}-{id}/", name="modifier_coiffeur", methods={"GET","POST"}, requirements={"slug"="^[a-z0-9](-?[a-z0-9])*$"})
     */
    public function edit(Request $request, Coiffeur $coiffeur): Response
    {
        $form = $this->createForm(CoiffeurType::class, $coiffeur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('coiffeurs');
        }

        return $this->render('coiffeur/edit.html.twig', [
            'coiffeur' => $coiffeur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="coiffeur_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Coiffeur $coiffeur): Response
    {
        if ($this->isCsrfTokenValid('delete'.$coiffeur->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($coiffeur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('coiffeur_index');
    }
}
