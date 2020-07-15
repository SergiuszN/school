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
}