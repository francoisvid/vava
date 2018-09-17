<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
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
    public function update(Request $request, Utilisateur $utilisateur): Response
    {
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('/utilisateur');
        }
        return $this->render('utilisateur/utilisateur.html.twig');
    }
}
