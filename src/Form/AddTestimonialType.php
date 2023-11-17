<?php

namespace App\Form;

use App\Entity\Testimonial;
use App\Util\FakeTranslator;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddTestimonialType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $trans = new FakeTranslator();

        $builder
            ->add('rating', HiddenType::class, [
                'label' => $trans->trans('landing.testimonial.form.rating'),
            ])
            ->add('name', TextType::class, [
                'label' => $trans->trans('landing.testimonial.form.name'),
            ])
            ->add('content', TextareaType::class, [
                'label' => $trans->trans('landing.testimonial.form.content'),
                'attr' => [
                    'rows' => '5',
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => $trans->trans('landing.testimonial.form.submit'),
                'attr' => [
                    'class' => 'btn-primary btn btn-block'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Testimonial::class,
        ]);
    }
}
