<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\EventRegistration;
use App\Form\EventCreateType;
use App\Form\EventEditType;
use App\Repository\EventRepository;
use App\Service\AppMailer;
use App\Util\FakeTranslator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/event')]
class AdminEventController extends AbstractController
{
    #[Route('s', name: 'admin_event_list')]
    public function list(EventRepository $eventRepository): Response
    {
        return $this->render('admin/event/list.html.twig', [
            'events' => $eventRepository->findAll()
        ]);
    }

    #[Route('/create', name: 'admin_event_create')]
    public function create(Request $request, EntityManagerInterface $em): RedirectResponse|Response
    {
        $form = $this->createForm(EventCreateType::class)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($form->getData());
            $em->flush();
            $this->addFlash('success', (new FakeTranslator())->trans('admin.event.create.success'));
            return $this->redirectToRoute('admin_event_list');
        }

        return $this->render('admin/event/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/edit/{event}', name: 'admin_event_edit')]
    public function edit(Event $event, Request $request, EntityManagerInterface $em): RedirectResponse|Response
    {
        $form = $this->createForm(EventEditType::class, $event)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', (new FakeTranslator())->trans('admin.event.edit.success'));
            return $this->redirectToRoute('admin_event_list');
        }

        return $this->render('admin/event/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/remove/{event}', name: 'admin_event_remove')]
    public function remove(Event $event, EntityManagerInterface $em): RedirectResponse
    {
        $em->remove($event);
        $em->flush();
        $this->addFlash('success', (new FakeTranslator())->trans('admin.event.remove.success'));
        return $this->redirectToRoute('admin_event_list');
    }

    #[Route('/subscribers/{event}', name: 'admin_event_subscribers')]
    public function subscribers(Event $event): Response
    {
        return $this->render('admin/event/subscriber_list.html.twig', [
            'subscribers' => $event->getRegistrations()
        ]);
    }

    #[Route('/subscriber/{registration}/payed', name: 'admin_event_subscriber_payed')]
    public function subscriberPayed(EventRegistration $registration, EntityManagerInterface $em, AppMailer $mailer): RedirectResponse
    {
        if ($registration->getStatus() === EventRegistration::STATUS_CONFIRMED) {
            $mailer->sendEventPayedRegistration($registration);

            $registration->setStatus(EventRegistration::STATUS_PAYED);
            $em->flush();
        }

        return $this->redirectToRoute('admin_event_subscribers', ['event' => $registration->getEvent()->getId()]);
    }

    #[Route('/subscriber/{registration}/remove', name: 'admin_event_subscriber_remove')]
    public function subscriberRemove(EventRegistration $registration, EntityManagerInterface $em): RedirectResponse
    {
        $eventId = $registration->getEvent()->getId();

        $em->remove($registration);
        $em->flush();

        return $this->redirectToRoute('admin_event_subscribers', ['event' => $eventId]);
    }

    #[Route('/subscriber/{registration}/resend', name: 'admin_event_subscriber_resend')]
    public function subscriberResendMail(EventRegistration $registration, AppMailer $mailer): RedirectResponse
    {
        switch ($registration->getStatus()) {
            case EventRegistration::STATUS_PAYED:
                $mailer->sendEventPayedRegistration($registration);
                break;
            case EventRegistration::STATUS_CONFIRMED:
                $mailer->sendEventSuccessRegistration($registration);
                break;
            case EventRegistration::STATUS_CREATED:
                $mailer->sendEventConfirmRegistration($registration);
                break;
        }

        return $this->redirectToRoute('admin_event_subscribers', ['event' => $registration->getEvent()->getId()]);
    }
}