<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\TimeStampale;

/**
 *
 * @ORM\Table(name="delete_sync")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class DeleteSync {

    /**
     * @ORM\Column(name="id",type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(name="n_table",type="string",length=255)
     */
    private $Ntable;

    /**
     * @ORM\Column(name="id_deleted",type="string",length=255)
     */
    private $idDeleted;
    
    /**
     * @ORM\Column(name="created_at",type="datetime",nullable=true)
     */
    private $createAt;
    function getCreateAt() {
        return $this->createAt;
    }
    function setCreateAt($createAt) {
        $this->createAt = $createAt;
    }

            
    public function setNtable($Ntable) {
        $this->Ntable = $Ntable;
    }

    public function setIdDeleted($idDeleted) {
        $this->idDeleted = $idDeleted;
    }

    public function getId() {
        return $this->id;
    }

    public function getNtable() {
        return $this->Ntable;
    }

    public function getIdDeleted() {
        return $this->idDeleted;
    }
    
    public function Serialize() {
        $data = array();
        $data['id'] = $this->id;
        $data['Ntable'] = $this->Ntable;
        $data['idDeleted'] = $this->idDeleted;
        $data['createAt'] = $this->createAt;
        return $data;
    }
    
    /**
     * Set initial value for created/updated values
     *
     * @ORM\PrePersist
     */
    public function setCreateAtValues()
    {
        $this->setCreateAt(new \DateTime());
    }
    
}
