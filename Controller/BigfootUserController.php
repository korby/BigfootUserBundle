<?php

namespace Bigfoot\Bundle\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Bigfoot\Bundle\CoreBundle\Theme\Menu\Item;
use Bigfoot\Bundle\UserBundle\Entity\BigfootUser;
use Bigfoot\Bundle\UserBundle\Form\BigfootUserType;

/**
 * BigfootUser controller.
 *
 * @Route("/admin/user")
 */
class BigfootUserController extends Controller
{

    /**
     * Lists all BigfootUser entities.
     *
     * @Route("/", name="admin_user")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BigfootUserBundle:BigfootUser')->findAll();

        $this->container->get('bigfoot.theme')['page_content']['globalActions']->addItem(new Item('crud_add', 'Add a user', 'admin_user_new'));

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new BigfootUser entity.
     *
     * @Route("/", name="admin_user_create")
     * @Method("POST")
     * @Template("BigfootUserBundle:BigfootUser:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new BigfootUser();
        $form = $this->createForm('bigfoot_user', $entity);
        $form->submit($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_user_edit', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new BigfootUser entity.
     *
     * @Route("/new", name="admin_user_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new BigfootUser();
        $form   = $this->createForm('bigfoot_user', $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing BigfootUser entity.
     *
     * @Route("/{id}/edit", name="admin_user_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BigfootUserBundle:BigfootUser')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find BigfootUser entity.');
        }

        $editForm = $this->createForm('bigfoot_user', $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing BigfootUser entity.
     *
     * @Route("/{id}", name="admin_user_update")
     * @Method("PUT")
     * @Template("BigfootUserBundle:BigfootUser:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BigfootUserBundle:BigfootUser')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find BigfootUser entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm('bigfoot_user', $entity);
        $editForm->submit($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_user_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a BigfootUser entity.
     *
     * @Route("/{id}", name="admin_user_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->submit($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('BigfootUserBundle:BigfootUser')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find BigfootUser entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_user'));
    }

    /**
     * Creates a form to delete a BigfootUser entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
