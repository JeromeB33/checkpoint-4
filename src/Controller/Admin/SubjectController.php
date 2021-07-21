<?php

namespace App\Controller\Admin;

use App\Entity\Subject;
use App\Form\SubjectType;
use App\Repository\SubjectRepository;
use App\Service\Slugify;
use DateTime as GlobalDateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/subject", name="admin_subject_")
 */
class SubjectController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(SubjectRepository $subjectRepository): Response
    {
        return $this->render('admin/subject/index.html.twig', [
            'subjects' => $subjectRepository->findAll()
        ]);
    }

    /**
     * @Route("/new", name="new")
     */
    public function new(Request $request, Slugify $slugify): Response
    {
        $subject = new Subject();
        $form = $this->createForm(SubjectType::class, $subject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugify->generate($subject->getTitle());
            $subject->setSlug($slug);
            $subject->setAuthor($this->getUser());
            $subject->setCreationDate(new GlobalDateTime());
            $subject->setIsValidate(false);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($subject);
            $entityManager->flush();

            return $this->redirectToRoute('admin_subject_index');
            }
        return $this->render('subject/new.html.twig', ["form" => $form->createView()]);
    }

    /**
     * @Route("/edit/{slug}", name="edit")
     */
    public function edit(Request $request,Slugify $slugify, Subject $subject): Response
    {
        $form = $this->createForm(SubjectType::class, $subject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugify->generate($subject->getTitle());
            $subject->setSlug($slug);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('admin_subject_index');
        }
        return $this->render('subject/edit.html.twig', ["form" => $form->createView(), 'subject' => $subject]);
    }

    /**
     * @Route("/{slug}", name="show")
     */
    public function show(Subject $subject): Response
    {
        return $this->render('subject/show.html.twig', [
            'subject' => $subject
        ]);
    }


    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Request $request, Subject $subject): Response
    {
        if ($this->isCsrfTokenValid('delete' . $subject->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($subject);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_article_index');
    }

    /**
     * @Route("/allow/{slug}", name="allow")
     */
    public function allow(Subject $subject): Response
    {
        $subject->setIsValidate(true);
        $subject->getAuthor()->setContribution($subject->getAuthor()->getContribution() + 100);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('admin_subject_index');
    }
}
