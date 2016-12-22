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

class IncomeController extends Controller {

    /**
     * @Route("/income/new", name="new_income")
     * @Security("has_role('ROLE_USER')")
     * @Method({"GET", "POST"})
     */
    public function newIncomeAction(Request $request) {
        $user = $this->container->get('security.context')->getToken()->getUser();

        $income = new Income($user);
        $form = $this->createFormBuilder($income)
                ->add('description', 'text', array('label' => 'Opis przychodu'))
                ->add('amount', 'number', array('label' => 'Kwota'))
                ->add('incomeDate', 'date', array('label' => 'Data'))
                ->add('incomeCategory', 'entity', array('class' => 'HBBundle:IncomeCategory',
                    'query_builder' => function(EntityRepository $er) use ($user) {
                        return $er->queryOwnedBy($user);
                    },
                    'choice_label' => 'name', 'label' => 'Kategoria'))
                ->add('account', 'entity', array('class' => 'HBBundle:Account',
                    'query_builder' => function(EntityRepository $er) use ($user) {
                        return $er->queryOwnedBy($user);
                    },
                    'choice_label' => 'name', 'label' => 'Zasilono: '))
                ->add('save', 'submit', array('label' => 'Potwierdź'))
                ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {

            $income = $form->getData();


            $income->setUser($user);
            $em = $this->getDoctrine()->getManager();
            $em->persist($income);
            $account = $income->getAccount();
            //$repository = $this->getDoctrine()->getRepository('HBBundle:Account');
            $account->addMoney($income->getAmount());

            $em->flush();
            



            return $this->redirectToRoute('show_allIncomes');
        }
        return $this->render('HBBundle:Income:new_income.html.twig', array(
                    'form' => $form->createView()));
    }

    /**
     * @Route("/income/{id}/modify")
     * @Security("has_role('ROLE_USER')")
     */
    public function modifyIncomeAction($id) {
        return $this->render('HBBundle:Income:modify_income.html.twig', array(
                        // ...
        ));
    }

    /**
     * @Route("/income/{id}/delete")
     * @Security("has_role('ROLE_USER')")
     */
    public function deleteIncomeAction($id) {
        return $this->render('HBBundle:Income:delete_income.html.twig', array(
                        // ...
        ));
    }

    /**
     * @Route("/income/all", name="show_allIncomes")
     * @Security("has_role('ROLE_USER')")
     * 
     */
    public function allIncomeAction() {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $repository = $this->getDoctrine()->getRepository('HBBundle:Income');

        $incomes = $repository->findByUser($user);

        return $this->render('HBBundle:Income:all_income.html.twig', array(
                    'incomes' => $incomes
        ));
    }

}
