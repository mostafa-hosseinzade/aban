<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\ActivityName;
use AppBundle\Entity\TimeStampale;
use AppBundle\Controller\CheckQueryController as Controller;

/**
 * Certificateofdoctors
 *
 * @ORM\Table(name="activity")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 * 
 */
class Activity extends TimeStampale {

    /**
     *
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \AppBundle\Entity\Animals
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ActivityName")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="activity_name_id", referencedColumnName="id",onDelete="CASCADE")
     * })
     * )}
     */
    private $activityName;

    /**
     *
     * @var string
     * @ORM\Column(name="`desc`",type="text",nullable=true)
     */
    private $desc;

    /**
     *
     * @var \AppBundle\Entity\Animals
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Animals")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="animale_id", referencedColumnName="id",onDelete="CASCADE")
     * })
     * )}
     */
    private $animale;

    /**
     *
     * @ORM\Column(name="`host`",type="integer",nullable=true)
     */
    private $host = 1;

    /**
     *
     * @var date
     * @ORM\Column(name="date",type="date",nullable=true)
     */
    private $date;

    /**
     *
     * @var boolean
     * @ORM\Column(name="send_sms",type="boolean")
     */
    private $SendSms = 0;

    /**
     * @ORM\Column(name="active_crone",type="boolean")
     */
    private $active_crone = 1;

    /**
     * @ORM\Column(name="send_with_message",type="boolean")
     */
    private $send_with_message = 1;

    function setSend_with_message($send_with_message) {
        $this->send_with_message = $send_with_message;
    }

    public function getSend_with_message() {
        return $this->send_with_message;
    }

    public function getActive_crone() {
        return $this->active_crone;
    }

    public function setActive_crone($active_crone) {
        $this->active_crone = $active_crone;
    }

    /**
     * 
     * @param type $type
     */
    public function getActivityName() {
        return $this->activityName;
    }

    /**
     * 
     * @param type $type
     */
    public function setActivityName(ActivityName $activity) {
        $this->activityName = $activity;
    }

    /**
     * 
     * @return $desc
     */
    public function getDesc() {
        return $this->desc;
    }

    /**
     * 
     * @param type $desc
     */
    public function setDesc($desc) {
        $this->desc = $desc;
    }

    /**
     * 
     * @return $date
     */
    public function getDate() {
        return $this->date;
    }

    /**
     * 
     * @param type $date
     */
    public function setDate($date) {
        $this->date = $date;
    }

    /**
     * 
     * @return $animale
     */
    public function getAnimals() {
        return $this->animale;
    }

    /**
     * 
     * @param \AppBundle\Entity\Animals $animale
     */
    public function setAnimals(Animals $animale) {
        $this->animale = $animale;
    }

    /**
     * 
     * @return $id
     */
    public function getId() {
        return $this->id;
    }

    /**
     * 
     * @param type $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    public function SetSendSms($send_sms) {
        $this->SendSms = $send_sms;
    }

    /**
     * @return boolean
     */
    public function getSendSms() {
        return $this->SendSms;
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate() {
        $this->host = 1;
    }

}
