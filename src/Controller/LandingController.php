<?php

namespace App\Controller;

use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @Route("/about", name="landing_about")
     */
    public function about()
    {
        return $this->render('landing/about.html.twig');
    }
}