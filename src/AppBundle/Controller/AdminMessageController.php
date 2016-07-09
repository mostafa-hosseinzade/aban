<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Route;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Service\Jalali;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\UserMessage;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Route("/admin/AdminMessage")
 */
class AdminMessageController extends Controller {

    /**
     * @Route("/",name="AdminMessage")
     * @Template("AppBundle::AdminMessage.html.twig")
     */
    public function index() {
        return array();
    }

    /**
     * 
     * @param type $offset
     * @param type $limit
     * @param type $attr
     * @param type $order
     * @param type $search
     * @Route("/offset/{offset}/attr/{attr}/order/{order}/search/{search}/limits/{limit}")
     */
    public function OffsetAttrOrderSearchLimitsAction($offset, $attr, $order, $search, $limit) {
        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();

        $statement = $connection->prepare(sprintf("select count(*) from user_message"));

        $statement->execute();
        $count = $statement->fetchAll();

        $Repository = $em->getRepository("AppBundle:UserMessage");
        if ($search == -1) {
            $q = $Repository->createQueryBuilder('p')
                    ->setFirstResult($offset)
                    ->setMaxResults($limit)
                    ->orderBy("p." . $attr, $order)
                    ->getQuery()
                    ->getResult();
        } else {
            $q = $Repository->createQueryBuilder('p')
                    ->where("p.message like '%$search%'")
                    ->setFirstResult($offset)
                    ->setMaxResults($limit)
                    ->orderBy("p." . $attr, $order)
                    ->getQuery()
                    ->getResult();
        }

        if (empty($q)) {
            $data['msg'] = "پیام ها;اطلاعاتی یافت نشد;error;true";
            $response = \json_encode($data);
            return new Response($response);
        }
        $data = array();
        $i = 0;
        foreach ($q as $value) {
            $data['data'][$i]['id'] = $value->getId();
            $data['data'][$i]['message'] = $value->getMessage();
            if($value->getMessageType() != 3){
            $data['data'][$i]['user_id'] = $value->getUser()->getId();
            $data['data'][$i]['username'] = $value->getUser()->getUserName();
            $data['data'][$i]['name'] = $value->getUser()->getName();
            $data['data'][$i]['family'] = $value->getUser()->getFamily();
            }else{
            $data['data'][$i]['user_id'] = '';    
            }
            $data['data'][$i]['message_type'] = $value->getMessageType();
            $data['data'][$i]['message_owner'] = $value->getMessageOwner();
            $data['data'][$i]['username_message_owner'] = $value->getUserNameMessageOwner();
            $data['data'][$i]['is_read'] = $value->getIsRead();

            if ($value->getCreateAt() != '') {
                $day = $value->getCreateAt();
                $day = $day->format('Y-m-d');
                $day = explode("-", $day);
                $day = Jalali::gregorian_to_jalali($day[0], $day[1], $day[1]);
                $data['data'][$i]['createAt'] = $day[0] . "/" . $day[1] . "/" . $day[2];
            } else {
                $data['data'][$i]['createAt'] = '';
            }
            $i++;
        }

        $data['count'] = $count[0]["count(*)"];
        $response = \json_encode($data);
        return new Response($response);
    }

    /**
     * @Route("/UserAll")
     */
    public function UserAll() {
        $connection = $this->getDoctrine()->getManager()->getConnection();
        $statement = $connection->prepare(sprintf("select id,name,family,username from fos_user where roles = 'a:0:{}'"));
        $statement->execute();
        $result = $statement->fetchAll();
        if (empty($result)) {
            $data['msg'] = "پیام ها;کاربری یافت نشد;error;true";
            $response = \json_encode($data);
            return new Response($response);
        }
        $data['data'] = $result;
        $response = \json_encode($data);
        return new Response($response);
    }

    /**
     * @Route("/AddMessage")
     * @Method("post")
     */
    public function AddMessage(Request $request) {
        if ((empty($request->get("user_id")) && $request->get("AllUserSend") == false) or empty($request->get("message"))) {
            $data['msg'] = "پیام ها;مشکل در اطلاعات ارسال شده;error;true";
            $response = \json_encode($data);
            return new Response($response);
        }
        $em = $this->getDoctrine()->getManager();
        
        $UserMessage = new UserMessage();
        $UserMessage->setMessage($request->get("message"));
        
        $UserMessage->setMessageOwner($this->getUser()->getId());
        if($request->get("AllUserSend") == true){
            $UserMessage->setMessageType(3);
        }  else {
           $UserMessage->setMessageType(1); 
           $user = $em->getRepository("AppBundle:User")->find($request->get("user_id"));
           $UserMessage->setUser($user);
        }
        
        $UserMessage->setUserNameMessageOwner("مدیریت سایت");
        $UserMessage->setCreateAt(new \DateTime());
        $em->persist($UserMessage);
        $em->flush();
        $data['msg'] = "پیام ها;اطلاعات با موفقیت ثبت شد;success;true";
        $response = \json_encode($data);
        return new Response($response);
    }
    
    /**
     * @Route("/DeleteMessage/{id}")
     */
    public function DeleteMessage($id) {
        $em = $this->getDoctrine()->getManager();
        $message = $em->getRepository("AppBundle:UserMessage")->find($id);
        if(!$message){
        $data['msg'] = "پیام ها;مشکل در اطلاعات ارسال شده;error;true";
        $response = \json_encode($data);
        return new Response($response);
        }
        $em->remove($message);
        $em->flush();
        $data['msg'] = "پیام ها;اطلاعات با حذف  شد;success;true";
        $response = \json_encode($data);
        return new Response($response);
    }

}
