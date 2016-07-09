<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use AppBundle\Entity\Ticket;

class TicketController extends FOSRestController {

    private function createNumberTicket() {
        $em = $this->getDoctrine()->getEntityManager();
        $Repository = $em->getRepository('AppBundle:Ticket');
        $query = $Repository->createQueryBuilder('p');
        $query->orderBy('p.number', 'desc')
                ->setMaxResults(1)
                ->setFirstResult(0);

        $q = $query->getQuery();
        $result = $q->getResult();
        if(count($result) > 0){
            return $result[0]->getNumber();
        }else{
            return 0;
        }
    }

    /**
     * Collection post action
     * @var Request $request
     * @return View|array
     * 
     */
    public function postTicketInsertAction(Request $request) {

        if ($request->request->get('date')) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = new Ticket();
            $date = new \DateTime($request->request->get('date'));
            $entity->setDate($date);

            $desc = $request->request->get('desc');
            $entity->setDesc($desc);

            $Animal = $this->getDoctrine()->getRepository('AppBundle:Animals')->find($request->request->get('AnimalsSelect'));
            $entity->setAnimals($Animal);

            $idTimeDoctor = $this->getDoctrine()->getRepository('AppBundle:TimeDoctor')->find($request->request->get('idTimeDoctor'));
            $entity->setTimeDoctor($idTimeDoctor);

            $entity->setUser($this->getUser());
            $entity->setActive(0);

            $number = $this->createNumberTicket() + 1;
            $entity->setNumber($number);

            $em->persist($entity);
            $em->flush();
        }


        return array(
            'message' => 'Ticket inserted',
            'request' => array(
                'number' => $entity->getNumber(),
                'active' => $entity->getActive(),
                'date' => $entity->getDate()
            ),
        );
    }

    /**
     * @return array
     * @View()
     *
     * @throws NotFoundHttpException when post not exist
     */
    public function getTicketUserAction() {

//        $em = $this->getDoctrine()->getManager();
//        $query = $em->createQuery(
//                        'SELECT p.number,p.active,p.date,p.id FROM AppBundle:Ticket p 
//                          WHERE p.user = :user ORDER BY p.createAt DESC'
//                )->setParameter('user', $this->getUser());

        $conn = $this->get('database_connection');
        $userid= $this->getUser()->getId();
        $query2 = $conn->query("SELECT ticket.*,reserve_time.* FROM ticket LEFT JOIN reserve_time ON ticket.id = reserve_time.ticket_id AND ticket.user_id = ".$userid." ORDER BY ticket.created_at DESC" );
 
        return $query2->fetchAll();
    }

    /**
     * Delete action
     * @var integer $id Id of the entity
     * @return View
     */
    public function deleteTicketUserAction($id) {
        $message = '';
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('AppBundle:Ticket')->find($id);
        $em->remove($entity);
        $em->flush();
        return array('deleteMessage' => '0');
    }

}
