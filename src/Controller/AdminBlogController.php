<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostCreateType;
use App\Form\PostEditType;
use App\Repository\PostRepository;
use App\Util\FakeTranslator;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/blog")
 */
class AdminBlogController extends AbstractController
{
    /**
     * @Route("/posts", name="admin_blog_post_list")
     */
    public function postList(PostRepository $postRepository)
    {
        return $this->render('admin/blog/post/list.html.twig', [
            'posts' => $postRepository->findAll()
        ]);
    }

    /**
     * @Route("/post/create", name="admin_blog_post_create")
     */
    public function postCreate(Request $request, EntityManagerInterface $em)
    {
        $post = new Post();
        $post->setCreated(new DateTime());
        $form = $this->createForm(PostCreateType::class, $post)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($form->getData());
            $em->flush();
            $this->addFlash('success', (new FakeTranslator())->trans('admin.blog.post.create.success'));
            return $this->redirectToRoute('admin_blog_post_list');
        }

        return $this->render('admin/blog/post/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/post/edit/{post}", name="admin_blog_post_edit")
     */
    public function edit(Post $post, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(PostEditType::class, $post)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', (new FakeTranslator())->trans('admin.blog.post.edit.success'));
            return $this->redirectToRoute('admin_blog_post_list');
        }

        return $this->render('admin/blog/post/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/post/remove/{post}", name="admin_blog_post_remove")
     */
    public function remove(Post $post, EntityManagerInterface $em)
    {
        $em->remove($post);
        $em->flush();
        $this->addFlash('success', (new FakeTranslator())->trans('admin.blog.post.remove.success'));
        return $this->redirectToRoute('admin_blog_post_list');
    }
}