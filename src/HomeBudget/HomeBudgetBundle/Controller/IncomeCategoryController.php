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
     * Create a set of categories for new user and save in database
     * 
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
     * Create new income category and save in database
     * 
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
            // if name of category exist
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
     * Modify category name and save changes
     * 
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
            // checking if name of category exist 
            $result = in_array($categoryName, $arrayWithCategoryNames);
           

            if (!$result) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($inCategory);
                $em->flush();
                return $this->redirectToRoute('new_inCategory');
            //if name of category exist in database
            } else {
                $message = 'Kategoria o takiej nazwie już istnieje';
            }
        }
        return $this->render('HBBundle:IncomeCategory:modify_inc_category.html.twig', array(
                    'form' => $form->createView(), 'message' => $message));
    }
    
    /**
     * Create form for new category
     * 
     * @param \HomeBudget\HomeBudgetBundle\Entity\IncomeCategory $inCategory
     * @return $form
     */
    private function creatingForm($inCategory) {
        $form = $this->createFormBuilder($inCategory)
                ->add('name', TextType::class, array('label' => 'Nazwa'))
                ->add('save', SubmitType::class, array('label' => 'Potwierdź'))
                ->getForm();

        return $form;
    }
    
    /**
     * Create form for modify category
     * 
     * @param \HomeBudget\HomeBudgetBundle\Entity\IncomeCategory $inCategory
     * @return $form
     */
    private function creatingFormForModify($inCategory) {
        $form = $this->createFormBuilder($inCategory)
                ->add('name', TextType::class, array('label' => 'Nazwa'))
                ->add('status', CheckboxType::class, array('label'
                    => 'Zaznacz jeśli chcesz aktywować', 'required' => false,))
                ->add('save', SubmitType::class, array('label' => 'Potwierdź'))
                ->getForm();

        return $form;
    }
    
    /**
     * Check if name category exist in database
     * 
     * @param array $categories
     * @param string $categoryName
     * @return boolean
     */
    private function existCategoryNameInDatabase($categories, $categoryName) {

        $result = false;
        foreach ($categories as $category) {
            if ($category->getName() === $categoryName) {
                $result = true;
            }
        }
        return $result;
    }
    
    /**
     * Create array with names without name of category to modify
     * 
     * @param array $categories
     * @param string $categoryName
     * @return array
     */
     private function createArrayWithCategoryNames($categories, $categoryName){
        $array = [];
        foreach ($categories as $category){
            if($category->getName() !== $categoryName){
                $array[] = $category->getName();
            } 
        }
        return $array;
    }

}
