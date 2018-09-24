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
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class VavaFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $this->generateRole($manager);
        $this->generateUser($manager);
        $this->generateCategorie($manager);
        $this->generateEntreprise($manager);
        $this->generateFavoris($manager);
        $this->generateCatEntreprise($manager);
        $this->generateContact($manager);
        $this->generateAdresse($manager);
        $this->generateAdresseEnt($manager);
        $this->generateActu($manager);
        
        $manager->flush();
    }
    
    public function generateRole($em){
        $role = new Privilege();
        $role->setRole("admin");
        $em->persist($role);
        $role2 = new Privilege();
        $role2->setRole("membre");
        $em->persist($role2);
        $em->flush();
    }
    
    public function generateUser($em){
        for($i=0;$i<=50;$i++){
        $usr = new Utilisateur();
        $usr->setNom("nom" . $i);
        $usr->setPrenom("prenom" . $i);
        $usr->setMail("test".$i."@test.com");
        $usr->setMdp("mdp".$i);
        $usr->setIsDeleted(false);
        $usr->setActif(true);
        $usr->setDateCreation(new DateTime());
        $usr->setPrivilege($em->find(Privilege::class, 2));
        $em->persist($usr);
        }
        $em->flush();
    }
    
    public function generateCategorie($em){
        for($i=0;$i<=10;$i++){
        $cat = new Categorie();
        $cat->setType("test".$i);
        $em->persist($cat);
        }
        $em->flush();
    }
    
    public function generateEntreprise($em){
        for($i=0;$i<100;$i++){
            $ent = new Entreprise();
            $ent->setIsDeleted(false);
            $ent->setLogo("logo".$i);
            $ent->setMail("entreprise".$i."@ent.com");
            $ent->setNom("entrecouille".$i);
            $ent->setPub(false);
            $ent->setRemarque("le doute m'habite".$i);
            $ent->setSalariesFormes(random_int(1,5));
            $ent->setSiteWeb("www.entrecouille".$i.".com");
            $ent->setTel("0609079030");
            $ent->setVisible(true);
            $em->persist($ent);
        }
        $em->flush();
    }
    
    public function generateFavoris($em){
        for($i=0;$i<50;$i++){
            $fav = new Favoris();
            $fav->setEntreprise($em->find(Entreprise::class, random_int(1,50)));
            $fav->setUtilisateur($em->find(Utilisateur::class, random_int(1,49)));
            $em->persist($fav);
        }
        $em->flush();
    }
    
    public function generateCatEntreprise($em){
        for($i=1;$i<100;$i++){
            $ce = new CategorieEntreprise();
            $ce->setCategorie($em->find(Categorie::class, random_int(1,11)));
            $ce->setEntreprise($em->find(Entreprise::class, $i));
            $em->persist($ce);
        }
        $em->flush();
    }
    
    public function generateContact($em){
        for($i = 0; $i<=99;$i++){
            $cent = new Contact();
            $cent->setEntreprise($em->find(Entreprise::class, random_int(1,99)));
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
            $em->persist($ad);
        }
        $em->flush();
    }
    
    public function generateAdresseEnt($em){
        for($i=0;$i<100;$i++){
            $rel = new AdresseEnteprise();
            $rel->setAdresse($em->find(Adresse::class, random_int(1,199)));
            $rel->setEntreprise($em->find(Entreprise::class, random_int(1,99)));
            $em->persist($rel);
        }
        $em->flush();
    }
    
    public function generateActu($em){
        for($i=0;$i<75;$i++){
            $new = new Actualite();
            $new->setArticle("la digue de cul lalalalalalalalalalalal".$i);
            $new->setDateCreation(new DateTime());
            $new->setIsDeleted(false);
            $new->setTitre("Titre".$i.$i.$i.$i.$i);
            $new->setVisible(true);
            $new->setAuteur($em->find(Utilisateur::class, random_int(1,49)));
            $em->persist($new);
        }
        $em->flush();
    }
}
