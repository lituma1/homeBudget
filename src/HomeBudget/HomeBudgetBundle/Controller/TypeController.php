<?php

namespace HomeBudget\HomeBudgetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use HomeBudget\HomeBudgetBundle\Entity\Type;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;


class TypeController extends Controller
{
    /**
     * @Route("/type/new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {   
        $type = new Type();
        $form = $this->createFormBuilder($type)
                ->add('name', 'text', array('label' => 'Nazwa'))
                
                ->add('save', 'submit', array('label' => 'Potwierdź'))
                ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {

            $type = $form->getData();
            $user = $this->container->get('security.context')->getToken()->getUser();
            
            $type->setUser($user);
            $em = $this->getDoctrine()->getManager();
            $em->persist($type);

            $em->flush();
            $repository = $this->getDoctrine()->getRepository('HBBundle:Type');
            $types = $repository->findAll();
            return $this->render('HBBundle:Type:show_all.html.twig', array(
                        'types' => $types));
        }
        return $this->render('HBBundle:Type:new.html.twig', array(
                    'form' => $form->createView()));
        
        
    }

    /**
     * @Route("/type/{id}/modify")
     */
    public function modifyAction($id)
    {
        return $this->render('HBBundle:Type:modify.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/type/{id}/delete")
     */
    public function deleteAction($id)
    {
        return $this->render('HBBundle:Type:delete.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/type/showAll")
     */
    public function showAllAction()
    {
        $repository = $this->getDoctrine()->getRepository('HBBundle:Type');
            $types = $repository->findAll();
        return $this->render('HBBundle:Type:show_all.html.twig', array(
            'types' => $types
        ));
    }

}
