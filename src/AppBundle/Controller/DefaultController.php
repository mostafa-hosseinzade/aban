<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/")
 */
class DefaultController extends Controller {

    /**
     * @Route("/panel",name="panel")
     * 
     */
    public function indexAction() {
        if ($this->getUser()) {
            if ($this->getUser()->hasRole('ROLE_SUPER_ADMIN')) {
                return $this->redirect($this->generateUrl('_admin'));
            } elseif ($this->getUser()->hasRole('ROLE_DOCTOR')) {
                return $this->redirect($this->generateUrl('doctor_panel'));
            } else {
                return $this->redirect($this->generateUrl('client_panel'));
            }
        } else {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
    }

    /**
     * @Route("/micro_search/{id}",name="micro_search")
     * 
     */
    public function microchipSearchAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository("AppBundle:Animals")->findOneBy(array('microChip' => $id));
        if ($entity) {
            $photo = $em->getRepository("AppBundle:Animalsphoto")->findOneBy(array('animals' => $entity, 'photoDefault' => true));
            $p = '';
            if ($photo) {
                $p = $photo->getPhoto();
            }
            $data = array(
                'name' => $entity->getName(),
                'photo' => $p,
                'sp' => $entity->getGoneh(),
                'br' => $entity->getNezhad(),
                'sex' => $entity->getSex(),
                'color' => $entity->getColor()
            );
            return new JsonResponse(array('data' => $data, 'status' => true));
        }
        return new JsonResponse(array('status' => false));
    }

