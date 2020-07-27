<?php

namespace App\Form;

use App\Entity\EventRegistration;
use App\Util\FakeTranslator;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventRegistrationCreateType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $trans = new FakeTranslator();

        $builder
            ->add('name', TextType::class, [
                'label' => $trans->trans('landing.eventRegistration.create.form.name'),
                'required' => true,
            ])
            ->add('email', EmailType::class, [
                'label' => $trans->trans('landing.eventRegistration.create.form.email'),
                'required' => true,
            ])
            ->add('phone', TextType::class, [
                'label' => $trans->trans('landing.eventRegistration.create.form.phone'),
                'required' => true,
            ])
            ->add('submit', SubmitType::class, [
                'label' => $trans->trans('landing.eventRegistration.create.form.submit'),
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => EventRegistration::class
        ]);
    }
}