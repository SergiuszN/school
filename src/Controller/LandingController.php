<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\EventRegistration;
use App\Entity\Post;
use App\Entity\PostCategory;
use App\Form\EventRegistrationCreateType;
use App\Repository\EventRepository;
use App\Repository\PostCategoryRepository;
use App\Repository\PostRepository;
use App\Security\Voters\EventVoter;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LandingController extends AbstractController
{
    /**
     * @Route("/", name="landing_home")
     */
    public function home(EventRepository $eventRepository)
    {
        return $this->render('landing/home.html.twig', [
            'events' => $eventRepository->findUpcomings()
        ]);
    }

    /**
     * @Route("/event-register/{event}", name="landing_register")
     */
    public function register(Event $event, EntityManagerInterface $em, Request $request)
    {
        if (!$this->isGranted(EventVoter::REGISTER, $event)) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(EventRegistrationCreateType::class)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var EventRegistration $registration */
            $registration = $form->getData();

            if (!$em->getRepository(EventRegistration::class)->isRegistered($registration->getEmail(), $event)) {
                $registration->setEvent($event);
                $registration->setCreated(new DateTime());
                $registration->setStatus(EventRegistration::STATUS_CREATED);

                $em->persist($registration);
                $em->flush();
            }

            return $this->redirectToRoute('landing_thank_you', ['event' => $event->getId()]);
        }

        return $this->render('landing/register.html.twig', [
            'form' => $form->createView(),
            'event' => $event
        ]);
    }

    /**
     * @Route("/thank-you/{event}", name="landing_thank_you")
     */
    public function thankYou(Event $event)
    {
        if (!$this->isGranted(EventVoter::REGISTER, $event)) {
            throw $this->createNotFoundException();
        }

        return $this->render('landing/thank_you.html.twig', [
            'event' => $event
        ]);
    }

    /**
     * @Route("/contact", name="landing_contact")
     */
    public function contact()
    {
        return $this->render('landing/contact.html.twig');
    }

    /**
     * @Route("/tac", name="landing_tac")
     */
    public function tac()
    {
        return $this->render('landing/tac.html.twig');
    }

    /**
     * @Route("/pp", name="landing_pp")
     */
    public function pp()
    {
        return $this->render('landing/pp.html.twig');
    }

    /**
     * @Route("/about", name="landing_about")
     */
    public function about()
    {
        return $this->render('landing/about.html.twig');
    }

    /**
     * @Route("/blog/{page}", name="landing_blog", defaults={"page" = 1}, requirements={"page" = "\d+"})
     */
    public function blog($page, PaginatorInterface $paginator, PostRepository $postRepository, PostCategoryRepository $postCategoryRepository)
    {
        return $this->render('landing/blog.html.twig', [
            'categories' => $postCategoryRepository->findAllActive(),
            'latest' => $postRepository->findLatest(),
            'page' => $paginator->paginate($postRepository->findLandingKnp(), $page, Post::MAX_PER_PAGE_LANDING),
        ]);
    }

    /**
     * @Route("/blog/category/{category}/{page}", name="landing_blog_category", defaults={"page" = 1}, requirements={"category" = "\d+", "page" = "\d+"})
     */
    public function blogCategory(PostCategory $category, $page, PaginatorInterface $paginator, PostRepository $postRepository, PostCategoryRepository $postCategoryRepository)
    {
        if (!$category->getIsActive()) {
            throw $this->createNotFoundException();
        }

        return $this->render('landing/blog.html.twig', [
            'categories' => $postCategoryRepository->findAllActive(),
            'latest' => $postRepository->findLatest(),
            'page' => $paginator->paginate($postRepository->findLandingKnpWithCategory($category), $page, Post::MAX_PER_PAGE_LANDING),
        ]);
    }

    /**
     * @Route("/blog/post/{post}", name="landing_blog_post", requirements={"post" = "\d+"})
     */
    public function blogPost(Post $post)
    {
        if (!$post->getIsActive()) {
            throw $this->createNotFoundException();
        }

        return $this->render('landing/post.html.twig', [
            'post' => $post
        ]);
    }
}