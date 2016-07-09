<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\UserMessage;

class UserMessageController extends FOSRestController {

    //SingleUser = 0 - MultiUser = 1 - SindleDoctor = 2 - MultiDoctor = 3


    private function GetAdminUser() {
        ///cli_panel/doctor/list
        $em = $this->getDoctrine()->getEntityManager();
        //Repository
        $Repository = $em->getRepository('AppBundle:User');
        //query
        $query = $Repository->createQueryBuilder('p');
        //read all doctors

        $query->where("p.roles LIKE :roles")
                ->setParameter('roles', '%ROLE_SUPER_ADMIN%');
        $q = $query->getQuery();
        $result = $q->getResult();
        if (count($result) > 0) {
            return $result[0];
        } else {
            return 0;
        }
    }

    /**
     * 
     * @return array
     * @View()
     *
     * @throws NotFoundHttpException when post not exist
     */
    public function getMessagesUserAction() {
        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();
        $statement = $connection->prepare
                (
sprintf(
        sprintf("select * from user_message where (ISNULL(user_id) or user_id ='%s') and is_read = 0 and ((message_type = 3 and DATEDIFF(NOW(),created_at) < 2 ) or message_type = '1') order by created_at", $this->getUser()->getId())));
        $statement->execute();
        $r = $statement->fetchAll();
        return $r;
//        return $this->getDoctrine()->getRepository('AppBundle:UserMessage')->findBy(array('user' => array($this->getUser(), null), 'isRead' => false, "messageType" => array(1, 3)), array('createAt' => 'DESC'));
    }

    /**
     * Put action
     * @var integer $id Id of the entity
     * @return View|array
     */
    public function putSetReadedMessageAction($id) {
        $em = $this->getDoctrine()->getEntityManager();
        $message = $this->getDoctrine()->getRepository('AppBundle:UserMessage')->find($id);
        if ($message->getMessageType() != 3) {
            $message->setIsRead(true);
            $em->flush();
        }
        return array('message' => '0');
    }

    /**
     * 
     * @return array
     * @View()
     *
     * @throws NotFoundHttpException when post not exist
     */
    public function postSendMessagetoadminAction(Request $request) {
        $user = $this->GetAdminUser();
        if ($user) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = new UserMessage();
            $entity->setUser($this->getUser());
            $entity->setMessage($request->request->get('message'));
            $entity->setMessageType(0);
            $entity->setIsRead(0);
            $entity->setMessageOwner($user->getId());
            $entity->setUserNameMessageOwner($user->getUserName());
            $em->persist($entity);
            $em->flush();
        } else {
            return array(
                "message" => "-1"
            );
        }

        return array(
            "message" => '0'
        );
    }

}
