<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations\Route;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity;

/**
 * With This Controller can connect to database with entity and other Controller
 *
 * @author Mr.Mostafa
 * 
 */
class CheckIdController extends Controller {


    public function CheckId() {
        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();
        $statement = $connection->prepare(sprintf("select id from animals order by id desc limit 1"));
        $statement->execute();
        $result = $statement->fetchAll();
        if (empty($result)) {
            return 2;
        }

        if ($result[0]['id'] % 2 == 0) {
            return $result[0]['id'] + 2;
        } else {
            return $result[0]['id'] + 1;
        }
    }
    
    public function test($table){
        $r = $this->CheckId($table);
        return $r;
    }
}
