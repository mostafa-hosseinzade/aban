<?php

namespace AppBundle\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Service\Jalali;
use AppBundle\Service\HelperFunction;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Entity\UserMessage;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Description of CroneController
 * @Route("/")
 */
class CroneController extends Controller {

    private $UrlMsg = "http://87.107.121.54/post/send.asmx?wsdl";
    private $UserNameMsg = "amin_kh_s";
    private $PasswordMsg = "9NSJMOet";
    private $SenderNumber = "30001220000048";

    ////////////////////
    // Crone Code
    ////////////////////
    /**
     * @Route("/CroneStart")
     */
    public function CroneStart() {
        $em = $this->getDoctrine()->getManager();
        $day = new \DateTime();
        $day = $day->add(new \DateInterval('P1D'));
        $day = new \DateTime($day->format("Y-m-d"));
//        $result = $em->getRepository("AppBundle:Activity")->findBy(array('date' => $day));
        $q = $em->getRepository("AppBundle:Activity")->createQueryBuilder('p');
        $result = $q->where("p.date = :date")
                ->setParameter("date", $day)
                ->andWhere("p.active_crone = '1'")
                ->andWhere("p.SendSms = 0")
                ->getQuery()
                ->getResult();

        if (empty($result)) {
            return new Response('Not Message');
        }
        $data = array();
        $i = 0;
        foreach ($result as $value) {
            if ($value->getSend_with_message()) {
                $UserMessage = new UserMessage();
                $UserMessage->setMessage($value->getDesc());

                $UserMessage->setMessageOwner($value->getAnimals()->getUser()->getId());
                $UserMessage->setMessageType(1);
                $UserMessage->setUser($value->getAnimals()->getUser());

                $UserMessage->setUserNameMessageOwner("مدیریت سایت");
                $UserMessage->setCreateAt(new \DateTime());
                $em->persist($UserMessage);
            }
            $value->setSendSms(1);
            $date = $value->getDate();
            $DateTime = $date->format('Y-m-d');
            $DateTime = explode('-', $DateTime);
            $DateTime = Jalali::gregorian_to_jalali($DateTime[0], $DateTime[1], $DateTime[2]);
            $text = $value->getActivityName()->getName() . " : (" . $DateTime[0] . "-" . $DateTime[1] . "-" . $DateTime[2] . ") \n" . $value->getAnimals()->getName() . "\n" . $value->getDesc();
            $number = $value->getAnimals()->getUser()->getMobile();
            $result = HelperFunction::SMS($this->UrlMsg, $this->UserNameMsg, $this->PasswordMsg, $this->SenderNumber, array($number), $text);
            $i++;
        }
        $em->flush();
        return new Response('Send Messages');
    }

    /**
     * @Route("ChangeCrone")
     * @Method("post")
     */
    public function ChangeCrone(Request $request) {
        if (empty($request->request->get("username")) || empty($request->request->get("password"))) {
            return new Response("please check your data many or one field is empty");
        }
        // get service fos_user
        $user_manager = $this->get("fos_user.user_manager");
        $factory = $this->get('security.encoder_factory');
        //check username
        $user = $user_manager->loadUserByUsername($request->request->get("username"));
        $encoder = $factory->getEncoder($user);
        //check password
        $check = ($encoder->isPasswordValid($user->getPassword(), $request->request->get("password"), $user->getSalt())) ? "true" : "false";
        if (!$check) {
            return new Response("username or password is invalid");
        }
        $em = $this->getDoctrine()->getManager();
        $activity = $em->getRepository("AppBundle:Activity")->find($request->request->get("id_activity"));
        if (!$activity) {
            return new Response("id activity is not valid");
        }
        $activity->setSendSms($request->get("send_sms"));
        $activity->setActive_crone($request->get("active_crone"));
        $activity->setSend_with_message($request->get("send_with_sms"));
        $em->flush();
        return new Response("Ok Changed");
    }

    /**
     * @Route("/SendCroneChange")
     * @Template("AppBundle::activity/send_activity.html.twig")
     */
    public function TestSend(Request $request) {
        return array();
    }

}
