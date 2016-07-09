<?php

namespace ForumBundle\Controller;

use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ForumBundle\Entity\Post;
use ForumBundle\Form\PostType;
use ForumBundle\Entity\Comment;
use ForumBundle\Form\CommentType;
use ForumBundle\Entity\GroupForum;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class ClientallrestController extends FOSRestController {

    /**
     * count all record in post table by filter
     * 
     * @return array
     * @View()
     *
     * @throws NotFoundHttpException when post not exist
     */
    public function getGrouppostscountClientAction($groupItem) {
        return array('Count' => count($this->getDoctrine()->getRepository('ForumBundle:Post')->findByGroup($groupItem)));
    }

    /**
     * count all record in post table by filter
     * 
     * @return array
     * @View()
     *
     * @throws NotFoundHttpException when post not exist
     */
    public function getGrouppostsonlycountClientAction($groupItem) {
        return count($this->getDoctrine()->getRepository('ForumBundle:Post')->findByGroup($groupItem));
    }

    /**
     *
     * @return array
     * @View()
     *
     * @throws NotFoundHttpException when groupforum not exist
     */
    public function getForumgroupitemcountAction() {
        //$entities = $this->getDoctrine()->getRepository('ForumBundle:GroupForum')->findAll();
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('ForumBundle:GroupForum');
        $query = $repository->createQueryBuilder('p');
        $query->addOrderBy("p.orderList", 'asc')
                ->andWhere("p.enabled = :enabled")
                ->setParameter("enabled", 1);
        $queryExcuted = $query->getQuery();
        $queryResult = $queryExcuted->getResult();
        $i = 0;
        $array = array();
        foreach ($queryResult as $item) {
            $count = $this->getGrouppostsonlycountClientAction($item->getId());
            $array[$i]['id'] = $item->getId();
            $array[$i]['title'] = $item->getTitle();
            $array[$i]['Count'] = $count;
            $array[$i]['create_at'] = $item->getCreateAt();
            $i++;
        }

        return $array;
    }

    /**
     * 
     * @param filter,attr,$asc,limit ,offset
     * @return array
     * @View()
     * 
     */
    public function getPostFilterOffsetLimitOrderGroupClientAction($filter, $offset, $limit, $attr, $asc, $itemGroup) {

        $group = $this->getDoctrine()->getRepository('ForumBundle:GroupForum')->find($itemGroup);

        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository('ForumBundle:Post');
        $query = $repository->createQueryBuilder('p');
        $query->where("p.id!=:id");
        $query->setParameter('id', '0');
        if ($filter != -1) {
            $fields = $em->getClassMetadata('ForumBundle:Post')->getFieldNames();
            foreach ($fields as $value) {
                $query->orWhere("p.{$value} LIKE :word");
            }
            $query->setParameter('word', '%' . $filter . '%');
        }
        $query->andWhere("p.group = :itemGroup")
                ->setParameter("itemGroup", $itemGroup)
                ->andWhere("p.enabled = :enabled")
                ->setParameter("enabled", 1)
                ->addOrderBy("p." . $attr, $asc)
                ->setFirstResult($offset)
                ->setMaxResults($limit);

        $q = $query->getQuery();

        return array(
            'entities' => $q->getResult(),
            'Count' => count($q->getResult()),
            'Group' => $group
        );
    }

    /**
     * Find Comments Items by post id
     * @return array
     * @View()
     *
     * @throws NotFoundHttpException when comment not exist
     */
    public function getCommentsByPostsClientAction($PostItem) {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('ForumBundle:Comment');
        $query = $repository->createQueryBuilder('p');
        $query->where("p.post = $PostItem")
                ->andWhere("p.enabled = 1");
        $q = $query->getQuery();
        return $q->getResult();
    }

    /**
     * 
     * @param id
     * @return array
     * @View()
     * @throws NotFoundHttpException when comment not exist
     */
    public function getPostsInsertLikeClientAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ForumBundle:Post')->find($id);
        $valueLike = $entity->getLike();
        $valueLike++;
        $entity->setLike($valueLike);
        $em->flush();
        return array(
            'like' => 'Like this post ok'
        );
    }

    /**
     * 
     * @param id
     * @return array
     * @View()
     * @throws NotFoundHttpException when comment not exist
     */
    public function getCommentsInsertLikeClientAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ForumBundle:Comment')->find($id);
        $valueLike = $entity->getLike();
        $valueLike++;
        $entity->setLike($valueLike);
        $em->flush();
        return array(
            'like' => 'Like this post ok'
        );
    }

    /**
     * 
     * @param filter
     * @return array
     * @View()
     * 
     */
    public function getPostLikeClientAction($id) {

        $em = $this->getDoctrine()->getRepository('ForumBundle:Post')->find($id);
        $like = $em->getLike();

        return array(
            'like' => $like
        );
    }

    /**
     * 
     * @param filter
     * @return array
     * @View()
     * 
     */
    public function getCommentLikeClientAction($id) {

        $em = $this->getDoctrine()->getRepository('ForumBundle:Comment')->find($id);
        $like = $em->getLike();

        return array(
            'like' => $like
        );
    }

    /**
     * 
     * @param filter
     * @return array
     * @View()
     * 
     */
    public function getPostFilterCountClientAction($filter) {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('ForumBundle:Post');
        $query = $repository->createQueryBuilder('p');

        if ($filter != -1) {
            $fields = $em->getClassMetadata('ForumBundle:Post')->getFieldNames();
            $i = 0;
            foreach ($fields as $value) {
                if ($i == 0) {
                    $query->where("p.{$value} LIKE :word");
                } else {
                    $query->orWhere("p.{$value} LIKE :word");
                }
                $i++;
            }
            $query->setParameter('word', '%' . $filter . '%');
        }

        $q = $query->getQuery();
        return array('count' => count($q->getResult()));
    }

    /**
     * Put action
     * @var Request $request
     * @var integer $id Id of the entity
     * @return View|array
     */
    public function putPostClientAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ForumBundle:Post')->find($id);
        $form = $this->createForm(new PostType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->flush();

            return $this->view(null, Response::HTTP_NO_CONTENT);
        } else {
            return array(
                'form' => $form,
            );
        }
    }

    /**
     * @param offset
     * @return array
     * @View()
     * 
     */
    public function getPostPaginationScrollAction($offset, $itemGroup, $id) {
        ///posts/{offset}/paginations/{itemGroup}/scroll
        $group = $this->getDoctrine()->getRepository('ForumBundle:GroupForum')->find($itemGroup);
        if ($id == -1) {
            $group = $this->getDoctrine()->getRepository('ForumBundle:GroupForum')->find($itemGroup);
            $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository('ForumBundle:Post');
            $query = $repository->createQueryBuilder('p');
            $query->andWhere("p.group = :itemGroup")
                    ->setParameter("itemGroup", $itemGroup)
                    ->andWhere("p.enabled = :enabled")
                    ->setParameter("enabled", 1)
                    ->addOrderBy("p.id", 'desc')
                    ->setFirstResult($offset)
                    ->setMaxResults(5);
            $q = $query->getQuery();
            $count=$q->getResult();
            $res=$q->getResult();
        }else{
            $res = $this->getDoctrine()->getRepository('ForumBundle:Post')->findBy(array('id'=>$id));
            $count=1;
        }
        return array(
            'forum' => $group,
            'entities' => $res,
            'children' => $count
        );
    }

    /**
     * Collection post action
     * @var Request $request
     * @return View|array
     * 
     */
    public function postPostAction(Request $request) {
        if (null === $this->getUser()) {
            return array('RES' => '403');
        }
        $entity = new Post();
        $entity->setUser($this->getUser());
        $form = $this->createForm('ForumBundle\Form\PostType', $entity);

        $form->handleRequest($request);

        $entity->setEnabled(true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($entity);
        $em->flush();
        return array('RES' => '200', 'post' => $entity);
    }

    /**
     * Collection Comment action
     * @var Request $request
     * @return View|array
     */
    public function postCommentAction(Request $request) {

        if (null === $this->getUser()) {
            return array('RES' => '403');
        }

        $entity = new Comment();
        $entity->setUser($this->getUser());

        $form = $this->createForm('ForumBundle\Form\CommentType', $entity);
        $form->handleRequest($request);
        $entity->setEnabled(true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($entity);
        $em->flush();
        return array('RES' => '200', 'comment' => $entity);
    }

    /**
     * get action
     * @return View|array
     */
    public function getAutClientAction() {
        if (null === $this->getUser()) {
            return false;
        } else {
            return true;
        }
    }

}
