<?php

namespace HomeBudget\HomeBudgetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use HomeBudget\HomeBudgetBundle\Entity\Account;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AccountController extends Controller {

    /**
     * @Route("/account/new", name="new_Account")
     * @Security("has_role('ROLE_USER')")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request) {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();

        $account = new Account($user);
        $form = $this->createFormBuilder($account)
                ->add('name', TextType::class, array('label' => 'Nazwa konta'))
                ->add('balance', NumberType::class, array('label' => 'Stan konta'))
                ->add('aim', TextType::class, array('label' => 'cel konta'))
                ->add('type', EntityType::class, array('class' => 'HBBundle:Type',
                    'query_builder' => function(EntityRepository $er) use ($user) {
                        return $er->queryOwnedBy($user);
                    },
                    'choice_label' => 'name', 'label' => 'Typ konta'))
                ->add('save', SubmitType::class, array('label' => 'Potwierdź'))
                ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {

            $account = $form->getData();
            $account->setStatus(true);

            $account->setUser($user);
            $em = $this->getDoctrine()->getManager();
            $em->persist($account);

            $em->flush();

            return $this->redirectToRoute('show_allAccounts');
        }
        return $this->render('HBBundle:Account:new.html.twig', array(
                    'form' => $form->createView()));
    }

    /**
     * @Route("/account/{id}/modify", name="modify_Account")
     * @Security("has_role('ROLE_USER')")
     * @Method({"GET", "POST"})
     */
    public function modifyAction(Request $request, $id) {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $repo = $this->getDoctrine()->getRepository('HBBundle:Account');
        $account = $repo->findOneById($id);
        $form = $this->createFormBuilder($account)
                ->add('name', TextType::class, array('label' => 'Nazwa konta'))
                ->add('balance', NumberType::class, array('label' => 'Stan konta'))
                ->add('aim', TextType::class, array('label' => 'cel konta'))
                ->add('type', EntityType::class, array('class' => 'HBBundle:Type',
                    'query_builder' => function(EntityRepository $er) use ($user) {
                        return $er->queryOwnedBy($user);
                    },
                    'choice_label' => 'name', 'label' => 'Typ konta'))
                ->add('save', SubmitType::class, array('label' => 'Potwierdź'))
                ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {

            $account = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($account);

            $em->flush();
            return $this->redirectToRoute('show_allAccounts');
        }

        return $this->render('HBBundle:Account:modify.html.twig', array(
                    'form' => $form->createView()));
    }

    /**
     * @Route("/account/{id}/delete", name="delete_Account")
     * @Security("has_role('ROLE_USER')")
     */
    public function deleteAction($id) {
        $em = $this->getDoctrine()->getManager();
        $account = $em->getRepository
                        ('HBBundle:Account')->find($id);
        if ($account) {
            $account->setStatus(false);
            $em->persist($account);
            $em->flush();
        }


        return $this->redirectToRoute('show_allAccounts');
    }

    /**
     * @Route("/account/{id}/submit", name="submit_delete")
     * @Security("has_role('ROLE_USER')")
     * @param type $id
     */
    public function submitAction($id) {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $repository = $this->getDoctrine()->getRepository('HBBundle:Account');
        $account = $repository->find($id);
        if ($account->getBalance() > 0) {
            $message = 'Przed usunięciem proszę przenieść środki na inne konto';
            $balance = $user->balanceOfAccounts();
             $accounts = $repository->findByUserAndStatus($user);
            return $this->render('HBBundle:Account:show_all.html.twig', array(
                        'accounts' => $accounts, 'balance' => $balance, 'message' => $message
            ));
        } else {
            return $this->render('HBBundle:Account:submit.html.twig', array(
                        'account' => $account
            ));
        }
    }

    /**
     * @Route("/account/showAll", name="show_allAccounts")
     * @Security("has_role('ROLE_USER')")
     */
    public function showAllAction() {
        $message = '';
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $repository = $this->getDoctrine()->getRepository('HBBundle:Account');

        $accounts = $repository->findByUserAndStatus($user);
        $balance = $user->balanceOfAccounts();
        return $this->render('HBBundle:Account:show_all.html.twig', array(
                    'accounts' => $accounts, 'balance' => $balance, 'message' => $message
        ));
    }

    /**
     * @Route("/account/{id}/moveMoney", name="move_Money")
     * @Security("has_role('ROLE_USER')")
     * @Method({"GET", "POST"})
     */
    public function moveMoneyAction(Request $request, $id) {

        $message = '';
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $repo = $this->getDoctrine()->getRepository('HBBundle:Account');
        $account = $repo->findOneById($id);
        $balance = $account->getBalance();
        $form = $this->createFormBuilder()
                ->add('amount', NumberType::class)
                ->add('account', EntityType::class, array('class' => 'HBBundle:Account',
                    'query_builder' => function(EntityRepository $er) use ($user, $id) {
                        return $er->queryOwnedByWithoutThisAccount($user, $id);
                    },
                    'choice_label' => 'name', 'label' => 'Na konto'))
                ->add('save', SubmitType::class, array('label' => 'Potwierdź'))
                ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $amount = $form['amount']->getData();
            if ($amount <= $account->getBalance()) {
                $account->spendMoney($amount);
                $accountToMove = $form['account']->getData();

                $accountToMove->addMoney($amount);
                $em = $this->getDoctrine()->getManager();
                $em->persist($accountToMove);
                $em->persist($account);

                $em->flush();
                return $this->redirectToRoute('show_allAccounts');
            } else {
                $message = 'Kwota do przeniesienia większa od salda';
            }
        }
        return $this->render('HBBundle:Account:move_money.html.twig', array(
                    'form' => $form->createView(), 'balance' => 'max kwota  ' . $balance,
                    'message' => $message));
    }

}
