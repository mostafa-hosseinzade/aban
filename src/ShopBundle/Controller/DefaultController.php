<?php

namespace ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ShopBundle\Entity\Factor;
use ShopBundle\Entity\Basket;

/**
 * @Route("/shop")
 */
class DefaultController extends Controller {

    /**
     * @Route("/",name="_def_shop")
     * @Template()
     */
    public function indexAction() {
        $menu = $this->getDoctrine()->getRepository('BlogBundle:Menu')->findAll();
        $ContentCategory = $this->getDoctrine()->getRepository('BlogBundle:ContentCategory')->findAll();

        $data = array();
        $data['menu'] = array();
        $data['ContentCategory'] = array();
        $j = 0;
        foreach ($ContentCategory as $value) {
            if ($value->getActive() == 1) {
                $data['ContentCategory'][$j]['title'] = $value->getTitle();
                $data['ContentCategory'][$j]['slug'] = $value->getSlug();
                $data['ContentCategory'][$j]['id'] = $value->getId();
                $j++;
            }
        }
        $i = 0;
        foreach ($menu as $value) {
            if ($value->getPage() != NULL && $value->getActive() == 1) {
                $data['menu'][$i]['title'] = $value->getTitle();
                if ($value->getParent() != NULL) {
                    $data['menu'][$i]['parent'] = $value->getParent();
                    $data['parent'][] = $value->getParent()->getId();
                } else {
                    $data['menu'][$i]['parent'] = -1;
                }
                $data['menu'][$i]['page'] = $value->getPage();
                $data['menu'][$i]['id'] = $value->getId();
                $data['menu'][$i]['parent'] = $value->getParent();
                $i++;
            }
        }
        $data['parent'] = array();
        for ($j = 0; $j < count($data['menu']); $j++) {
            if (in_array($data['menu'][$j]['id'], $data['parent'])) {
                foreach ($data['menu'] as $key => $child) {
                    if (isset($child['parent'])) {
                        if ($child['parent']->getId() == $data['menu'][$j]['id']) {
                            $data['menu'][$j]['child'][] = $child;
                        }
                        unset($data['menu'][$key]);
                    }
                }
            } else {
                $data['menu'][$j]['child'][0] = 0;
            }
        }

        foreach ($data['menu'] as $key => $value) {
            if (isset($value['child']) == false) {
                $data['menu'][$key]['child'][0] = 0;
            }
        }
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('BlogBundle:Content');

        //read news content
        $queryNews = $repository->createQueryBuilder('p');
        $queryNews->where("p.ctg =:id")
                ->setParameter('id', 1)
                ->setMaxResults(8);
        $qN = $queryNews->getQuery();
        $data['news'] = $qN->getResult();
        $countN = count($data['news']);
        //read isi content
        $queryISI = $repository->createQueryBuilder('p');
        $queryISI->where("p.ctg =:id")
                ->setParameter('id', 2)
                ->setMaxResults(8);

        $qI = $queryISI->getQuery();
        $data['isi'] = $qI->getResult();
        $countI = count($data['isi']);
        return array('data' => $data, 'countN' => $countN, 'countI' => $countI);
    }

