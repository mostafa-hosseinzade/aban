<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\User;
use AppBundle\Entity\TimeStampale;

/**
 * Certificateofdoctors
 *
 * @ORM\Table(name="certificateofdoctors")
 * @ORM\Entity
 *  
 * 
 */
class Certificateofdoctors extends TimeStampale {
 
    /**
     * @var string
     *
     * @ORM\Column(name="certificate", type="string", length=255, nullable=false)
     */
    private $certificate;

    /**
     * @var string
     *
     * @ORM\Column(name="dateOfRegistration", type="string", length=255, nullable=false)
     */
    private $dateofregistration;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expiryDate", type="string",length=255, nullable=false)
     */
    private $expirydate;

    /**
     * @var string
     *
     * @ORM\Column(name="photo", type="text")
     */
    private $photo;

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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id",onDelete="CASCADE")
     * })
     */
    private $user;

    /**
     * Set certificate
     *
     * @param string $certificate
     *
     * @return Certificateofdoctors
     */
    public function setCertificate($certificate) {
        $this->certificate = $certificate;

        return $this;
    }

    /**
     * Get certificate
     *
     * @return string
     */
    public function getCertificate() {
        return $this->certificate;
    }

    /**
     * Set dateofregistration
     *
     * @param string $dateofregistration
     *
     * @return Certificateofdoctors
     */
    public function setDateofregistration($dateofregistration) {
        $this->dateofregistration = $dateofregistration;

        return $this;
    }

    /**
     * Get dateofregistration
     *
     * @return string
     */
    public function getDateofregistration() {
        return $this->dateofregistration;
    }

    /**
     * Set expirydate
     *
     * @param \DateTime $expirydate
     *
     * @return Certificateofdoctors
     */
    public function setExpirydate($expirydate) {
        $this->expirydate = $expirydate;

        return $this;
    }

    /**
     * Get expirydate
     *
     * @return \DateTime
     */
    public function getExpirydate() {
        return $this->expirydate;
    }

    /**
     * Set photo
     *
     * @param \DateTime $photo
     *
     * @return Certificateofdoctors
     */
    public function setPhoto($photo) {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return \DateTime
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
     * Set user
     *
     * @param User $user
     *
     * @return Certificateofdoctors
     */
    public function setUser(User $user = null) {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser() {
        return $this->user;
    }

}
