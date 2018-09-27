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
        $q = $request->query->get('q');
        $c = $request->query->get('c');

        if(isset($c)){
            $recherche = $this->recherchedeuxpointzero($q, $c);
        }else{
            $recherche = null;
        }

        return $this->render('map/index.html.twig', [
            'controller_name' => 'MapController',
            'data' => $recherche,
            'apikey' => 'test',
        ]);
    }


////////////////////  OLD REHCERHCE ////////////////////

    public function recherche($data){

        if (!empty( $this->rechercheVille($data))){
            return  $this->rechercheVille($data);
        }elseif (!empty($this->rechercheCategorie($data))){
            return $this->rechercheCategorie($data);
        }elseif (!empty($this->rechercheAdresse($data))){
            return $this->rechercheAdresse($data);
        }else{
            $this->addFlash('error', "Deso j'ai pas en bdd ");
        }

    }

////////////////////  VILLE ////////////////////

    public function rechercheVille($data){

        $newAdresse = new Adresse();
        $newAdresseEntreprise = new AdresseEnteprise();
        $newEntreprise = new Entreprise();
        $newCategorieEntreprise = new CategorieEntreprise();

        $arrayVide = array();

        $newAdresse = $this->getDoctrine()->getRepository(Adresse::class)->findBy(array(
            'ville' => $data
        ));

        if (!empty($newAdresse)){

            foreach ($newAdresse as $na){

                $idadresse =  $na->getId();

                $newAdresseEntreprise = $this->getDoctrine()->getRepository(AdresseEnteprise::class)->findBy(array(
                    'id' => $idadresse
                ));

                $newEntreprise = $this->getDoctrine()->getRepository(Entreprise::class)->findBy(array(
                    'id'=>$newAdresseEntreprise
                ));

                foreach ($newEntreprise as $item) {

                    if($item->getIsDeleted() != 1){


                        $nou = array(
                            "id"=>$item->getId(),
                            "nom"=>$item->getNom(),
                            "tel"=>$item->getTel(),
                            "numero"=>$na->getNumero(),
                            "rue"=>$na->getRue(),
                            "ville"=>$na->getVille(),
                            "codePostal"=>$na->getCodePostal(),
                            "la"=>$na->getLatitude(),
                            "lo"=>$na->getLongitude(),
                        );
                    }



                    array_push($arrayVide, $nou);
                }

            }
            return $arrayVide;

        }

    }

////////////////////  CATEGORIE ////////////////////
    public function rechercheCategorie($data){
        //Init de l'adresse
        $newAdresse = new Adresse();
        //Init de entreprise
        $newEntreprise = new Entreprise();
        //Init de CategorieEntreprise
        $newCategorieEntreprise = new CategorieEntreprise();
        //Init de
        $newAdresseEntreprise = new AdresseEnteprise();
        //Init de categorie
        $newCategorie = new Categorie();

        $arrayVide = array();


        //Je regarde si en bdd la data existe
        $newCategorie = $this->getDoctrine()->getRepository(Categorie::class)->findBy(array(
            'type' => $data
        ));
        if (!empty($newCategorie)) {
            // On va get toutes les boites qui on la categorie rechercher
            $newCategorieEntreprise = $this->getDoctrine()->getRepository(CategorieEntreprise::class)->findBy(array(
                'categorie' => $newCategorie[0]->getId()
            ));
            // On boucle pour recup le nom de l'entreprise via son id
            foreach ($newCategorieEntreprise as $entry){
                //Je stocke le nom pour le reutiliser
                $nomentreprise = $entry->getEntreprise()->getNom();
                $tel = $entry->getEntreprise()->getTel();

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
                     if( $entry->getEntreprise()->getIsDeleted() != 1){
                        $nou = array(
                            "id"=>$ide,
                            "nom"=>$nomentreprise,
                            "tel"=>$tel,
                            "numero"=>  $y->getAdresse()->getNumero(),
                            "rue"=>$y->getAdresse()->getRue(),
                            "ville"=>$y->getAdresse()->getVille(),
                            "codePostal"=>$y->getAdresse()->getCodePostal(),
                            "la"=>$y->getAdresse()->getLatitude(),
                            "lo"=>$y->getAdresse()->getLongitude()
                        );

                        array_push($arrayVide, $nou);
                     }
                    }
                }

            }
            return $arrayVide;

        }
    }


