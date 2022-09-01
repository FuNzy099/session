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
     * @Route("/stagiaires", name="showListStagiaires")
     * todo | ManagerRegistry => permet d'interagir avec la base de donnée, c'est une classe implémenté avec Symfony.
     */
    public function show(\Doctrine\Persistence\ManagerRegistry $doctrine): Response

    {
        $stagiaires = $doctrine->getRepository(Stagiaire::class)->findAll();

        return $this->render('stagiaire/list.html.twig', [
            "stagiaires" => $stagiaires
            
        ]);
    }



    /**
     * @Route("/stagiaire/add", name="ajouterStagiaire")
     * ManagerRegistry => nous permet d'utiliser $doctrine qui est une couche d'abstraction qui permet de communiquer avec la base de donnée
     * todo | Stagiaire $stagiaire => c'est l'objet que je souhaite ajouter en base de donnée
     * todo | Request => Permet un echange entre le client et le serveur
     */
    public function ajouterStagiaire(ManagerRegistry $doctrine, Stagiaire $stagiaire = null, Request $request): Response
    {
        $stagiaire = new Stagiaire();

        // todo | createForm => permet de construire un formulaire qui ce repose sur le builder de StagiaireType qui proviennent eux même des propriétés de l'entity Stagiaires
        $form = $this->createForm(StagiairesType::class, $stagiaire);

        // todo | handleRequest permet de récuperer et d'analyser les données saisient dans le formulaire, c'est en gros le sasse entre la saisi du formulaire et l'envoi des données dans la base donnée
        $form->handleRequest($request);

        // todo | isSubmitted => c'est l'équivalent de isset($_POST["submit"]), en gros le formulaire est-il validé ?!
        // todo | isValid => c'est l'équivalent du filter_input, en gros il permet de verifier l'integrité des données saisient dans le formulaire
        if ($form->isSubmitted() && $form->isValid()) {

            // On appelle notre objet stagiaire dans le but de l'hydrater avec les données saisient dans le formulaire.
            $stagiaire = $form->getData();

            // On passe par un manager pour recuperer depuis l'objet doctrine notre getManager(), c'est grace à lui qu'on accede à persist() et flush()
            $entityManager = $doctrine->getManager();

            // On persist notre objet stagiaire, c'est l'équivalent de prepare() en PDO
            $entityManager->persist($stagiaire);

            // On flush, c'est l'equivalent de execute() en PDO,  c'est dans cette étape flush() que notre insert into ce fait 
            $entityManager->flush();

            return $this->redirectToRoute('showListStagiaires');
        }
        // ! $this->addFlash('success', 'Action completed successfully.');


        return $this->render('stagiaire/add.html.twig', [
            "formAddStagiaire" => $form->createView(),
        ]);
    }



    /**
     * @Route("/stagiaire/{id}/modifier", name="modifierStagiaire")
     */
    public function modifier(ManagerRegistry $doctrine, Stagiaire $stagiaire, Request $request)
    {

        // createForm permet de construire un formulaire qui ce repose sur le builder de StagiaireType qui proviennent eux même des propriétés de l'entity de Stagiaires
        $form = $this->createForm(StagiairesType::class, $stagiaire);

        // handleRequest permet de récupere et d'analyser les données saisient dans le formulaire, c'est en gros le sasse entre la saisi du formulaire et l'envoie des données dans la base de donnée
        $form->handleRequest($request);

        // isSubmitted ==> c'est l'équivalent de isset($_POST["submit"]), en gros le forulaire est-il validé ?!!
        // isValid ==> c'est l'équivalent du filter_input, en gros il permet de verifer l'integrité des données saisient dans le formulaire
        if ($form->isSubmitted() && $form->isValid()) {

            // On passe par un manager pour récuperer depuis l'obje doctrine notre gerManager(), c'est grace à lui qu'o accede à persist() et flush()
            $entityManager = $doctrine->getManager();

            // On persist notre objet stagiaire, c'est l'équivalent de prepare() en PDO
            $entityManager->persist($stagiaire);

            // On flush, c'est l'équivalent de execute() en PDO
            $entityManager->flush();

            return $this->redirectToRoute('showListStagiaires');
        }

        return $this->render('stagiaire/edit.html.twig', [
            "formModifierStagiaire" => $form->createView(),
        ]);
    }



    /**
     * @Route("/stagiaire/{id}/delete", name="deleteStagiaire")
     * ManagerRegistry => nous permet d'utiliser $doctrine qui est une couche d'abstration qui permet de comuniquer avec la base de donnée
     */
    public function supprimer(ManagerRegistry $doctrine, Stagiaire $stagiaire)
    {
        $entityManager = $doctrine->getManager();
        $entityManager->remove($stagiaire);           //// la function remove permet de supprimer à l'occurance ici un stagiaire
        $entityManager->flush();                      //// la function flush est l'équivalent de execute en PDO

        // Redirection vers la liste des stagiaires
        return $this->redirectToRoute("showListStagiaires");
    }
}
