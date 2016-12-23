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

class ExpendController extends Controller {

    /**
     * @Route("/expend/new", name="new_expend")
     * @Security("has_role('ROLE_USER')")
     * @Method({"GET", "POST"})
     */
    public function newExpendAction(Request $request) {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $message = '';

        $expend = new Expend($user);
        $form = $this->createFormBuilder($expend)
                ->add('description', 'text', array('label' => 'Opis wydatku'))
                ->add('amount', 'number', array('label' => 'Kwota'))
                ->add('expendDate', 'date', array('label' => 'Data'))
                ->add('expendCategory', 'entity', array('class' => 'HBBundle:ExpendCategory',
                    'query_builder' => function(EntityRepository $er) use ($user) {
                        return $er->queryOwnedBy($user);
                    },
                    'choice_label' => 'name', 'label' => 'Kategoria'))
                ->add('account', 'entity', array('class' => 'HBBundle:Account',
                    'query_builder' => function(EntityRepository $er) use ($user) {
                        return $er->queryOwnedBy($user);
                    },
                    'choice_label' => 'name', 'label' => 'Zapłacono z: '))
                ->add('save', 'submit', array('label' => 'Potwierdź'))
                ->getForm();
        $form->handleRequest($request);
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
                    'form' => $form->createView(),
                    'message' => $message
        ));
    }

    /**
     * @Route("/expend/{id}/modify", name="modify_expend")
     * @Security("has_role('ROLE_USER')")
     */
    public function modifyExpendAction(Request $request, $id) {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $message = '';

        $repo = $this->getDoctrine()->getRepository('HBBundle:Expend');
        $expend = $repo->findOneById($id);
        $form = $this->createFormBuilder($expend)
                ->add('description', 'text', array('label' => 'Opis wydatku'))
                ->add('amount', 'number', array('label' => 'Kwota'))
                ->add('expendDate', 'date', array('label' => 'Data'))
                ->add('expendCategory', 'entity', array('class' => 'HBBundle:ExpendCategory',
                    'query_builder' => function(EntityRepository $er) use ($user) {
                        return $er->queryOwnedBy($user);
                    },
                    'choice_label' => 'name', 'label' => 'Kategoria'))
                ->add('account', 'entity', array('class' => 'HBBundle:Account',
                    'query_builder' => function(EntityRepository $er) use ($user) {
                        return $er->queryOwnedBy($user);
                    },
                    'choice_label' => 'name', 'label' => 'Zapłacono z: '))
                ->add('save', 'submit', array('label' => 'Potwierdź'))
                ->getForm();
        $form->handleRequest($request);
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
                    'form' => $form->createView(),
                    'message' => $message
        ));
    }

    /**
     * @Route("/expend/{id}/delete", name="delete_expend")
     * @Security("has_role('ROLE_USER')")
     */
    public function deleteExpendAction($id) {
        return $this->render('HBBundle:Expend:delete_expend.html.twig', array(
                        // ...
        ));
    }

    /**
     * @Route("/expend/all", name="show_allExpends")
     * @Security("has_role('ROLE_USER')")
     */
    public function allExpendAction() {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $repository = $this->getDoctrine()->getRepository('HBBundle:Expend');

        $expends = $repository->sortByDate($user);

        return $this->render('HBBundle:Expend:all_expend.html.twig', array(
                    'expends' => $expends
        ));
    }

}
