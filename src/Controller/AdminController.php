<?php

namespace App\Controller;
//use Symfony\Component\HttpFoundation\JsonResponse;


use App\Entity\Actualite;
use App\Entity\Utilisateur;
use App\Entity\Categorie;
use App\Entity\Adresse;
use App\Entity\Entreprise;
use App\Entity\Contact;
use App\Entity\AdresseEnteprise;
use App\Entity\CategorieEntreprise;
use App\Form\UtilisateurType;
use App\Repository\ActualiteRepository;
use App\Repository\EntrepriseRepository;
use App\Repository\AdresseEntepriseRepository;
use App\Repository\UtilisateurRepository;
use App\Repository\CategorieRepository;
use DateTime;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

//use Symfony\Flex\Response;

/**
 * @Route("/admin", name="admin")
 * 
 */
class AdminController extends AbstractController
{
    /**
     *
     * @Route ("/")
     */
    public function index(CategorieRepository $repo, AuthorizationCheckerInterface $authChecker, ActualiteRepository $actualite)
    {
    if (!$authChecker->isGranted('ROLE_ADMIN')) {
                return $this->redirectToRoute("home");
    }
    
        return $this->render('admin/index.html.twig', ['categorie' => $repo->findAll()]);
    }

    /*
     * 
     * Partie dédiée à l'interraction avec la table utilisateur de la base de données
     * 
     */

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
     * @Route("/user/del/{id}", name="delete_user")
     * @Method({"GET"})
     *
     */
    public function test(Utilisateur $user)
    {
//        var_dump($user->getIsDeleted());
        $user->setIsDeleted(TRUE);
        $user->setActif(FALSE);
        //var_dump($user->getIsDeleted());
        $this->getDoctrine()->getManager()->merge($user);
        $this->getDoctrine()->getManager()->flush();
        //$retour= array("gg" => "Et c'est le GG!");
        //return new JsonResponse((array("gg" => "Et c'est le GG!")));
        return $this->json(array("gg" => json_encode($user)), 200);
        //return "Et c'est le GG!";//$this->redirectToRoute('admin_user_index');
        //return $this->render('admin/index.html.twig');
    }


    /**
     * @Route("/user/unb/{id}", name="unban_user", methods="GET|POST")
     *
     */
    public function unban(Utilisateur $user)
    {

        $user->setIsDeleted(FALSE);
        $user->setActif(TRUE);

        $this->getDoctrine()->getManager()->merge($user);
        $this->getDoctrine()->getManager()->flush();

        /**
         * Utilisation d'un encoder et de l'objet GetSetMethodNormalizer afin de traiter en amont le format de la date
         */
        $encoder = new JsonEncoder();
        $normalizer = new GetSetMethodNormalizer();


        $dateTime = $user->getDateCreation();
        $callback = function ($dateTime) {
            return $dateTime instanceof \DateTime
                ? $dateTime->format("Y-m-d H:i:s")
                : '';
        };
        $normalizer->setCallbacks(array('dateCreation' => $callback, 'dateNaissance' => $callback));

        $normalizer->setIgnoredAttributes(array("utilisateurs", "mdp", "password", "username", "salt", "dateNaissance", "dateCreation", "tel", "actif", "isDeleted", "adresse", "actualites", "favoris", "sexe", "roles", "role", "privilege"));


        $serializer = new Serializer(array($normalizer), array($encoder),array(new DateTimeNormalizer()));
        $test = ($serializer->serialize($user, 'json'));
        $users = array($user);
//        var_dump($test);
//        var_dump($this->json(array("gg" => $serializer->serialize($user, 'json')), 200));
//        header('Content-type: application/json; charset=utf-8');
        return $this->json(array("data" => $users), 200, array("Content-Type" => "application/json", "charset" => "utf-8"));

//        return new \Symfony\Component\HttpFoundation\JsonResponse($serializer->serialize($user, 'json'));
//        return $this->json(array("gg" => $user), 200, array("Content-Type" => "application/json", "charset" => "utf-8"), array("CircularReference" => 5));
    }

    /**
     * @Route("/user/actif", name="user_actif", methods="GET")
     *
     */
    public function getAllUsersActif(UtilisateurRepository $utilisateurRepository){

        $users = $utilisateurRepository->FindAllIfNotDel();
        return $this->json(array("data" => $users), 200, array("Content-Type" => "application/json", "charset" => "utf-8"));
    }

