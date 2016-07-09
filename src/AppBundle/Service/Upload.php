<?php

namespace AppBundle\Service;

class Upload {

    private $file;
    private $NameFile;
    private $oldFile;

    public function SetFile($file) {
        if ($file != null) {
            $this->file = $file;
        } else {
            return false;
        }
    }

    public function setOldFile($name) {
        $this->oldFile = $name;
    }

    protected function tmpUploadedRootDir() {
        return __DIR__ . '/../Sql/LastUpload';
    }

    protected function getUploadRootDir() {
        return $this->tmpUploadedRootDir() . '/';
    }

    public function UploadFile() {
        if ($this->file == NULL) {
            return;
        }
        if ($this->NameFile != NULL or strlen($this->NameFile) > 0) {
            $this->NameFile = null;
        }
        if (!empty($this->oldFile)) {
            $UrlFile = explode('/', $this->oldFile);
            $UrlFile = $UrlFile[4];
            if (file_exists($this->getUploadRootDir() . $UrlFile) == true) {
                unlink($this->getUploadRootDir() . $UrlFile);
            }
        }
        $name_file = new \DateTime();
        $name_file = $name_file->format('YmdHis');
        $this->getFile()->move($this->getUploadRootDir(), $this->getFile()->getClientOriginalName());
        $this->NameFile = $this->getFile()->getClientOriginalName();
        $this->file = null;
        return $this->NameFile;
    }

    /**
     * 
     * @return file
     */
    function getFile() {

        return $this->file;
    }

}
