<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\TimeStampale;

/**
 * Animalscategory
 *
 * @ORM\Table(name="animalscategory")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Animalscategory extends TimeStampale {

    /**
     * @var string
     *
     * @ORM\Column(name="animalsType", type="string", length=128, nullable=false)
     */
    private $animalstype;

    /**
     * @var string
     *
     * @ORM\Column(name="describtion", type="text", length=65535, nullable=false)
     */
    private $describtion;

    /**
     *
     * @ORM\Column(name="`host`",type="integer",nullable=true)
     */
    private $host = 1;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * Set animalstype
     *
     * @param string $animalstype
     *
     * @return Animalscategory
     */
    public function setAnimalstype($animalstype) {
        $this->animalstype = $animalstype;

        return $this;
    }

    /**
     * Get animalstype
     *
     * @return string
     */
    public function getAnimalstype() {
        return $this->animalstype;
    }

    /**
     * Set describtion
     *
     * @param string $describtion
     *
     * @return Animalscategory
     */
    public function setDescribtion($describtion) {
        $this->describtion = $describtion;

        return $this;
    }

    /**
     * Get describtion
     *
     * @return string
     */
    public function getDescribtion() {
        return $this->describtion;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    public function Serialize() {
        $data = array();
        $data['id'] = $this->getId();
        $data['title'] = $this->getAnimalstype();
        $data['describtion'] = $this->getDescribtion();
        return $data;
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate() {
        $this->host = 1;
    }

}
