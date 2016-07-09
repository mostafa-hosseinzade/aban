<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Animals
 *
 * @ORM\Table(name="tableOptions")
 * @ORM\Entity
 * 
 */
class TableOptions {

    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     *
     * @var string
     * @ORM\Column(name="optionName", type="string",length=255, nullable=false) 
     */
    private $optionName;

    /**
     *
     * @var string
     * @ORM\Column(name="optionValue", type="string",length=255, nullable=false) 
     */
    private $optionValue;

    
    function getOptionName() {
        return $this->optionName;
    }

    function getOptionValue() {
        return $this->optionValue;
    }

    function setOptionName($optionName) {
        $this->optionName = $optionName;
    }

    function setOptionValue($optionValue) {
        $this->optionValue = $optionValue;
    }

}
