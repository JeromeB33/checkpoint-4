<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Subject;
use App\Form\CommentType;
use App\Form\SubjectType;
use App\Repository\SubjectRepository;
use App\Repository\TagsRepository;
use App\Repository\UserRepository;
use App\Service\Slugify;
use DateTime as GlobalDateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/subject", name="subject_")
 */
class SubjectController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(SubjectRepository $subjectRepository,
    UserRepository $userRepository,
    TagsRepository $tagsRepository): Response
    {
        if ($this->getUser() == '') {
            return $this->redirectToRoute('app_login');
        }
        return $this->render('subject/index.html.twig', [
            'tags' => $tagsRepository->findAll(),
            'users' => $userRepository->findBy([], ['contribution' => 'DESC'], 5),
            'subjects' => $subjectRepository->findBy([], ['creationDate' => 'DESC'])
        ]);
    }

    /**
     * @Route("/new", name="new")
     */
    public function new(Request $request, Slugify $slugify): Response
    {
        if ($this->getUser() == '') {
            return $this->redirectToRoute('app_login');
        }
        $subject = new Subject();
        $form = $this->createForm(SubjectType::class, $subject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugify->generate($subject->getTitle());
            $subject->setSlug($slug);
            $subject->setUser($this->getUser());
            $subject->setCreationDate(new GlobalDateTime());
            $subject->setIsValidate(false);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($subject);
            $entityManager->flush();

            return $this->redirectToRoute('subject_index');
            }
        return $this->render('subject/new.html.twig', ["form" => $form->createView()]);
    }

    /**
     * @Route("/edit/{slug}", name="edit")
     */
    public function edit(Request $request,Slugify $slugify, Subject $subject): Response
    {
        if ($this->getUser() == '') {
            return $this->redirectToRoute('app_login');
        }
        $form = $this->createForm(SubjectType::class, $subject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugify->generate($subject->getTitle());
            $subject->setSlug($slug);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('subject_index');
        }
        return $this->render('subject/edit.html.twig', ["form" => $form->createView(), 'subject' => $subject]);
    }

    /**
     * @Route("/{slug}", name="show")
     */
    public function show(Request $request, Subject $subject): Response
    {
        if ($this->getUser() == '') {
            return $this->redirectToRoute('app_login');
        }
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $entityManager = $this->getDoctrine()->getManager();
            $comment->setsubject($subject);
            $comment->setUser($user);
            $comment->setCommentDate(new GlobalDateTime());
            $user->setContribution($user->getContribution() + 10);
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('subject_show', ['slug' => $subject->getSlug()]);
        }

        return $this->render('subject/show.html.twig', [
            'subject' => $subject,
            'form' => $form->createView()
        ]);
    }
}
