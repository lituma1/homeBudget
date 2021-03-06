<?php

namespace HomeBudget\HomeBudgetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use HomeBudget\HomeBudgetBundle\Entity\Expend;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityRepository;
use HomeBudget\HomeBudgetBundle\Repository\AccountRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class ExpendController extends Controller {

    /**
     * Create new Expend and save in database
     * 
     * @Route("/expend/new", name="new_expend")
     * @Security("has_role('ROLE_USER')")
     * @Method({"GET", "POST"})
     */
    public function newExpendAction(Request $request) {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $message = '';

        $expend = new Expend();
        $form = $this->creatingForm($expend, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $expend = $form->getData();
            $expend->setUser($user);
            $em = $this->getDoctrine()->getManager();
            $em->persist($expend);
            $account = $expend->getAccount();
            $result = $account->spendMoney($expend->getAmount());

            if ($result) {
                $em->flush();
                return $this->redirectToRoute('show_allExpends');
            } else {
                $message = 'Wydatek większy od salda, nie udało się go zapisać';
            }
        }
        return $this->render('HBBundle:Expend:new_expend.html.twig', array(
                    'form' => $form->createView(), 'message' => $message
        ));
    }

    /**
     * Modify expend and save in database
     * 
     * @Route("/expend/{id}/modify", name="modify_expend")
     * @Security("has_role('ROLE_USER')")
     */
    public function modifyExpendAction(Request $request, $id) {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $message = '';
        $repo = $this->getDoctrine()->getRepository('HBBundle:Expend');
        $expendToModify = $repo->findOneById($id);
        $amountToModify = $expendToModify->getAmount();
        $accountToModify = $expendToModify->getAccount();
        $form = $this->creatingForm($expendToModify, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $expend = $form->getData();
            $expend->setUser($user);
            $em = $this->getDoctrine()->getManager();
            $account = $expend->getAccount();
            $em->persist($expend);

            if ($account->getId() == $accountToModify->getId()) {
                return $this->modifyExpendWithoutAccount($form, $em, $expend, $amountToModify, $account);
            } else {
                return $this->modifyExpendAndAccounts($form, $em, $expend, $account, $amountToModify, $accountToModify);
            }
        }
        return $this->render('HBBundle:Expend:modify_expend.html.twig', array(
                    'form' => $form->createView(), 'message' => $message
        ));
    }

    /**
     * Delete expend and save changes in database
     * 
     * @Route("/expend/{id}/delete", name="delete_expend")
     * @Security("has_role('ROLE_USER')")
     */
    public function deleteExpendAction(Request $request, $id) {

        $repository = $this->getDoctrine()->getRepository('HBBundle:Expend');
        $expend = $repository->find($id);

        $form = $this->createFormBuilder($expend)
                ->add('save', SubmitType::class, array('label' => 'Potwierdź'))
                ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {

            $expend = $form->getData();

            $em = $this->getDoctrine()->getManager();
            if ($expend) {
                $account = $expend->getAccount();
                $account->addMoney($expend->getAmount());
                $em->remove($expend);

                $em->flush();
            }


            return $this->redirectToRoute('show_allExpends');
        }
        return $this->render('HBBundle:Expend:delete_expend.html.twig', array(
                    'form' => $form->createView(), 'expend' => $expend
        ));
    }

    /**
     * Show all user expends and prepare data for js chart
     * 
     * @Route("/expend/all", name="show_allExpends")
     * @Security("has_role('ROLE_USER')")
     */
    public function allExpendAction() {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $repository = $this->getDoctrine()->getRepository('HBBundle:Expend');
        $sumOfExpends = $user->sumOfExpends();
        $expends = $repository->sortByDate($user);
        $data = null;
        if($expends){
            $categoriesAndAmounts = $this->sumOfExpendsByCategory($expends);
            $arrayForChart = $this->creatingArrayForChart($categoriesAndAmounts);
            $data = json_encode($arrayForChart);
        }
        
        return $this->render('HBBundle:Expend:all_expend.html.twig', array(
                    'expends' => $expends, 'data' => $data, 'sum' => $sumOfExpends
        ));
    }
    
    /**
     * Create form for new expend or for modifying expend
     * 
     * @param \HomeBudget\HomeBudgetBundle\Entity\Expend $expend
     * @param \HomeBudget\HomeBudgetBundle\Entity\User $user
     * @return $form
     */
    private function creatingForm($expend, $user) {
        $form = $this->createFormBuilder($expend)
                ->add('description', TextType::class, array('label' => 'Opis wydatku'))
                ->add('amount', NumberType::class, array('label' => 'Kwota'))
                ->add('expendCategory', EntityType::class, array('class' => 'HBBundle:ExpendCategory',
                    'query_builder' => function(EntityRepository $er) use ($user) {
                        return $er->queryOwnedBy($user);
                    },
                    'choice_label' => 'name', 'label' => 'Kategoria'))
                ->add('account', EntityType::class, array('class' => 'HBBundle:Account',
                    'query_builder' => function(EntityRepository $er) use ($user) {
                        return $er->queryOwnedBy($user);
                    },
                    'label' => 'Zapłacono z: '))
                ->add('expendDate', DateType::class, array('widget' => 'single_text',
                    'attr' => ['class' => 'js-datepicker'],
                    'html5' => false,
                    'label' => 'Data'))
                ->add('save', SubmitType::class, array('label' => 'Potwierdź'))
                ->getForm();

        return $form;
    }
    
    /**
     * Modify Expend and balances of two accounts
     * 
     * @param type $form
     * @param type $em
     * @param \HomeBudget\HomeBudgetBundle\Entity\Expend $expend
     * @param \HomeBudget\HomeBudgetBundle\Entity\Account $account
     * @param float $amountToModify
     * @param \HomeBudget\HomeBudgetBundle\Entity\Account $accountToModify
     * 
     */
    private function modifyExpendAndAccounts($form, $em, $expend, $account, $amountToModify, $accountToModify) {

        $result = $account->spendMoney($expend->getAmount());
        if ($result) {
            $accountToModify->addMoney($amountToModify);
            $em->flush();
            return $this->redirectToRoute('show_allExpends');
        } else {
            $message = 'Wydatek większy od salda, nie udało się go zmodyfikować';
            return $this->render('HBBundle:Expend:modify_expend.html.twig', array(
                        'form' => $form->createView(), 'message' => $message
            ));
        }
    }
    
    /**
     * Modify expend 
     * 
     * @param type $form
     * @param type $em
     * @param \HomeBudget\HomeBudgetBundle\Entity\Expend $expend
     * @param float $amountToModify
     * @param \HomeBudget\HomeBudgetBundle\Entity\Account $account
     * 
     */
    private function modifyExpendWithoutAccount($form, $em, $expend, $amountToModify, $account) {
        if ($expend->getAmount() != $amountToModify) {
            $amount = $expend->getAmount() - $amountToModify;
            $result = $account->spendMoney($amount);

            if ($result) {
                $em->flush();
                return $this->redirectToRoute('show_allExpends');
            } else {
                $message = 'Wydatek większy od salda, nie udało się go zmodyfikować';
                return $this->render('HBBundle:Expend:modify_expend.html.twig', array(
                            'form' => $form->createView(), 'message' => $message
                ));
            }
        } else {

            $em->flush();
            return $this->redirectToRoute('show_allExpends');
        }
    }
    
    /**
     * Create array with sum of expends by category
     * 
     * @param type $expends
     * @return array
     */
    private function sumOfExpendsByCategory($expends) {
        $arrayWithCategoryAndAmounts = [];
        foreach ($expends as $expend) {
            $category = $expend->getExpendCategory()->getName();
            if (array_key_exists($category, $arrayWithCategoryAndAmounts)) {
                $arrayWithCategoryAndAmounts["$category"] += $expend->getAmount();
            } else {
                $arrayWithCategoryAndAmounts["$category"] = $expend->getAmount();
            }
        }


        return $arrayWithCategoryAndAmounts;
    }
    
    /**
     * Create array for js charts
     * 
     * @param type $array
     * @return type
     */
    private function creatingArrayForChart($array) {
        $arrayForChart = [];
        foreach ($array as $key => $value) {
            $array2 = [];
            $array2['name'] = $key;
            $array2['amount'] = $value;
            $arrayForChart[] = $array2;
        }
        foreach ($arrayForChart as $key => $row) {
            $name[$key] = $row['name'];
            $amount[$key] = $row['amount'];
        }
        array_multisort($amount, SORT_DESC, $name, SORT_ASC, $arrayForChart);
        return $arrayForChart;
    }

}
