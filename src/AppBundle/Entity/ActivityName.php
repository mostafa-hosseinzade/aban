<?php

namespace AppBundle\Entity;

use AppBundle\Entity\CroneActivity;
use Doctrine\ORM\Mapping as ORM;

/**
 * ActivityName
 * @ORM\Table(name="activity_name")
 * @ORM\Entity
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 */
class ActivityName {

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime",nullable=true)
     */
    private $createAt;

    /**
     *
     * @var integer
     * @ORM\Column(name="id",type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     *
     * @var string
     * @ORM\Column(name="name",type="string",length=255,nullable=false)
     */
    private $name;

    /**
     * @ORM\Column(name="`host`",type="integer",nullable=true)
     */
    private $host = 1;

    /**
     * @ORM\Column(name="updated_at",type="datetime",nullable=true)
     */
    private $updateAt;

    /**
     * 
     * @return name
     */
    public function getName() {
        return $this->name;
    }

    /**
     * 
     * @param type $name
     * @return \AppBundle\Entity\CroneName
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    public function getId() {
        return $this->id;
    }

    /**
     * Set initial value for created/updated values
     *
     * @ORM\PrePersist
     */
    public function setCreateAtValues() {
        $this->setCreateAt(new \DateTime());
    }

    public function __construct() {
        $this->setCreateAtValues();
    }

    public function getCreateAt() {
        return $this->createAt;
    }

    public function setCreateAt($createAt) {
        $this->createAt = $createAt;
    }

    /**
     * Auto set the updated date
     *
     * @ORM\PreUpdate
     */
    public function setUpdateAtValue() {
        $this->setUpdateAt(new \DateTime());
        $this->host = 1;
    }

}
