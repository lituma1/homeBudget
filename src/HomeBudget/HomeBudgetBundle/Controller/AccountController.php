<?php

namespace HomeBudget\HomeBudgetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use HomeBudget\HomeBudgetBundle\Entity\Account;
use Symfony\Component\HttpFoundation\Request;
use HomeBudget\HomeBudgetBundle\Entity\Type;
use Doctrine\ORM\EntityRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class AccountController extends Controller {

    /**
     * @Route("/account/new")
     * @Security("has_role('ROLE_USER')")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request) {
        $user = $this->container->get('security.context')->getToken()->getUser();

        $account = new Account($user);
        $form = $this->createFormBuilder($account)
                ->add('name', 'text', array('label' => 'Nazwa konta'))
                ->add('balance', 'number', array('label' => 'Stan konta'))
                ->add('aim', 'text', array('label' => 'cel konta'))
                ->add('type', 'entity', array('class' => 'HBBundle:Type',
                    'query_builder' => function(EntityRepository $er) use ($user) {
                        return $er->queryOwnedBy($user);
                    },
                    'choice_label' => 'name', 'label' => 'Typ konta'))
                ->add('save', 'submit', array('label' => 'Potwierdź'))
                ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {

            $account = $form->getData();


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
     * @Route("/account/{id}/modify")
     * @Security("has_role('ROLE_USER')")
     */
    public function modifyAction($id) {
        return $this->render('HBBundle:Account:modify.html.twig', array(
                        // ...
        ));
    }

    /**
     * @Route("/account/{id}/delete")
     * @Security("has_role('ROLE_USER')")
     */
    public function deleteAction($id) {
        return $this->render('HBBundle:Account:delete.html.twig', array(
                        // ...
        ));
    }

    /**
     * @Route("/account/showAll", name="show_allAccounts")
     * @Security("has_role('ROLE_USER')")
     */
    public function showAllAction() {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $repository = $this->getDoctrine()->getRepository('HBBundle:Account');

        $accounts = $repository->findByUser($user);
        return $this->render('HBBundle:Account:show_all.html.twig', array(
                    'accounts' => $accounts
        ));
    }

}
