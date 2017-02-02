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

        $links = [];
        $linkAcc['href'] = 'show_allAccounts';
        $linkAcc['text'] = "Konta";
        $links[] = $linkAcc;
        $linkExp['href'] = 'show_allExpends';
        $linkExp['text'] = "Wydatki";
        $links[] = $linkExp;
        $linkInc['href'] = 'show_allIncomes';
        $linkInc['text'] = "Przychody";
        $links[] = $linkInc;
        $linkEdit['href'] = 'fos_user_profile_edit';
        $linkEdit['text'] = 'Edytuj swoje dane';
        $links[] = $linkEdit;
        $linkOut['href'] = 'fos_user_security_logout';
        $linkOut['text'] = 'Wyloguj';
        $links[] = $linkOut;

        return $this->render('HBBundle:Main:show_main_page.html.twig', array(
                    'links' => $links
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

        $links = [];
        $linkLog['href'] = 'fos_user_security_login';
        $linkLog['text'] = "Logowanie";
        $links[] = $linkLog;
        $linkReg['href'] = 'fos_user_registration_register';
        $linkReg['text'] = "Rejestracja";

        $links[] = $linkReg;

        return $this->render('HBBundle:Main:show_main_page.html.twig', array(
                    'links' => $links
        ));
    }

}
