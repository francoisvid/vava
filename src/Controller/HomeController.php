<?php

namespace App\Controller;

use App\Repository\ActualiteRepository;
use Swift_Mailer;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(ActualiteRepository $actualite)
    {
        $news = $actualite->findAll();

        return $this->render('home/home.html.twig', [
            'controller_name' => 'HomeController',
            'actualites' => $news,
        ]);
    }


//    /**
//     * @Route("/", name="test")
//     */
//    public function rss(){
//        $member=""; //optionnel si vous etes inscrit sur la plateforme actifpub  le parametrage se fait dans votre espace membre
//        $fichier="https://www.webrankinfo.com/dossiers/feed";
//        $limite="1";  // sujets compris entre 1
//        $limite1="10";   //  et plus
//        $aspect="1";  //  0 ou 1 (1 permet d'afficher lien + description, 0 que les liens)
//        $minute="1";  //  0 ou 1 (1 permet d'afficher date et heure, 0 pas de date et heure)
//        $sujet="1"; //  0 ou 1 (1 permet d'afficher le titre des sujets traités, 0 pas de titre )
//        $te="Geneva, Arial, sans-serif";  // Police de caractères (Verdana, arial etc...)
//        $fil_textsize="11"; // taille des liens et description
//        $title_textcolor="1F70B4"; // couleur des liens (000000 donne noir)
//        $tlien="none"; // style du lien none ou underline
//        $text_textcolor="000000";  // couleur description (000000 donne noir)
//        $frame_color="FFFFFF"; // couleur arrière plan (FFFFFF donne blanc)
//        $content="1"; // 0 ou 1 comme paramètre optionnel, 1  format html,  0  format texte
//        $extract="";  // laisser vide ou indiquez le nombre de caractères que vous souhaitez garder dans le corps du flux
//        $cache="15"; // gestion du cache exprimée en minutes - en fonction de la fréquence de mise à jour
//        $fichier="http://www.actifpub.com/rss.php?fichier_AP_=$fichier&limite_AP_=$limite&limite1_AP_=$limite1&fil_textsize_AP_=$fil_textsize&text_textcolor_AP_=$text_textcolor&title_textcolor_AP_=$title_textcolor&frame_color_AP_=$frame_color&te_AP_=$te&aspect_AP_=$aspect&minute_AP_=$minute&sujet_AP_=$sujet&content_AP_=$content&tlien_AP_=$tlien&cache_AP_=$cache&extract_AP_=$extract&member_AP_=$member";
//
//        $fichier=str_replace(" ","+",$fichier);
//        $ch = curl_init();
//        curl_setopt ($ch, CURLOPT_URL, "$fichier");
//        curl_setopt($ch, CURLOPT_HEADER, 0);
////        curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
//        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT,7);
//        curl_setopt ($ch, CURLOPT_TIMEOUT,25);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
//        $affiche_contenu=curl_exec($ch);
//        curl_close ($ch);
//        echo  $affiche_contenu;
//
//        return $this->render('home/home.html.twig', [
//            'controller_name' => 'HomeController',
//        ]);
//    }
    
     /**
     * @Route("/contact", name="contact", methods="POST")
     */
    public function contact(Request $request, Swift_Mailer $mailer)
    {
        $post = $request->request->all();
        
        $message = (new \Swift_Message('Message provenant de '.$post['from']))
            ->setFrom($post['email'])
            ->setTo('vavabeweb@gmail.com')
            ->setBody($post['contenu'],
                'text/html'
            );
        

        if ($mailer->send($message) != 0)
            {
            return $this->json("news", 200, array("Content-Type" => "application/json", "charset" => "utf-8"));
            }
            else
            {
            return $this->json(array("data" => "Failed"), 404, array("Content-Type" => "application/json", "charset" => "utf-8"));
            }
    }
}
