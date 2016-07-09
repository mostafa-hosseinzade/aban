<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use AppBundle\Entity\Animals;
use AppBundle\Entity\Animalsphoto;
use AppBundle\Service\Jalali;
use Symfony\Component\HttpFoundation\Response;

class AnimalsController extends FOSRestController {

    /**
     *
     * @return array
     * @View()
     *
     * @throws NotFoundHttpException when comment not exist
     */
    public function getAnimalsUserAction() {
//        $infoAnimals = $this->getDoctrine()->getRepository('AppBundle:Animals')->findBy(array('user' => $this->getUser(), 'active' => true));
//        $photodefault = $this->getDoctrine()->getRepository('AppBundle:Animalsphoto')->findBy(array('animals' => $infoAnimals, 'photoDefault' => true));
//        $HistoryInfo = $this->getDoctrine()->getManager()->getRepository("AppBundle:Historyofanimalexamination")->findBy(array("animals"=>$infoAnimals));
        //$countanimals = count($infoAnimals);
        //
        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();
        $result = $connection->query(sprintf('select a.name,a.id,a.active,a.age,a.sex,a.weight'
                        . ',a.stature,a.microChip,a.color,c.animalsType,c.describtion,a.codeParvande'
                        . ',a.goneh,a.Animalscategory_id,a.nezhad,a.dateCreateParvande,a.created_at,a.updated_at from animals a LEFT join  '
                        . ' animalscategory c on a.animalscategory_id = c.id where a.active="1" and a.user_id = ' . $this->getUser()->getId()));

        $result = $result->fetchAll();
        if (empty($result)) {
            $data['animale']['msg'] = ' کاربران;حیوانی برای این کاربر ثبت نشده است;danger;true';
            $result = \json_encode($data);
            return new Response($result);
        }
        $data = array();
        $i = 0;
        foreach ($result as $val) {
            $result = $connection->query(sprintf("select photo from animalsphoto where animals_id = '%s' and photoDefault = '1' limit 1", $val['id']));
            $result->execute();
            $photo = $result->fetchAll();

            $data['defaultPhoto'][$i]['name'] = $val['name'];
            $data['defaultPhoto'][$i]['id'] = $val['id'];
            $data['defaultPhoto'][$i]['active'] = $val['active'];
            $data['defaultPhoto'][$i]['age'] = $val['age'];
            $data['defaultPhoto'][$i]['sex'] = $val['sex'];
            $data['defaultPhoto'][$i]['weight'] = $val['weight'];
            $data['defaultPhoto'][$i]['stature'] = $val['stature'];
            $data['defaultPhoto'][$i]['microChip'] = $val['microChip'];
            $data['defaultPhoto'][$i]['color'] = $val['color'];
            $data['defaultPhoto'][$i]['animalsType'] = $val['animalsType'];
            $data['defaultPhoto'][$i]['codeParvande'] = $val['codeParvande'];
            $data['defaultPhoto'][$i]['goneh'] = $val['goneh'];
            $data['defaultPhoto'][$i]['nezhad'] = $val['nezhad'];
            if (isset($photo[0])) {
                $data['defaultPhoto'][$i]['photo'] = $photo[0]['photo'];
            }
            $data['defaultPhoto'][$i]['animalsTypeId'] = $val['Animalscategory_id'];

            if ($val['dateCreateParvande'] != '') {
                $DateTime = $val['dateCreateParvande'];
                $DateTime = new \DateTime($DateTime);
                $DateTime = $DateTime->format('Y-m-d');
                $DateTime = explode('-', $DateTime);
                $DateTime = Jalali::gregorian_to_jalali($DateTime[0], $DateTime[1], $DateTime[2]);
                $DateTime = $DateTime[0] . '/' . $DateTime[1] . '/' . $DateTime[2];
                $data['defaultPhoto'][$i]['dateCreateParvande'] = $DateTime;
            } else {
                $data['defaultPhoto'][$i]['dateCreateParvande'] = '';
            }
            if ($val['created_at'] != '') {
                $DateTime = $val['created_at'];
                $DateTime = new \DateTime($DateTime);
                $DateTime = $DateTime->format('Y-m-d');
                $DateTime = explode('-', $DateTime);
                $DateTime = Jalali::gregorian_to_jalali($DateTime[0], $DateTime[1], $DateTime[2]);
                $DateTime = $DateTime[0] . '/' . $DateTime[1] . '/' . $DateTime[2];
                $data['defaultPhoto'][$i]['cteateAt'] = $DateTime;
            } else {
                $data['defaultPhoto'][$i]['cteateAt'] = '';
            }
            if ($val['updated_at'] != '') {
                $DateTime = $val['updated_at'];
                $DateTime = new \DateTime($DateTime);
                $DateTime = $DateTime->format('Y-m-d');
                $DateTime = explode('-', $DateTime);
                $DateTime = Jalali::gregorian_to_jalali($DateTime[0], $DateTime[1], $DateTime[2]);
                $DateTime = $DateTime[0] . '/' . $DateTime[1] . '/' . $DateTime[2];
                $data['defaultPhoto'][$i]['updateAt'] = $DateTime;
            } else {
                $data['defaultPhoto'][$i]['updateAt'] = '';
            }
            $i++;
        }
        return array(
//            'infoAnimals' => $data['infoAnimals'],
            'defaultPhoto' => $data['defaultPhoto'],
        );
    }

