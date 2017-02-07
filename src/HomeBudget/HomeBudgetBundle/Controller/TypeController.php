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
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class TypeController extends Controller {

    /**
     * @Route("/type/new", name="new_type")
     * @Security("has_role('ROLE_USER')")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request) {
        $message = '';
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $repository = $this->getDoctrine()->getRepository('HBBundle:Type');
        $types = $repository->findByUserAndStatus($user);
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
            $type->setStatus(true);
            
            $typeName = $type->getName();
            $em = $this->getDoctrine()->getManager();
            if(!$this->existTypeNameInDatabase($user, $typeName)){
                $em->persist($type);
                $em->flush();
                return $this->redirectToRoute('new_type');
            } else {
                $message = 'Typ o takiej nazwie już istnieje';
            }
            
        }
        return $this->render('HBBundle:Type:new.html.twig', array(
                    'form' => $form->createView(), 'types' => $types,
                    'message' => $message));
    }

    /**
     * @Route("/type/{id}/modify", name="modify_type")
     * @Security("has_role('ROLE_USER')")
     * @Method({"GET", "POST"})
     */
    public function modifyAction(Request $request, $id) {
        $repository = $this->getDoctrine()->getRepository('HBBundle:Type');

        $type = $repository->find($id);
        $form = $this->createFormBuilder($type)
                ->add('name', TextType::class, array('label' => 'Nazwa'))
                ->add('status', CheckboxType::class, array('label' =>
                    'Zaznacz jeśli chcesz aktywować', 'required' => false,))
                ->add('save', SubmitType::class, array('label' => 'Potwierdź'))
                ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {

            $type = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($type);
            $em->flush();

            return $this->redirectToRoute('new_type');
        }
        return $this->render('HBBundle:Type:modify.html.twig', array(
                    'form' => $form->createView(),));
    }
    private function existTypeNameInDatabase($user, $typeName){
        $repository = $this->getDoctrine()->getRepository('HBBundle:Type');
        $types = $repository->findByUserAndStatus($user);
        $result = false;
        foreach ($types as $type) {
            if($type->getName() === $typeName){
               $result = true;
            }
        } 
        return $result;
    }
}
