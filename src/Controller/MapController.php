<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MapController extends AbstractController
{
    /**
     * @Route("/map", name="map")
     */
    public function index()
    {
        return $this->render('map/utilisateur.html.twig', [
            'controller_name' => 'MapController',
        ]);
    }
}
