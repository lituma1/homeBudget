<?php

namespace HomeBudget\HomeBudgetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use HomeBudget\HomeBudgetBundle\Entity\IncomeCategory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class IncomeCategoryController extends Controller {
    
    /**
     * @Route("/incomeCategory/add", name="add_inc_categories")
     * @Security("has_role('ROLE_USER')")
     * @Method({"GET", "POST"})
     */
    public function addIncCategoriesAction(Request $request) {
        $incCategories = ['Pensja', 'Najem', 'Lotto', 'Sprzedaż', 'Umowa o dzieło',
            '500 plus', 'Umowa zlecenie', 'Zwrot podatku'];
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $form = $this->createFormBuilder()
                ->add('save', SubmitType::class, array('label' => 'Potwierdź'))
                ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            foreach ($incCategories as $incCategoryName) {
                $incCategory = new IncomeCategory();
                $incCategory->setStatus(true);
                $incCategory->setName($incCategoryName);
                $incCategory->setUser($user);
                $em = $this->getDoctrine()->getManager();
                $em->persist($incCategory);
            }
            $em->flush();
            return $this->redirectToRoute('new_inCategory');
        }
        return $this->render('HBBundle:IncomeCategory:add_inc_categories.html.twig', array(
                    'form' => $form->createView()));
    }
    /**
     * @Route("/incomeCategory/new", name="new_inCategory")
     * @Security("has_role('ROLE_USER')")
     * @Method({"GET", "POST"})
     */
    public function newIncCategoryAction(Request $request) {
        $message = '';
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $repository = $this->getDoctrine()->getRepository('HBBundle:IncomeCategory');
        $inCategories = $repository->findByUserAndStatus($user);
        $inCategory = new IncomeCategory();
        $form = $this->creatingForm($inCategory);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {

            $inCategory = $form->getData();
            $inCategory->setUser($user);
            $inCategory->setStatus(true);
            $categoryName = $inCategory->getName();
            $em = $this->getDoctrine()->getManager();
            if (!$this->existCategoryNameInDatabase($inCategories, $categoryName)) {
                $em->persist($inCategory);
                $em->flush();
                return $this->redirectToRoute('new_inCategory');
            } else {
                $message = 'Kategoria o takiej nazwie już istnieje';
            }
        }
        return $this->render('HBBundle:IncomeCategory:new_inc_category.html.twig', array(
                    'form' => $form->createView(),
                    'message' => $message,
                    'inCategories' => $inCategories));
    }

    /**
     * @Route("/incomeCategory/{id}/modify", name="modify_inc_category")
     * @Security("has_role('ROLE_USER')")
     * @Method({"GET", "POST"})
     */
    public function modifyIncCategoryAction(Request $request, $id) {
        $message = '';
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $repository = $this->getDoctrine()->getRepository('HBBundle:IncomeCategory');
        $inCategory = $repository->find($id);
        $inCategories = $repository->findByUser($user);
        $arrayWithCategoryNames = $this->createArrayWithCategoryNames($inCategories, $inCategory->getName());
        $form = $this->creatingFormForModify($inCategory);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {

            $inCategory = $form->getData();
            $categoryName = $inCategory->getName();
            
            $result = in_array($categoryName, $arrayWithCategoryNames);
           

            if (!$result) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($inCategory);
                $em->flush();
                return $this->redirectToRoute('new_inCategory');
            } else {
                $message = 'Kategoria o takiej nazwie już istnieje';
            }
        }
        return $this->render('HBBundle:IncomeCategory:modify_inc_category.html.twig', array(
                    'form' => $form->createView(), 'message' => $message));
    }

    private function creatingForm($inCategory) {
        $form = $this->createFormBuilder($inCategory)
                ->add('name', TextType::class, array('label' => 'Nazwa'))
                ->add('save', SubmitType::class, array('label' => 'Potwierdź'))
                ->getForm();

        return $form;
    }

    private function creatingFormForModify($inCategory) {
        $form = $this->createFormBuilder($inCategory)
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
