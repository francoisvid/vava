<?php

namespace App\Controller;

use App\Entity\Adresse;
use App\Entity\AdresseEnteprise;
use App\Entity\Categorie;
use App\Entity\CategorieEntreprise;
use App\Entity\Entreprise;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class MapController extends AbstractController
{
    /**
     * @Route("/map", name="map")
     */
    public function index(Request $request)
    {
        // Je regarde ce qui est pas ser dans l'uri avec le parametre "q" que j'envoie dans ma fonction recherche
        $this->recherche($request->query->get('q'));

        $entreprise = array(
            array(
                'nom'=>'Nom 1',
                'adresse'=>'une adresse',
                'lo'=>37.4555,
                'la'=>41.455555,
                'pub'=>'une pub',
            ),
            array(
                'nom'=>'Nom 2',
                'adresse'=>'une adresse',
                'lo'=>59.4555,
                'la'=>41.455555,
                'pub'=>'une pub',
            ),
            array(
                'nom'=>'Nom 3',
                'adresse'=>'une adresse',
                'lo'=>34.4555,
                'la'=>41.455555,
                'pub'=>'une pub',
            )
        );

        $apikey = 'AIzaSyCiGsX1wx-4Ry3eEOG6HCT7e3Xz9lwJqZY';
        $ney = "sdqedqezdqzd";



        return $this->render('map/index.html.twig', [
            'controller_name' => 'MapController',
            'data' => $entreprise,
            'apikey' => 'test',
        ]);
    }

    public function recherche($data){
    //Init de categorie
    $newCategorie = new Categorie();

    //Init de l'adresse
    $newAdresse = new Adresse();

    //Init de entreprise
    $newEntreprise = new Entreprise();

    //Init de CategorieEntreprise
    $newCategorieEntreprise = new CategorieEntreprise();

    //Init de
    $newAdresseEntreprise = new AdresseEnteprise();


    //Je regarde si en bdd la data existe
    $newCategorie = $this->getDoctrine()->getRepository(Categorie::class)->findBy(array(
        'type' => $data
    ));

    // Si ell est null alors j'affiche un message de fail

    if (!empty($newCategorie)) {
        // On va get toutes les boites qui on la categorie rechercher
               $newCategorieEntreprise = $this->getDoctrine()->getRepository(CategorieEntreprise::class)->findBy(array(
                    'categorie' => $newCategorie[0]->getId()
                ));
                // On boucle pour recup le nom de l'entreprise via son id
               foreach ($newCategorieEntreprise as $entry){
                   //Je stocke le nom pour le reutiliser
                   $nomentreprise = $entry->getEntreprise()->getNom();

                   //Je regarde en bdd ce que contien mon enrtreprise via son nom
                   $entreprise = $newEntreprise = $this->getDoctrine()->getRepository(Entreprise::class)->findBy(array(
                       'nom' => $nomentreprise
                    ));

                   //Je boucle dans mon entreprise pour recup son id
                  foreach ($entreprise as $t){

                      $ide = $t->getId();
                      // Je me sert de son id pour aller chercher son adresse.
                      $yolo = $newAdresseEntreprise = $this->getDoctrine()->getRepository(AdresseEnteprise::class)->findBy(array(
                          'id'=> $ide
                      ));
                       //Je boucle pour sortir les info
                      foreach ($yolo as $y){


                          echo $nomentreprise .' ';
                          $y->getAdresse()->getNumero();
                          $y->getAdresse()->getRue();
                          $y->getAdresse()->getVille();
                          $y->getAdresse()->getCodePostal();
                          $y->getAdresse()->getLatitude();
                          $y->getAdresse()->getLongitude();
                          

                      }




                  }


               }











    }else{
        echo "Deso j'ai pas ca en bdd bb ";
    }



    }
}
