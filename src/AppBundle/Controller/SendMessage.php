<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Route;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\UserMessage;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * This Class Send Message From The Application
 *
 * @author Mr.Mostafa
 * @Route("/Message")
 */
class SendMessage extends Controller {

    /**
     * @Route("/SendMessage",name="TestSendMessage")
     * @Method("post")
     */
    public function SendMessage(Request $request) {
        if (empty($request->request->get("username")) || empty($request->request->get("password")) || empty($request->request->get('msg_type')) || empty($request->request->get("msg"))) {
            return new Response("0");
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
            return new Response("0");
        }
        $em = $this->getDoctrine()->getManager();
        // if message for one user
        if ($request->request->get("msg_type") == 1) {
            if (empty($request->request->get("user_id"))) {
                return new Response("0");
            }
            $user_msg = $em->getRepository("AppBundle:User")->find($request->request->get("user_id"));
            if (!$user_msg) {
                return new Response("0");
            }
            $entity = new UserMessage();
            $entity->setUser($user_msg);
            $entity->setMessage($request->request->get('msg'));
            $entity->setMessageType(1);
            $entity->setIsRead(0);
            $entity->setMessageOwner($user->getId());
            $entity->setUserNameMessageOwner($user->getUserName());
            $em->persist($entity);
            $em->flush();
            return new Response($entity->getId());
        }
        // insert message for all user
        $entity = new UserMessage();
        $entity->setMessage($request->request->get('msg'));
        $entity->setMessageType(3);
        $entity->setIsRead(0);
        $entity->setMessageOwner($user->getId());
        $entity->setUserNameMessageOwner($user->getUserName());
        $em->persist($entity);
        $em->flush();
        return new Response($entity->getId());
    }

    /**
     * @Route("/TestSend")
     * @Template("AppBundle::message/test_send.html.twig")
     */
    public function TestSend() {
        return array();
    }

}
