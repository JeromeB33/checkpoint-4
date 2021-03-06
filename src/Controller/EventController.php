<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use App\Repository\UserRepository;
use App\Service\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/event", name="event_")
 */
class EventController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(EventRepository $eventRepository, UserRepository $userRepository): Response
    {
        if ($this->getUser() == '') {
            return $this->redirectToRoute('app_login');
        }
        $events = $eventRepository->findBy([], ['date' => 'ASC']);
        return $this->render('event/index.html.twig', [
            'users' => $userRepository->findBy([], ['contribution' => 'DESC'], 5),
            'events' => $events
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
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugify->generate($event->getTitle());
            $event->setSlug($slug);
            $event->setCreator($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('event_index');
        }

        return $this->render('event/new.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}", name="show")
     */
    public function show(Event $event): Response
    {
        if ($this->getUser() == '') {
            return $this->redirectToRoute('app_login');
        }
        return $this->render('event/show.html.twig', [
            'event' => $event
        ]);
    }

    /**
     * @Route("/edit/{slug}", name="edit")
     */
    public function edit(Request $request, Event $event, Slugify $slugify): Response
    {
        if ($this->getUser() == '') {
            return $this->redirectToRoute('app_login');
        }
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugify->generate($event->getTitle());
            $event->setSlug($slug);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('event_index');
        }

        return $this->render('event/new.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Request $request, Event $event): Response
    {
        if ($this->isCsrfTokenValid('delete' . $event->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($event);
            $entityManager->flush();
        }

        return $this->redirectToRoute('event_index');
    }

    /**
     * @Route("/join/{slug}", name="join")
     */
    public function join(Event $event): Response
    {
        if(count($event->getPlayers()) < $event->getPlayerSlot()){
            $manager = $this->getDoctrine()->getManager();
            $event->addPlayer($this->getUser());
            $this->getUser()->setContribution($this->getUser()->getContribution() + 10);
            $manager->flush();
        }

        return $this->redirectToRoute('event_show', ['slug' => $event->getSlug()]);
    }

    /**
     * @Route("/leave/{slug}", name="leave")
     */
    public function leave(Event $event): Response
    {
            $manager = $this->getDoctrine()->getManager();
            $event->removePlayer($this->getUser());
            $this->getUser()->setContribution($this->getUser()->getContribution() - 10);
            $manager->flush();

        return $this->redirectToRoute('event_show', ['slug' => $event->getSlug()]);
    }
}
