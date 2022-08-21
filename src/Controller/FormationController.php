<?php

namespace App\Controller;

use App\Entity\Formation;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


class FormationController extends AbstractController
{
    /**
     * @Route("/formation/{idFormation}", name="showDetailFormation")
     * @ParamConverter("formation", options={"mapping": {"idFormation": "id"}})
     * todo | cette fonction permet d'afficher les sessions d'une formation à l'aide de son id
     */
    public function show(Formation $formation): Response
    {
        return $this->render('session/index.html.twig', [

            'formation' => $formation,

        ]);
    }

}
