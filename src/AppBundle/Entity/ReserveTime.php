<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\User;
use AppBundle\Entity\Ticket;
use AppBundle\Entity\Animals;
use AppBundle\Entity\TimeStampale;

/**
 * Animals
 *
 * @ORM\Table(name="reserve_time")
 * @ORM\Entity
 */
class ReserveTime extends TimeStampale {

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
     * @var \AppBundle\Entity\Expertise
     * 
     * 
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Ticket")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ticket_id", referencedColumnName="id",onDelete="CASCADE")
     * })
     */
    private $ticket;
   
    
    /**
     *
     * @var FieldTime
     * @ORM\Column(name="fieldtime", type="time", nullable=true) 
     */
    private $fieldtime;
    function getFieldtime() {
        return $this->fieldtime;
    }

    function setFieldtime($fieldtime) {
        $this->fieldtime = $fieldtime;
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
     * Set Sections
     *
     * @param \AppBundle\Entity\Animals $animals
     *
     * @return Animals
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
     * Set Sections
     *
     * @param \AppBundle\Entity\Ticket $ticket
     *
     * @return Ticket
     */
    public function setTicket(Ticket $ticket = null) {
        $this->ticket = $ticket;

        return $this;
    }

    /**
     * Get Sections
     *
     * @return \AppBundle\Entity\Ticket
     */
    public function getTicket() {
        return $this->ticket;
    }    
    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

}
