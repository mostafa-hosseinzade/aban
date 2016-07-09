<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace BlogBundle\Controller;

use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;

class ImagesController extends FOSRestController {

    /**
     * @return array
     * @View()
     * @throws NotFoundHttpException when content not exist
     */
    public function getEntityNameParentIdPropFilterAction($entity, $name, $parent, $id, $prop, $filter) {
        $this->setConfig();
        $session = $this->get('Session');
        $conf = $session->get('myconfig');
        $namespace = $conf['entity'][$entity]['class'];
        $namespace2 = $conf['entity'][$parent]['class'];
        //print_r(in_array($name,$conf['entity'][$parent]['form']));
        $condition = null;
        foreach ($conf['entity'][$parent]['form'] as $value) {
            if (key_exists('if', $value)) {
                $condition = $value['if'];
            }
        }

        $em = $this->getDoctrine()->getManager();
        $result;
        if ($entity != $parent) {
            if ($filter == 'u') {
                $p = $em->createQuery(sprintf('SELECT p FROM %s p WHERE p.id = %d', $namespace2, $id))->getOneOrNullResult();
                $qb = $em->createQuery(sprintf('SELECT p FROM %s p WHERE p.id NOT IN (SELECT IDENTITY(c.%s) FROM %s c WHERE c.%s IS NOT NULL AND c.%s != %d)', $namespace, $name, $namespace2, $name, $name, ($p) ? $p[1] : -1));
                $result = $qb->getResult();
            } else if ($filter == 's') {
                $qb = $em->createQuery(sprintf('SELECT p FROM %s p WHERE p.%s = %s', $namespace, $parent, $id));
                $result = $qb->getResult();
            } else {
                $result = $em->createQuery(sprintf('SELECT p FROM %s p ', $namespace))->getResult();
            }
        } else {
            $qb = $em->createQuery(sprintf('SELECT p FROM %s p WHERE p.id != %s', $namespace, $id));
            $result = $qb->getResult();
        }
        if ($condition && count($condition) == 2) {
            $arrayFilter = array();

            foreach ($result as $val) {
                $func = 'get' . ucfirst($condition[0]);
                if ($val->$func() != $condition[1]) {
                    $arrayFilter[] = $val;
                }
            }
            $result = $arrayFilter;
        }
        $array = array();
        foreach ($result as $val) {
            $func = 'get' . ucfirst($prop);
            if ($val->$func()) {
                $array[] = array('id' => $val->getId(), $prop => $val->$func());
            }
        }
        return $array;
    }

    /**
     * 
     * @param entity,filter,attr,asc,offset,limit 
     * @return array
     * @View()
     * 
     */
    public function getEntityFilterOrderByOffsetLimitAction($entity, $filter, $attr, $asc, $offset, $limit) {
        $this->setConfig();
        $session = $this->get('Session');
        $conf = $session->get('myconfig');
        $namespace = $conf['entity'][$entity]['class'];
        $em = $this->getDoctrine()->getManager();
//        $fields = $conf['entity'][$entity]['prop']; //$em->getClassMetadata($namespace)->getFieldNames();
        $repository = $em->getRepository($namespace);
        $query = $repository->createQueryBuilder('p');
        if ($filter != -1) {
            $queryCondition = $query->expr()->orX();
            $fields = $conf['entity'][$entity]['table-s'];
            foreach ($fields as $value) {
                if ($value != 'src' && $value != 'createAt' && $value != 'updateAt' && $value != 'id' && $value != 'orderList') {
                    $queryCondition->add(sprintf("p.%s  LIKE '%s'", $value, '%' . $filter . '%'));
                }
            }
            $query->add('where', $queryCondition);
        }
        $query2 = $query;
        $count = count($query2->getQuery()->getResult());
        $query->addOrderBy("p.$attr", $asc)
                ->setFirstResult($offset)
                ->setMaxResults($limit);
        $q = $query->getQuery();
        return array('data' => $q->getResult(), 'count' => $count);
    }

    /**
     * Put action
     * @var Request $request
     * @var integer $id Id of the entity
     * @return View|array
     */
    public function putEntityByidAction(Request $request, $en, $id) {
        $this->setConfig();
        $session = $this->get('Session');
        $conf = $session->get('myconfig');
        $namespace = $conf['entity'][$en]['class'];
        $bundle = explode('\\', $namespace);
        $em = $this->getDoctrine()->getManager();
        if ($id == -1) {
            $class = $namespace;
            $entity = new $class();
        } else {
            $entity = $em->getRepository($namespace)->find($id);
        }
        $form = $this->createForm(sprintf('%s\Form\%sType', $bundle[0], $en), $entity);
        $data = $request->request->all();
        $form->submit($data[strtolower($en)]);
        if (method_exists($entity, 'setUser')) {
            $entity->setUser($this->getUser());
        }
        if ($form->isValid()) {
            if ($id == -1) {
                $em->persist($entity);
            }
            $em->flush();
            return $entity;
        } else {
            return array(
                'form' => $form,
            );
        }
    }

    /**
     * Delete action
     * @var integer $id Id of the entity
     * @return View
     */
    public function deleteEntityByidAction($en, $id) {
        $this->setConfig();
        $session = $this->get('Session');
        $conf = $session->get('myconfig');
        $namespace = $conf['entity'][$en]['class'];
        $em = $this->getDoctrine()->getManager();
        if ($en != 'Page' && $id != 1) {
            $entity = $em->getRepository($namespace)->find($id);
            $em->remove($entity);
            $em->flush();
        }
        return $this->view(null, Response::HTTP_NO_CONTENT);
    }

    private function setConfig() {
        $yaml = new Parser();
        try {
            $value = $yaml->parse(file_get_contents('../app/config/admin.yml'));
        } catch (ParseException $e) {
            printf("Unable to parse the YAML string: %s", $e->getMessage());
        }
        $session = $this->get('Session');
        if (!$session->get('myconfig')) {
            $session->set('myconfig', $value);
        }
    }

}
