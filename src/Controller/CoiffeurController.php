<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Coiffeur;

class CoiffeurController extends AbstractController
{
    /**
     * @Route("/coiffeurs/", name="coiffeurs")
     */
    public function index(): Response
    {

        $coiffeurs = $this->getDoctrine()->getRepository(Coiffeur::class)->findAll();

        return $this->render('coiffeur/index.html.twig', [
            'coiffeurs' => $coiffeurs,
        ]);
    }

    /**
     * @Route("/coiffeurs/modifier-{slug}-{id}/", name="modifier_coiffeur", requirements={"slug"="^[a-z0-9](-?[a-z0-9])*$"})
     */
    public function modifier($id): Response
    {

        $coiffeur = $this->getDoctrine()->getRepository(Coiffeur::class)->find($id);

        return $this->render('coiffeur/modifier.html.twig', [
            'coiffeur' => $coiffeur,
        ]);
    }
}
