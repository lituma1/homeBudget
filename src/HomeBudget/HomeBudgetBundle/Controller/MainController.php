<?php

namespace HomeBudget\HomeBudgetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class MainController extends Controller {

    /**
     * Show main page for logged user
     * 
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
     * Show main page
     * 
     * @Route("/", name="main_page")
     * 
     */
    public function showMainPageAction() {

        if ($this->container->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('Panel');
        }

        return $this->render('HBBundle:Main:show_main_page.html.twig', array(
        ));
    }

    /**
     * Show cookies policy
     * 
     * @Route("/cookiesPolicy", name="cookies_policy")
     */
    public function showCookiesPolicyAction() {

        return $this->render('HBBundle:Main:cookies_policy.html.twig', array(
        ));
    }

    /**
     * Show contact details
     * 
     * @Route("/contact", name="contact_details")
     */
    public function showContactPageAction() {

        return $this->render('HBBundle:Main:contact_page.html.twig', array());
    }

}
