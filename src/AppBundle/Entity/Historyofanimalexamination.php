<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\TimeStampale;
use AppBundle\Entity\Animals;
use AppBundle\Service\Jalali;

/**
 * Historyofanimalexamination
 *
 * @ORM\Table(name="historyofanimalexamination")
 * @ORM\Entity
 */
class Historyofanimalexamination extends TimeStampale {

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateHistory", type="date", nullable=false)
     */
    private $datehistory;

    /**
     * @var string
     *
     * @ORM\Column(name="Describ", type="text", length=65535, nullable=false)
     */
    private $describ;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    
    /**
     *
     * @var integer
     * @ORM\Column(name="host",type="integer",length=1)
     */
    private $host = 1;
    /**
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Animals")
     * @ORM\JoinColumns({
     *  @ORM\JoinColumn(name="animals_id",referencedColumnName="id",onDelete="CASCADE")
     * })
     */
    private $animals;

    /**
     *
     * @ORM\Column(name="file",type="text")
     */
    private $file;

    public function getAnimals() {
        return $this->animals;
    }

    public function getFile() {
        return $this->file;
    }

    /**
     * Set datehistory
     *
     * @param \DateTime $datehistory
     *
     * @return Historyofanimalexamination
     */
    public function setDatehistory($datehistory) {
        $this->datehistory = $datehistory;

        return $this;
    }

    /**
     * Get datehistory
     *
     * @return \DateTime
     */
    public function getDatehistory() {
        return $this->datehistory;
    }

    /**
     * Set describ
     *
     * @param string $describ
     *
     * @return Historyofanimalexamination
     */
    public function setDescrib($describ) {
        $this->describ = $describ;

        return $this;
    }

    /**
     * Get describ
     *
     * @return string
     */
    public function getDescrib() {
        return $this->describ;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    public function setAnimals(Animals $animals) {
        $this->animals = $animals;
    }

    public function setFile($file) {
        $this->file = $file;
    }

    public function Serialization() {
        $data['id'] = $this->id;
        $data['file'] = $this->file;
        $data['Describ'] = $this->describ;
        if (!empty($this->datehistory)) {
            $DateTime = $this->datehistory;
            $DateTime = $DateTime->format("Y-m-d");
            $DateTime = explode("-", $DateTime);
            $DateTime = Jalali::gregorian_to_jalali($DateTime[0], $DateTime[1], $DateTime[2]);
            $data['dateHistory'] = $DateTime[0] . '/' . $DateTime[1] . "/" . $DateTime[2];
        } else {
            $data['dateHistory'] = "";
        }
        if (!empty($this->getCreateAt())) {
            $DateTime = $this->getCreateAt();
            $DateTime = $DateTime->format("Y-m-d");
            $DateTime = explode("-", $DateTime);
            $DateTime = Jalali::gregorian_to_jalali($DateTime[0], $DateTime[1], $DateTime[2]);
            $data['createAt'] = $DateTime[0] . '/' . $DateTime[1] . "/" . $DateTime[2];
        } else {
            $data['createAt'] = "";
        }

        if (!empty($this->getUpdateAt())) {
            $DateTime = $this->getCreateAt();
            $DateTime = $DateTime->format("Y-m-d");
            $DateTime = explode("-", $DateTime);
            $DateTime = Jalali::gregorian_to_jalali($DateTime[0], $DateTime[1], $DateTime[2]);
            $data['updateAt'] = $DateTime[0] . '/' . $DateTime[1] . "/" . $DateTime[2];
        } else {
            $data['updateAt'] = "";
        }
        $data['animals_id'] = $this->getAnimals()->getId();
        return $data;
    }

}
