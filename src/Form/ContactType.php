<?php

namespace App\Form;

use App\DTO\ContactDTO;
use App\Util\FakeTranslator;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $trans = new FakeTranslator();

        $builder
            ->add('name', TextType::class, [
                'label' => $trans->trans('landing.contact.form.name'),
                'required' => true,
            ])
            ->add('email', EmailType::class, [
                'label' => $trans->trans('landing.contact.form.email'),
                'required' => true,
            ])
            ->add('question', TextareaType::class, [
                'label' => $trans->trans('landing.contact.form.question'),
                'required' => true,
            ])
            ->add('submit', SubmitType::class, [
                'label' => $trans->trans('landing.contact.form.submit'),
                'attr' => [
                    'class' => 'btn btn-primary btn-block',
                ],
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ContactDTO::class
        ]);
    }
}