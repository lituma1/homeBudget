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
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class ExpendCategoryController extends Controller {

    /**
     * @Route("/expendCategory/new", name="new_exCategory")
     * @Security("has_role('ROLE_USER')")
     * @Method({"GET", "POST"})
     */
    public function newExpCategoryAction(Request $request) {
        $message = '';
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $repository = $this->getDoctrine()->getRepository('HBBundle:ExpendCategory');
        $exCategories = $repository->findByUser($user);
        $exCategory = new ExpendCategory();
        $form = $this->creatingForm($exCategory);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {

            $exCategory = $form->getData();
            $exCategory->setUser($user);
            $exCategory->setStatus(true);
            $categoryName = $exCategory->getName();
            $em = $this->getDoctrine()->getManager();
            if (!$this->existCategoryNameInDatabase($exCategories, $categoryName)) {
                $em->persist($exCategory);
                $em->flush();
                return $this->redirectToRoute('new_exCategory');
            } else {
                $message = 'Kategoria o takiej nazwie już istnieje';
            }
        }
        return $this->render('HBBundle:ExpendCategory:new_exp_category.html.twig', array(
                    'form' => $form->createView(),
                    'message' => $message,
                    'exCategories' => $exCategories));
    }

    /**
     * @Route("/expendCategory/{id}/modify", name="modify_exp_category")
     * @Security("has_role('ROLE_USER')")
     * @Method({"GET", "POST"})
     */
    public function modifyExpCategoryAction(Request $request, $id) {
        $message = '';
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $repository = $this->getDoctrine()->getRepository('HBBundle:ExpendCategory');
        $exCategory = $repository->find($id);
        $exCategories = $repository->findByUser($user);
        $arrayWithCategoryNames = $this->createArrayWithCategoryNames($exCategories, $exCategory->getName());
        $form = $this->creatingFormForModify($exCategory);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {

            $exCategory = $form->getData();
            $categoryName = $exCategory->getName();
            
            $result = in_array($categoryName, $arrayWithCategoryNames);
           

            if (!$result) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($exCategory);
                $em->flush();
                return $this->redirectToRoute('new_exCategory');
            } else {
                $message = 'Kategoria o takiej nazwie już istnieje';
            }
        }
        return $this->render('HBBundle:ExpendCategory:modify_exp_category.html.twig', array(
                    'form' => $form->createView(), 'message' => $message));
    }

    private function creatingForm($exCategory) {
        $form = $this->createFormBuilder($exCategory)
                ->add('name', TextType::class, array('label' => 'Nazwa'))
                ->add('save', SubmitType::class, array('label' => 'Potwierdź'))
                ->getForm();

        return $form;
    }

    private function creatingFormForModify($exCategory) {
        $form = $this->createFormBuilder($exCategory)
                ->add('name', TextType::class, array('label' => 'Nazwa'))
                ->add('status', CheckboxType::class, array('label'
                    => 'Zaznacz jeśli chcesz aktywować', 'required' => false,))
                ->add('save', SubmitType::class, array('label' => 'Potwierdź'))
                ->getForm();

        return $form;
    }

     private function existCategoryNameInDatabase($categories, $typeName) {

        $result = false;
        foreach ($categories as $type) {
            if ($type->getName() === $typeName) {
                $result = true;
            }
        }
        return $result;
    }
    private function createArrayWithCategoryNames($categories, $string){
        $array = [];
        foreach ($categories as $category){
            if($category->getName() !== $string){
                $array[] = $category->getName();
            } 
        }
        return $array;
    }

}
