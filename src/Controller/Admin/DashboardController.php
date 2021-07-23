<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use App\Repository\EventRepository;
use App\Repository\SubjectRepository;
use App\Repository\UserRepository;
use App\Entity\Subject;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dashboard", name="dashboard_")
 */
class DashboardController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(EventRepository $eventRepository,
    UserRepository $userRepository,
    SubjectRepository $subjectRepository,
    CommentRepository $commentRepository
    ): Response
    {
        return $this->render('admin/dashboard.html.twig', [
            'reportcom' => $commentRepository->findBy(['reported' => true]),
            'newsubject' => $subjectRepository->findBy(['validate' => false]),
            'events' => $eventRepository->findAll(),
            'users' => $userRepository->findAll(),
            'subjects' => $subjectRepository->findAll()
        ]);
    }

    /**
     * @Route("/subject/validation", name="validation")
     */
    public function validationPage(EventRepository $eventRepository,
    UserRepository $userRepository,
    SubjectRepository $subjectRepository,
    CommentRepository $commentRepository
    ): Response
    {

        return $this->render('admin/subject/index.html.twig', [
            'reportcom' => $commentRepository->findBy(['reported' => true]),
            'newsubject' => $subjectRepository->findBy(['validate' => false]),
            'events' => $eventRepository->findAll(),
            'users' => $userRepository->findAll(),
            'subjects' => $subjectRepository->findAll()
        ]);
    }

    /**
     * @Route("/subject/{slug}", name="subject_show")
     */
    public function showSubject(Subject $subject,
    EventRepository $eventRepository,
    UserRepository $userRepository,
    SubjectRepository $subjectRepository,
    CommentRepository $commentRepository
    ): Response
    {
        return $this->render('admin/subject/show.html.twig', [
            'subject' => $subject,
            'reportcom' => $commentRepository->findBy(['reported' => true]),
            'newsubject' => $subjectRepository->findBy(['validate' => false]),
            'events' => $eventRepository->findAll(),
            'users' => $userRepository->findAll(),
            'subjects' => $subjectRepository->findAll()
        ]);
    }

    /**
     * @Route("/comment", name="report")
     */
    public function commentReport(EventRepository $eventRepository,
    UserRepository $userRepository,
    SubjectRepository $subjectRepository,
    CommentRepository $commentRepository
    ): Response
    {
        return $this->render('admin/comment/index.html.twig', [
            'reportcom' => $commentRepository->findBy(['reported' => true]),
            'newsubject' => $subjectRepository->findBy(['validate' => false]),
            'events' => $eventRepository->findAll(),
            'users' => $userRepository->findAll(),
            'subjects' => $subjectRepository->findAll()
        ]);
    }
}
