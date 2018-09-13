<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UtilisateurController extends AbstractController
{
    /**
     * @Route("/utilisateur", name="utilisateur")
     */
    public function index()
    {
        return $this->render('utilisateur/utilisateur.html.twig', [
            'controller_name' => 'UtilisateurController',
        ]);
    }
}
