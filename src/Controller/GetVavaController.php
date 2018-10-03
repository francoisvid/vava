<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class GetVavaController extends AbstractController
{
    /**
     * @Route("/getvava", name="get_vava")
     */
    public function index()
    {
        return $this->render('get_vava/index.html.twig', [
            'controller_name' => 'GetVavaController',
        ]);
    }
}