    /**
     * @Route("/{id}/ctg_list",defaults={"id" = -1}, name="shop_ajax")
     * @Template()
     */
    public function subCtgAjaxAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('ShopBundle:Category')->findBy(array('parent' => $id == -1 ? null : $id));
        $path = array();
        if ($id != -1) {
            $ctg = $em->getRepository('ShopBundle:Category')->find($id);
            $path[] = ['id' => null, 'name' => $ctg->getName(), 'isHasSubCtg' => $ctg->getIsHasSubCtg()];
            $var = $ctg->getParent();
            while ($var) {
                $path[] = ['id' => $var->getId(), 'name' => $var->getName(), 'isHasSubCtg' => $var->getIsHasSubCtg()];
                $var = $var->getParent();
            }
        }
        $res = array();
        $i = 0;
        foreach ($entities as $value) {
            $res[$i]['name'] = $value->getName();
            $res[$i]['id'] = $value->getId();
            $res[$i]['photo'] = $value->getPhoto();
            $res[$i]['isHasSubCtg'] = $value->getIsHasSubCtg();
            if ($value->getParent()) {
                $res[$i]['parent'] = $value->getParent()->getId();
            } else {
                $res[$i]['parent'] = -1;
            }
            $i++;
        }
        return new \Symfony\Component\HttpFoundation\Response(json_encode(array('data' => $res, 'path' => array_reverse($path))));
    }

    /**
     * @Route("/{id}/pro_list", name="products_ajax")
     * @Template()
     */
    public function productAjaxAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('ShopBundle:Product')->findByCategory($id);
        $path = array();
        if ($id != -1) {
            $ctg = $em->getRepository('ShopBundle:Category')->find($id);
            $path[] = ['id' => null, 'name' => $ctg->getName(), 'isHasSubCtg' => $ctg->getIsHasSubCtg()];
            $var = $ctg->getParent();
            while ($var) {
                $path[] = ['id' => $var->getId(), 'name' => $var->getName(), 'isHasSubCtg' => $var->getIsHasSubCtg()];
                $var = $var->getParent();
            }
        }
        $res = array();
        $i = 0;
        foreach ($entities as $value) {
            $res[$i]['name'] = $value->getName();
            $res[$i]['id'] = $value->getId();
            $res[$i]['photo'] = $value->getPhoto();
            $res[$i]['rate'] =$value->getRate();
            $res[$i]['price'] = $value->getPrice();
            $res[$i]['product_no'] = $value->getProductNo();
            $i++;
        }
        return new \Symfony\Component\HttpFoundation\Response(json_encode(array('data' => $res, 'path' => array_reverse($path))));
    }

    /**
     * @Route("/{id}/pro", name="product_ajax")
     * @Template()
     */
    public function modalAjaxAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ShopBundle:Product')->find($id);
        $c=$em->getConnection();
        $q=$c->query(sprintf('SELECT sum(b.number) as num  , MONTH(f.date) as month FROM `basket` b join factor f on b.factor=f.id WHERE product=%s group by YEAR(f.date), MONTH(f.date) order by f.id desc limit 12',$id));
        $month=array('دی','بهمن','اسفند','فروردین','اردیبهشت','خرداد','تیر','مرداد','شهریور','مهر','آبان','آذر');
        $labels=array();
        $data=array();
        foreach ($q->fetchAll() as $val){
            $data[]=$val['num'];
            $labels[]=$month[$val['month']-1];
        }
        $res = array(
            'name' => $entity->getName(),
            'id' => $entity->getId(),
            'photo' => $entity->getPhoto(),
            'price' => $entity->getPrice(),
            'descr' => $entity->getDescr(),
            'rate' => $entity->getRate(),
            'data' => array_reverse($data),
            'labels' => array_reverse($labels),
            'category_id' => $entity->getCategory()->getId(),
            'category_name' => $entity->getCategory()->getName(),
            'product_no' => $entity->getProductNo()
        );

        return new \Symfony\Component\HttpFoundation\Response(json_encode($res));
    }

    /**
     * @Route("/comment_list/{id}", name="get_comment_list")
     * @Template()
     */
    public function getCommentListAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ShopBundle:Product')->find($id);
        $repository = $em->getRepository('ShopBundle:ProComment');
        $query = $repository->createQueryBuilder('p');
        $query->where('p.product = :product')
                ->setParameter('product', $entity)
                ->addOrderBy("p.id", 'desc')
                ->setFirstResult(0)
                ->setMaxResults(15);
        $comment = $query->getQuery()->getResult();
        $commentList = array();
        foreach ($comment as $val) {
            $commentList[] = array(
                'user' => $val->getUser()->getName(),
                'photo' => $val->getUser()->getPhoto(),
                'comment' => $val->getComment(),
                'date' => $val->getCreatedAt()
            );
        }

        return new \Symfony\Component\HttpFoundation\Response(json_encode($commentList));
    }

    /**
     * @Route("/image_list/{id}", name="get_image_list")
     * @Template()
     */
    public function getImageListAction($id) {
        $em = $this->getDoctrine()->getManager();
        $imgs = $em->getRepository('ShopBundle:ProImg')->findByProduct($id);
        $imgList = array();
        foreach ($imgs as $val) {
            $imgList[] = $val->getSrc();
        }
        return new \Symfony\Component\HttpFoundation\Response(json_encode($imgList));
    }

    /**
     * @Route("/{id}/find_product_by_no", name="find_product_by_no_ajax")
     * @Template()
     */
    public function basketAjaxAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('ShopBundle:Product')->findByProductNo($id);
        $res = '';

        if ($entities) {
            $res = array(
                'name' => $entities[0]->getName(),
                'id' => $entities[0]->getId(),
                'price' => $entities[0]->getPrice(),
                'product_no' => $entities[0]->getProductNo(),
            );
        }

        return new \Symfony\Component\HttpFoundation\Response(json_encode($res));
    }

    /**
     * @Route("/get_user", name="get_user_ajax")
     * @Template()
     */
    public function userAjaxAction() {
        $entity = $this->getUser();
        if (!$entity) {
            return new \Symfony\Component\HttpFoundation\Response(json_encode(array('valid_user' => false)));
        }
        $res = array(
            'name' => $entity->getName(),
            'lname' => $entity->getFamily(),
            'mobile' => $entity->getMobile(),
            'email' => $entity->getEmail(),
            'address' => $entity->getAddress(),
            'post_code' => $entity->getPostCode(),
            'money' => $entity->getMoney(),
            'role' => $entity->getRoles(),
        );
        return new \Symfony\Component\HttpFoundation\Response(json_encode(array('user' => $res)));
    }

    /**
     * @Route("/payment", name="payment")
     * @Template()
     */
    public function paymentAction(Request $request) {
        if (!$this->getUser()) {
            return new \Symfony\Component\HttpFoundation\Response(json_encode(array('error' => 'invalid_user', 'message' => 'حساب کاربری شما معتبر نیست.')));
        }
        $taxation = 0.09;
        $discount = 0.1;
        $other = 10000;

        $data = $request->request->all();
        $pro = array();
        foreach ($data as $value) {
            $pro[$value['i']] = $value['nu'];
        }
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('ShopBundle:Product')->findBy(array('id' => array_keys($pro)));
        $total = 0;
        foreach ($entities as $entity) {
            $total+=$entity->getPrice() * $pro[$entity->getId()];
        }
        $total = $total * (1 + $taxation - $discount) + $other;
        $factor = new Factor();
        $factor->setCustomer($this->getUser());
        $factor->setTotal($total);
        $factor->setIpClient($request->getClientIp());
        $factor->setRefsale(substr(md5(date('yyyyMMDD')), 1, 11));
        if ($this->getUser()->getMoney() < $total) {
            return new \Symfony\Component\HttpFoundation\Response(json_encode(array('error' => 'lack_of_money', 'message' => 'موجودی شما کافی نیست.')));
        }
        $em->persist($factor);
        foreach ($entities as $entity) {
            $basket = new Basket();
            $basket->setFactor($factor);
            $basket->setProduct($entity);
            $basket->setNumber($pro[$entity->getId()]);
            $em->persist($basket);
        }
        $userManager = $this->get('fos_user.user_manager');
        $this->getUser()->setMoney($this->getUser()->getMoney() - $total);
        $userManager->updateUser($this->getUser());
        $em->flush();
        return new \Symfony\Component\HttpFoundation\Response(json_encode(array('success' => 'success', 'total' => $this->getUser()->getMoney(), 'ref' => $factor->getRefsale())));
    }

    /**
     * @Route("/{id}/Forum/admin", name="adminForum_ajax")
     * @Template()
     */
    public function forumAjaxAction() {
        
    }

    /**
     * @Route("/report", name="report_ajax")
     * @Template()
     */
    public function reportAjaxAction() {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery("SELECT count(b) ,  p.name FROM ShopBundle:Product p JOIN ShopBundle:Basket b where p.id=b.product group by b.product");
        $result = $query->getResult();

        return new Response(json_encode($result));
    }

    /**
     * @Route("/rating/{id}/{rate}", name="rating")
     * @Template()
     */
    public function ratingAction($id, $rate) {
        if (!$this->getUser()) {
            return new Response('');
        }
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ShopBundle:Product')->find($id);
        if (!$entity) {
            return new Response('');
        }
        $oldRate = $entity->getRate() ? $entity->getRate() : 0;
        $num = $entity->getNumRate() ? $entity->getNumRate() : 0;
        if ($oldRate == 0) {
            $entity->setRate($rate);
            $entity->setNumRate(1);
        } else {
            $num++;
            $diff = $rate - $oldRate;
            $entity->setRate($oldRate + ($diff / $num));
            $entity->setNumRate($num);
        }
        $em->flush();

        return new Response($entity->getRate());
    }

    /**
     * @Route("/add_comment_for_product", name="add_comment_for_product")
     * @Template()
     */
    public function addCommentAction(Request $request) {
        if (!$this->getUser()) {
            return new Response('');
        }
        $data = $request->request->all();
        if (!isset($data['id']) || !isset($data['comment'])) {
            return new Response('');
        }
        $em = $this->getDoctrine()->getManager();
        if (!$product = $em->getRepository('ShopBundle:Product')->find($data['id'])) {
            return new Response('');
        }
        $entity = new \ShopBundle\Entity\ProComment();
        $entity->setComment($data['comment']);
        $entity->setUser($this->getUser());
        $entity->setProduct($product);
        $em->persist($entity);
        $em->flush();
        $res = array(
            'user' => $entity->getUser()->getName(),
            'photo' => $entity->getUser()->getPhoto(),
            'comment' => $entity->getComment(),
            'date' => $entity->getCreatedAt()
        );
        return new Response(json_encode($res));
    }

}
