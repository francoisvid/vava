<?php

namespace App\Controller;

use App\Entity\Adresse;
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


    //Je regarde si en bdd la data existe
    $newCategorie = $this->getDoctrine()->getRepository(Categorie::class)->findBy(array(
        'type' => $data
    ));



    // Si ell est null alors j'affiche un message de fail

   // Utiliser un switch qui sera mieux !      

    if (!empty($newCategorie)) {
        // On va get toutes les boites qui on la categorie rechercher
        // On va les foutre dans un array histoire de la passer dans ma vue soon pour afficher les points

       $newCategorieEntreprise = $this->getDoctrine()->getRepository(CategorieEntreprise::class)->findBy(array(
            'categorie' => $newCategorie[0]->getId()
        ));

       var_dump($newCategorieEntreprise);







    }else{
        echo "Deso j'ai pas ca en bdd bb ";
    }



    }
}
