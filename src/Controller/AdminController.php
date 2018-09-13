<?php

namespace App\Controller;

use App\Form\UtilisateurType;
use App\Repository\UtilisateurRepository;
use App\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

//use Symfony\Flex\Response;

    /**
     * @Route("/admin", name="admin")
     */
class AdminController extends AbstractController
{
    /**
     *
     * @Route ("/")
     */
    public function index(UtilisateurRepository $utilisateurRepository)
    {
        return $this->render('admin/utilisateur.html.twig', ['utilisateurs' => $utilisateurRepository->findAll()]);
    }
    
    /*
     * 
     * Partie dédiée à l'interraction avec la table utilisateur de la base de données
     * 
     */
    
      /**
     * @Route("/a", name="admin_user_index", methods="GET")
     */
    public function indexbis(UtilisateurRepository $utilisateurRepository): Response
    {
        $utilisateurs = $utilisateurRepository->findAll();
        return $this->render('utilisateur/utilisateur.html.twig', ['utilisateurs' => $utilisateurRepository->findAll()]);
    }

    /**
     * @Route("/new", name="utilisateur_new", methods="GET|POST")
     */
    public function create(Request $request): Response
    {
        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($utilisateur);
            $em->flush();

            return $this->redirectToRoute('utilisateur_index');
        }

        return $this->render('utilisateur/new.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="utilisateur_show", methods="GET")
     */
    public function show(Utilisateur $utilisateur): Response
    {
        $user = $utilisateur;
        return $this->render('utilisateur/show.html.twig', ['utilisateur' => $utilisateur]);
    }

    /**
     * @Route("/{id}/edit", name="utilisateur_edit", methods="GET|POST")
     */
    public function edit(Request $request, Utilisateur $utilisateur): Response
    {
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('utilisateur_edit', ['id' => $utilisateur->getId()]);
        }

        return $this->render('utilisateur/edit.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="utilisateur_delete", methods="DELETE")
     */
    public function delete(Request $request, Utilisateur $utilisateur): Response
    {
        if ($this->isCsrfTokenValid('delete'.$utilisateur->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($utilisateur);
            $em->flush();
        }

        return $this->redirectToRoute('utilisateur_index');
    }
    
    /**
     * @Route("/user/del/{id}", name="delete_user", methods="GET")
     */
    public function test(Utilisateur $user)
    {
        $user->setIsDeleted(TRUE);
        $this->getDoctrine()->getManager()->persist($user)->flush();
        return $this->redirectToRoute('admin_user_index');
    }
}
