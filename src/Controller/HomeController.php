<?php

namespace App\Controller;

use App\Entity\Formation;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    // ! Pour le moment sert à rien => A verifier !!!!!!!
    /**
     * @Route("/home", name="app_home")
     * todo | cette fonction permet d'afficher les formations dans le home
     * todo | ManagerRegistry => permet d'interagir avec la base de donnée, c'est une classe implémenté avec Symfony.
     */
    public function index(\Doctrine\Persistence\ManagerRegistry $doctrine): Response
    {
        // nous allons chercher la methode findAll qui ce trouve dans le repository de la class Formation, elle nous permettra d'afficher toutes les formations dans la base de donnée
        $formations = $doctrine->getRepository(Formation::class)->findAll();
        
        // Le render est la méthode qui permet d'envoyer le résultat dans la vue
        return $this->render('home/index.html.twig', [
            'formations' => $formations,
        ]);
    }
}
