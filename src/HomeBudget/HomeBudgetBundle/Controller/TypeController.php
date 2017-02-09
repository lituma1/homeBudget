<?php

namespace HomeBudget\HomeBudgetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use HomeBudget\HomeBudgetBundle\Entity\Type;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class TypeController extends Controller {

    /**
     * @Route("/type/addTypes", name="add_new_types")
     * @Security("has_role('ROLE_USER')")
     * @Method({"GET", "POST"})
     */
    public function addTypesAction(Request $request) {
        $types = ['Rachunek bieżący', 'Konto USD', 'Konto EUR',
            'Fundusz inwestycyjny', 'Rachunek maklerski',
            'Rachunek oszczędnościowy', 'Gotówka'];
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $form = $this->createFormBuilder()
                ->add('save', SubmitType::class, array('label' => 'Potwierdź'))
                ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            foreach ($types as $typeName) {
                $type = new Type();
                $type->setStatus(true);
                $type->setName($typeName);
                $type->setUser($user);
                $em = $this->getDoctrine()->getManager();
                $em->persist($type);
            }
            $em->flush();
            return $this->redirectToRoute('new_type');
        }
        return $this->render('HBBundle:Type:add_types.html.twig', array(
                    'form' => $form->createView()));
    }

    /**
     * @Route("/type/new", name="new_type")
     * @Security("has_role('ROLE_USER')")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request) {
        $message = '';
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $repository = $this->getDoctrine()->getRepository('HBBundle:Type');
        $types = $repository->findByUserAndStatus($user);
        $type = new Type();
        $form = $this->createFormBuilder($type)
                ->add('name', TextType::class, array('label' => 'Nazwa'))
                ->add('save', SubmitType::class, array('label' => 'Potwierdź'))
                ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {

            $type = $form->getData();
            $type->setUser($user);
            $type->setStatus(true);

            $typeName = $type->getName();
            $em = $this->getDoctrine()->getManager();
            if (!$this->existTypeNameInDatabase($types, $typeName)) {
                $em->persist($type);
                $em->flush();
                return $this->redirectToRoute('new_type');
            } else {
                $message = 'Typ o takiej nazwie już istnieje';
            }
        }
        return $this->render('HBBundle:Type:new.html.twig', array(
                    'form' => $form->createView(), 'types' => $types,
                    'message' => $message));
    }

    /**
     * @Route("/type/{id}/modify", name="modify_type")
     * @Security("has_role('ROLE_USER')")
     * @Method({"GET", "POST"})
     */
    public function modifyAction(Request $request, $id) {
        $message = '';
        $repository = $this->getDoctrine()->getRepository('HBBundle:Type');
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $types = $repository->findByUserAndStatus($user);
        $type = $repository->find($id);
        $arrayWithTypeNames = $this->createArrayWithTypeNames($types, $type->getName());

        $form = $this->createFormBuilder($type)
                ->add('name', TextType::class, array('label' => 'Nazwa'))
                ->add('status', CheckboxType::class, array('label' =>
                    'Zaznacz jeśli chcesz aktywować', 'required' => false))
                ->add('save', SubmitType::class, array('label' => 'Potwierdź'))
                ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $type = $form->getData();
            $typeName = $type->getName();
            $result = in_array($typeName, $arrayWithTypeNames);

            if (!$result) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($type);
                $em->flush();
                return $this->redirectToRoute('new_type');
            } else {
                $message = 'Typ o takiej nazwie już istnieje';
            }
        }
        return $this->render('HBBundle:Type:modify.html.twig', array(
                    'form' => $form->createView(), 'message' => $message));
    }

    private function existTypeNameInDatabase($types, $typeName) {

        $result = false;
        foreach ($types as $type) {
            if ($type->getName() === $typeName) {
                $result = true;
            }
        }
        return $result;
    }

    private function createArrayWithTypeNames($types, $string) {
        $array = [];
        foreach ($types as $type) {
            if ($type->getName() !== $string) {
                $array[] = $type->getName();
            }
        }
        return $array;
    }

}
