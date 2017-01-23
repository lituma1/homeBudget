<?php

namespace HomeBudget\HomeBudgetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use HomeBudget\HomeBudgetBundle\Entity\IncomeCategory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class IncomeCategoryController extends Controller {

    /**
     * @Route("/incomeCategory/new", name="new_inCategory")
     * @Security("has_role('ROLE_USER')")
     * @Method({"GET", "POST"})
     */
    public function newIncCategoryAction(Request $request) {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $repository = $this->getDoctrine()->getRepository('HBBundle:IncomeCategory');
        $inCategories = $repository->findByUserAndStatus($user);
        $inCategory = new IncomeCategory();
        $form = $this->creatingForm($inCategory);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {

            $inCategory = $form->getData();
            $user = $this->container->get('security.token_storage')->getToken()->getUser();

            $inCategory->setUser($user);
            $inCategory->setStatus(true);
            $em = $this->getDoctrine()->getManager();
            $em->persist($inCategory);

            $em->flush();



            return $this->redirectToRoute('new_income');
        }
        return $this->render('HBBundle:IncomeCategory:new_inc_category.html.twig', array(
                    'form' => $form->createView(), 'inCategories' => $inCategories));
    }

    /**
     * @Route("/incomeCategory/{id}/modify", name="modify_inc_category")
     * @Security("has_role('ROLE_USER')")
     * @Method({"GET", "POST"})
     */
    public function modifyIncCategoryAction(Request $request, $id) {
        $repository = $this->getDoctrine()->getRepository('HBBundle:IncomeCategory');
        
        $inCategory = $repository->find($id);
        $form = $this->creatingFormForModify($inCategory);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {

            $inCategory = $form->getData();
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($inCategory);
            $em->flush();

            return $this->redirectToRoute('new_income');
        }
        return $this->render('HBBundle:IncomeCategory:modify_inc_category.html.twig', array(
                    'form' => $form->createView(), ));
       
    }

    private function creatingForm($inCategory){
        $form = $this->createFormBuilder($inCategory)
                ->add('name', TextType::class, array('label' => 'Nazwa'))
                ->add('save', SubmitType::class, array('label' => 'Potwierdź'))
                ->getForm();
        
        return $form;
    }
    private function creatingFormForModify($inCategory){
        $form = $this->createFormBuilder($inCategory)
                ->add('name', TextType::class, array('label' => 'Nazwa'))
                ->add('status', CheckboxType::class, array('label' 
                    => 'Odznacz jeśli chcesz dezaktywować katagorię', 'required' => false,))
                ->add('save', SubmitType::class, array('label' => 'Potwierdź'))
                ->getForm();
        
        return $form;
    }
}
