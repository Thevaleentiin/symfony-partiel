<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Note;
use App\Form\NoteType;
use App\Entity\Matiere;

class NoteController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request, TranslatorInterface $trans)
    {
        $em = $this->getDoctrine()->getManager();

        $note = new Note();
        $note->setNoteDate(new \DateTime('now'));

        $form = $this->createForm(NoteType::class, $note);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($note);
            $em->flush();

            $this->addFlash(
                'success',
                $trans->trans('note.created')
            );
        }

        $notes = $em->getRepository(Note::class)->findAll();
        return $this->render('note/index.html.twig', [
            'notes' => $notes,
            'add_note' => $form->createView()
        ]);
    }
}
