<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\User;
use AppBundle\Service\HelperFunction;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class MobileRegistrationController extends FOSRestController {

    private $UrlMsg = "http://87.107.121.54/post/send.asmx?wsdl";
    private $UserNameMsg = "amin_kh_s";
    private $PasswordMsg = "9NSJMOet";
    private $SenderNumber = "30001220000048";

    /**
     * 
     * @return array
     * @View()
     *
     * @throws NotFoundHttpException when post not exist
     */
    public function postRegUserMobileAction(Request $request) {
        $infoUser = $request->request->get('user');
        $em = $this->get('fos_user.user_manager');
        $temp = $this->getDoctrine()->getRepository('AppBundle:User')->findBy(array('mobile' => $infoUser['mobile']));
        if (count($temp) > 0) {
            return array('error' => 'این کاربر تکراری است');
        }

        $user = $em->createUser();

        $user->setName($infoUser['name']);
        $user->setFamily($infoUser['family']);
        $user->setEmail($infoUser['email']);
        $user->setEmailCanonical($infoUser['email']);
        $user->setMobile($infoUser['mobile']);
        $user->setUsername($infoUser['mobile']);
        $user->setUsernameCanonical($infoUser['mobile']);
        $user->setSex($infoUser['sex']);
        $user->setAddress($infoUser['address']);

        $rndNumber = mt_rand(1000, 99999);
        $user->setPlainPassword($rndNumber);
        $user->setConfirmationToken(md5($rndNumber));
        $user->setEnabled(false);

        $smsMessage = '**آبان پت**';
        $smsMessage = $smsMessage . 'شماره فعالسازی نرم افزار و  پسورد کاربری شما:'.$rndNumber;
        $result = HelperFunction::SMS($this->UrlMsg, $this->UserNameMsg, $this->PasswordMsg, $this->SenderNumber, $infoUser['mobile'], $smsMessage);
        $em->updateUser($user);
        if ($result == 0) {
            return array('Message' => 'خطای ارسال اس ام اس', 'status' => 1);
        }
    }

    /**
     * 
     * @return array
     * @View()
     *
     * @throws NotFoundHttpException when post not exist
     */
    
    public function getFindUserByTokenAction($mdCode, $mobile) {
       
//        if(!$request->isXmlHttpRequest()){
//         //   new \Symfony\Component\Validator\Exception
//            throw \Symfony\Component\HttpKernel\Exception\NotFoundHttpException();
//        }
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserByConfirmationToken(md5($mdCode));
        if (is_null($user)) {
            return array('Message' => 'کد شما اشتباه می باشد', 'status' => 0);
        }
        $user->setEnabled(true);
        $user->setConfirmationToken(null);
        $userManager->updateUser($user);
        return array('Message' => 'logined', 'status' => 1);
    }

    /**
     *
     * @return array
     * @View()
     *
     * @throws NotFoundHttpException when comment not exist
     */
    public function getAnimalsCategoryMobileAction() {
        ///animals/category/mobile.{_format} 
        return $this->getDoctrine()->getRepository('AppBundle:Animalscategory')->findAll();
    }


}
