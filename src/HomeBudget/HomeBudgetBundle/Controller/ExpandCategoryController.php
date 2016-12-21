<?php

namespace HomeBudget\HomeBudgetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ExpandCategoryController extends Controller
{
    /**
     * @Route("/expendCategory/new")
     */
    public function newExpCategoryAction()
    {
        return $this->render('HBBundle:ExpandCategory:new_exp_category.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/expandCategory/{id}/modify")
     */
    public function modifyExpCategoryAction($id)
    {
        return $this->render('HBBundle:ExpandCategory:modify_exp_category.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/expandCategory/{id}/delete")
     */
    public function deleteExpCategoryAction($id)
    {
        return $this->render('HBBundle:ExpandCategory:delete_exp_category.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/expendCategory/all")
     */
    public function showAllExpCategoryAction()
    {
        return $this->render('HBBundle:ExpandCategory:show_all_exp_category.html.twig', array(
            // ...
        ));
    }

}
