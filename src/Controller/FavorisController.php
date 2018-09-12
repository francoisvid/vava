<?php

namespace App\Controller;

use App\Entity\Favoris;
use App\Form\FavorisType;
use App\Repository\FavorisRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/favoris")
 */
class FavorisController extends AbstractController
{
    /**
     * @Route("/{id_user}.{id_entreprise}", name="favoris_add_entreprise", methods="GET|POST")
     */
    public function ajoutEnFavoris($id_user,$id_entreprise){


        return new Response("id user " . $id_user . " et id entreprise : ". $id_entreprise);

    }


}