    /**
     * @Route("/user/del", name="user_del", methods="GET")
     *
     */
    public function getAllUsersDeleted(UtilisateurRepository $utilisateurRepository){

        $users = $utilisateurRepository->FindAllDeleted();
//        var_dump($users);
        return $this->json(array("data" => $users), 200, array("Content-Type" => "application/json", "charset" => "utf-8"));
    }

    /**
     * @Route("/company/active", name="company_active", methods="GET")
     *
     */
    public function getAllCompanyActive(EntrepriseRepository $entRepo){

        $ent = $entRepo->FindAllIfNotDel();
        return $this->json(array("data" => $ent), 200, array("Content-Type" => "application/json", "charset" => "utf-8"));
    }

    /**
     * @Route("/company/inactive", name="company_inactive", methods="GET")
     *
     */
    public function getAllCompanyDelInactive(EntrepriseRepository $entRepo){

        $ent = $entRepo->FindAllDeleted();
//        $ents = $entRepo->FindAllIfNotDel();
        return $this->json(array("data" => $ent), 200, array("Content-Type" => "application/json", "charset" => "utf-8"));
    }
    
    /**
     * @Route("/cat/create", name="cat_create", methods="POST")
     *
     */
    public function createCat(Request $request, ObjectManager $em){
        $post = $request->request->all();
        
        $cat = new Categorie();
        (isset($post['type']))? $cat->setType($post['type']): "";
        
        if($cat->getType() !== null){
             $em->persist($cat);
             $em->flush();
            return $this->json(array("status" => "success"), 200, array("Content-Type" => "application/json", "charset" => "utf-8"));
        }else{
            return $this->json(array("status" => "fail"), 400, array("Content-Type" => "application/json", "charset" => "utf-8"));
        }
    }
    
    /**
     * @Route("/company/delete/{id}", name="ent_delete", methods="POST")
     */
    public function deleteCompany(Entreprise $ent, ObjectManager $em){
//        var_dump($ent->getId());
        $ent->setIsDeleted(true);
        $ent->setVisible(false);
        $em->merge($ent);
        $em->flush();
        return $this->json(array("status" => "réussite"), 200, array("Content-Type" => "application/json", "charset" => "utf-8"));
        
    }
    
    /**
     * @Route("/company/active/{id}", name="ent_active", methods="POST")
     */
    public function activeCompany(Entreprise $ent, ObjectManager $em){
//        var_dump($ent->getId());
        $ent->setIsDeleted(false);
        $ent->setVisible(true);
        $em->merge($ent);
        $em->flush();
        return $this->json(array("status" => "réussite"), 200, array("Content-Type" => "application/json", "charset" => "utf-8"));
        
    }
    
    /**
     * @Route("/company/adr/{id}", name="adr_active", methods="GET")
     */
    public function adresseCompany(Entreprise $ent, AdresseEntepriseRepository $entRepo, ObjectManager $em){
        $retour = array();
        $adresses =  $entRepo->findBy(array("entreprise" => $ent->getId()));
        foreach($adresses as $adresse){
            array_push($retour,$em->find(Adresse::class, $adresse->getAdresse()));
        }
        //retourne la liste des adresses de la societe ciblé
        return $this->json(array("data" => $retour), 200, array("Content-Type" => "application/json", "charset" => "utf-8"));

        
    }
    
    
    /**
     * @Route("/company/update/{id}", name="company_update", methods="POST")
     */
    public function updateCompany(Request $request, Entreprise $ent, ObjectManager $em){
        $post = $request->request->all();

        $ent->setNom($post['nom']);
        $ent->setTel((int)$post['tel']);
        $ent->setMail($post['mail']);
        $ent->setLogo($post['logo']);
        $ent->setSiteWeb($post['site']);
        $ent->setSalariesFormes($post['salaries']);
        $ent->setRemarque($post['remarque']);
        $ent->setPub($post['pub']);
        $em->merge($ent);
        $em->flush();
        return $this->json(array("status" => "réussite"), 200, array("Content-Type" => "application/json", "charset" => "utf-8"));
        
    }
    
