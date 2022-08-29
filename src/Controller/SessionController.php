<?php

namespace App\Controller;

use App\Entity\Session;
use App\Entity\Stagiaire;
use App\Form\AddStagiaireSessionType;
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
        $this -> formationRepository = $formationRepository;
    }



    /**
     * @Route("/session/{idSession}", name="showDetailsSession")
     * @ParamConverter("session", options={"mapping": {"idSession": "id"}})
     * todo | Cette function permet d'afficher les infos d'une session (ex: liste des stagiaires, programme de la session, etc etc)
     * todo | Je l'appelle dans le lien au sein de session/index.html.twig
     */
    public function show(Session $session): Response
    {

        
        return $this->render('session/detail.html.twig', [
            'session' => $session,
        ]);



    }


    /**
     * @Route("/session/add/{idSession}", name="addStagiaireSession")
     * @ParamConverter("session", options={"mapping": {"idSession": "id"}})
     * todo | Je l'appelle dans le lien au sein de session/index.html.twig
     */
    public function addStagiaireSession(ManagerRegistry $doctrine, Stagiaire $stagiaire, Request $request, Session $session){
      
        // Permet de créer le formulaire à partir du builder de AddStagiaireSessionType qui est lui même construit des propriete de l'entity Stagiaire
        $form = $this -> createForm(AddStagiaireSessionType::class, $stagiaire);

        // la function handleRequest permet de récuperer et d'analyser les données saisi dans le formulaire, c'est le sasse entre la saisi formulaire en l'envoie des données dans la base de donnée
        $form -> handleRequest($request);
       
        // isSubmitter ==> Permet de verifier si le formulaire et validé, c'est l'équivalent du $_POST["submit]
        // isValid ==> Permet de verifier l'intégrité des données saisi, c'est l'équivalent du filter_input
        if($form -> isSubmitted() && $form -> isValid()){

            $stagiaire = $form -> getData();

            $entityMangager = $doctrine -> getManager();

            $entityMangager -> persist($stagiaire);

            $entityMangager -> flush();

            return $this -> redirectToRoute('showDetailsSession');

        }

        return $this -> render('session/addStagiaire.html.twig', [
            "formAddStagiaireSession" => $form -> createView(),
        ]);

    }
}
