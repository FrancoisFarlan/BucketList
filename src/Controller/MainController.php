<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{

    /**
     * @Route("/", "main_home")
     */
    public function home(): Response {
        return $this->render("main/home.html.twig");
    }

    /**
     * @Route("/about", "main_about")
     */
    public function about(): Response {

        $teamData = file_get_contents("../data/team.json");
        $teamMembers = json_decode($teamData, true);

        return $this->render("main/aboutus.html.twig", [
            'teamMembers' => $teamMembers
        ]);
    }

}