<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Matiere;
use App\Form\MatiereType;

class MatiereController extends AbstractController
{
    /**
     * @Route("/matieres", name="matieres")
     */
    public function index(Request $request, TranslatorInterface $trans)
    {
        $em = $this->getDoctrine()->getManager();

        $matiere = new Matiere();

        $form = $this->createForm(MatiereType::class, $matiere);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($matiere);
            $em->flush();

            $this->addFlash(
                'success',
                $trans->trans('matiere.created')
            );
        }

        $matieres = $em->getRepository(Matiere::class)->findAll();

        return $this->render('matiere/index.html.twig', [
            'matieres' => $matieres,
            'add_matiere' => $form->createView()
        ]);
    }

    /**
     * @Route("/matiere/{id}", name="matiere")
     */
    public function matiere(Matiere $matiere, Request $request, TranslatorInterface $trans){
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(MatiereType::class, $matiere);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($matiere);
            $em->flush();
            $this->addFlash('success', $trans->trans('matiere.modifier'));
        }

        return $this->render('matiere/matiere.html.twig', [
            'matiere'      => $matiere,
            'edit_form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="matiereDelete")
     */
    public function matiereDelete(Matiere $matiere = null, TranslatorInterface $trans){
        if($matiere != null){
            $em = $this->getDoctrine()->getManager();
            $em->remove($matiere);
            $em->flush();
            $this->addFlash('success', $trans->trans('matiere.delete'));
        }

        return $this->redirectToRoute('matieres');
    }
}
