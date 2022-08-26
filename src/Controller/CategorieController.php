<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategorieController extends AbstractController
{
    /**
     * @Route("/categorie", name="app_categorie")
     */
    public function index(): Response
    {
        return $this->render('categorie/index.html.twig', [
            'categorie_name' => 'CategorieController',
        ]);
    }


     /**
     * @Route("/categorie/add", name="ajouterCategorie")
     * todo | ManagerRegistry => nous permet d'utiliser $doctrine qui est une couche d'abstraction qui permet de communiquer avec la base de donnée
     * todo | Atelier $atelier => c'est l'objet que je souhaite ajouter en base de donnée
     * todo | Request => Permet un echange entre le client et le serveur
     */
    public function ajouterCategorie(ManagerRegistry $doctrine, Categorie $categorie, Request $request): Response
    {
        if(!$categorie){
            $categorie = new Categorie();
        }
        

        // todo | createForm => permet de construire un formulaire qui ce repose sur le builder de AjouterAtelierType qui proviennent eux même de l'entity Atelier
        $form = $this->createForm(CategorieType::class, $categorie); 

        // todo | handleRequest permet de récupérer et analyser les données saisis dans le formulaire, c'est en gros le sasse entre la saisi du formulaire et l'envoi dans la base donnée
        $form -> handleRequest($request);

        // todo | isSubmitted => c'est l'équivalent de isset($_POST["submit"]), en gros le formulaire est-il validé ?!
        // todo | isValid => c'est l'équivalent du filter_input, en gros il permet de verifier l'integrité des données saisient dans le formulaire
        if($form -> isSubmitted() && $form -> isValid()){

            // On apelle notre objet atelier dans le but de l'hydrater avec les données saisient dans le formulaire.
            // Si on aurait voulu hydrater uniquement le titre ==> $truc = $bidulle["titre"] -> getData()
            $categorie = $form -> getData();


            // On passe par un manager pour récuperer depuis l'objet $doctrine notre getManager(), c'est grace à cette methode qu'on à accès à persist() et flush()
            $entityManager = $doctrine -> getManager();

            // On persist notre objet $atelier, c'est l'équivalent de notre prepare() en PDO 
            $entityManager -> persist($categorie);

            // On flush, c'est l'équivalent de notre execute() en PDO, c'est dans cette étape flush() que notre insert into ce fait 
            $entityManager -> flush();

            // return $this -> redirectToRoute('showDetailsSession', array("idSession"=>$session->getId()) );
        }

        //  Vue pour afficher le formulaire d'ajout d'un atelier
        return $this->render('categorie/add.html.twig', [
            "formAddCategorie" => $form -> createView(),
            // "editAtelier" => $atelier -> getId()
        ]);
    }

}
