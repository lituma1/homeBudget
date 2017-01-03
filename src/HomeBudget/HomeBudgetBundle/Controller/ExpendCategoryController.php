<?php

namespace HomeBudget\HomeBudgetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use HomeBudget\HomeBudgetBundle\Entity\ExpendCategory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ExpendCategoryController extends Controller {

    /**
     * @Route("/expendCategory/new", name="new_exCategory")
     * @Security("has_role('ROLE_USER')")
     * @Method({"GET", "POST"})
     */
    public function newExpCategoryAction(Request $request) {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $repository = $this->getDoctrine()->getRepository('HBBundle:ExpendCategory');
        $exCategories = $repository->findByUserAndStatus($user);
        $exCategory = new ExpendCategory();
        $form = $this->createFormBuilder($exCategory)
                ->add('name', TextType::class, array('label' => 'Nazwa'))
                ->add('save', SubmitType::class, array('label' => 'Potwierdź'))
                ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {

            $exCategory = $form->getData();
            $user = $this->container->get('security.context')->getToken()->getUser();
            $exCategory->setUser($user);
            $exCategory->setStatus(true);
            $em = $this->getDoctrine()->getManager();
            $em->persist($exCategory);
            $em->flush();

            return $this->redirectToRoute('new_expend');
        }
        return $this->render('HBBundle:ExpendCategory:new_exp_category.html.twig', array(
                    'form' => $form->createView(), 'exCategories' => $exCategories));
    }

    /**
     * @Route("/expandCategory/{id}/modify")
     * @Security("has_role('ROLE_USER')")
     */
    public function modifyExpCategoryAction($id) {
        return $this->render('HBBundle:ExpendCategory:modify_exp_category.html.twig', array(
                        // ...
        ));
    }

    /**
     * @Route("/expandCategory/{id}/delete")
     */
    public function deleteExpCategoryAction($id) {
        return $this->render('HBBundle:ExpendCategory:delete_exp_category.html.twig', array(
                        // ...
        ));
    }

    

}
