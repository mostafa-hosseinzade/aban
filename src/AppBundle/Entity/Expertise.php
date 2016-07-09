<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\User;
use AppBundle\Entity\TimeStampale;

/**
 * Animals
 *
 * @ORM\Table(name="expertise")
 * @ORM\Entity
 */
class Expertise extends TimeStampale {

    /**
     * @var string
     * 
     * @ORM\Column(name="value", type="string", length=255, nullable=false)
     */
    private $value;

    /**
     * @var integer
     * 
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * Set value
     *
     * @param string $value
     *
     * @return Expertise
     */
    public function setValue($value) {
        $this->value = $value;

        return $this;
    }

    /**
     * Get Value
     *
     * @return string
     */
    public function getValue() {
        return $this->value;
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
