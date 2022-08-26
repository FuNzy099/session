<?php

namespace App\Controller;

use COM;
use App\Entity\Atelier;
use App\Entity\Session;
use App\Entity\Categorie;
use App\Form\AjouterAtelierType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AtelierController extends AbstractController
{
    /**
     * @Route("/atelier", name="app_atelier")
     */
    public function index(): Response
    {
        return $this->render('atelier/index.html.twig', [
            'controller_name' => 'AtelierController',
        ]);
    }
}
