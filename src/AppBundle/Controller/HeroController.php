<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Hero;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Hero controller.
 *
 * @Route("hero")
 */
class HeroController extends Controller
{
    /**
     * Lists all hero entities.
     *
     * @Route("/", name="hero_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $heroes = $em->getRepository('AppBundle:Hero')->findAll();

        return $this->render('hero/index.html.twig', array(
            'heroes' => $heroes,
        ));
    }

    /**
     * Creates a new hero entity.
     *
     * @Route("/new", name="hero_new")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $hero = new Hero();
        $form = $this->createForm('AppBundle\Form\HeroType', $hero);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($hero);
            $em->flush();

            return $this->redirectToRoute('hero_show', array('id' => $hero->getId()));
        }

        return $this->render('hero/new.html.twig', array(
            'hero' => $hero,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a hero entity.
     *
     * @Route("/{id}", name="hero_show")
     * @Method("GET")
     */
    public function showAction(Hero $hero)
    {
        $deleteForm = $this->createDeleteForm($hero);

        return $this->render('hero/show.html.twig', array(
            'hero' => $hero,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing hero entity.
     *
     * @Route("/{id}/edit", name="hero_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Hero $hero)
    {
        $deleteForm = $this->createDeleteForm($hero);
        $editForm = $this->createForm('AppBundle\Form\HeroType', $hero);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('hero_edit', array('id' => $hero->getId()));
        }

        return $this->render('hero/edit.html.twig', array(
            'hero' => $hero,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a hero entity.
     *
     * @Route("/{id}", name="hero_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Hero $hero)
    {
        $form = $this->createDeleteForm($hero);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($hero);
            $em->flush();
        }

        return $this->redirectToRoute('hero_index');
    }

    /**
     * Creates a form to delete a hero entity.
     *
     * @param Hero $hero The hero entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Hero $hero)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('hero_delete', array('id' => $hero->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
