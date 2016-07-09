<?php
namespace BlogBundle\Controller;
use Symfony\Component\HttpFoundation\File\File;

class ApiUploadedFile extends File
{
    public function __construct($base64Content)
    {
        $filePath = tempnam(sys_get_temp_dir(), 'UploadedFile');
        $file = fopen($filePath, "w");
        stream_filter_append($file, 'convert.base64-decode');
        fwrite($file, $base64Content);
        $meta_data = stream_get_meta_data($file);
        $path = $meta_data['uri'];
        fclose($file);
        parent::__construct($path, true);
    }
}