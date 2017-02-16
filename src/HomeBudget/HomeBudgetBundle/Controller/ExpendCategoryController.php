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
     * Create a set of categories for new user and save in database
     * 
     * @Route("/incomeCategory/add", name="add_exp_categories")
     * @Security("has_role('ROLE_USER')")
     * @Method({"GET", "POST"})
     */
    public function addExpCategoriesAction(Request $request) {
        $expCategories = ['Rozrywak', 'Kultura', 'Ubrania', 'Jedzenie', 'Samochód',
            'Wakacje', 'Edukacja dzieci', 'Prezenty', 'Środki czystości', 'Zwierzęta',
            'Remont', 'Urządzenie mieszkania', 'Gaz', 'Prąd', 'Czynsz', 'Telewizja kablowa',
            'Internet', 'Telefon'];
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $form = $this->createFormBuilder()
                ->add('save', SubmitType::class, array('label' => 'Potwierdź'))
                ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            foreach ($expCategories as $expCategoryName) {
                $incCategory = new ExpendCategory();
                $incCategory->setStatus(true);
                $incCategory->setName($expCategoryName);
                $incCategory->setUser($user);
                $em = $this->getDoctrine()->getManager();
                $em->persist($incCategory);
            }
            $em->flush();
            return $this->redirectToRoute('new_exCategory');
        }
        return $this->render('HBBundle:ExpendCategory:add_exp_categories.html.twig', array(
                    'form' => $form->createView()));
    }
    /**
     * Create new expend category and save in database
     * 
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
            // if name of category exist
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
     * Modify category name and save changes
     * 
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
            // checking if name of category exist 
            $result = in_array($categoryName, $arrayWithCategoryNames);
           
            
            if (!$result) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($exCategory);
                $em->flush();
                return $this->redirectToRoute('new_exCategory');
            //if name of category exist in database
            } else {
                $message = 'Kategoria o takiej nazwie już istnieje';
            }
        }
        return $this->render('HBBundle:ExpendCategory:modify_exp_category.html.twig', array(
                    'form' => $form->createView(), 'message' => $message));
    }
    
    /**
     * Create form for new category
     * 
     * @param \HomeBudget\HomeBudgetBundle\Entity\ExpendCategory $exCategory
     * @return $form
     */
    private function creatingForm($exCategory) {
        $form = $this->createFormBuilder($exCategory)
                ->add('name', TextType::class, array('label' => 'Nazwa'))
                ->add('save', SubmitType::class, array('label' => 'Potwierdź'))
                ->getForm();

        return $form;
    }
    
    /**
     * Create form for modify 
     * 
     * @param \HomeBudget\HomeBudgetBundle\Entity\ExpendCategory $exCategory
     * @return $form
     */
    private function creatingFormForModify($exCategory) {
        $form = $this->createFormBuilder($exCategory)
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