    /**
     * 
     * @param type $id
     * @Route("/History/{id}/id/{filter}/filter/{offset}/offsets/{limit}/limits")
     */
    public function getAnimalHistory($id, $filter, $limit, $offset) {
        $em = $this->getDoctrine()->getManager();
        $q = $em->getRepository("AppBundle:Historyofanimalexamination")->createQueryBuilder("p");
        $result = $q->where("p.animals = :animals")
                ->setParameter("animals", $id)
                ->orderBy("p.createAt", "desc")
                ->setMaxResults($limit)
                ->setFirstResult($offset)
                ->getQuery()
                ->getResult();
        if (empty($result)) {
            $data['history']['msg'] = ' سوابق;اطلاعاتی یافت نشد;danger;true';
            $response = \json_encode($data);
            return new Response($response);
        }
        $i = 0;
        $data = array();
        foreach ($result as $value) {
            $data['history'][$i] = $value->Serialization();
            $i++;
        }
        $connection = $em->getConnection();
        $statement = $connection->prepare(sprintf("select count(*) as c from historyofanimalexamination where animals_id = '%s'", $id));
        $statement->execute();
        $count = $statement->fetchAll();
        $data['count'] = $count[0]['c'];
        $response = \json_encode($data);
        return new Response($response);
    }

    /**
     * Put action
     * @var Request $request
     * @var integer $id Id of the entity
     * @return View|array
     */
    public function putAnimalsEditInfoAction(Request $request, $id) {
        ///cli_panel/animals/{id}/edit/info.{_format} 
        $em = $this->getDoctrine()->getEntityManager();
        $infoAnimals = $this->getDoctrine()->getRepository('AppBundle:Animals')->find($id);
        $infoAnimals->setName($request->request->get('name'));
        $infoAnimals->setAge($request->request->get('age'));
        $infoAnimals->setSex($request->request->get('sex'));
        $infoAnimals->setWeight($request->request->get('weight'));
        $infoAnimals->setStature($request->request->get('stature'));
        //$infoAnimals->setMicroChip($request->request->get('microChip'));
        //set category animals
        $catAnimal = $this->getDoctrine()->getRepository('AppBundle:Animalscategory')->find($request->request->get('Animalscategory'));
        $infoAnimals->setAnimalscategory($catAnimal);
        $em->flush();
        return array('Success' => 'بدرستی ویرایش شد');
    }

    /**
     * Collection post action
     * @var Request $request
     * @return View|array
     * 
     */
    public function postAnimalsInsertInfoAction(Request $request) {
        if ($request->request->get('photo')) {
            $em = $this->getDoctrine()->getEntityManager();
            $animalEntity = new Animals();
            $animalEntity->setName($request->request->get('name'));
            $animalEntity->setAge($request->request->get('age'));
            $animalEntity->setSex($request->request->get('sex'));
            $animalEntity->setWeight($request->request->get('weight'));
            $animalEntity->setStature($request->request->get('stature'));
            $animalEntity->setMicroChip($request->request->get('microChip'));

            $catAnimalEntity = $this->getDoctrine()->getRepository('AppBundle:Animalscategory')->find($request->request->get('Animalscategory'));

            $animalEntity->setAnimalscategory($catAnimalEntity);
            $animalEntity->setUser($this->getUser());
            $animalEntity->SetActive(false);

            $em->persist($animalEntity);

            $em->flush();
            //print_r($request->request);

            $photoEntity = new Animalsphoto();
            $photoEntity->setAnimals($animalEntity);
            $photoEntity->setPhotoDefault(true);
            $photo = $request->request->get('photo');
            $photoEntity->setPhoto($photo);

            $em->persist($photoEntity);
            $em->flush();
        } else {
            return array('message' => '-1');
        }
        return array('message' => '0');
    }

    /**
     * @param int $id 
     * @return array
     * @View()
     * @throws NotFoundHttpException when post not exist
     */
    public function getActivityAnimalsAction($idAnimal) {
        $animal = $this->getDoctrine()->getRepository('AppBundle:Animals')->find($idAnimal);
        $em = $this->getDoctrine()->getManager();
        $Repository = $em->getRepository('AppBundle:Activity');
        $query = $Repository->createQueryBuilder('p');
        $query->where("p.animale = :animal")
                ->orderBy("p.date", "desc")
                ->setMaxResults(10)
                ->setParameter('animal', $animal);
        $q = $query->getQuery();
        $result = $q->getResult();
        return $result;
    }

}
