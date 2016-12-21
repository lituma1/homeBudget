<?php

namespace HomeBudget\HomeBudgetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class MainController extends Controller
{
    /**
     * @Route("/")
     */
    public function showMainPageAction()
    {
        return $this->render('HBBundle:Main:show_main_page.html.twig', array(
            // ...
        ));
    }

}
