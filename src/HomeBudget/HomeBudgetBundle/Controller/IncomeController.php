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
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $repository = $this->getDoctrine()->getRepository('HBBundle:Income');

        $incomes = $repository->sortByDate($user);
        
        return $this->render('HBBundle:Income:all_income.html.twig', array(
                    'incomes' => $incomes
        ));
    }

}
