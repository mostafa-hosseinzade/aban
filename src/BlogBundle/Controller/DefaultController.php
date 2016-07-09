<?php

namespace BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use BlogBundle\Entity\Contact;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Dumper;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;

/**
 * @Route("/")
 */
class DefaultController extends Controller {

    /**
     * @Route("/",name="_def")
     * @Template()
     */
    public function indexAction() {
        $menu = $this->getDoctrine()->getRepository('BlogBundle:Menu')->findAll();
        $ContentCategory = $this->getDoctrine()->getRepository('BlogBundle:ContentCategory')->findAll();

        $data = array();
        $data['ContentCategory'] = array();
        $data['menu'] = array();
        $data['parent'] = array();
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

        //Slider Info
        $em = $this->getDoctrine()->getManager();
        $result = $em->getRepository("AppBundle:Slider")->findBy(array('active' => 1));
        $i = 0;
        foreach ($result as $value) {
            $data['slider'][$i]['file'] = $value->getFile();
            $data['slider'][$i]['title_h1'] = $value->getTitleH1();
            $data['slider'][$i]['title_h3'] = $value->getTitleH3();
            $data['slider'][$i]['id'] = $value->getId();
            $i++;
        }
        //End Slider iNfo

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
     * @Route("/partial",name="partial")
     * @Template()
     */
    public function PartialAction() {
        $menu = $this->getDoctrine()->getRepository('BlogBundle:Menu')->findAll();
        $ContentCategory = $this->getDoctrine()->getRepository('BlogBundle:ContentCategory')->findAll();

        $data = array();
        $data['ContentCategory'] = array();
        $data['menu'] = array();
        $data['parent'] = array();
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
        return array('data' => $data);
    }

    /**
     * @Route("/contact", name="send_contact")
     */
    public function contact(Request $request) {
        $data = $request->request->all();
        $entity = new Contact();
        $entity->setName($data['name']);
        $entity->setMobile($data['phone']);
        $entity->setEmail($data['email']);
        $entity->setSubject(isset($data['subject']) ? $data['subject'] : "");
        $entity->setMsg($data['msg']);
        $em = $this->getDoctrine()->getManager();
        $em->persist($entity);
        $em->flush();
        return new Response('ok');
    }

    /**
     * @Route("/galleries/{filter}/{offset}/{limit}/{attr}/{asc}")
     * @param filter,attr,$asc,limit ,offset
     * @return array
     * 
     */
    public function getGalleryFilterOffsetLimitOrderAction($filter, $offset, $limit, $attr, $asc) {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('BlogBundle:Gallery');
        $RepositoryImage = $em->getRepository('BlogBundle:Images');
        $queryNotFilter = $repository->createQueryBuilder('p');
        if ($filter == -1) {
            $queryNotFilter->addOrderBy("p." . $attr, $asc)
                    ->setFirstResult($offset)
                    ->setMaxResults($limit);
            $q = $queryNotFilter->getQuery();
            $result = $q->getResult();

            $queryImage = $RepositoryImage->createQueryBuilder('p');

            $data = array();
            $i = 0;
            foreach ($result as $value) {
                $data[$i]['id'] = $value->getId();
                $data[$i]['title'] = $value->getTitle();
                $queryImage->where("p.gallery =:id")
                        ->setParameter('id', $value->getId())
                        ->setMaxResults(1);
                $query2 = $queryImage->getQuery();
                $ResultImage = $query2->getResult();
                foreach ($ResultImage as $value) {
                    $data[$i]['img'] = $value->getSrc();
                }

                $i++;
            }
            $value = \json_encode($data);
            return new Response($value);
        } else {
            $query = $repository->createQueryBuilder('p');
            $fields = $em->getClassMetadata('BlogBundle:Gallery')->getFieldNames();
            $i = 0;
            foreach ($fields as $value) {
                if ($i == 0) {
                    $query->where("p.{$value} LIKE :word");
                } else {
                    $query->orWhere("p.{$value} LIKE :word");
                }
                $i++;
            }
            $query->addOrderBy($attr, $asc)
                    ->setFirstResult($offset)
                    ->setMaxResults($limit)
                    ->setParameter('word', '%' . $filter . '%');
            $q = $query->getQuery();
            return new Response($q->getResult());
        }
    }

    /**
     * @Route("/galleries/{id}/{offset}/{limit}/{attr}/{asc}/show")
     * @param id
     * @return array
     * 
     */
    public function getGalleryImageAction($id, $offset, $limit, $attr, $asc) {
        $em = $this->getDoctrine()->getManager();
        $RepositoryImage = $em->getRepository('BlogBundle:Images');
        $queryImage = $RepositoryImage->createQueryBuilder('p');

        $queryImage->where("p.gallery =:id")
                ->setParameter('id', $id)
                ->addOrderBy("p." . $attr, $asc)
                ->setFirstResult($offset)
                ->setMaxResults($limit);

        $query2 = $queryImage->getQuery();
        $ResultImage = $query2->getResult();
        $data = array();
        $i = 0;
        foreach ($ResultImage as $value) {
            $data[$i]['img'] = $value->getSrc();
            $data[$i]['alt'] = $value->getAlt();
            $data[$i]['id'] = $value->getId();
            $data[$i]['created_at'] = $value->getCreateAt();
            $i++;
        }
        $data['length'] = count($data);
        $value = \json_encode($data);
        return new Response($value);
    }

    /**
     * @Route("/Countgallery")
     * @return array
     *
     * @throws NotFoundHttpException when content not exist
     */
    public function getGallerysCountAction() {
        $count = $this->getDoctrine()->getRepository('BlogBundle:Gallery')->findAll();
        return new Response(count($count));
    }

    /**
     * @Route("/pages/{id}")
     * @param id
     * @return array
     * 
     */
    public function getPageAction($id) {
        $em = $this->getDoctrine()->getManager();
        $result = $em->getRepository('BlogBundle:Page')->find($id);
        $result->setVisit($result->getVisit() + 1);
        $em->flush();
        $data[0] = array(
            'id' => $result->getId(),
            'title' => $result->getTitle(),
            'content' => $result->getContent(),
            'meta' => $result->getVisit(),
            'created_at' => explode(',', $result->getMeta())
        );
        return new Response(json_encode($data));
    }

    /**
     * @Route("/content/{id}")
     * @param id
     * @return array
     * 
     */
    public function getContentAction($id) {
        $em = $this->getDoctrine()->getManager();
        $ResultContent = $em->getRepository('BlogBundle:Content')->findByCtg($id);
        $ContentCategory = $this->getDoctrine()->getRepository('BlogBundle:ContentCategory')->find($id);
        $data = array();
        foreach ($ResultContent as $value) {
            $datetime = $this->get("mohebifar.datetime", $value->getCreatedAt());
            $data[] = array(
                'id' => $value->getId(),
                'title' => $value->getTitle(),
                'content' => $value->getContent(),
                'visit' => $value->getVisit(),
                'created_at' => $datetime->format('n/j F'),
            );
        }
        $data['ctg'] = $ContentCategory->getTitle();
        return new Response(json_encode($data));
    }

    /**
     * @Route("/ContentShow/{id}")
     * @param id
     * @return array
     * 
     */
    public function getContentShowAction($id) {
        $em = $this->getDoctrine()->getManager();
        $result = $em->getRepository('BlogBundle:Content')->find($id);
        $result->setVisit($result->getVisit() + 1);
        $em->flush();
        $data[0] = array(
            'title' => $result->getTitle(),
            'content' => $result->getContent(),
            'visit' => $result->getVisit(),
            'id' => $result->getId(),
            'category_id' => $result->getCtg()->getId(),
            'category_title' => $result->getCtg()->getTitle()
        );
        return new Response(json_encode($data));
    }

    /**
     * @Route("/content/{content_id}/{filter}/{offset}/{limit}/{attr}/{asc}")
     * @param filter,attr,$asc,limit ,offset
     * @return array
     * 
     */
    public function getContentFilterOffsetLimitOrderAction($content_id, $filter, $offset, $limit, $attr, $asc) {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('BlogBundle:Content');
        $ContentCategory = $this->getDoctrine()->getRepository('BlogBundle:ContentCategory')->find($content_id);
        $queryNotFilter = $repository->createQueryBuilder('p');
        if ($filter == -1) {
            $queryNotFilter
                    ->where('p.ctg = :ctg')
                    ->setParameter("ctg", $content_id)
                    ->addOrderBy("p." . $attr, $asc)
                    ->setFirstResult($offset)
                    ->setMaxResults($limit);
            $q = $queryNotFilter->getQuery();
            $result = $q->getResult();
        } else {
            $query = $repository->createQueryBuilder('p');
            $fields = $em->getClassMetadata('BlogBundle:Content')->getFieldNames();
            $j = 0;
            foreach ($fields as $value) {
                if ($j == 0) {
                    $query->where("p.{$value} LIKE :word");
                } else {
                    $query->orWhere("p.{$value} LIKE :word");
                }
                $j++;
            }
            $query->andWhere('p.ctg = :ctg')
                    ->setParameter("ctg", $content_id)
                    ->addOrderBy($attr, $asc)
                    ->setFirstResult($offset)
                    ->setMaxResults($limit)
                    ->setParameter('word', '%' . $filter . '%');
            $q = $query->getQuery();
            $result = $q->getResult();
        }
        $data = array();
        foreach ($result as $value) {
            $datetime = $this->get("mohebifar.datetime", $value->getCreatedAt());
            $data[] = array(
                'id' => $value->getId(),
                'title' => $value->getTitle(),
                'content' => $value->getContent(),
                'visit' => $value->getVisit(),
                'created_at' => $datetime->format('n/j F'),
            );
        }
        $data['length'] = count($data);
        $data['ctg'] = $ContentCategory->getTitle();
        return new Response(json_encode($data));
    }

    /**
     * @Route("/init_admin",name="_init")
     * @Template("BlogBundle:Admin:init.html.twig")
     */
    public function initAction() {
        $entities = array();
        $em2 = $this->getDoctrine()->getManager();
        $em = $this->getDoctrine()->getManager();
        $meta = $em->getMetadataFactory()->getAllMetadata();
        foreach ($meta as $m) {
            $name = explode('\\', $m->getName());
            if (count($name) == 3 && $name[1] == 'Entity') {
                $type = $em2->getClassMetadata($m->getName())->fieldMappings;
                $fields = array();
                foreach ($type as $key => $t) {
                    $fields[] = array(
                        'name' => $key,
                        'type' => $t['type'] ? $t['type'] : string,
                        'label' => $t['fieldName'] ? $t['fieldName'] : $key
                    );
                }
                $prop = $em2->getClassMetadata($m->getName())->getFieldNames();
                $entities[$name[2]] = array(
                    'name' => $name[2],
                    'class' => $m->getName(),
                    'prop' => $prop,
                    'label' => $name[2],
                    'enable' => true,
                    'action' => array('edit', 'add', 'delete', 'show', 'export', 'import'),
                    'list' => $fields,
                    'show' => $fields,
                    'form' => $fields,
                    'import' => [],
                    'export' => []
                );
            }
        }

        $dumper = new Dumper();

        $yaml2 = $dumper->dump(array('entity' => $entities), 4);

        file_put_contents('../app/config/admin.yml', $yaml2);
        $yaml = new Parser();
        try {
            $value = $yaml->parse(file_get_contents('../app/config/admin.yml'));
        } catch (ParseException $e) {
            printf("Unable to parse the YAML string: %s", $e->getMessage());
        }
        return array('entity' => json_encode(array('entity' => $value)));
    }

}
