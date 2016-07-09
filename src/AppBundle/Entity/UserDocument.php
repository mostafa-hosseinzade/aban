<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use AppBundle\Entity\User;
use AppBundle\Service\Jalali;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_document")
 * @ExclusionPolicy("all")
 * @ORM\HasLifecycleCallbacks()
 */
class UserDocument extends TimeStampale {

    /**
     * @var string
     *
     * @ORM\Column(name="photo", type="text", nullable=false)
     * @Expose
     */
    private $photo;

    /**
     * Set photo
     * @param string $photo
     * @return UserDocument
     */
    public function setPhoto($photo) {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     * @return string
     */
    public function getPhoto() {
        return $this->photo;
    }

    /**
     * @var string
     * @Expose
     * @ORM\Column(name="document_title", type="string", length=255, nullable=false)
     */
    private $documentTitle;

    /**
     * Set documentTitle
     * @param string $documentTitle
     * @return UserDocument
     */
    public function setDocumentTitle($documentTitle) {
        $this->documentTitle = $documentTitle;
        return $this;
    }

    /**
     * Get documentTitle
     * @return string
     */
    public function getDocumentTitle() {
        return $this->documentTitle;
    }

    /**
     * @var integer
     * 
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Expose
     */
    private $id;

    /**
     * @var \AppBundle\Entity\User
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
     * Show All Data In Array
     */
    public function Serialize() {
        $data = array();
        $data['documentTitle'] = $this->documentTitle;
        $data['id'] = $this->id;
        if($this->getUpdateAt() != ''){
            $d = $this->getUpdateAt();
            $d = $d->format("Y/m/d");
            $d = explode("/", $d);
            $d = Jalali::gregorian_to_jalali($d[0], $d[1], $d[2]);
            $data['updateAt'] = $d[0]."/".$d[1]."/".$d[2];
        }
        if($this->getCreateAt() != ''){
            $d = $this->getCreateAt();
            $d = $d->format("Y/m/d");
            $d = explode("/", $d);
            $d = Jalali::gregorian_to_jalali($d[0], $d[1], $d[2]);
            $data['createAt'] = $d[0]."/".$d[1]."/".$d[2];
        }        
        $data['photo'] = $this->getPhoto();
        
        return $data;
    }
}
