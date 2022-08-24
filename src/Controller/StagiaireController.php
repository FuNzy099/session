<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StagiaireController extends AbstractController
{
    /**
     * @Route("/stagiaire", name="app_stagiaire")
     */
    public function index(): Response
    {
        return $this->render('stagiaire/index.html.twig', [
            'controller_name' => 'StagiaireController',
        ]);
    }

    /**
     * @Route("/stagiaire", name="ajouterStagiaire")
     */
    public function ajouterStagiaire(): Response
    {
        return $this->render('stagiaire/index.html.twig', [
            
        ]);
    }
}
