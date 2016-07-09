<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\User;
use AppBundle\Entity\Sections;
use AppBundle\Entity\TimeStampale;

/**
 * Animals
 *
 * @ORM\Table(name="sections_user_category_rel")
 * @ORM\Entity
 */
class SectionsUserCategory extends TimeStampale {

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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Sections")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="sections_id", referencedColumnName="id",onDelete="CASCADE")
     * })
     */
    private $sections;

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
     * @param \AppBundle\Entity\Sections $sections
     *
     * @return Sections
     */
    public function setSetions(Sections $sections = null) {
        $this->sections = $sections;

        return $this;
    }

    /**
     * Get Sections
     *
     * @return \AppBundle\Entity\Sections
     */
    public function getSections() {
        return $this->sections;
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
