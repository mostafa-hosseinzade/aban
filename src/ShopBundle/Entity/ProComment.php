<?php

namespace ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\User;

/**
 * Status
 *
 * @ORM\Table(name="pro_comment")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class ProComment {

    /**
     * @var string
     *
     * @ORM\Column(name="src", type="text", nullable=false)
     */
    private $comment;

    /**
     * @var \ShopBundle\Entity\Product
     *
     * @ORM\ManyToOne(targetEntity="ShopBundle\Entity\Product")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product", referencedColumnName="id")
     * })
     */
    private $product;

    /**
     * @var \AppBundle\Entity\User
     * 
     * 
     * 
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \DateTime
     * 
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;
    
    /**
     * @ORM\PrePersist()
     * 
     */
    public function setCreatedAtValue() {
        $this->createdAt = new \DateTime();
    }
    function getComment() {
        return $this->comment;
    }

    function getProduct() {
        return $this->product;
    }

    function getUser() {
        return $this->user;
    }

    function getId() {
        return $this->id;
    }

    function getCreatedAt() {
        return $this->createdAt;
    }

    function setComment($comment) {
        $this->comment = $comment;
    }

    function setProduct(\ShopBundle\Entity\Product $product) {
        $this->product = $product;
    }

    function setUser(\AppBundle\Entity\User $user) {
        $this->user = $user;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setCreatedAt(\DateTime $createdAt) {
        $this->createdAt = $createdAt;
    }


}
