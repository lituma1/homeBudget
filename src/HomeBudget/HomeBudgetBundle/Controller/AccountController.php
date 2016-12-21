<?php

namespace HomeBudget\HomeBudgetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use HomeBudget\HomeBudgetBundle\Entity\Account;
use Symfony\Component\HttpFoundation\Request;

class AccountController extends Controller
{
    /**
     * @Route("/new")
     */
    public function newAction()
    {
        return $this->render('HBBundle:Account:new.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/{id}/modify")
     */
    public function modifyAction($id)
    {
        return $this->render('HBBundle:Account:modify.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/{id}/delete")
     */
    public function deleteAction($id)
    {
        return $this->render('HBBundle:Account:delete.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/showAll", name="show_allAccounts")
     */
    public function showAllAction()
    {
        
        
        return $this->render('HBBundle:Account:show_all.html.twig', array(
            // ...
        ));
    }

}