    /**
     * @Route("/registration",name="reg")
     * 
     */
    public function registrationAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $data = $request->request->all();
        if ($em->getRepository('AppBundle:User')->findBy(array('email' => $data['user']['email']))) {
            return new \Symfony\Component\HttpFoundation\Response(json_encode(array('success' => false, 'type' => 1, 'message' => 'کاربری با این ایمیل در سیستم وجود دارد.')));
        }
        if ($em->getRepository('AppBundle:User')->findBy(array('username' => $data['user']['mobile']))) {
            return new \Symfony\Component\HttpFoundation\Response(json_encode(array('success' => false, 'type' => 2, 'message' => 'کاربری با این شماره موبایل در سیستم وجود دارد.')));
        }
        $class = '\AppBundle\Entity\User';
        $entity = new $class();
        $form = $this->createForm('AppBundle\Form\UserType', $entity);
        $form->submit($data['user']);
        if ($form->isValid()) {
            $entity->setUsername($entity->getMobile());
            $tokenGenerator = $this->get('fos_user.util.token_generator');
            $entity->setConfirmationToken($tokenGenerator->generateToken());
            $em->persist($entity);
            $em->flush();
            $msg = sprintf('یک ایمیل حاوی لینک فعال سازی به شما ارسال شده است.');
            $this->sendResettingEmailMessage($entity, 'FOSUserBundle:Registration:email.html.twig');

            return new \Symfony\Component\HttpFoundation\Response(json_encode(array('success' => true, 'message' => $msg)));
        } else {
            return new \Symfony\Component\HttpFoundation\Response(json_encode(array('success' => false, 'message' => 'خطا در ثبت نام شما')));
        }
    }

    /**
     * @Route("/registration_activation/{token}",name="reg_activate")
     * 
     */
    public function findByTokenAction($token) {
        $em = $this->getDoctrine()->getManager();
        if ($entity = $em->getRepository('AppBundle:User')->findOneByConfirmationToken($token)) {
            $entity->setConfirmationToken(null);
            $entity->setEnabled(true);
            $em->flush();
//            $token = new \Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken($user, $pw, "main", array("ROLE_USER"));
//            $this->get('security.context')->setToken($token);
//
//            $event = new \Symfony\Component\Security\Http\Event\InteractiveLoginEvent($this->getRequest(), $token);
//            $this->get('event_dispatcher')->dispatch('security.interactive_login', $event);
//
//            $user = $this->get('security.context')->getToken()->getUser();
            return $this->redirect($this->generateUrl('panel'));
        }
        return $this->redirect($this->generateUrl('_def'));
    }

    /**
     * @Route("/search/{type}/{word}/{offset}",name="search")
     * @Template("AppBundle::resultSearch.html.twig")
     */
    public function searchAction($type,$word,$offset) {
        $em = $this->getDoctrine()->getManager();
        $searchTerms = explode(' ', $word);
        $searchTermBits = array();
        $searchTermContent = array();
        $searchTermPost = array();
        $searchTermDoctor = array();
        $searchTermImg = array();
        $searchTermProduct = array();
        foreach ($searchTerms as $term) {
            $term = trim($term);
            if (!empty($term)) {
                $searchTermBits[] = "c.meta LIKE '%$term%'";
                $searchTermBits[] = "c.title LIKE '%$term%'";
                $searchTermBits[] = "c.content LIKE '%$term%'";
                $searchTermContent[] = "c.title LIKE '%$term%'";
                $searchTermContent[] = "c.content LIKE '%$term%'";
                $searchTermProduct[] = "c.name LIKE '%$term%'";
                $searchTermProduct[] = "c.keyword LIKE '%$term%'";
                $searchTermProduct[] = "c.descr LIKE '%$term%'";
                $searchTermImg[] = "c.alt LIKE '%$term%'";
                $searchTermDoctor[] = "u.family LIKE '%$term%'";
                $searchTermDoctor[] = "u.name LIKE '%$term%'";
            }
        }
        $c=$em->getConnection();
       // 
        
        $q=$c->query(sprintf("select d.id , d.title , d.content from (select * from page p where p.title like '%s' OR p.meta like '%s' OR p.content like '%s' UNION ALL  select * from page c where %s) d group by d.id  limit 10 ",'%' . $word . '%', '%' . $word . '%', '%' . $word . '%', implode(' OR ', $searchTermBits)));
        $q2=$c->query(sprintf("select d.id , d.title , d.content from (select * from content p where p.title like '%s' OR  p.content like '%s' UNION ALL  select * from content c where %s) d group by d.id  limit 10 ", '%' . $word . '%', '%' . $word . '%', implode(' OR ', $searchTermContent)));
        $q3=$c->query(sprintf("select d.id , d.title , d.content , d.group_id from (select * from post p where p.title like '%s' OR  p.content like '%s' UNION ALL  select * from post c where %s) d group by d.id  limit 10 ", '%' . $word . '%', '%' . $word . '%', implode(' OR ', $searchTermContent)));
        $q4=$c->query(sprintf("select d.id , d.name , d.price ,d.category , d.photo , d.productNo from (select * from product p where p.name like '%s' OR  p.keyword like '%s' OR  p.descr like '%s' UNION ALL  select * from product c where %s ) d group by d.id  limit 10 ", '%' . $word . '%','%' . $word . '%', '%' . $word . '%', implode(' OR ', $searchTermProduct)));
        $q5=$c->query(sprintf("select d.id , d.alt , d.src ,d.gallery_id from (select * from images p where p.alt like '%s' UNION ALL  select * from images c where %s) d group by d.id  limit 10 ", '%' . $word . '%', implode(' OR ', $searchTermImg)));
        $q6 = $c->query(sprintf("select u.name , u.family , u.id , u.photo  from fos_user u where (u.family like '%s' or u.name like '%s' or %s) and u.roles like '%s'  limit 10 ", '%' . $word . '%', '%' . $word . '%', implode(' OR ', $searchTermDoctor),'%ROLE_DOCTOR%'));
        $ids=array();
        $user=$q6->fetchAll();
        $exp=array();
        foreach($user as $val){
            $ids[]=$val['id'];
            $exp[$val['id']]=array();
        }
        $Result4 = $em->getRepository('AppBundle:ExpertiseUserCategory')->findBy(array('user' => $ids));
        foreach($Result4 as $val){
            $exp[$val->getUser()->getId()][]=$val->getExpertise()->getValue();
        }
        return array('res'=>array($q->fetchAll(),$q2->fetchAll(),$q3->fetchAll(),$q4->fetchAll(),$q5->fetchAll(),$user,$exp));
    }

    public function sendResettingEmailMessage($user, $themplate) {
        $url = $user->getConfirmationToken();
        $rendered = $this->renderView($themplate, array(
            'user' => $user,
            'confirmationUrl' => $url
        ));
        $this->sendEmailMessage($rendered, 'ثبت نام', 'webmaster@abanpet.com', $user->getEmail());
    }

    /**
     * @param string $renderedTemplate
     * @param string $fromEmail
     * @param string $toEmail
     */
    protected function sendEmailMessage($renderedTemplate, $subject, $fromEmail, $toEmail) {

        $message = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom($fromEmail)
                ->setTo($toEmail)
                ->setBody($renderedTemplate, 'text/html');
        $mailer = $this->get('mailer');

        $mailer->send($message);
    }

}
