<?php

namespace App\Controller\Admin;

use App\Entity\Tags;
use App\Repository\TagsRepository;
use App\Form\TagsType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("admin/tags", name="admin_tags_")
 */
class TagsController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(TagsRepository $tagsRepository): Response
    {
        return $this->render('admin/tags/index.html.twig', [
            'tags' => $tagsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="new")
     */
    public function new(Request $request): Response
    {
        $tag = new Tags();
        $form = $this->createForm(TagsType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tag);
            $entityManager->flush();

            return $this->redirectToRoute('admin_tags_index');
        }

        return $this->render('admin/tags/new.html.twig', [
            'tag' => $tag,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit")
     */
    public function edit(Request $request, Tags $tag): Response
    {
        $form = $this->createForm(ArticleType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_tags_index');
        }

        return $this->render('admin/tags/edit.html.twig', [
            'tag' => $tag,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Request $request, Tags $tags): Response
    {
        if ($this->isCsrfTokenValid('delete' . $tags->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($tags);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_tags_index');
    }
}
