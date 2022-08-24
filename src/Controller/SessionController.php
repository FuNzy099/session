<?php

namespace App\Controller;

use doctrine;
use App\Entity\Session;
use App\Entity\Stagiaire;
use App\Repository\FormationRepository;
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
     * todo | Je l'apelle dans le lien au sein de session/index.html.twig
     */
    public function show(Session $session): Response
    {
        return $this->render('session/detail.html.twig', [
            'session' => $session,
        ]);
    }
}
