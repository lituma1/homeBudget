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
     * @Route("/expend/new", name="new_expend")
     * @Security("has_role('ROLE_USER')")
     * @Method({"GET", "POST"})
     */
    public function newExpendAction(Request $request) {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $message = '';

        $expend = new Expend();
        $form = $this->creatingForm($request, $expend, $user);
        if ($form->isSubmitted()) {

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
        $form = $this->creatingForm($request, $expendToModify, $user);
        if ($form->isSubmitted()) {

            $expend = $form->getData();


            $expend->setUser($user);
            $em = $this->getDoctrine()->getManager();
            $account = $expend->getAccount();
            $em->persist($expend);
            if ($account->getId() == $accountToModify->getId()) {
                if ($expend->getAmount() != $amountToModify) {
                    $amount = $expend->getAmount() - $amountToModify;


                    $result = $account->spendMoney($amount);

                    if ($result) {
                        $em->flush();
                        return $this->redirectToRoute('show_allExpends');
                    } else {
                        $message = 'Wydatek większy od salda, nie udało się go zmodyfikować';
                    }
                } else {

                    $em->flush();
                    return $this->redirectToRoute('show_allExpends');
                }
            } else {
                $result = $account->spendMoney($expend->getAmount());
                if ($result) {
                    $accountToModify->addMoney($amountToModify);
                    $em->flush();
                    return $this->redirectToRoute('show_allExpends');
                } else {
                    $message = 'Wydatek większy od salda, nie udało się go zmodyfikować';
                }
            }
        }
        return $this->render('HBBundle:Expend:modify_expend.html.twig', array(
                    'form' => $form->createView(),
                    'message' => $message
        ));
    }

    /**
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
     * @Route("/expend/all", name="show_allExpends")
     * @Security("has_role('ROLE_USER')")
     */
    public function allExpendAction() {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $repository = $this->getDoctrine()->getRepository('HBBundle:Expend');

        $expends = $repository->sortByDate($user);

        return $this->render('HBBundle:Expend:all_expend.html.twig', array(
                    'expends' => $expends
        ));
    }
    private function creatingForm(Request $request, $expend, $user){
        $form = $this->createFormBuilder($expend)
                ->add('description', TextType::class, array('label' => 'Opis wydatku'))
                ->add('amount', NumberType::class, array('label' => 'Kwota'))
                ->add('expendDate', DateType::class, array('label' => 'Data'))
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
                ->add('save', SubmitType::class, array('label' => 'Potwierdź'))
                ->getForm();
        $form->handleRequest($request);
        return $form;
    }
}
