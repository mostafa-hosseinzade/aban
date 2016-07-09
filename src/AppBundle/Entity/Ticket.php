<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\User;
use AppBundle\Entity\Sections;
use AppBundle\Entity\TimeStampale;
use AppBundle\Entity\Animals;

/**
 * Animals
 *
 * @ORM\Table(name="ticket")
 * @ORM\Entity
 */
class Ticket extends TimeStampale {

    /**
     * @var integer
     * 
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

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
     * @var \AppBundle\Entity\TimeDoctor
     * 
     * 
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\TimeDoctor")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="time_doctor_id", referencedColumnName="id",onDelete="CASCADE")
     * })
     */
    private $timedoctor;

    /**
     *
     * @var string
     * @ORM\Column(name="_date", type="date", nullable=true) 
     */
    private $date;

    /**
     *
     * @var string
     * @ORM\Column(name="number", type="string",length=255, nullable=true) 
     */
    private $number;

    /**
     * 
     * @var boolean Description
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * @var \AppBundle\Entity\Expertise
     * 
     * 
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Animals")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="animale_id", referencedColumnName="id",onDelete="CASCADE")
     * })
     */
    private $animals;

    /**
     * Set Sections
     *
     * @param \AppBundle\Entity\Animals $animals
     *
     * @return Ticket
     */
    public function setAnimals(Animals $animals = null) {
        $this->animals = $animals;

        return $this;
    }

    /**
     * Get Sections
     *
     * @return \AppBundle\Entity\Animals
     */
    public function getAnimals() {
        return $this->animals;
    }

    /**
     *
     * @var string
     * @ORM\Column(name="_desc" , type="string" ,length=3000,nullable=true)
     */
    private $desc;

    /**
     * Set day
     *
     * @param string $desc
     *
     * @return string
     */
    public function setDesc($desc) {
        $this->desc = $desc;

        return $this;
    }

    /**
     * Get $day
     *
     * @return string
     */
    public function getDesc() {
        return $this->desc;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Ticket
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
     * Set timedoctor
     *
     * @param \AppBundle\Entity\TimeDoctor $timedoctor
     *
     * @return Ticket
     */
    public function setTimeDoctor(TimeDoctor $timedoctor = null) {
        $this->timedoctor = $timedoctor;

        return $this;
    }

    /**
     * Get timedoctor
     *
     * @return \AppBundle\Entity\TimeDoctor
     */
    public function getTimeDoctor() {
        return $this->timedoctor;
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
     * Set date
     *
     * @param string $date
     *
     * @return Expertise
     */
    public function setDate($date) {
        $this->date = $date;

        return $this;
    }

    /**
     * Get $date
     *
     * @return string
     */
    public function getDate() {
        return $this->date;
    }

    /**
     * Set ac
     *
     * @param boolean $active
     *
     * @return Active
     */
    public function setActive($active) {
        $this->active = $active;

        return $this;
    }

    /**
     * Get $active
     *
     * @return boolean
     */
    public function getActive() {
        return $this->active;
    }

    /**
     * Set date
     *
     * @param string $number
     *
     * @return Expertise
     */
    public function setNumber($number) {
        $this->number = $number;

        return $this;
    }

    /**
     * Get $number
     *
     * @return string
     */
    public function getNumber() {
        return $this->number;
    }

}
