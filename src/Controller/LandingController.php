<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\EventRegistration;
use App\Entity\Post;
use App\Entity\PostCategory;
use App\Entity\Testimonial;
use App\Form\AddTestimonialType;
use App\Form\ContactType;
use App\Form\EventRegistrationCreateType;
use App\Repository\EventRepository;
use App\Repository\PostCategoryRepository;
use App\Repository\PostRepository;
use App\Security\Voters\EventVoter;
use App\Service\AppMailer;
use App\Service\TelegramNotify;
use App\Util\FakeTranslator;
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
    public function register(Event $event, EntityManagerInterface $em, Request $request, AppMailer $mailer)
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

                $mailer->sendEventConfirmRegistration($registration);
            }

            return $this->redirectToRoute('landing_thank_you');
        }

        return $this->render('landing/register.html.twig', [
            'form' => $form->createView(),
            'event' => $event
        ]);
    }

    /**
     * @Route("/thank-you", name="landing_thank_you")
     */
    public function thankYou()
    {
        return $this->render('landing/thank_you.html.twig');
    }

    /**
     * @Route("/confirm-registration/{eventRegistration}-{token}", name="landing_confirm_registration")
     */
    public function confirmRegistration(EventRegistration $eventRegistration, $token, EntityManagerInterface $em, AppMailer $mailer, TelegramNotify $telegramNotify)
    {
        if (!$eventRegistration->isValidToken($token)) {
            return $this->redirectToRoute('landing_home');
        }

        if ($eventRegistration->getStatus() === EventRegistration::STATUS_CREATED) {
            $mailer->sendEventSuccessRegistration($eventRegistration);

            $eventRegistration->setStatus(EventRegistration::STATUS_CONFIRMED);
            $em->flush();

            $telegramNotify->sendRegistrationNotify($eventRegistration);
        }

        $this->addFlash('success', (new FakeTranslator())->trans('landing.confirmRegistration.flash.success'));
        return $this->redirectToRoute('landing_home');
    }

    /**
     * @Route("/contact", name="landing_contact")
     */
    public function contact(Request $request, AppMailer $mailer)
    {
        $form = $this->createForm(ContactType::class)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mailer->sendContact($form->getData());
            $this->addFlash('success', (new FakeTranslator())->trans('landing.contact.flash.success'));
            return $this->redirectToRoute('landing_home');
        }

        return $this->render('landing/contact.html.twig', [
            'form' => $form->createView()
        ]);
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
        if (!$this->getUser() && !$post->getIsActive()) {
            throw $this->createNotFoundException();
        }

        return $this->render('landing/post.html.twig', [
            'post' => $post
        ]);
    }

    /**
     * @Route("/testimonials/{page}", name="landing_testimonials", defaults={"page" = 1}, requirements={"page" = "\d+"})
     */
    public function testimonials(int $page, Request $request, PaginatorInterface $paginator, EntityManagerInterface $em, TelegramNotify $telegramNotify)
    {
        $form = $this->createForm(AddTestimonialType::class)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Testimonial $testimonial */
            $testimonial = $form->getData();
            $em->persist($testimonial);
            $em->flush();

            $telegramNotify->sendTestimonialNotify($testimonial);

            $this->addFlash('success', (new FakeTranslator())->trans('landing.testimonial.flash.success'));
            return $this->redirectToRoute('landing_home');
        }

        return $this->render('landing/testimonials.html.twig', [
            'form' => $form->createView(),
            'page' => $paginator->paginate(
                $em->getRepository(Testimonial::class)->findLandingKnp(), $page, Testimonial::MAX_PER_PAGE_LANDING
            ),
        ]);
    }
}