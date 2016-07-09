<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use AppBundle\Entity\User;
use AppBundle\Entity\TimeStampale;

/**
 * UserMessage
 *
 * @ORM\Table(name="user_message")
 * @ORM\Entity
 * @ExclusionPolicy("all")
 * @ORM\HasLifecycleCallbacks()
 */
class UserMessage extends TimeStampale {

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
     * 
     * 
     * 
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id",onDelete="CASCADE")
     * })
     */
    private $user;
    
    
    
    

    /**
     * @var string
     * @ORM\Column(name="message_type", type="integer", nullable=true)
     * 
     * @Expose
     * 
     */
    private $messageType;

    /**
     * @var string
     * @ORM\Column(name="message", type="string",length=1024, nullable=true)
     * 
     * @Expose
     * 
     */
    private $message;
    
    
      /**
     * @var string
     * @ORM\Column(name="username_owner_message", type="string",length=256, nullable=true)
     * 
     * @Expose
     * 
     */
    private $userNameMessageOwner;
    
    function getUserNameMessageOwner() {
        return $this->userNameMessageOwner;
    }

    function setUserNameMessageOwner($userNameMessageOwner) {
        $this->userNameMessageOwner = $userNameMessageOwner;
    }

    
    function getMessageOwner() {
        return $this->messageOwner;
    }

    function setMessageOwner($messageOwner) {
        $this->messageOwner = $messageOwner;
    }

        
    /**
     * @var string
     * @ORM\Column(name="message_owner", type="integer", nullable=true)
     * 
     * @Expose
     * 
     */
    private $messageOwner;

    /**
     * @var string
     * @ORM\Column(name="is_read", type="boolean", nullable=true)
     * @Expose
     * 
     */
    private $isRead = false;
    
    

    function getId() {
        return $this->id;
    }

    function getUser() {
        return $this->user;
    }

    function getMessageType() {
        return $this->messageType;
    }

    function getMessage() {
        return $this->message;
    }

    function getIsRead() {
        return $this->isRead;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setUser(User $user) {
        $this->user = $user;
    }

    function setMessageType($messageType) {
        $this->messageType = $messageType;
    }

    function setMessage($message) {
        $this->message = $message;
    }

    function setIsRead($isRead) {
        $this->isRead = $isRead;
    }

}
