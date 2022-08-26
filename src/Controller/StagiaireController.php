<?php

namespace App\Controller;

use App\Entity\Stagiaire;
use App\Form\StagiairesType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StagiaireController extends AbstractController
{
    /**
     * @Route("/stagiaires", name="showListStagiaire")
     * todo | cette fonction permet d'afficher tous les stagiaires
     * todo | ManagerRegistry => permet d'interagir avec la base de donnée, c'est une classe implémenté avec Symfony.
     */
    public function show(\Doctrine\Persistence\ManagerRegistry $doctrine): Response

    {
        $stagiaires = $doctrine -> getRepository(Stagiaire::class) -> findAll();

        return $this->render('stagiaire/list.html.twig', [
            "stagiaires" => $stagiaires
        ]);
    }

    /**
     * @Route("/stagiaires/add", name="ajouterStagiaire")
     * todo | ManagerRegistry => nous permet d'utiliser $doctrine qui est une couche d'abstraction qui permet de communiquer avec la base de donnée
     * todo | Stagiaire $stagiaire => c'est l'objet que je souhaite ajouter en base de donnée
     * todo | Request => Permet un echange entre le client et le serveur
     */
    public function ajouterStagiaire(ManagerRegistry $doctrine, Stagiaire $stagiaire = null, Request $request ): Response
    {
        $stagiaire = new Stagiaire();

        // todo | createForm => permet de construire un formulaire qui ce repose sur le builder de StagiaireType qui proviennent eux même de l'entity Stagiaires
        $form = $this -> createForm(StagiairesType::class, $stagiaire);

        // todo | handleRequest permet de récuperer et d'analyser les données saisient dans le formulaire, c'est en gros le sasse entre la saisi du formulaire et l'envoi dans la base donnée
        $form -> handleRequest($request);

        // todo | isSubmitted => c'est l'équivalent de isset($_POST["submit"]), en gros le formulaire est-il validé ?!
        // todo | isValid => c'est l'équivalent du filter_input, en gros il permet de verifier l'integrité des données saisient dans le formulaire
        if($form -> isSubmitted() && $form -> isValid()){

            // on appelle notre objet stagiaire dans le but de l'hydrater avec les données saisient dans le formulaire.
            $stagiaire = $form -> getData();

            // On passe par un manager pour recuperer depuis l'objet doctrine notre getManager(), c'est grace à lui qu'on accede à persist() et flush()
            $entityManager = $doctrine -> getManager();

            // On persist notre objet stagiaire, c'est l'équivalent de prepare() en PDO
            $entityManager -> persist($stagiaire);

            // On flush, c'est l'equivalent de execute() en PDO,  c'est dans cette étape flush() que notre insert into ce fait 
            $entityManager -> flush();

            return $this -> redirectToRoute('showListStagiaire');
        }


        return $this->render('stagiaire/add.html.twig', [
            "formAddStagiaire" => $form -> createView(),
        ]);
    }
}
