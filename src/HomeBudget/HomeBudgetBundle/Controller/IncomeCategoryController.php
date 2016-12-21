<?php

namespace HomeBudget\HomeBudgetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use HomeBudget\HomeBudgetBundle\Entity\IncomeCategory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class IncomeCategoryController extends Controller {

    /**
     * @Route("/incomeCategory/new")
     * @Security("has_role('ROLE_USER')")
     * @Method({"GET", "POST"})
     */
    public function newIncCategoryAction(Request $request) {
        $inCategory = new IncomeCategory();
        $form = $this->createFormBuilder($inCategory)
                ->add('name', 'text', array('label' => 'Nazwa'))
                ->add('save', 'submit', array('label' => 'Potwierdź'))
                ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {

            $inCategory = $form->getData();
            $user = $this->container->get('security.context')->getToken()->getUser();

            $inCategory->setUser($user);
            $em = $this->getDoctrine()->getManager();
            $em->persist($inCategory);

            $em->flush();



            return $this->redirectToRoute('show_allIncomeCategories');
        }
        return $this->render('HBBundle:IncomeCategory:new_inc_category.html.twig', array(
                    'form' => $form->createView()));
    }

    /**
     * @Route("/incomeCategory/{id}/modify")
     */
    public function modifyIncCategoryAction($id) {
        return $this->render('HBBundle:IncomeCategory:modify_inc_category.html.twig', array(
                        // ...
        ));
    }

    /**
     * @Route("/incomeCategory/all", name="show_allIncomeCategories")
     */
    public function showAllIncCategoryAction() {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $repository = $this->getDoctrine()->getRepository('HBBundle:IncomeCategory');
        $inCategories = $repository->findByUser($user);
        return $this->render('HBBundle:IncomeCategory:show_all_inc_category.html.twig', array(
                    'inCategories' => $inCategories
        ));
    }

}
