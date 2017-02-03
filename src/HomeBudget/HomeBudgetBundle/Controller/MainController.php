<?php

namespace HomeBudget\HomeBudgetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class MainController extends Controller {

    /**
     * @Route("/panel", name="Panel")
     * @Security("has_role('ROLE_USER')")
     */
    public function showMainPageAfterLoginAction() {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $userName = $user->getUserName();
        return $this->render('HBBundle:Main:show_main_page2.html.twig', array(
                    'userName' => $userName
        ));
    }

    /**
     * @Route("/")
     * 
     */
    public function showMainPageAction() {

        if ($this->container->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('Panel');
        }

        return $this->render('HBBundle:Main:show_main_page.html.twig', array(
                    
        ));
    }

}
