<?php

namespace App\Controller;
//use Symfony\Component\HttpFoundation\JsonResponse;


use App\Entity\Actualite;
use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Repository\ActualiteRepository;
use App\Repository\UtilisateurRepository;
use App\Repository\EntrepriseRepository;
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
use function Symfony\Component\Debug\header;

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
        return $this->render('admin/index.html.twig', ['utilisateurs' => $utilisateurRepository->findAllIfNotDel(),
            'utilisateursban' => $utilisateurRepository->FindAllDeleted()]);
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
     * @Route("/company/active", name="company_active", methods="GET")
     *
     */
    public function getAllCompanyDelInactive(EntrepriseRepository $entRepo){

        $ent = $entRepo->FindAllIfNotDel();
        $ents = $entRepo->FindAllIfNotDel();
        return $this->json(array("data" => $ents, "datab" => $ent), 200, array("Content-Type" => "application/json", "charset" => "utf-8"));
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

    /**
     * @Route("/contact", name="contact", methods="POST")
     */
    public function contact($name, \Swift_Mailer $mailer)
    {
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom($_POST["email"])
            ->setTo('vavabeweb@gmail.com')
            ->setBody(
                $this->renderView(
                    'base/base.html.twig',
                    array('name' => $name)
                ),
                'text/html'
            );

        $mailer->send($message);

        return $this->render("
        {# templates/emails/registration.html.twig #}
        <h3>You did it! You registered!</h3>
        
        Hi {{ name }}! You're successfully registered.
        
        {# example, assuming you have a route named \"login\" #}
        To login, go to: <a href=\"{{ url('login') }}\">...</a>.
        
        Thanks!
        
        {# Makes an absolute URL to the /images/logo.png file #}
        <img src=\"{{ absolute_url(asset('images/logo.png')) }}\">");
    }
}