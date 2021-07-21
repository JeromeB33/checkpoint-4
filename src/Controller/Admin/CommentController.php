<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Comment;
use App\Form\CommentType;

/**
 * @Route("/comment", name="comment_")
 */
class CommentController extends AbstractController
{
    /**
     * @Route("/edit/{id}", name="edit")
     */
    public function edit(Request $request, Comment $comment): Response
    {
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('subject_show', ["slug" => $comment->getSubject()->getSlug()]);
        }

        return $this->render('comment/edit.html.twig', ["form" => $form->createView(),
         'comment' => $comment]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Request $request, Comment $comment): Response
    {
        if ($this->isCsrfTokenValid('delete' . $comment->getId(), $request->request->get('_token'))) {
            $comment->getUser()->setContribution($comment->getUser()->getContribution() - 10 );
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($comment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('subject_show', ["slug" => $comment->getSubject()->getSlug()]);
    }

    /**
     * @Route("/report/{id}", name="report")
     */
    public function report(Comment $comment): Response
    {
        $comment->setReported(true);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('subject_show', ['slug' => $comment->getSubject()->getSlug()]);
    }
}
