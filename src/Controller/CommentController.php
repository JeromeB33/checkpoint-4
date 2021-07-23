<?php

namespace App\Controller;

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
     * @Route("/report/{id}", name="report")
     */
    public function report(Comment $comment): Response
    {
        $comment->setReported(true);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('subject_show', ['slug' => $comment->getSubject()->getSlug()]);
    }
}
