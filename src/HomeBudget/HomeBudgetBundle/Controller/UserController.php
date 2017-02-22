<?php

namespace HomeBudget\HomeBudgetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller {

    /**
     * @Route("/delete", name="delete_user")
     * @Security("has_role('ROLE_USER')")
     * @Method({"GET", "POST"})
     */
    public function deleteAction(Request $request) {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();

        $form = $this->createFormBuilder($user)
                ->add('save', SubmitType::class, array('label' => 'Potwierdź usunięcie konta'))
                ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted()){
            
            $this->get('fos_user.user_manager')->deleteUser($user);
            $this->getDoctrine()->getManager()->flush();
            
            return $this->redirectToRoute('fos_user_security_logout');
        }
        return $this->render('HBBundle:User:delete_user.html.twig', array(
                    'form' => $form->createView()
        ));
    }

}
