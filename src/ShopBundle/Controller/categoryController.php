<?php

namespace ShopBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use ShopBundle\Entity\category;
use ShopBundle\Form\categoryType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

/**
 * category controller.
 *
 * @Route("/category")
 */
class categoryController extends Controller {

    /**
     * Lists all category entities.
     * @Route("/", name="category")
     * @Method("GET")
     * @Template()
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('ShopBundle:category')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Creates a new category entity.
     *
     * @Route("/", name="creatCtg")
     * @Method("POST")
     */
    public function createAction(Request $request) {
        $entity = new category();
        $em = $this->getDoctrine()->getManager();
        $checkCtg = $em->getRepository('ShopBundle:Category')->findOneBy(array('ctgNo' => $request->get('ctgNo')));
        if ($checkCtg) {
            return new Response( "شماره گروه تکراری است");
        }
        if (!empty($request->get('parent'))) {
            echo $request->get('parent');
            $parent = $em->getRepository('ShopBundle:Category')->find($request->get('parent'));
            $entity->setParent($parent);
            $check = $em->getRepository('ShopBundle:product')->findOneBy(array('category' => $parent));
            if ($check) {
                return new Response("والد انتخاب شده دارای محصول میباشد");
            } else {
                $parent->setIsHasSubCtg(TRUE);
                $entity->setName($request->get('name'));
                $entity->setCtgNo($request->get('ctgNo'));
                $entity->setDescr($request->get('descr'));
                $entity->setIsHasSubCtg(FALSE);
                $entity->setPhoto($request->get('photo'));
                $entity->setCreatAt(new \DateTime());
                $entity->setParent($parent);
            }
        } else {
            $entity->setName($request->get('name'));
            $entity->setCtgNo($request->get('ctgNo'));
            $entity->setDescr($request->get('descr'));
            $entity->setIsHasSubCtg(FALSE);
            $entity->setPhoto($request->get('photo'));
            $entity->setCreatAt(new \DateTime());
            $entity->setParent(null);
        }
        $em->persist($entity);
        $em->flush();

        return new Response('ok inserted id is ;' . $entity->getId());
    }

    /**
     * Creates a form to create a category entity.
     *
     * @param category $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(category $entity) {
        $form = $this->createForm(new categoryType(), $entity, array(
            'action' => $this->generateUrl('category_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new category entity.
     *
     * @Route("/new", name="category_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction() {
        $entity = new category();
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a category entity.
     *
     * @Route("/{id}", name="category_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ShopBundle:category')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find category entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing category entity.
     *
     * @Route("/{ctgNo}/edit", name="category_edit")
     * @Method("PUT")
     * @Template()
     */
    public function editAction($ctgNo) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ShopBundle:category')->find($ctgNo);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find category entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($ctgNo);

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Creates a form to edit a category entity.
     *
     * @param category $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(category $entity) {
        $form = $this->createForm(new categoryType(), $entity, array(
            'action' => $this->generateUrl('category_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing category entity.
     *
     * @Route("/{id}/update", name="category_update")
     * @Method("PUT")
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ShopBundle:category')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find category entity.');
        }
        $parent = $em->getRepository('ShopBundle:category')->find($request->get('parent'));
//        $deleteForm = $this->createDeleteForm($ctgNo);
//        $editForm = $this->createEditForm($entity);
//        $editForm->handleRequest($request);
        $entity->setName($request->get('name'));
        $entity->setCtgNo($request->get('ctgNo'));
        $entity->setParent($parent);
        $entity->setIsHasSubCtg($request->get('isHasSubCtg'));
        $entity->setPhoto($request->get('photo'));
        $entity->setDescr($request->get('descr'));
        $em->flush();

        $data = array(
            'name' => $request->get('name'),
            'ctgNo' => $request->get('ctgNo'),
        );

        return new Response($data);
    }

    /**
     * Deletes a category entity.
     *
     * @Route("/{ctgNo}/ctg_del", name="category_delete")
     * @Method("GET")
     */
    public function deleteAction($ctgNo) {

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ShopBundle:category')->findOneBy(array('ctgNo' => $ctgNo));
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find category entity.');
        }
        $em->remove($entity);
        $em->flush();
        return new Response('deleted');
        return $this->redirect($this->generateUrl('category'));
    }

    /**
     * Creates a form to delete a category entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('category_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('label' => 'Delete'))
                        ->getForm();
    }

    /**
     * Show categories entities.
     *
     * @Route("/ctg_showAll", name="category_show")
     * @Method("GET")
     * @return array
     */
    public function showAllAction() {

        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('ShopBundle:category')->findAll();
        $serializer = $this->get('serializer');
        $json = $serializer->serialize($entities, 'json');
        $response = new Response($json);
        $response->headers->set('Content-Type', 'appliction/json');

        return $response;
    }

}
