<?php

namespace App\Controller;

use App\Entity\Programme;
use App\Entity\Session;
use App\Entity\Stagiaire;
use App\Repository\SessionRepository;
use App\Repository\FormationRepository;
use Doctrine\Persistence\ManagerRegistry;
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
    public function show(ManagerRegistry $doctrine, SessionRepository $sr, Session $session): Response
    {
        //// 1er methode: On recupere la methode findNonInscri dans le getRepository de la class Session, elle nous permet d'afficher les stagiaire non inscrit
        //// $stagiaires = $doctrine -> getRepository(Session::class)-> findNonInscrit($session -> getId());

        // 2eme methode: Pour éviter l'erreur de la 1er méthode qui n'en est pas une, on peut injecter dans les paramètres de la function le SessionRepository pour récuperer la methode findNonInscrit
        $stagiaires = $sr->findNonInscrit($session->getId());

        $programmes = $doctrine -> getRepository(Programme::class)->findAll();

        // $programmes = $sr -> findNonProgrammer($session->getId());

        // var_dump($programmes);
        // die;

        return $this->render('session/detail.html.twig', [
            'session' => $session,
            'stagiaires' => $stagiaires,
            'programmes' => $programmes
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



    // --------------------- 

}