    /**
     * @Route("/company/create", name="ent_create", methods="POST")
     *
     */
    public function createCompany(Request $request, ObjectManager $em){
        $post = $request->request->all();
//        var_dump($post);
//        var_dump($_POST);
        $adr = new Adresse();
        (isset($post['adresse']['numero']))? $adr->setNumero($post['adresse']['numero']): "";
        (isset($post['adresse']['rue']))? $adr->setRue($post['adresse']['rue']): "";
        (isset($post['adresse']['ville']))? $adr->setVille($post['adresse']['ville']): "";
        (isset($post['adresse']['cp']))? $adr->setCodePostal($post['adresse']['cp']): "";
        (isset($post['adresse']['latitude']))? $adr->setLatitude($post['adresse']['latitude']): "";
        (isset($post['adresse']['longitude']))? $adr->setLongitude($post['adresse']['longitude']): "";
        $em->persist($adr);
        $em->flush();
 
        $ent = new Entreprise();
        //$ent->setCategorie($em->find(Categorie::class, $post['categorie']));
        (isset($post['nom']))? $ent->setNom($post['nom']): "";
        (isset($post['tel']))? $ent->setTel($post['tel']): "";
        (isset($post['mail']))? $ent->setMail($post['mail']): "";
        (isset($post['logo']))? $ent->setLogo($post['logo']): "";
        (isset($post['site']))? $ent->setSiteWeb($post['site']): "";
        (isset($post['salarie']))? $ent->setSalariesFormes($post['salarie']): "";
        (isset($post['remarque']))? $ent->setRemarque($post['remarque']): "";
        $ent->setPub(0);
        $ent->setVisible(0);
        $ent->setIsDeleted(0);
        $em->persist($ent);
        $em->flush();
        
        
        $cont = new Contact();
        (isset($post['contact']['nom']))? $cont->setNom($post['contact']['nom']): "";
        (isset($post['contact']['prenom']))? $cont->setPrenom($post['contact']['prenom']): "";
        (isset($post['contact']['genre']))? $cont->setSexe($post['contact']['genre']): "";
        (isset($post['contact']['fonction']))? $cont->setFonction($post['contact']['fonction']): "";
        (isset($post['contact']['mail']))? $cont->setMail($post['contact']['mail']): "";
        (isset($post['contact']['tel']))? $cont->setTel((int)$post['contact']['tel']): "";
        (isset($post['contact']['remarque']))? $cont->setRemarque($post['contact']['remarque']): "";
        $cont->setEntreprise($ent);
        $em->persist($cont);
        $em->flush();

        
        $cat = new CategorieEntreprise();
        $cat->setEntreprise($ent);
        $cat->setCategorie($em->find(Categorie::class, $post['categorie']));
        $em->persist($cat);
        $em->flush();
        
        $adEnt = new AdresseEnteprise();
        $adEnt->setAdresse($adr);
        $adEnt->setEntreprise($ent);
        $em->persist($adEnt);
        $em->flush();
        
        
        
            return $this->json(array("status" => "GG"), 200, array("Content-Type" => "application/json", "charset" => "utf-8"));
        
    }
    
    /**
     * @Route("/contact/create", name="contact_create", methods="POST")
     *
     */
    public function createContact(Request $request, ObjectManager $em){
        $post = $request->request->all();
        
        $cont = new Contact();
        $cont->setNom($post['nom']);
        $cont->setPrenom($post['prenom']);
        $cont->setSexe($post['genre']);
        $cont->setFonction($post['fonction']);
        $cont->setRemarque($post['remarque']);
        $cont->setMail($post['mail']);
        $cont->setTel((int)$post['tel']);
        $cont->setEntreprise($em->find(Entreprise::class, $post['entreprise']));
        
        $em->persist($cont);
        $em->flush();
        
        return $this->json(array("success" => "Insert réussi"), 200, array("Content-Type" => "application/json", "charset" => "utf-8"));
    }
    
    /**
     * @Route("/contact/update/{id}", name="contact_update", methods="POST")
     *
     */
    public function updateContact(Contact $contact, Request $request, ObjectManager $em){
        $post = $request->request->all();
        
        $contact->setNom($post['nom']);
        $contact->setPrenom($post['prenom']);
        $contact->setTel((int)$post['tel']);
        $contact->setMail($post['mail']);
        
        $em->merge($contact);
        $em->flush();
        
        return $this->json(array("success" => "Edition réussi"), 200, array("Content-Type" => "application/json", "charset" => "utf-8"));
    }


    /**
     * @Route("/news/create", name="addNews", methods="POST")
     */
    public function addNews(ActualiteRepository $actualite, Request $request, ObjectManager $em){

        $news = new Actualite();

        $post = $request->request->all();

        $news->setAuteur($em->find(Utilisateur::class, 1));
        $news->setTitre($post['titre']);
        $news->setArticle($post['article']);
        $news->setDateCreation(new \DateTime());
        $news->setVisible(1);
        $news->setIsDeleted(0);

        $em->persist($news);
        $em->flush();

        return $this->json(array("data" => "news"), 200, array("Content-Type" => "application/json", "charset" => "utf-8"));

    }
}
