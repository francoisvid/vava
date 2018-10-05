<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\UtilisateurRepository")
 * @UniqueEntity(
 * fields={"mail"},
 * message="L'email que vous avez indiqué est déjà utilisé !"
 * )
 */
class Utilisateur implements UserInterface, SerializerInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mail;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=8, minMessage="Votre mot de passe doit faire minimum 8 caractères")
     */
    private $mdp;

    /**
     * @Assert\EqualTo(propertyPath="mdp", message="Les mots de passe ne correspondent pas. Veuillez réessayer.")
     */
    public $confirm_mdp;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $tel;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateNaissance;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreation;

    /**
     * @ORM\Column(type="boolean")
     */
    private $actif;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDeleted;



    /**
     * @ORM\ManyToOne(targetEntity="Adresse", inversedBy="utilisateurs")
     */
    private $adresse;

//    /**
//     * @ ORM\OneToMany(targetEntity="App\Entity\Actualite", mappedBy="auteur", orphanRemoval=true)
//     */
//    private $actualites;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Favoris", mappedBy="utilisateur", orphanRemoval=true)
     */
    private $favoris;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sexe;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Privilege", inversedBy="utilisateurs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $privilege;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photo;

    public function __construct()
    {
        $this->actualites = new ArrayCollection();
        $this->favoris = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }


    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getMdp(): ?string
    {
        return $this->mdp;
    }

    public function setMdp(string $mdp): self
    {
        $this->mdp = $mdp;

        return $this;
    }

    public function getTel(): ?int
    {
        return $this->tel;
    }

    public function setTel(int $tel): self
    {
        $this->tel = $tel;

        return $this;
    }

    public function getDateNaissance(): ?string
    {
        if($this->dateNaissance !== null){
            return $this->dateNaissance->format("Y-m-d H:i:s");
        }else{
            return '';
        }
//        return $this->dateNaissance->format("Y-m-d H:i:s");

    }

    public function setDateNaissance(DateTimeInterface $dateNaissance): self
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    public function getDateCreation(): ?string
    {
        if($this->dateCreation !== null){
            return $this->dateCreation->format("Y-m-d H:i:s");
        }else{
            return '';
        };
//        return $this->DateCreation->format("Y-m-d H:i:s");
    }

    public function setDateCreation(DateTimeInterface $DateCreation): self
    {
        $this->dateCreation = $DateCreation;

        return $this;
    }

    public function getActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): self
    {
        $this->actif = $actif;

        return $this;
    }

    public function getIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(bool $isDeleted): self
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }


    public function getAdresse(): ?Adresse
    {
        return $this->adresse;
    }

    public function setAdresse(Adresse $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

//    /**
//     * @ return Collection|Actualite[]
//     */
//    public function getActualites(): ?Collection
//    {
//        return $this->actualites;
//    }
//
//    public function addActualite(Actualite $actualite): self
//    {
//        if (!$this->actualites->contains($actualite)) {
//            $this->actualites[] = $actualite;
//            $actualite->setAuteur($this);
//        }
//
//        return $this;
//    }
//
//    public function removeActualite(Actualite $actualite): self
//    {
//        if ($this->actualites->contains($actualite)) {
//            $this->actualites->removeElement($actualite);
//            // set the owning side to null (unless already changed)
//            if ($actualite->getAuteur() === $this) {
//                $actualite->setAuteur(null);
//            }
//        }
//
//        return $this;
//    }

    /**
     * @return Collection|Favoris[]
     */
    public function getFavoris(): ?Collection
    {
        return $this->favoris;
    }

    public function addFavori(Favoris $favori): self
    {
        if (!$this->favoris->contains($favori)) {
            $this->favoris[] = $favori;
            $favori->setUtilisateur($this);
        }

        return $this;
    }

    public function removeFavori(Favoris $favori): self
    {
        if ($this->favoris->contains($favori)) {
            $this->favoris->removeElement($favori);
            // set the owning side to null (unless already changed)
            if ($favori->getUtilisateur() === $this) {
                $favori->setUtilisateur(null);
            }
        }

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): self
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function deserialize($data, $type, $format, array $context = array()): object {
        return "";
    }

    public function serialize($data, $format, array $context = array()): string {
        return "";
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        return array($this->getPrivilege());
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @return string The password
     */
    public function getPassword()
    {
        return $this->mdp;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->nom;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials(){}

    public function getPrivilege(): ?string
    {
        return $this->privilege->getRole();
    }

    public function setPrivilege(?Privilege $privilege): self
    {
        $this->privilege = $privilege;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

}