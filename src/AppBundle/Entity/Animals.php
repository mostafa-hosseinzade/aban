<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\User;
use AppBundle\Entity\Animalscategory;
use AppBundle\Entity\TimeStampale;

/**
 * Animals
 *
 * @ORM\Table(name="animals")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Animals extends TimeStampale {

    /**
     * 
     * @var boolean Description
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    function getActive() {
        return $this->active;
    }

    function setActive($active) {
        $this->active = $active;
    }

    /**
     * @var string
     * 
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     *
     * @ORM\Column(name="`host`",type="integer",nullable=true)
     */
    private $host = 1;

    /**
     * @var string
     * 
     * @ORM\Column(name="age", type="string", length=128, nullable=true)
     */
    private $age;

    /**
     * @var string
     *
     * @ORM\Column(name="sex", type="boolean", nullable=true)
     */
    private $sex = 1;

    /**
     * @var string
     *
     * @ORM\Column(name="weight", type="string", length=128, nullable=true)
     */
    private $weight;

    /**
     * @var string
     * 
     * @ORM\Column(name="stature", type="string", length=128, nullable=true)
     */
    private $stature;

    /**
     * @var string
     * 
     * @ORM\Column(name="microChip", type="string", length=128, nullable=true)
     */
    private $microChip;

    /**
     * @var string
     * 
     * @ORM\Column(name="color", type="string", length=128, nullable=true)
     */
    private $color;

    /**
     * @var integer
     * 
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    function getColor() {
        return $this->color;
    }

    function setColor($color) {
        $this->color = $color;
    }

    /**
     * @var \AppBundle\Entity\User
     * 
     * 
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id",onDelete="CASCADE")
     * })
     */
    private $user;

    /**
     * @var \AppBundle\Entity\Animalscategory
     * 
     * 
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Animalscategory")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Animalscategory_id", referencedColumnName="id",onDelete="SET NULL")
     * })
     */
    private $Animalscategory;


    //--------------------------------------------------------------------------
    //new feilds

    /**
     * @var string
     * @ORM\Column(name="codeParvande", type="string", length=255, nullable=true)
     */
    private $codeParvande;

    /**
     * Set codeParvande
     * @param string $codeParvande
     * @return Animals
     */
    public function setCodeParvande($codeParvande) {
        $this->codeParvande = $codeParvande;
        return $this;
    }

    /**
     * Get codeParvande
     * @return string
     */
    public function getCodeParvande() {
        return $this->codeParvande;
    }

    /**
     * @var string
     * @ORM\Column(name="goneh", type="string", length=255, nullable=true)
     */
    private $goneh;

    /**
     * Set goneh
     * @param string $goneh
     * @return Animals
     */
    public function setGoneh($goneh) {
        $this->goneh = $goneh;
        return $this;
    }

    /**
     * Get goneh
     * @return string
     */
    public function getGoneh() {
        return $this->goneh;
    }

    /**
     * @var string
     * @ORM\Column(name="nezhad", type="string", length=255, nullable=true)
     */
    private $nezhad;

    /**
     * Set nezhad
     * @param string $nezhad
     * @return Animals
     */
    public function setNezhad($nezhad) {
        $this->nezhad = $nezhad;
        return $this;
    }

    /**
     * Get goneh
     * @return string
     */
    public function getNezhad() {
        return $this->nezhad;
    }

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreateParvande", type="datetime",nullable=true)
     */
    private $dateCreateParvande;

    /**
     * Set dateCreateParvande
     *
     * @param \DateTime $dateCreateParvande
     * @return Post
     */
    public function setDateCreateParvande($dateCreateParvande) {
        $this->dateCreateParvande = $dateCreateParvande;

        return $this;
    }

    /**
     * Get dateCreateParvande
     *
     * @return \DateTime
     */
    public function getDateCreateParvande() {
        return $this->dateCreateParvande;
    }

    //--------------------------------------------------------------------------

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Animals
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set age
     *
     * @param string $age
     *
     * @return Animals
     */
    public function setAge($age) {
        $this->age = $age;

        return $this;
    }

    /**
     * Get age
     *
     * @return string
     */
    public function getAge() {
        return $this->age;
    }

    /**
     * Set sex
     *
     * @param string $sex
     *
     * @return Animals
     */
    public function setSex($sex) {
        $this->sex = $sex;

        return $this;
    }

    /**
     * Get sex
     *
     * @return string
     */
    public function getSex() {
        return $this->sex;
    }

    /**
     * Set weight
     *
     * @param string $weight
     *
     * @return Animals
     */
    public function setWeight($weight) {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Get weight
     *
     * @return string
     */
    public function getWeight() {
        return $this->weight;
    }

    /**
     * Set may
     *
     * @param string $stature
     *
     * @return Animals
     */
    public function setStature($stature) {
        $this->stature = $stature;

        return $this;
    }

    /**
     * Get stature
     *
     * @return string
     */
    public function getStature() {
        return $this->stature;
    }

    /**
     * Set microChip
     *
     * @param string $microChip
     *
     * @return Animals
     */
    public function setMicroChip($microChip) {
        $this->microChip = $microChip;

        return $this;
    }

    /**
     * Get microChip
     *
     * @return string
     */
    public function getMicroChip() {
        return $this->microChip;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Animals
     */
    public function setUser(User $user = null) {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * Set animalscategory
     *
     * @param \AppBundle\Entity\Animalscategory $animalscategory
     *
     * @return Animals
     */
    public function setAnimalscategory(Animalscategory $animalscategory = null) {
        $this->Animalscategory = $animalscategory;

        return $this;
    }

    /**
     * Get animalscategory
     *
     * @return \AppBundle\Entity\Animalscategory
     */
    public function getAnimalscategory() {
        return $this->Animalscategory;
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate() {
        $this->host = 1;
    }

}
