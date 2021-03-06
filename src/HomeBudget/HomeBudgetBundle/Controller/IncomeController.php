<?php

namespace HomeBudget\HomeBudgetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityRepository;
use HomeBudget\HomeBudgetBundle\Entity\Income;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use HomeBudget\HomeBudgetBundle\Entity\Account;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class IncomeController extends Controller {

    /**
     * Create new income and save in database
     * 
     * @Route("/income/new", name="new_income")
     * @Security("has_role('ROLE_USER')")
     * @Method({"GET", "POST"})
     */
    public function newIncomeAction(Request $request) {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $income = new Income();
        $form = $this->creatingForm($income, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $income = $form->getData();
            $income->setUser($user);
            $em = $this->getDoctrine()->getManager();
            $em->persist($income);
            $account = $income->getAccount();
            $account->addMoney($income->getAmount());
            $em->flush();

            return $this->redirectToRoute('show_allIncomes');
        }
        return $this->render('HBBundle:Income:new_income.html.twig', array(
                    'form' => $form->createView()));
    }

    /**
     * Modify income and save in database
     * 
     * @Route("/income/{id}/modify", name="modify_income")
     * @Security("has_role('ROLE_USER')")
     */
    public function modifyIncomeAction(Request $request, $id) {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $message = '';
        $repo = $this->getDoctrine()->getRepository('HBBundle:Income');
        $incomeToModify = $repo->findOneById($id);
        $amountToModify = $incomeToModify->getAmount();
        $accountToModify = $incomeToModify->getAccount();
        $form = $this->creatingForm($incomeToModify, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $income = $form->getData();
            $account = $income->getAccount();
            $income->setUser($user);
            $em = $this->getDoctrine()->getManager();
            $em->persist($income);
            if ($account->getId() == $accountToModify->getId()) {
                return $this->modifyIncomeWithoutAccount($form, $em, $income, $amountToModify, $accountToModify);
            } else {
                return $this->modifyIncomeAndAccounts($form, $em, $income, $account, $amountToModify, $accountToModify);
            }
        }

        return $this->render('HBBundle:Income:modify_income.html.twig', array(
                    'form' => $form->createView(), 'message' => $message));
    }

    /**
     * Delete income and save changes in database
     * 
     * @Route("/income/{id}/delete", name="delete_income")
     * @Security("has_role('ROLE_USER')")
     */
    public function deleteIncomeAction(Request $request, $id) {
        //$user = $this->container->get('security.token_storage')->getToken()->getUser();
        $message = '';
        $repository = $this->getDoctrine()->getRepository('HBBundle:Income');
        $income = $repository->find($id);
        $form = $this->createFormBuilder($income)
                ->add('save', SubmitType::class, array('label' => 'Potwierdź'))
                ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {

            $income = $form->getData();

            $em = $this->getDoctrine()->getManager();
            if ($income) {

                $account = $income->getAccount();
                $result = $account->spendMoney($income->getAmount());
                if ($result) {
                    $em->persist($account);
                    $em->remove($income);
                    $em->flush();
                    return $this->redirectToRoute('show_allIncomes');
                } else {
                    $message = 'nie można usunąć przychodu, saldo rachunku nie może być'
                            . ' ujemne';
                }
            }
        }
        return $this->render('HBBundle:Income:delete_income.html.twig', array(
                    'form' => $form->createView(), 'income' => $income, 'message' => $message
        ));
    }

    /**
     * Show all user incomes and prepare data for js chart
     * 
     * @Route("/income/all", name="show_allIncomes")
     * @Security("has_role('ROLE_USER')")
     * 
     */
    public function allIncomeAction() {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $repository = $this->getDoctrine()->getRepository('HBBundle:Income');
        $incomes = $repository->sortByDate($user);
        $sumOfIncomes = $user->sumOfIncomes();
        $data = null;
        if ($incomes) {
            $categoriesAndAmounts = $this->sumOfIncomesByCategory($incomes);
            $arrayForChart = $this->creatingArrayForChart($categoriesAndAmounts);
            $data = json_encode($arrayForChart);
        }

        return $this->render('HBBundle:Income:all_income.html.twig', array(
                    'incomes' => $incomes, 'data' => $data, 'sum' => $sumOfIncomes
        ));
    }
    
    /**
     * Create form for new income or for modifying income
     * 
     * @param \HomeBudget\HomeBudgetBundle\Entity\Income $income
     * @param \HomeBudget\HomeBudgetBundle\Entity\User $user
     * @return $form
     */
    private function creatingForm($income, $user) {
        $form = $this->createFormBuilder($income)
                ->add('description', TextType::class, array('label' => 'Opis przychodu'))
                ->add('amount', NumberType::class, array('label' => 'Kwota'))
                ->add('incomeCategory', EntityType::class, array('class' => 'HBBundle:IncomeCategory',
                    'query_builder' => function(EntityRepository $er) use ($user) {
                        return $er->queryOwnedBy($user);
                    },
                    'choice_label' => 'name', 'label' => 'Kategoria'))
                ->add('account', EntityType::class, array('class' => 'HBBundle:Account',
                    'query_builder' => function(EntityRepository $er) use ($user) {
                        return $er->queryOwnedBy($user);
                    },
                    'choice_label' => 'name', 'label' => 'Zasilono: '))
                ->add('incomeDate', DateType::class, array('widget' => 'single_text',
                    'attr' => ['class' => 'js-datepicker'],
                    'html5' => false,
                    'label' => 'Data'))
                ->add('save', SubmitType::class, array('label' => 'Potwierdź'))
                ->getForm();

        return $form;
    }
    
    /**
     * Modify income without changing account
     * 
     * @param type $form
     * @param type $em
     * @param \HomeBudget\HomeBudgetBundle\Entity\Income $income
     * @param float $amountToModify
     * @param \HomeBudget\HomeBudgetBundle\Entity\Account $accountToModify
     * 
     */
    private function modifyIncomeWithoutAccount($form, $em,  $income, $amountToModify, $accountToModify) {
        if ($amountToModify !== $income->getAmount()) {
            $amount = $amountToModify - $income->getAmount();
            $result = $accountToModify->spendMoney($amount);
            if ($result) {
                $em->flush();
                return $this->redirectToRoute('show_allIncomes');
            } else {
                $message = 'nie można zmodyfikować przychodu, saldo rachunku nie może być'
                        . ' ujemne';
                return $this->render('HBBundle:Income:modify_income.html.twig', array(
                            'form' => $form->createView(), 'message' => $message
                ));
            }
        } else {
            $em->flush();
            return $this->redirectToRoute('show_allIncomes');
        }
    }
    
    /**
     * Modify income and balances of two accounts
     * 
     * @param type $form
     * @param type $em
     * @param \HomeBudget\HomeBudgetBundle\Entity\Income $income
     * @param \HomeBudget\HomeBudgetBundle\Entity\Account $account
     * @param float $amountToModify
     * @param \HomeBudget\HomeBudgetBundle\Entity\Account $accountToModify
     * 
     */
    private function modifyIncomeAndAccounts($form, $em, $income, $account, $amountToModify, $accountToModify) {
        $account->addMoney($income->getAmount());
        $result = $accountToModify->spendMoney($amountToModify);
        if ($result) {
            $em->flush();
            return $this->redirectToRoute('show_allIncomes');
        } else {
            $message = 'nie można zmodyfikować przychodu, saldo rachunku nie może być'
                    . ' ujemne';
            return $this->render('HBBundle:Income:modify_income.html.twig', array(
                        'form' => $form->createView(), 'message' => $message
            ));
        }
    }

    /**
     * Create array with sum of incomes by category
     * 
     * @param array $incomes
     * @return array
     */
    private function sumOfIncomesByCategory($incomes) {
        $arrayWithCategoryAndAmounts = [];
        foreach ($incomes as $income) {
            $category = $income->getIncomeCategory()->getName();
            if (array_key_exists($category, $arrayWithCategoryAndAmounts)) {
                $arrayWithCategoryAndAmounts["$category"] += $income->getAmount();
            } else {
                $arrayWithCategoryAndAmounts["$category"] = $income->getAmount();
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
