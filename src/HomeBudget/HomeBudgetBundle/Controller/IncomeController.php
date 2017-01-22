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
     * @Route("/income/new", name="new_income")
     * @Security("has_role('ROLE_USER')")
     * @Method({"GET", "POST"})
     */
    public function newIncomeAction(Request $request) {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $income = new Income();
        $form = $this->creatingForm($request, $income, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {

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
        $form = $this->creatingForm($request, $incomeToModify, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {

            $income = $form->getData();
            $account = $income->getAccount();

            $em = $this->getDoctrine()->getManager();
            $em->persist($income);
            if ($account->getId() == $accountToModify->getId()) {
                if ($amountToModify != $income->getAmount()) {
                    $amount = $amountToModify - $income->getAmount();
                    $result = $account->spendMoney($amount);
                    if ($result) {
                        $em->flush();
                        return $this->redirectToRoute('show_allIncomes');
                    } else {
                        $message = 'nie można zmodyfikować przychodu, saldo rachunku nie może być'
                                . ' ujemne';
                    }
                }
            } else {
                $account->addMoney($income->getAmount());
                $result = $accountToModify->spendMoney($amountToModify);
                if ($result) {
                    $em->flush();
                    return $this->redirectToRoute('show_allIncomes');
                } else {
                    $message = 'nie można zmodyfikować przychodu, saldo rachunku nie może być'
                            . ' ujemne';
                }
            }
        }

        return $this->render('HBBundle:Income:modify_income.html.twig', array(
                    'form' => $form->createView(), 'message' => $message));
    }

    /**
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
     * @Route("/income/all", name="show_allIncomes")
     * @Security("has_role('ROLE_USER')")
     * 
     */
    public function allIncomeAction() {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $repository = $this->getDoctrine()->getRepository('HBBundle:Income');

        $incomes = $repository->sortByDate($user);

        return $this->render('HBBundle:Income:all_income.html.twig', array(
                    'incomes' => $incomes
        ));
    }
    private function creatingForm(Request $request, $income, $user) {
        $form = $this->createFormBuilder($income)
                ->add('description', TextType::class, array('label' => 'Opis przychodu'))
                ->add('amount', NumberType::class, array('label' => 'Kwota'))
                ->add('incomeDate', DateType::class, array('label' => 'Data'))
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
                ->add('save', SubmitType::class, array('label' => 'Potwierdź'))
                ->getForm();
        
        return $form;
    }
    
}
