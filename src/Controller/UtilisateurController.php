<?php

namespace App\Controller;

use App\Entity\Adresse;
use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Doctrine\Common\Persistence\ObjectManager;
use PhpParser\Node\Expr\Array_;
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
        // recupère toutes les données receuilli grace a la requettes ajax
        $post = $request->request->all();


        $adresse = $em->find(Adresse::class, $utilisateur->getAdresse()->getId());
        (isset($post['numero']))?$adresse->setNumero((int)$post['numero']) : "";
        (isset($post['rue']))?$adresse->setRue($post['rue']) : "";
        (isset($post['ville']))?$adresse->setVille($post['ville']) : "";
        (isset($post['codepostal']))?$adresse->setCodePostal((int)$post['codepostal']) : null;

//            ->setRue($post['rue'])
//            ->setVille($post['ville'])
//            ->setCodePostal($post['codepostal']);

        $this->getDoctrine()->getManager()->persist($adresse);
        $this->getDoctrine()->getManager()->flush();


//        $utilisateur->setAdresse($adresse)
        $retour = new Array_();
        foreach($post as $key => $value){
            var_dump($key);
            var_dump($value);
        }

        (isset($post['nom']))?$utilisateur->setNom($post['nom']) : "";
        (isset($post['prenom']))?$utilisateur->setPrenom($post['prenom']) : "";
        (isset($post['mail']))?$utilisateur->setMail($post['mail']) : "";
        (isset($post['tel']))?$utilisateur->setTel((int)$post['tel']) : "";
        (isset($post['sexe']))?$utilisateur->setSexe($post['sexe']) : "";
        (isset($post['date']))?$utilisateur->setDateNaissance(new \DateTime($post['date'])) : "";
//            ->setDateNaissance(new \DateTime($post['date']));
//            ->setNom($post['nom'])
//            ->setPrenom($post['prenom'])
//            ->setMail($post['mail'])
//            ->setTel($post['tel'])
//            ->setSexe($post['sexe'])

        //mise en BDD des modifications de l'utilisateur
        $this->getDoctrine()->getManager()->merge($utilisateur);
        $this->getDoctrine()->getManager()->flush();



        //return ce que tu veux ensuite
        return $this->json(array('gg'=>'HH'));

    }
}
