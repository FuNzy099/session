<?php

namespace App\Controller;

use App\Entity\Atelier;
use App\Form\AjouterAtelierType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

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

       /**
     * @Route("/atelier/{id}", name="ajouterAtelier")
     * todo | ManagerRegistry => couche d'abstraction qui permet de communiquer avec la base de donnée
     * todo | Request => Permet un echange entre le client et le serveur
     */
    public function ajouterAtelier(ManagerRegistry $doctrine, Atelier $atelier, Request $request): Response
    {
        // todo | createForm => permet de créer automatiquement un formulaire qui ce base sur le bundler AjouterAtelierType qui ce base lui même sur l'objet atelier 
        $form = $this->createForm(AjouterAtelierType::class, $atelier); 

        // todo | handleRequest permet de récupérer et analyser les données
        $form -> handleRequest($request);

        if($form -> isSubmitted() && $form -> isValid()){
            $atelier = $form -> getData();
            $entityManager = $doctrine -> getManager();
            $entityManager -> persist($atelier);
            $entityManager -> flush();
        }


        return $this->render('atelier/add.html.twig', [
            
        ]);
    }
}
