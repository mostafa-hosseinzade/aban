<?php

namespace AppBundle\Service;

use SoapClient;

class HelperFunction {

    public function __construct() {
        
    }

    public static function SMS($url, $username, $password, $senderNumbers, $recipientNumbers, $messageBodies) {
        $client = new \SoapClient($url);
        // url panel sms abanpet http://my.sepehritc.com
        if (is_array($recipientNumbers)) {
            $params = array(
                'username' => $username,
                'password' => $password,
                'from' => $senderNumbers,
                'to' => $recipientNumbers,
                'text' => $messageBodies,
                'isflash'=>false,
                'udh'=>"",
                'recId'=>array(0),
                'status'=>0x0
            );
        } else {
            $params = array(
                'username' => $username,
                'password' => $password,
                'from' => $senderNumbers,
                'to' => array($recipientNumbers),
                'text' => $messageBodies,
                'isflash'=>false,
                'udh'=>"",
                'recId'=>array(0),
                'status'=>0x0
            );
        }

        $result=$client->SendSms($params)->SendSmsResult;
        return $result;
    }

    public static function EMAIL($TO, $SUBJECT, $MessageBody) {

////////////////  Swift Mailer ////////////////////////////
                $message = \Swift_Message::newInstance()
                ->setSubject($SUBJECT)
                ->setFrom('info@abanpet.com')
                ->setTo($TO)
                ->setBody($MessageBody ,'text/html');
                $this->get('mailer')->send($message);
////////////////////////////////////////////////////////////    

//        $headers = "MIME-Version: 1.0" . "\r\n";
//        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
//        // More headers
//        $headers .= 'From: <seminars@piramonco.com>' . "\r\n";
//
//        $send = mail($TO, $SUBJECT, $MessageBody, $headers);
//        if ($send) {
//            return TRUE;
//        } else {
//            return FALSE;
//        }
    }

}
