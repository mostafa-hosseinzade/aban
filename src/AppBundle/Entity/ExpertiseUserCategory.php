<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\User;
use AppBundle\Entity\Expertise;
use AppBundle\Entity\TimeStampale;

/**
 * Animals
 *
 * @ORM\Table(name="expertise_user_category_rel")
 * @ORM\Entity
 */
class ExpertiseUserCategory extends TimeStampale {

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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Expertise")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="expertise_id", referencedColumnName="id",onDelete="CASCADE")
     * })
     */
    private $expertise;

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
     * Set Expertise
     *
     * @param \AppBundle\Entity\Expertise $expertise
     *
     * @return Animals
     */
    public function setExpertise(Expertise $expertise = null) {
        $this->expertise = $expertise;

        return $this;
    }

    /**
     * Get Expertise
     *
     * @return \AppBundle\Entity\Expertise
     */
    public function getExpertise() {
        return $this->expertise;
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
