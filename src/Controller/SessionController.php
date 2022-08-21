<?php

namespace App\Controller;

use App\Entity\Session;
use App\Repository\FormationRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class SessionController extends AbstractController
{
    protected $formationRepository;
    public function __construct(FormationRepository $formationRepository)
    {
        $this -> formationRepository = $formationRepository;
    }

    /**
     * @Route("/session", name="app_session")
     */
    public function index(ManagerRegistry $doctrine): Response
    {

        $sessions = $doctrine -> getRepository(Session::class) -> findBy([], ["lieu" => "ASC"]);
        return $this->render('session/index.html.twig', [
            "session" => $sessions,
        ]);
    }
}
