<?php

namespace HomeBudget\HomeBudgetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use HomeBudget\HomeBudgetBundle\Entity\Expend;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityRepository;

class ExpendController extends Controller
{
    /**
     * @Route("/expend/new")
     * @Security("has_role('ROLE_USER')")
     * @Method({"GET", "POST"})
     */
    public function newExpendAction(Request $request)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();

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
                ->add('save', 'submit', array('label' => 'Potwierdź'))
                ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {

            $expend = $form->getData();


            $expend->setUser($user);
            $em = $this->getDoctrine()->getManager();
            $em->persist($expend);

            $em->flush();

            return $this->redirectToRoute('show_allExpends');
        }
        return $this->render('HBBundle:Expend:new_expend.html.twig', array(
                    'form' => $form->createView()));
    }

    /**
     * @Route("/expend/{id}/modify")
     */
    public function modifyExpendAction($id)
    {
        return $this->render('HBBundle:Expend:modify_expend.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/expend/{id}/delete")
     */
    public function deleteExpendAction($id)
    {
        return $this->render('HBBundle:Expend:delete_expend.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/expend/all", name="show_allExpends")
     */
    public function allExpendAction()
    {
        return $this->render('HBBundle:Expend:all_expend.html.twig', array(
            // ...
        ));
    }

}
