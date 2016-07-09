<?php

namespace AppBundle\Entity;

use AppBundle\Entity\TimeStampale;
use Doctrine\ORM\Mapping as ORM;

/**
 * Certificateofdoctors
 *
 * @ORM\Table(name="slider")
 * @ORM\Entity
 * 
 */
class Slider extends TimeStampale {

    /**
     *
     * @var integer
     * @ORM\Column(name="id",type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     *
     * @var string
     * @ORM\Column(name="file",type="string",length=300,nullable=false)
     */
    private $file;

    /**
     *
     * @var type 
     * @ORM\Column(name="title_h3",type="string",length=300,nullable=false)
     */
    private $title_h3;
    
    /**
     *
     * @var boolean
     * @ORM\Column(name="active",type="boolean")
     */
    private $active = 1;

    /**
     *
     * @ORM\Column(name="title_h1",type="string",length=300,nullable=false) 
     */
    private $title_h1;
    
    private $img;

    public function getId() {
        return $this->id;
    }
    
    public function getActive(){
        return $this->active;
    }
    
    public function setActive($active){
        $this->active = $active;
        return $this;
    }

    public function getFile() {
        return $this->file;
    }

    public function setFile($file) {
        $this->file = $file;
    }

    public function setTitleH3($title_h3) {
        $this->title_h3 = $title_h3;
        return $this;
    }

    public function getTitleH3() {
        return $this->title_h3;
    }

    public function setTitleH1($title_h1) {
        $this->title_h1 = $title_h1;
        return $this;
    }

    public function getTitleH1() {
        return $this->title_h1;
    }

    public function getFullFilePath() {
        return null === $this->file ? null : $this->getUploadRootDir() . $this->file;
    }

    protected function tmpUploadedRootDir() {
        return __DIR__ . '/../../../web/bundles/public/img/slider';
    }

    protected function getUploadRootDir() {
        return $this->tmpUploadedRootDir() . '/';
    }

    public function UploadImg() {
        if ($this->img == NULL) {
            return;
        }
        if ($this->file != NULL or strlen($this->file) > 0) {
            if (\file_exists($this->getFullFilePath())) {
                unlink($this->getFullFilePath());
            }
        }
        $name_file = new \DateTime();
        $name_file = $name_file->format('YmdHis');
        $this->getImg()->move($this->getUploadRootDir(), "slider".$name_file . '.' . $this->getImg()->guessClientExtension());
        $this->setFile("slider".$name_file. '.' . $this->getImg()->guessClientExtension());
        $this->img = null;
        return true;
    }

    /**
     * 
     * @return file
     */
    function getImg() {
        return $this->img;
    }

    /**
     * 
     * @param type $img
     */
    function setImg($img) {
        $this->img = $img;
    }

}
