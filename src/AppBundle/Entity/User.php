<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 * @ExclusionPolicy("all")
 * @ORM\HasLifecycleCallbacks()
 */
class User extends BaseUser {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * 
     * @Expose
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     *  
     * @Expose
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(name="family", type="string", length=255, nullable=true)
     * 
     * @Expose
     */
    private $family;

    /**
     *
     * @ORM\Column(name="`host`",type="integer",nullable=true)
     */
    private $host = 1;

    /**
     * @var string
     * @ORM\Column(name="phone", type="string", length=255,nullable=true)
     * 
     * @Expose
     */
    private $phone;

    /**
     * @var string
     * @ORM\Column(name="mobile", type="string", length=255,nullable=true)
     * 
     * @Expose
     */
    private $mobile;

    /**
     * @var boolean
     * @ORM\Column(name="sex", type="boolean",nullable=false)
     * 
     * @Expose
     */
    private $sex;

    /**
     *
     * @var \DateTime
     * @ORM\Column(name="updated_at",type="datetime",nullable=true)
     */
    private $updateAt;

    /**
     *
     * @var \DateTime
     * @ORM\Column(name="created_at",type="datetime",nullable=true)
     */
    private $createAt;

    /**
     * Set createAt
     *
     * @param \DateTime $createAt
     * @return Post
     */
    public function setCreateAt($createAt) {
        $this->createAt = $createAt;

        return $this;
    }

    /**
     * Get $createAt
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
     * @return Post
     * 
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
     * @var string
     * @ORM\Column(name="post_code", type="string", length=128,nullable=true)
     * @Expose
     */
    private $postCode;

    /**
     * Gets postCode
     *
     * @return string
     */
    public function getPostCode() {
        return $this->postCode;
    }

    /**
     * Set postCode
     *
     * @param string $postCode
     *
     * @return User
     */
    public function setPostCode($postCode) {
        $this->postCode = $postCode;
    }

    function getSex() {
        return $this->sex;
    }

    function setSex($sex) {
        $this->sex = $sex;
    }

    function getEnabled() {
        return $this->enabled;
    }

    function setEnabled($enabled) {
        $this->enabled = $enabled;
    }

    /**
     * @var integer
     * @ORM\Column(name="money", type="integer",options={"default" = 0},nullable=true)
     * @Expose
     */
    private $money;

    /**
     * Gets money
     *
     * @return integer
     */
    public function getMoney() {
        return $this->money;
    }

    /**
     * Set money
     *
     * @param integer $money
     *
     * @return User
     */
    public function setMoney($money) {
        $this->money = $money;
    }

    /**
     * Gets expiresAt
     *
     * @return integer
     */
    public function getExpiresAt() {
        return $this->expiresAt;
    }

    /**
     * Set expiresAt
     *
     *
     * @return ExpiredAt
     */
    public function setExpiresAt(\DateTime $expiresAt = null) {
        $this->expiresAt = $expiresAt;
    }

    /**
     * Gets mobile
     *
     * @return string
     */
    public function getMobile() {
        return $this->mobile;
    }

    /**
     * Set mobile
     *
     * @param string $mobile
     *
     * @return User
     */
    public function setMobile($mobile) {
        $this->mobile = $mobile;
    }

    /**
     * @var string
     * @ORM\Column(name="address", type="string", length=255,nullable=true)
     * @Expose
     */
    private $address;

    /**
     * Gets address
     *
     * @return string
     */
    public function getAddress() {
        return $this->address;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return User
     */
    public function setAddress($address) {
        $this->address = $address;
    }

    /**
     * Gets phone
     *
     * @return string
     */
    public function getPhone() {
        return $this->phone;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return User
     */
    public function setPhone($phone) {
        $this->phone = $phone;
    }

    /**
     * Gets name
     * 
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param string $name
     * 
     * @return User
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * Gets locked
     * 
     * @return string
     */
    public function getLocked() {
        return $this->locked;
    }

    /**
     * Set locked
     *
     * @param string $locked
     * 
     * @return User
     */
    public function setLocked($locked) {
        $this->locked = $locked;
    }

    /**
     * Gets family
     *
     * @return string
     */
    public function getFamily() {
        return $this->family;
    }

    /**
     * Set family
     *
     * @param string $family
     *
     * @return User
     */
    public function setFamily($family) {
        $this->family = $family;
    }

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $usernameCanonical;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $emailCanonical;

    /**
     * @var boolean
     */
    protected $enabled;

    /**
     * The salt to use for hashing
     *
     * @var string
     */
    protected $salt;

    /**
     * Encrypted password. Must be persisted.
     *
     * @var string
     */
    protected $password;

    /**
     * Plain password. Used for model validation. Must not be persisted.
     *
     * @var string
     */
    protected $plainPassword;

    /**
     * @var \DateTime
     */
    protected $lastLogin;

    /**
     * Random string sent to the user email address in order to verify it
     *
     * @var string
     */
    protected $confirmationToken;

    /**
     * @var \DateTime
     */
    protected $passwordRequestedAt;

    /**
     * @var boolean
     */
    protected $locked;

    /**
     * @var boolean
     */
    protected $expired;

    /**
     * @var \DateTime
     */
    protected $expiresAt;

    /**
     * @var array
     */
    protected $roles;

    /**
     * @var boolean
     */
    protected $credentialsExpired;

    /**
     * @var \DateTime
     */
    protected $credentialsExpireAt;

    public function __construct() {
        parent::__construct();
        $this->salt = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
    }

    /**
     * @var string
     *
     * @ORM\Column(name="photo", type="text", nullable=true)
     * 
     * @Expose
     */
    private $photo;

    /**
     * Set photo
     *
     * @param string $photo
     *
     * @return User
     */
    public function setPhoto($photo) {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return string
     */
    public function getPhoto() {
        return $this->photo;
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

    /**
     * Set initial value for created/updated values
     *
     * @ORM\PrePersist
     */
    public function setCreateAtValues() {
        $this->setCreateAt(new \DateTime());
    }

}
