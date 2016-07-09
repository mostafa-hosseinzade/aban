<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\TimeStampale;
use AppBundle\Entity\Animals;

/**
 * Animalsphoto
 *
 * @ORM\Table(name="animalsphoto")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Animalsphoto extends TimeStampale {

    /**
     * @var \AppBundle\Entity\Animals
     * 
     * 
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Animals")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="animals_id", referencedColumnName="id",onDelete="CASCADE")
     * })
     */
    private $animals;

    /**
     * @var string
     *
     * @ORM\Column(name="photo", type="text", nullable=false)
     */
    private $photo;

    /**
     * @var boolean
     *
     * @ORM\Column(name="photoDefault", type="boolean", nullable=false)
     */
    private $photoDefault;
    
    /**
     * @ORM\Column(name="host",type="boolean")
     */
    private $host = 1;
    
    function getHost() {
        return $this->host;
    }

    function setHost($host) {
        $this->host = $host;
    }

        /**
     * Set photoDefault
     *
     * @param boolean $photoDefault
     *
     * @return Animalsphoto
     */
    public function setPhotoDefault($photoDefault) {
        $this->photoDefault = $photoDefault;

        return $this;
    }

    /**
     * Get photoDefault
     *
     * @return boolean
     */
    public function getPhotoDefault() {
        return $this->photoDefault;
    }

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * Set photo
     *
     * @param string $photo
     *
     * @return Animalsphoto
     */
    public function setPhoto($photo) {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return string
     */
    public function getPhoto() {
        return $this->photo;
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
     * Set animals
     *
     * @param Animals $animals
     *
     * @return Animalsphoto
     */
    public function setAnimals(Animals $animals = null) {
        $this->animals = $animals;

        return $this;
    }

    /**
     * Get animals
     *
     * @return Animals
     */
    public function getAnimals() {
        return $this->animals;
    }
    
    public function preUpdate() {
        $this->host = 1;
    }

}
