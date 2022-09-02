<?php

namespace App\Controller;

use App\Entity\Atelier;
use App\Entity\Session;
use App\Entity\Programme;
use App\Entity\Stagiaire;
use App\Form\ProgrammeType;
use App\Repository\SessionRepository;
use App\Repository\FormationRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


class SessionController extends AbstractController
{
    protected $formationRepository;
    public function __construct(FormationRepository $formationRepository)
    {
        $this->formationRepository = $formationRepository;
    }



    // --------------------- Affichage des informations générale d'une session (liste des stagiaires inscrit/non inscrit, programme de la session, etc etc)

    /**
     * @Route("/session/{id}", name="showDetailsSession")
     */
    public function show(SessionRepository $sr, Session $session): Response
    {
        //// 1er methode: On recupere la methode findNonInscri dans le getRepository de la class Session, elle nous permet d'afficher les stagiaire non inscrit
        //// $stagiaires = $doctrine -> getRepository(Session::class)-> findNonInscrit($session -> getId());

        // 2eme methode: Pour éviter l'erreur de la 1er méthode qui n'en est pas une, on peut injecter dans les paramètres de la function le SessionRepository pour récuperer la methode findNonInscrit
        $stagiaires = $sr->findNonInscrit($session->getId());

        $nonProgrammes = $sr->findNonProgrammer($session->getId());

        return $this->render('session/detail.html.twig', [
            'session' => $session,
            'stagiaires' => $stagiaires,
            'nonProgrammes' => $nonProgrammes,
            // 'ateliers' => $ateliers
        ]);
    }



    // --------------------- Inscription d'un stagiaire à la session

    /**
     * @Route("/session/addStagiaire/{idSession}/{idStagiaire}", name="addStagiaireSession")
     * @ParamConverter("session", options={"mapping":{"idSession":"id"}} )
     * @ParamConverter("stagiaire", options={"mapping":{"idStagiaire":"id"}} )
     */
    public function inscription(ManagerRegistry $doctrine, Session $session, Stagiaire $stagiaire): Response
    {

        // On récupere la methode addStagiaire() qui ce trouve dans l'entity Session
        $session->addStagiaire($stagiaire);

        // On passe par un manager pour recuperer depuis l'objet doctrine notre getManager(), c'est grace à lui qu'on accede à persist() et flush()
        $entityManager = $doctrine->getManager();

        // On persist notre objet stagiaire, c'est l'équivalent de prepare() en PDO
        $entityManager->persist($stagiaire);

        // On flush, c'est l'equivalent de execute() en PDO,  c'est dans cette étape flush() que notre insert into ce fait 
        $entityManager->flush();

        // On fais un redirection vers la vue
        return $this->redirectToRoute('showDetailsSession', ['id' => $session->getId()]);
    }



    // --------------------- Désinscription d'un stagiaire à la session formation

    /**
     * @Route("/session/deleteStagiaire/{idSession}/{idStagiaire}", name="deleteStagiaireSession")
     * @ParamConverter("session", options={"mapping":{"idSession":"id"}} )
     * @ParamConverter("stagiaire", options={"mapping":{"idStagiaire":"id"}} )
     */
    public function desinscription(ManagerRegistry $doctrine, Session $session, Stagiaire $stagiaire): Response
    {

        // On rcupere la methode removeStagiaire() qui ce trouve dans l'entity Session
        $session->removeStagiaire($stagiaire);

        // On passe par un manager pour recuperer depuis l'objet doctrine notre getManager(), c'est grace à lui qu'on accede à persist() et flush()
        $entityManager = $doctrine->getManager();

        // On flush, c'est l'equivalent de execute() en PDO,  c'est dans cette étape flush() que notre insert into ce fait 
        $entityManager->flush();

        // On fais un redirection vers la vue
        return $this->redirectToRoute('showDetailsSession', ['id' => $session->getId()]);
    }




    // --------------------- Programmer un atelier dans une session

    /**
     * @Route("/session/addProgrammeSession/{idSession}/{idAtelier}", name="addProgrammeSession")
     * @ParamConverter("session", options={"mapping":{"idSession":"id"}} )
     * @ParamConverter("atelier", options={"mapping":{"idAtelier":"id"}} )
     */
    public function programmer(ManagerRegistry $doctrine, Session $session, Programme $programme = null, Atelier $atelier): Response
    {

        if (!empty($_POST)) {

            //// Je me suis documenté à l'aide de la doc symfony:
            //// https://symfony.com/doc/current/doctrine.html => paragraphe Persisting Objects to the Database

            // On instancie un nouveau objet programme
            $programme = new Programme();

            // On récupére à l'aide du formulaire le nbJours de l'atelier grace à la super global $_POST
            $nbJours = $_POST["nbJours"];


            $programme->setNbJours($nbJours);


            $programme->setAtelier($atelier);


            $programme->setSession($session);


            // On récupere la methode addProgrammesSessio() qui ce trouve dans l'entity Session
            $session->addProgrammesSession($programme);

            // On passe par un manager pour recuperer depuis l'objet doctrine notre getManager(), c'est grace à lui qu'on accede à persist() et flush()
            $entityManager = $doctrine->getManager();

            // On persist notre objet stagiaire, c'est l'équivalent de prepare() en PDO
            $entityManager->persist($programme);

            $entityManager->persist($session);

            $entityManager->persist($atelier);

            // On flush, c'est l'equivalent de execute() en PDO,  c'est dans cette étape flush() que notre insert into ce fait 
            $entityManager->flush();

            // On fais un redirection vers la vue
            return $this->redirectToRoute('showDetailsSession', ['id' => $session->getId()]);
        }
    }



    // --------------------- Déprogrammer un atelier d'une session

    /**
     * @Route("/session/deleteProgrammeSession/{idSession}/{idProgramme}", name="deleteProgrammeSession")
     * @ParamConverter("session", options={"mapping":{"idSession":"id"}} )
     * @ParamConverter("programme", options={"mapping":{"idProgramme":"id"}} )
     */
    public function deprogrammer(ManagerRegistry $doctrine, Session $session, Programme $programme): Response
    {

        // On rcupere la methode removeProgrammesSession() qui ce trouve dans l'entity Session
        $session->removeProgrammesSession($programme);

        // On passe par un manager pour recuperer depuis l'objet doctrine notre getManager(), c'est grace à lui qu'on accede à persist() et flush()
        $entityManager = $doctrine->getManager();

        // On flush, c'est l'equivalent de execute() en PDO,  c'est dans cette étape flush() que notre insert into ce fait 
        $entityManager->flush(null);

        // On fais un redirection vers la vue
        return $this->redirectToRoute('showDetailsSession', ['id' => $session->getId()]);
    }
}