////////////////////  ADRESSE ////////////////////
    public function rechercheAdresse($data){

        $newAdresse = new Adresse();
        $newAdresseEntreprise = new AdresseEnteprise();
        $newEntreprise = new Entreprise();
        $arrayVide = array();
        $newdata = str_replace("%20", " ", $data);
        $newAdresse = $this->getDoctrine()->getRepository(Adresse::class)->findBy(array(
            'rue' => $newdata
        ));
        if (!empty($newAdresse)){
            foreach ($newAdresse as $na){

               $idadresse =  $na->getId();

               $newAdresseEntreprise = $this->getDoctrine()->getRepository(AdresseEnteprise::class)->findBy(array(
                   'id' => $idadresse
               ));
               $newEntreprise = $this->getDoctrine()->getRepository(Entreprise::class)->findBy(array(
                    'id'=>$newAdresseEntreprise
                ));

                foreach ($newEntreprise as $item) {

                    if($item->getIsDeleted() != 1){
                        $nou = array(
                            "id"=>$item->getId(),
                            "nom"=>$item->getNom(),
                            "tel"=>$item->getTel(),
                            "numero"=>  $na->getNumero(),
                            "rue"=>$na->getRue(),
                            "ville"=>$na->getVille(),
                            "codePostal"=>$na->getCodePostal(),
                            "la"=>$na->getLatitude(),
                            "lo"=>$na->getLongitude()
                        );
                    }
                    array_push($arrayVide, $nou);
               }

            }
            return $arrayVide;
        }
    }

////////////////////  NOM ////////////////////
    public function rechercheNom($data){

        $newEntreprise = new Entreprise();
        $newAdresseEnteprise = new AdresseEnteprise();
        $newAdresse = new Adresse();
        $arrayVide = array();

        $newEntreprise = $this->getDoctrine()->getRepository(Entreprise::class)->findBy(array(
            "nom"=> $data
        ));

        foreach ($newEntreprise as $item) {
            if($item->getIsDeleted() != 1){
                $var = $newAdresseEnteprise = $this->getDoctrine()->getRepository(AdresseEnteprise::class)->findBy(array(
                    "entreprise" => $item->getId()
                ));
                foreach ($var as $i) {
                    $nou = array(
                        "id"=>$item->getId(),
                        "nom"=>$item->getNom(),
                        "tel"=>$item->getTel(),
                        "rue"=>$i->getAdresse()->getRue(),
                        "ville"=>$i->getAdresse()->getVille(),
                        "codePostal"=>$i->getAdresse()->getCodePostal(),
                        "la"=>$i->getAdresse()->getLatitude(),
                        "lo"=>$i->getAdresse()->getLongitude()
                    );
               }
            }
            array_push($arrayVide, $nou);
        }
        return $arrayVide;
    }


////////////////////  NEW REHCERHCE ////////////////////

    public function recherchedeuxpointzero($q, $c){
        //Le but est de metre a jour la fonction
        // De recherche sur le site qui n'etait pas optimiser
        // Q = VILLE C = NOM OU CAT

        $nc = str_replace("%20", " ", $c);

        $vide = array();

        if ($this->rechercheCategorie($nc)){

            $result = $this->rechercheCategorie($nc);

            foreach ($result as $r){

                $ville = $r["ville"];
                if ($ville == $q){
                    array_push($vide, $r);
                }

            }
            return $vide;

        }elseif ($this->rechercheNom($nc)){

            $result = $this->rechercheNom($nc);


            foreach ($result as $r){

                $ville = $r["nom"];
                if ($ville == $nc){
                    array_push($vide, $r);
                }

            }
            return $vide;
        }elseif($this->rechercheVille($q)){
            return $this->rechercheVille($q);
        }else{
            $this->addFlash('error', "Deso j'ai pas en bdd ");
        }


    }



}
