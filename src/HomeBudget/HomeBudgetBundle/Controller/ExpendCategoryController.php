<?php

namespace HomeBudget\HomeBudgetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use HomeBudget\HomeBudgetBundle\Entity\ExpendCategory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class ExpendCategoryController extends Controller
{
    /**
     * @Route("/expendCategory/new", name="new_exCategory")
     * @Security("has_role('ROLE_USER')")
     * @Method({"GET", "POST"})
     */
    public function newExpCategoryAction(Request $request)
    {
        $exCategory = new ExpendCategory();
        $form = $this->createFormBuilder($exCategory)
                ->add('name', 'text', array('label' => 'Nazwa'))
                
                ->add('save', 'submit', array('label' => 'Potwierdź'))
                ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {

            $exCategory = $form->getData();
            $user = $this->container->get('security.context')->getToken()->getUser();
            
            $exCategory->setUser($user);
            $em = $this->getDoctrine()->getManager();
            $em->persist($exCategory);

            $em->flush();
            
            
            
            return $this->redirectToRoute('show_allExpendCategories');
        }
        return $this->render('HBBundle:ExpendCategory:new_exp_category.html.twig', array(
                    'form' => $form->createView()));
        
    }

    /**
     * @Route("/expandCategory/{id}/modify")
     */
    public function modifyExpCategoryAction($id)
    {
        return $this->render('HBBundle:ExpendCategory:modify_exp_category.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/expandCategory/{id}/delete")
     */
    public function deleteExpCategoryAction($id)
    {
        return $this->render('HBBundle:ExpendCategory:delete_exp_category.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/expendCategory/all", name="show_allExpendCategories")
     */
    public function showAllExpCategoryAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $repository = $this->getDoctrine()->getRepository('HBBundle:ExpendCategory');
            $exCategories = $repository->findByUser($user);
        return $this->render('HBBundle:ExpendCategory:show_all_exp_category.html.twig', array(
            'exCategories' => $exCategories
        ));
        
    }

}
