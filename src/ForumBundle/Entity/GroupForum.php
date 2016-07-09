<?php

namespace ForumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Entity\User;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\SerializedName;
/**
 * GroupForum
 *
 * @ORM\Table(name="group_forum", indexes={@ORM\Index(name="user_id", columns={"user_id"}), @ORM\Index(name="user_id_2", columns={"user_id"})})
 * @ORM\Entity
 * 
 * @ORM\HasLifecycleCallbacks()
 * 
 */
class GroupForum {

    /**
     * @ORM\PrePersist()
     */
    public function setCreatedAtValue() {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    /**
     * @ORM\PreUpdate()
     */
    public function setUpdatedAtValue() {
        $this->updatedAt = new \DateTime();
    }

    /**
     * @var string
     * @Expose
     * @Assert\NotBlank(message = "not_blank")
     * @Assert\Length(
     *      min = 3,
     *      minMessage = "minMessageName3Char",
     * )
     * @ORM\Column(name="title", type="string", length=1024, nullable=false)
     */
    private $title;

    /**
     * @var string
     * @Expose
     * @Assert\NotBlank(message = "not_blank")
     * @ORM\Column(name="meta", type="string", length=1024, nullable=true)
     */
    private $meta;

    /**
     * @var integer
     * @Expose
     * 
     * 
     * @ORM\Column(name="order_list", type="integer", nullable=true)
     * @SerializedName("orderList")
     */
    private $orderList;

    /**
     * @var \DateTime
     * @Expose
     * 
     * @ORM\Column(name="create_at", type="datetime", nullable=true)
     */
    private $createAt;

    /**
     * @var \DateTime
     * @Expose
     * 
     * @ORM\Column(name="update_at", type="datetime", nullable=true)
     */
    private $updateAt;

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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var boolean
     *
     * @ORM\Column(name="enabled", type="boolean", nullable=false)
     */
    private $enabled;

    public function __construct() {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
//        $this->setCreateAt(new \DateTime());
//        $this->setUpdateAt(new \DateTime());

        $this->enabled = false;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return GroupForum
     */
    public function setTitle($title) {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Set meta
     *
     * @param string $meta
     *
     * @return GroupForum
     */
    public function setMeta($meta) {
        $this->meta = $meta;

        return $this;
    }

    /**
     * Get meta
     *
     * @return string
     */
    public function getMeta() {
        return $this->meta;
    }

    /**
     * Set orderList
     *
     * @param integer $orderList
     *
     * @return GroupForum
     */
    public function setOrderList($orderList) {
        $this->orderList = $orderList;

        return $this;
    }

    /**
     * Get orderList
     *
     * @return integer
     */
    public function getOrderList() {
        return $this->orderList;
    }

    /**
     * Set createAt
     *
     * @param \DateTime $createAt
     *
     * @return GroupForum
     */
    public function setCreateAt($createAt) {
        $this->createAt = $createAt;

        return $this;
    }

    /**
     * Get createAt
     *
     * @return \DateTime
     */
    public function getCreateAt() {
        return $this->createAt;
    }

    /**
     * Set updateAt
     *
     * @param \DateTime $updateAt
     *  
     * @return GroupForum
     */
    public function setUpdateAt($updateAt) {
        $this->updateAt = $updateAt;

        return $this;
    }

    /**
     * Get updateAt
     *  
     * @return \DateTime
     */
    public function getUpdateAt() {
        return $this->updateAt;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return GroupForum
     */
    public function setUser(\AppBundle\Entity\User $user = null) {
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
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return GroupForum
     */
    public function setEnabled($enabled) {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean
     */
    public function getEnabled() {
        return $this->enabled;
    }

}
