<?php

namespace App\Controller;

use App\Entity\Adresse;
use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Doctrine\Common\Persistence\ObjectManager;
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
    public function update(Request $request, Utilisateur $utilisateur, ObjectManager $em)
    {
        // équivaut à $_POST donc tu as toutes les données recueillies au travers de ta requête Ajax
        $post = $request->request->all();


        $adresse = $em->find(Adresse::class, $utilisateur->getAdresse()->getId())
            ->setNumero($post['numero'])
            ->setRue($post['rue'])
            ->setVille($post['ville'])
            ->setCodePostal($post['codepostal']);

        $this->getDoctrine()->getManager()->persist($adresse);
        $this->getDoctrine()->getManager()->flush();


        $utilisateur->setAdresse($adresse)
            ->setNom($post['nom'])
            ->setPrenom($post['prenom'])
            ->setMail($post['mail'])
            ->setTel($post['tel'])
            ->setSexe($post['sexe'])
            ->setDateNaissance(new \DateTime($post['date']));

        //mise en BDD des modifications de l'utilisateur
        $this->getDoctrine()->getManager()->merge($utilisateur);
        $this->getDoctrine()->getManager()->flush();



        //return ce que tu veux ensuite
        return $this->json(array('gg'=>'HH'));

    }
}
