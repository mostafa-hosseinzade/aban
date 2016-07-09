<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\User;
use AppBundle\Entity\TimeStampale;

/**
 * Animals
 *
 * @ORM\Table(name="timedoctor")
 * @ORM\Entity
 */
class TimeDoctor extends TimeStampale {

    /**
     * @var string
     * 
     * @ORM\Column(name="one", type="string",length=255, nullable=true)
     */
    private $one;

    /**
     * @var string
     * 
     * @ORM\Column(name="tow", type="string",length=255, nullable=true)
     */
    private $tow;

    /**
     * @var string
     * 
     * @ORM\Column(name="three", type="string",length=255, nullable=true)
     */
    private $three;

    /**
     * @var string
     * 
     * @ORM\Column(name="four", type="string",length=255, nullable=true)
     */
    private $four;

    /**
     * @var string
     * 
     * @ORM\Column(name="five", type="string",length=255, nullable=true)
     */
    private $five;

    /**
     * @var string
     * 
     * @ORM\Column(name="six", type="string",length=255, nullable=true)
     */
    private $six;

    /**
     * @var string
     * 
     * @ORM\Column(name="seven", type="string",length=255, nullable=true)
     */
    private $seven;

    /**
     * @var integer
     * 
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Date
     * 
     * @ORM\Column(name="started",type="date")
     */
    private $started;


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
     * Set created
     *
     * @param \DateTime $started
     * @return Post
     */
    public function setStarted($started) {
        $this->started = $started;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getStarted() {
        return $this->started;
    }

    /**
     * Set day
     *
     * @param string $one
     *
     * @return Expertise
     */
    public function setOne($one) {
        $this->one = $one;

        return $this;
    }

    /**
     * Get $day
     *
     * @return string
     */
    public function getOne() {
        return $this->one;
    }

    /**
     * Set day
     *
     * @param string $tow
     *
     * @return Expertise
     */
    public function setTow($tow) {
        $this->tow = $tow;

        return $this;
    }

    /**
     * Get $hour
     *
     * @return string
     */
    public function getTow() {
        return $this->tow;
    }

    /**
     * Set day
     *
     * @param string $three
     *
     * @return Expertise
     */
    public function setThree($three) {
        $this->three = $three;

        return $this;
    }

    /**
     * Get $three
     *
     * @return string
     */
    public function getThree() {
        return $this->three;
    }

    /**
     * Set day
     *
     * @param string $four
     *
     * @return Expertise
     */
    public function setFour($four) {
        $this->four = $four;

        return $this;
    }

    /**
     * Get $four
     *
     * @return string
     */
    public function getFour() {
        return $this->four;
    }

    /**
     * Set day
     *
     * @param string $five
     *
     * @return Expertise
     */
    public function setFive($five) {
        $this->five = $five;

        return $this;
    }

    /**
     * Get $hour
     *
     * @return string
     */
    public function getFive() {
        return $this->five;
    }

    /**
     * Set day
     *
     * @param string $six
     *
     * @return Expertise
     */
    public function setSix($six) {
        $this->six = $six;

        return $this;
    }

    /**
     * Get $six
     *
     * @return string
     */
    public function getSix() {
        return $this->six;
    }

    /**
     * Set day
     *
     * @param string $seven
     *
     * @return Expertise
     */
    public function setSeven($seven) {
        $this->seven = $seven;

        return $this;
    }

    /**
     * Get $seven
     *
     * @return string
     */
    public function getSeven() {
        return $this->seven;
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
