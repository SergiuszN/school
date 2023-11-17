<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\PostCategory;
use App\Form\PostCategoryCreateType;
use App\Form\PostCategoryEditType;
use App\Form\PostCreateType;
use App\Form\PostEditType;
use App\Repository\PostCategoryRepository;
use App\Repository\PostRepository;
use App\Util\FakeTranslator;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/blog')]
class AdminBlogController extends AbstractController
{
    #[Route('/posts', name: 'admin_blog_post_list')]
    public function postList(PostRepository $postRepository): Response
    {
        return $this->render('admin/blog/post/list.html.twig', [
            'posts' => $postRepository->findAll()
        ]);
    }

    #[Route('/post/toggle/{post}', name: 'admin_blog_post_toggle')]
    public function postToggle(Post $post, EntityManagerInterface $em): RedirectResponse
    {
        $post->setIsActive(!$post->getIsActive());
        $em->flush();
        return $this->redirectToRoute('admin_blog_post_list');
    }

    #[Route('/post/create', name: 'admin_blog_post_create')]
    public function postCreate(Request $request, EntityManagerInterface $em): RedirectResponse|Response
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

    #[Route('/post/edit/{post}', name: 'admin_blog_post_edit')]
    public function postEdit(Post $post, Request $request, EntityManagerInterface $em): RedirectResponse|Response
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

    #[Route('/post/remove/{post}', name: 'admin_blog_post_remove')]
    public function postRemove(Post $post, EntityManagerInterface $em): RedirectResponse
    {
        $em->remove($post);
        $em->flush();
        $this->addFlash('success', (new FakeTranslator())->trans('admin.blog.post.remove.success'));
        return $this->redirectToRoute('admin_blog_post_list');
    }

    #[Route('/categories', name: 'admin_blog_category_list')]
    public function categoryList(PostCategoryRepository $postCategoryRepository): Response
    {
        return $this->render('admin/blog/category/list.html.twig', [
            'categories' => $postCategoryRepository->findAll()
        ]);
    }

    #[Route('/category/toggle/{category}', name: 'admin_blog_category_toggle')]
    public function categoryToggle(PostCategory $category, EntityManagerInterface $em): RedirectResponse
    {
        $category->setIsActive(!$category->getIsActive());
        $em->flush();
        return $this->redirectToRoute('admin_blog_category_list');
    }

    #[Route('/category/create', name: 'admin_blog_category_create')]
    public function categoryCreate(Request $request, EntityManagerInterface $em): RedirectResponse|Response
    {
        $form = $this->createForm(PostCategoryCreateType::class, new PostCategory())
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($form->getData());
            $em->flush();
            $this->addFlash('success', (new FakeTranslator())->trans('admin.blog.category.create.success'));
            return $this->redirectToRoute('admin_blog_category_list');
        }

        return $this->render('admin/blog/category/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/category/edit/{category}', name: 'admin_blog_category_edit')]
    public function categoryEdit(PostCategory $category, Request $request, EntityManagerInterface $em): RedirectResponse|Response
    {
        $form = $this->createForm(PostCategoryEditType::class, $category)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', (new FakeTranslator())->trans('admin.blog.category.edit.success'));
            return $this->redirectToRoute('admin_blog_category_list');
        }

        return $this->render('admin/blog/category/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/category/remove/{category}', name: 'admin_blog_category_remove')]
    public function categoryRemove(PostCategory $category, EntityManagerInterface $em): RedirectResponse
    {
        if ($category->getPosts()->count() > 0) {
            $this->addFlash('danger', (new FakeTranslator())->trans('admin.blog.category.remove.errorCategoryHavePosts'));
            return $this->redirectToRoute('admin_blog_category_list');
        }

        $em->remove($category);
        $em->flush();
        $this->addFlash('success', (new FakeTranslator())->trans('admin.blog.category.remove.success'));
        return $this->redirectToRoute('admin_blog_category_list');
    }
}