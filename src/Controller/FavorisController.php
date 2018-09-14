<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Entity\Favoris;
use App\Entity\Utilisateur;
use App\Form\FavorisType;
use App\Repository\FavorisRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FavorisController extends AbstractController
{
    /**
     * @Route("/favoris/{Utilisateur}.{Entreprise}", name="favoris_add")
     */
    public function ajoutEnFavoris(Utilisateur $Utilisateur, Entreprise $Entreprise){


        // Je doit vérifier que ce favoris n'existe pas deja
        // Je vais boucler dans mes favoris et test
        // Si le favori existe pas je le cree
        //sinon je reponf false

        $newFavoris = new Favoris();
        $newFavorisTest = new Favoris();

        $entityManager = $this->getDoctrine()->getManager();

//        $newFavorisTest = $this->getDoctrine()->getRepository(Favoris::class)->findBy(array(
//            'utilisateur'=>$Utilisateur
//        ));


//        $newFavoris = $this->getDoctrine()->getRepository(Favoris::class)->findBy(array(
//            'utilisateur'=>$Utilisateur,
//            'entreprise'=>$Entreprise
//        ));
//
//        dump($newFavoris);


        dump($this->getDoctrine()->getRepository(Favoris::class)->findByUserAndEntreprise(1, 1));
//
//
//        foreach ($newFavorisTest as $na){
//
//            $id_user =  $na->getUtilisateur()->getId();
//
//            $id_entreprise=  $na->getEntreprise()->getId();
//
////            if (/**$id_user != $Utilisateur->getId() && **/ $id_entreprise != $Entreprise->getId()  ){
////                $newFavoris->setEntreprise($Entreprise)
////                    ->setUtilisateur($Utilisateur);
////
////                $entityManager->persist($newFavoris);
////
////                $entityManager->flush();
////
////                return new Response("GG");
////            }else{
////                return new Response("RIP");
////            }
//
//            if( $id_entreprise == $Entreprise->getId()){
//                echo "oui";
//                break;
//            }else{
//                echo "non";
//
//                // CETTE PARTIE MARCHE
//                $newFavoris->setEntreprise($Entreprise)
//                    ->setUtilisateur($Utilisateur);
//                $entityManager->persist($newFavoris);
//
//                $entityManager->flush();
//
//                return new Response("GG");
//            }
//
//
//
//        }



        return new Response();

}


    /**
     * @Route("/favoris/get/{Utilisateur}", name="favoris_get")
     */
    public function getFav(Utilisateur $Utilisateur){

    $newFavoris = new Favoris();

        $newFavoris = $this->getDoctrine()->getRepository(Favoris::class)->findBy(array(
            'utilisateur'=>$Utilisateur
        ));

        $arrayvide = array();

        foreach ($newFavoris as $new) {
            $newarray = array($new->getId(), $new->getEntreprise()->getNom());
            array_push($arrayvide, $newarray);
        };


        $response = new Response(json_encode($arrayvide));
        $response->headers->set('Content-Type', 'application/json');

        return $response;


    }

    /**
     * @Route("/favoris/del/{id}", name="favoris_del")
     */
    public function supprimerUnFavori($id){

        $entityManager = $this->getDoctrine()->getManager();

        $favoris = $entityManager->getRepository(Favoris::class)->find($id);

        if (!$favoris) {
            return $this->redirectToRoute('home');
        }

        $entityManager->remove($favoris);
        $entityManager->flush();

        return $this->redirectToRoute('home');

    }


}