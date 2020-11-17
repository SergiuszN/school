<?php

namespace App\Controller;

use App\Entity\Testimonial;
use App\Repository\TestimonialRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="admin_home")
     */
    public function home()
    {
        return $this->redirectToRoute('admin_event_list');
    }

    /**
     * @Route("/testimonial/list", name="admin_testimonial_list")
     */
    public function testimonialList(TestimonialRepository $repository)
    {
        return $this->render('admin/testimonial/list.html.twig', [
            'testimonials' => $repository->findAllOrderedByDateDesc()
        ]);
    }

    /**
     * @Route("/testimonial/toggle/{testimonial}", name="admin_testimonial_toggle")
     */
    public function testimonialToggle(Testimonial $testimonial, EntityManagerInterface $em)
    {
        $testimonial->setIsActive(!$testimonial->getIsActive());
        $em->flush();

        return $this->redirectToRoute('admin_testimonial_list');
    }

    /**
     * @Route("/testimonial/remove/{testimonial}", name="admin_testimonial_remove")
     */
    public function testimonialRemove(Testimonial $testimonial, EntityManagerInterface $em)
    {
        $em->remove($testimonial);
        $em->flush();

        return $this->redirectToRoute('admin_testimonial_list');
    }

    /**
     * @Route("/testimonial/public/{testimonial}", name="admin_testimonial_public")
     */
    public function testimonialPublic(Testimonial $testimonial, EntityManagerInterface $em)
    {
        $testimonial->setIsActive(true);
        $em->flush();

        return $this->redirectToRoute('landing_testimonials');
    }
}