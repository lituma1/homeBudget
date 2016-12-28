<?php

namespace HomeBudget\HomeBudgetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use HomeBudget\HomeBudgetBundle\Entity\Type;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class TypeController extends Controller
{
    /**
     * @Route("/type/new", name="new_type")
     * @Security("has_role('ROLE_USER')")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {   
        $type = new Type();
        $form = $this->createFormBuilder($type)
                ->add('name', TextType::class, array('label' => 'Nazwa'))
                
                ->add('save', SubmitType::class, array('label' => 'Potwierdź'))
                ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {

            $type = $form->getData();
            $user = $this->container->get('security.context')->getToken()->getUser();
            
            $type->setUser($user);
            $em = $this->getDoctrine()->getManager();
            $em->persist($type);

            $em->flush();
            
            
            
            return $this->redirectToRoute('new_Account');
        }
        return $this->render('HBBundle:Type:new.html.twig', array(
                    'form' => $form->createView()));
        
        
    }

    /**
     * @Route("/type/{id}/modify")
     * @Security("has_role('ROLE_USER')")
     */
    public function modifyAction($id)
    {
        return $this->render('HBBundle:Type:modify.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/type/{id}/delete")
     * @Security("has_role('ROLE_USER')")
     */
    public function deleteAction($id)
    {
        return $this->render('HBBundle:Type:delete.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/type/showAll", name="show_allTypes")
     * @Security("has_role('ROLE_USER')")
     */
    public function showAllAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $repository = $this->getDoctrine()->getRepository('HBBundle:Type');
            $types = $repository->findByUser($user);
        return $this->render('HBBundle:Type:show_all.html.twig', array(
            'types' => $types
        ));
    }

}
