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

        $newFavoris = new Favoris();
        $newFavorisTest = new Favoris();

        $entityManager = $this->getDoctrine()->getManager();

        $newFavorisTest = $this->getDoctrine()->getRepository(Favoris::class)->findBy(array(
            'utilisateur'=>$Utilisateur
        ));

if (!empty($newFavorisTest)){
        foreach ($newFavorisTest as $nf){

            if($Utilisateur->getId() == $nf->getId()){
                return new Response("yen a un");
            }else{

                $newFavoris->setEntreprise($Entreprise)
                    ->setUtilisateur($Utilisateur);

                $entityManager->persist($newFavoris);

                $entityManager->flush();

                return new Response();

            }
        }


    }
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