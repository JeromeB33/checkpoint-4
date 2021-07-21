<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/profile", name="user_")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="show")
     */
    public function show(): Response
    {
        return $this->render('user/show.html.twig');
    }

    /**
     * @Route("/edit", name="edit")
     */
    public function edit(Request $request): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('user_show');
        }
    return $this->render('user/edit.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("delete/{id}", name="delete")
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('home');
    }
}
