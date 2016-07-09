<?php

namespace ShopBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use ShopBundle\Entity\product;
use ShopBundle\Form\productType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

/**
 * product controller.
 *
 * @Route("/product")
 */
class productController extends Controller {

    /**
     * Lists all product entities.
     * @Route("/", name="product")
     * @Method("GET")
     * @Template()
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('ShopBundle:product')->findAll();
        return array(
            'entities' => $entities,
        );
    }

    /**
     * Find Category
     * 
     * @Route("/findCtg", name="findCtg")
     * @Method("GET")
     * @Template()
     */
    public function findCtgAction() {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('ShopBundle:category')->findBy(array('is_has_sub_ctg' => FALSE));
        $data = array();
        foreach ($category as $value) {
            $data[] = array(
                'name' => $value->getName(),
                'id' => $value->getId());
        }
        return new Response(json_encode($data));
    }

    /**
     * Creates a new product entity.
     *
     * @Route("/create", name="product_create")
     * @Method("POST")
     * @Template("ShopBundle:product:new.html.twig")
     */
    public function createAction(Request $request) {
        $entity = new product();
        $em = $this->getDoctrine()->getManager();
        $checkPro = $em->getRepository('ShopBundle:product')->findOneBy(array('productNo' => $request->get('productNo')));
        if ($checkPro) {  
            return new Response("existProductNo");
            
        }
        $category = $em->getRepository('ShopBundle:category')->find($request->get('category'));
        $entity->setName($request->get('name'));
        $entity->setCategory($category);
        $entity->setProductNo($request->get('productNo'));
        $entity->setPrice($request->get('price'));
        $entity->setPhoto($request->get('photo'));
        $entity->setDescr($request->get('descr'));
        $entity->setCreatAt(new \DateTime());
        $em->persist($entity);
        $em->flush();
        return new Response('ok inserted id is ;' . $entity->getId());

        
        return new Response($form->getErrors());
        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a product entity.
     *
     * @param product $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(product $entity) {
        $form = $this->createForm(new productType(), $entity, array(
            'action' => $this->generateUrl('product_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new product entity.
     *
     * @Route("/new", name="product_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction() {
        $entity = new product();
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a product entity.
     *
     * @Route("/{id}", name="product_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ShopBundle:product')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find product entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing product entity.
     *
     * @Route("/{id}/edit", name="product_edit")
     * @Method("PUT")
     * @Template()
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ShopBundle:product')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find product entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Creates a form to edit a product entity.
     *
     * @param product $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(product $entity) {
        $form = $this->createForm(new productType(), $entity, array(
            'action' => $this->generateUrl('product_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing product entity.
     *
     * @Route("/{id}/update", name="product_update")
     * @Method("PUT")

     */
    public function updateAction(Request $request, $id) {

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ShopBundle:product')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find product entity.');
        }
        $category = $em->getRepository('ShopBundle:Category')->find($request->get('category'));
        $entity->setCategory($category);
        $entity->setName($request->get('name'));
        $entity->setProductNo($request->get('productNo'));
        $entity->setPrice($request->get('price'));
        $entity->setPhoto($request->get('photo'));
        $entity->setDescr($request->get('descr'));
        $em->flush();
        $serializer = $this->get('serializer');
        $json = $serializer->serialize($entity, 'json');
        $response = new Response($json);
        $response->headers->set('Content-Type', 'appliction/json');

        return $response;
    }

    /**
     * Deletes a product entity.
     *
     * @Route("/{id}/pro_del", name="product_delete")
     * @Method("GET")
     */
    public function deleteAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ShopBundle:Product')->find($id);
//        return new Response();
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find product entity.');
        }
        $em->remove($entity);
        $em->flush();
        return new Response('delete');
        return $this->redirect($this->generateUrl('product'));
    }

    /**
     * Creates a form to delete a product entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('product_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('label' => 'Delete'))
                        ->getForm()
        ;
    }

    /**
     * Show categories entities.
     *
     * @Route("/product_showAll", name="product_show")
     * @Method("GET")
     * @return array
     */
    public function showAllAction() {

        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('ShopBundle:product')->findAll();
        $serializer = $this->get('serializer');
        $json = $serializer->serialize($entities, 'json');
        $response = new Response($json);
        $response->headers->set('Content-Type', 'appliction/json');

        return $response;
    }

}
