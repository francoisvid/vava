<?php

namespace App\Controller;

use App\Entity\Adresse;
use App\Entity\AdresseEnteprise;
use App\Entity\Categorie;
use App\Entity\CategorieEntreprise;
use App\Entity\Entreprise;
use Doctrine\Common\Persistence\ObjectManager;
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
            $recherche = $this->Coucou($q, $c);
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
                    'adresse' => $idadresse
                ));

                foreach ($newAdresseEntreprise as $i) {

                $newEntreprise = $this->getDoctrine()->getRepository(Entreprise::class)->findBy(array(
                    'id'=> $i->getEntreprise()
                ));



                foreach ($newEntreprise as $item) {



                    if($item->getIsDeleted() != 1){


                        $nou = array(
                            "id"=>$item->getId(),
                            "nom"=>$item->getNom(),
                            "tel"=>$item->getTel(),
                            "logo"=>$item->getLogo(),
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


        $em = $this->getDoctrine()->getManager();

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
                // entreprise

                $ent = $em->find(Entreprise::class, $entry->getEntreprise());

                //Je stocke le nom pour le reutiliser
                $nomentreprise = $ent->getNom();
                $tel = $ent->getTel();


                //Je regarde en bdd ce que contien mon enrtreprise via son nom
                $entreprise = $newEntreprise = $this->getDoctrine()->getRepository(Entreprise::class)->findBy(array(
                    'nom' => $nomentreprise
                ));

                //Je boucle dans mon entreprise pour recup son id
                foreach ($entreprise as $t){

                    $ide = $t->getId();


                    // Je me sert de son id pour aller chercher son adresse.
                    $yolo = $newAdresseEntreprise = $this->getDoctrine()->getRepository(AdresseEnteprise::class)->findBy(array(
                        'entreprise'=> $ide
                    ));

                    //Je boucle pour sortir les info
                    foreach ($yolo as $y){

                        if( $ent->getIsDeleted() != 1){

                            // Call object manager sur l'apdresse
                            $adr = $em->find(Adresse::class, $y->getAdresse());


                            $nou = array(
                                "id"=>$ide,
                                "nom"=>$nomentreprise,
                                "tel"=>$tel,
                                "numero"=>  $adr->getNumero(),
                                "rue"=>$adr->getRue(),
                                "ville"=>$adr->getVille(),
                                "codePostal"=>$adr->getCodePostal(),
                                "la"=>$adr->getLatitude(),
                                "lo"=>$adr->getLongitude(),
                                "logo"=>$t->getLogo(),


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
                            "logo"=>$item->getLogo(),
                            "numero"=>  $na->getNumero(),
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

////////////////////  NOM ////////////////////
    public function rechercheNom($data){

        $newEntreprise = new Entreprise();
        $newAdresseEnteprise = new AdresseEnteprise();
        $newAdresse = new Adresse();
        $arrayVide = array();

        $em = $this->getDoctrine()->getManager();

        $newEntreprise = $this->getDoctrine()->getRepository(Entreprise::class)->findBy(array(
            "nom"=> $data
        ));

        if (!empty($newAdresse)){

        foreach ($newEntreprise as $item) {
            if($item->getIsDeleted() != 1){
                $var = $newAdresseEnteprise = $this->getDoctrine()->getRepository(AdresseEnteprise::class)->findBy(array(
                    "entreprise" => $item->getId()
                ));


                foreach ($var as $i) {

                    $adr = $em->find(Adresse::class, $i->getAdresse());
                    $nou = array(
                        "id"=>$item->getId(),
                        "nom"=>$item->getNom(),
                        "tel"=>$item->getTel(),
                        "logo"=>$item->getLogo(),
                        "rue"=>$adr->getRue(),
                        "ville"=>$adr->getVille(),
                        "codePostal"=>$adr->getCodePostal(),
                        "la"=>$adr->getLatitude(),
                        "lo"=>$adr->getLongitude(),

                    );
                }
            }
            array_push($arrayVide, $nou);
        }
        return $arrayVide;
        }
    }


////////////////////  NEW REHCERHCE ////////////////////

    public function recherchedeuxpointzero($q, $c){
        //Le but est de metre a jour la fonction
        // De recherche sur le site qui n'etait pas optimiser
        // Q = VILLE C = NOM OU CAT

        $nc = str_replace("%20", " ", $c);

        $vide = array();


        // Si ca trouve un truc qui corespond a une categorie ca passe
        if ($this->rechercheCategorie($nc)){

            $result = $this->rechercheCategorie($nc);
            foreach ($result as $r){
                $ville = $r["ville"];
                if ($ville == $q){
                    array_push($vide, $r);
                }

            }
            //je lui retourne un array avec les infos
            return $vide;
            //Meme chose pour lr nom
        }elseif ($this->rechercheNom($nc)){
            $result = $this->rechercheNom($nc);
            foreach ($result as $r){
                $ville = $r["nom"];
                if ($ville == $nc){
                    array_push($vide, $r);
                }
            }
            return $vide;
            //Si je trouve un truc qui corespond a mon champ ville je lui retourne
        }elseif($this->rechercheVille($q)){
            return $this->rechercheVille($q);

            // Je recherche dans les categories
        }elseif($this->rechercheCategorie($c)){
            return $this->rechercheCategorie($c);
            //Sinon je le retourne une erreure
        }else{
            $this->addFlash('error', "Deso j'ai pas en bdd ");
        }


    }

    //Je vais regarder si ma ville est en bdd
    // Si oui alors je recherhce ce qu'il y a dans la categorie
    //Si je trouve pas je vais taper juste dans la table cat

    public function Coucou($q, $c){

        $nc = str_replace("%20", " ", $c);
        $vide = array();

        if (!is_null($this->rechercheVille($q))){
            if (!is_null($this->rechercheCategorie($nc))){
                $result = $this->rechercheCategorie($nc);
                foreach ($result as $r){
                    $ville = $r["ville"];
                    if ($ville == $q){
                        array_push($vide, $r);
                    }
                }
                return $vide;
            }elseif(!is_null($this->rechercheVille($q))){
                return $this->rechercheVille($q);
            }elseif(!is_null($this->rechercheNom($nc))){
                return $this->rechercheNom($nc);
            }
        }elseif(!is_null($this->rechercheCategorie($nc))){
            return $this->rechercheCategorie($nc);
        }elseif(count($this->rechercheNom($nc))  != 0){
            return $this->rechercheNom($nc);
        }else{
            $this->addFlash('error', "Désolé il n'y a rien en base de donnée 😢 ");
        }
    }
}
