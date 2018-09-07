<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EntrepriseRepository")
 */
class Entreprise
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
     * @ORM\Column(type="integer")
     */
    private $tel;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mail;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $logo;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $remarque;

    /**
     * @ORM\Column(type="integer")
     */
    private $salariesFormes;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $siteWeb;

    /**
     * @ORM\Column(type="boolean")
     */
    private $pub;

    /**
     * @ORM\Column(type="boolean")
     */
    private $visible;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDeleted;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Favoris", mappedBy="entreprise")
     */
    private $favoris;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\AdresseEnteprise", mappedBy="entreprise")
     */
    private $adresseEnteprises;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Contact", mappedBy="entreprise")
     */
    private $contacts;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CategorieEntreprise", mappedBy="entreprise")
     */
    private $categorieEntreprises;

    public function __construct()
    {
        $this->favoris = new ArrayCollection();
        $this->adresseEnteprises = new ArrayCollection();
        $this->contacts = new ArrayCollection();
        $this->categorieEntreprises = new ArrayCollection();
    }

    public function getId(): ?int
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

    public function getTel(): ?int
    {
        return $this->tel;
    }

    public function setTel(int $tel): self
    {
        $this->tel = $tel;

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

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getRemarque(): ?string
    {
        return $this->remarque;
    }

    public function setRemarque(?string $remarque): self
    {
        $this->remarque = $remarque;

        return $this;
    }

    public function getSalariesFormes(): ?int
    {
        return $this->salariesFormes;
    }

    public function setSalariesFormes(int $salariesFormes): self
    {
        $this->salariesFormes = $salariesFormes;

        return $this;
    }

    public function getSiteWeb(): ?string
    {
        return $this->siteWeb;
    }

    public function setSiteWeb(?string $siteWeb): self
    {
        $this->siteWeb = $siteWeb;

        return $this;
    }

    public function getPub(): ?bool
    {
        return $this->pub;
    }

    public function setPub(bool $pub): self
    {
        $this->pub = $pub;

        return $this;
    }

    public function getVisible(): ?bool
    {
        return $this->visible;
    }

    public function setVisible(bool $visible): self
    {
        $this->visible = $visible;

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

    /**
     * @return Collection|Favoris[]
     */
    public function getFavoris(): Collection
    {
        return $this->favoris;
    }

    public function addFavori(Favoris $favori): self
    {
        if (!$this->favoris->contains($favori)) {
            $this->favoris[] = $favori;
            $favori->setEntreprise($this);
        }

        return $this;
    }

    public function removeFavori(Favoris $favori): self
    {
        if ($this->favoris->contains($favori)) {
            $this->favoris->removeElement($favori);
            // set the owning side to null (unless already changed)
            if ($favori->getEntreprise() === $this) {
                $favori->setEntreprise(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|AdresseEnteprise[]
     */
    public function getAdresseEnteprises(): Collection
    {
        return $this->adresseEnteprises;
    }

    public function addAdresseEnteprise(AdresseEnteprise $adresseEnteprise): self
    {
        if (!$this->adresseEnteprises->contains($adresseEnteprise)) {
            $this->adresseEnteprises[] = $adresseEnteprise;
            $adresseEnteprise->setEntreprise($this);
        }

        return $this;
    }

    public function removeAdresseEnteprise(AdresseEnteprise $adresseEnteprise): self
    {
        if ($this->adresseEnteprises->contains($adresseEnteprise)) {
            $this->adresseEnteprises->removeElement($adresseEnteprise);
            // set the owning side to null (unless already changed)
            if ($adresseEnteprise->getEntreprise() === $this) {
                $adresseEnteprise->setEntreprise(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Contact[]
     */
    public function getContacts(): Collection
    {
        return $this->contacts;
    }

    public function addContact(Contact $contact): self
    {
        if (!$this->contacts->contains($contact)) {
            $this->contacts[] = $contact;
            $contact->setEntreprise($this);
        }

        return $this;
    }

    public function removeContact(Contact $contact): self
    {
        if ($this->contacts->contains($contact)) {
            $this->contacts->removeElement($contact);
            // set the owning side to null (unless already changed)
            if ($contact->getEntreprise() === $this) {
                $contact->setEntreprise(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CategorieEntreprise[]
     */
    public function getCategorieEntreprises(): Collection
    {
        return $this->categorieEntreprises;
    }

    public function addCategorieEntreprise(CategorieEntreprise $categorieEntreprise): self
    {
        if (!$this->categorieEntreprises->contains($categorieEntreprise)) {
            $this->categorieEntreprises[] = $categorieEntreprise;
            $categorieEntreprise->setEntreprise($this);
        }

        return $this;
    }

    public function removeCategorieEntreprise(CategorieEntreprise $categorieEntreprise): self
    {
        if ($this->categorieEntreprises->contains($categorieEntreprise)) {
            $this->categorieEntreprises->removeElement($categorieEntreprise);
            // set the owning side to null (unless already changed)
            if ($categorieEntreprise->getEntreprise() === $this) {
                $categorieEntreprise->setEntreprise(null);
            }
        }

        return $this;
    }
}
