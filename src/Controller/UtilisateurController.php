<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UtilisateurController extends AbstractController
{
    /**
     * @Route("/utilisateur", name="utilisateur")
     */
    public function index(UtilisateurRepository $utilisateurRepository)
    {
        return $this->render(
            'utilisateur/utilisateur.html.twig', ['utilisateur' => $utilisateurRepository->find($this->getUser()->getId())]);
    }

    /**
     * @Route("/utilisateur/update/{id}", name="update", methods="GET|POST")
     */
    public function update(Request $request, Utilisateur $utilisateur = null)
    {
        // équivaut à $_POST donc tu as toutes les données recueillies au travers de ta requête Ajax
        $post = $request->request->all();


//        var_dump($post);
        //disons que tu as edit le nom
        $utilisateur->setNom($post['nom']);
        $utilisateur->setPrenom($post['prenom']);
        $utilisateur->setMail($post['mail']);
        $utilisateur->setTel($post['tel']);
        $utilisateur->setSexe($post['sexe']);
//        $utilisateur->setDateNaissance($post['date'| date("y:m:d")]);

        //mise en BDD des modifications de l'utilisateur
        $this->getDoctrine()->getManager()->merge($utilisateur);
        $this->getDoctrine()->getManager()->flush();

        //return ce que tu veux ensuite

        
    }
}
