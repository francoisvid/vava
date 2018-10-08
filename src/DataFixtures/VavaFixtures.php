<?php

namespace App\DataFixtures;

use App\Entity\Actualite;
use App\Entity\Adresse;
use App\Entity\AdresseEnteprise;
use App\Entity\Categorie;
use App\Entity\CategorieEntreprise;
use App\Entity\Contact;
use App\Entity\Entreprise;
use App\Entity\Favoris;
use App\Entity\Privilege;
use App\Entity\Utilisateur;
use DateTime;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class VavaFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        $this->generateRole($manager);
        $this->generateCategorie($manager);
        $this->generateEntreprise($manager);
        $this->generateAdresse($manager);
        $this->generateUser($manager);
        $this->generateFavoris($manager);
        $this->generateCatEntreprise($manager);
        $this->generateContact($manager);
        $this->generateAdresseEnt($manager);
        $this->generateActu($manager);

        $manager->flush();
    }

    public function generateRole($em){
        $role = new Privilege();
        $role->setRole("ROLE_ADMIN");
        $em->persist($role);
        $role2 = new Privilege();
        $role2->setRole("ROLE_USER");
        $em->persist($role2);
        $em->flush();
    }

    public function generateUser($em){

        for($i=0;$i<=10;$i++){
            $usr = new Utilisateur();
            $usr->setNom("Utilisateur " . $i);
            $usr->setPrenom("prenom" . $i);
            $usr->setMail("dummymail".$i."@dummy.dum");
            $usr->setMdp($this->encoder->encodePassword($usr, 'motdepasse'));
            
            $usr->setIsDeleted(false);
            $usr->setActif(true);
            $usr->setPhoto("https://image.flaticon.com/icons/svg/236/236832.svg");
            $usr->setDateCreation(new DateTime());
            $usr->setPrivilege($em->find(Privilege::class, 2));
            $em->persist($usr);
        }
            $em->flush();
            
            $usr = new Utilisateur();
            $usr->setNom("admin");
            $usr->setPrenom("prenom");
            $usr->setMail("admin@admin.fr");
            $usr->setMdp($this->encoder->encodePassword($usr, 'motdepasse'));
            $usr->setIsDeleted(false);
            $usr->setActif(true);
            $usr->setPhoto("https://image.flaticon.com/icons/svg/236/236831.svg");
            $usr->setDateCreation(new DateTime());
            $usr->setPrivilege($em->find(Privilege::class, 1));
            $em->persist($usr);
            $em->persist($usr);
        
    }

    public function generateCategorie($em){
        
            $cat = new Categorie();
            $cat->setType("Banque");
            $em->persist($cat);
        
            $cat1 = new Categorie();
            $cat1->setType("Administration");
            $em->persist($cat1);
            
            $cat2 = new Categorie();
            $cat2->setType("Santé");
            $em->persist($cat2);
            
            $cat3 = new Categorie();
            $cat3->setType("Divers");
            $em->persist($cat3);
            
            
        $em->flush();
    }

    public function generateEntreprise($em){
        
            $ent = new Entreprise();
            $ent->setIsDeleted(false);
            $ent->setLogo("https://upload.wikimedia.org/wikipedia/fr/thumb/2/22/Banquepopulaire_logo.svg/1011px-Banquepopulaire_logo.svg.png");
            $ent->setMail("banquepopulaire@pop.fr");
            $ent->setNom("Banque Populaire");
            $ent->setPub(false);
            $ent->setRemarque("Rien à ajouter pour le moment.");
            $ent->setSalariesFormes(random_int(1,5));
            $ent->setSiteWeb("https://www.banquepopulaire.fr");
            $ent->setTel("0354221000");
            $ent->setVisible(true);
            $em->persist($ent);
            $em->flush();
            
            
            $ent = new Entreprise();
            $ent->setIsDeleted(false);
            $ent->setLogo("https://static4.seety.pagesjaunes.fr/dam_6760784/1ad33326-04f1-437c-900b-9f7c2ce4c7e1-800");
            $ent->setMail("Non renseigné");
            $ent->setNom("Pharmacie Populaire");
            $ent->setPub(false);
            $ent->setRemarque("Rien à ajouter pour le moment.");
            $ent->setSalariesFormes(1);
            $ent->setSiteWeb("https://www.pharmacie-populaire-montpellier.fr/");
            $ent->setTel("0467060680");
            $ent->setVisible(true);
            $em->persist($ent);
            $em->flush();



            $ent = new Entreprise();
            $ent->setIsDeleted(false);
            $ent->setLogo("https://static4.seety.pagesjaunes.fr/dam_6760784/1ad33326-04f1-437c-900b-9f7c2ce4c7e1-800");
            $ent->setMail("Non renseigné");
            $ent->setNom("Pharmacie Populaire");
            $ent->setPub(false);
            $ent->setRemarque("Rien à ajouter pour le moment.");
            $ent->setSalariesFormes(1);
            $ent->setSiteWeb("https://www.pharmacie-populaire-montpellier.fr/");
            $ent->setTel("0467060680");
            $ent->setVisible(true);
            $em->persist($ent);
            $em->flush();
        
    }

    public function generateFavoris($em){
        for($i=0;$i<2;$i++){
            $fav = new Favoris();
            $fav->setEntreprise($em->find(Entreprise::class, random_int(1,2)));
            $fav->setUtilisateur($em->find(Utilisateur::class, random_int(1,10)));
            $em->persist($fav);
        }
        $em->flush();
    }

    public function generateCatEntreprise($em){
        for($i=1;$i<2;$i++){
            $ce = new CategorieEntreprise();
            $ce->setCategorie($em->find(Categorie::class, random_int(1,3)));
            $ce->setEntreprise($em->find(Entreprise::class, $i));
            $em->persist($ce);
        }
        $em->flush();
    }

    public function generateContact($em){
        for($i = 0; $i<=2;$i++){
            $cent = new Contact();
            $cent->setEntreprise($em->find(Entreprise::class, random_int(1,2)));
            $cent->setFonction("Fonctionnaire");
            $cent->setMail("contact".$i."@alad.com");
            $cent->setNom("Contact".$i);
            $cent->setPrenom("Prenom".$i);
            $cent->setRemarque("il est cool!");
            $cent->setTel("0919839030");
            $cent->setSexe("H");
            $em->persist($cent);
        }
        $em->flush();
    }


    public function generateAdresse($em){
        for($i=0;$i<200;$i++){
            $ad = new Adresse();
            $ad->setCodePostal(random_int(00000,99999));
            $ad->setNumero(random_int(11111,99595));
            $ad->setRue("la rue qui pue".$i);
            $ad->setVille("randomVille".$i);
//            $ad->setLatitude(getRandomInRange(-180, 180, 3));
//            $ad->setLongitude(getRandomInRange(-180, 180, 3));
            $em->persist($ad);
        }
        $em->flush();
//        
//            $ad = new Adresse();
//            $ad->setCodePostal();
//            $ad->setNumero(random_int(11111,99595));
//            $ad->setRue("la rue qui pue".$i);
//            $ad->setVille("randomVille".$i);
        
    }

    public function generateAdresseEnt($em){
        for($i=0;$i<100;$i++){
            $rel = new AdresseEnteprise();
            $rel->setAdresse($em->find(Adresse::class, random_int(1,199)));
            $rel->setEntreprise($em->find(Entreprise::class, random_int(1,2)));
            $em->persist($rel);
        }
        $em->flush();
    }

    public function generateActu($em){
        for($i=0;$i<75;$i++){
            $new = new Actualite();
            $new->setArticle("Ceci est l'article numéro ".$i);
            $new->setDateCreation(new DateTime());
            $new->setIsDeleted(false);
            $new->setTitre("Titre ".$i.$i.$i.$i.$i);
            $new->setVisible(true);
            $new->setAuteur($em->find(Utilisateur::class, random_int(1,10)));
            $em->persist($new);
        }
        $em->flush();
    }
}
