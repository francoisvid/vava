<?php

namespace App\Controller;

use App\Entity\Privilege;
use App\Entity\Utilisateur;
use App\Form\RegisterType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;



class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="inscription")
     *
     */
    public function register(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        $user = new Utilisateur();
        $form = $this->createForm(RegisterType::class, $user);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $hash= $encoder->encodePassword($user, $user->getPassword());

            $user->setPrivilege($manager->find(Privilege::class,1))
                 ->setDateCreation(new \DateTime())
                 ->setActif(true)
                 ->setIsDeleted(false)
                 ->setMdp($hash);
            // 3) Encode the password (you could also do this via Doctrine listener)
//            $password = $passwordEncoder->encodePassword($user, $user->getMdp());
//            $user->setMdp($password);

            // 4) save the User!
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($user);
            $manager->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('connexion');
        }

        return $this->render(
            'security/register.html.twig',
            array('formRegister' => $form->createView())
        );
    }

    /**
     * @Route("/connexion", name="connexion")
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/deconnexion", name="security_logout")
     */
    public function logout(){

    }
}
