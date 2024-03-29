<?php

namespace App\Form;

use App\Entity\Event;
use App\Util\FakeTranslator;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class EventCreateType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $trans = new FakeTranslator();

        $builder
            ->add('name', TextType::class, [
                'label' => $trans->trans('admin.event.create.form.name'),
                'required' => true,
            ])
            ->add('date', DateType::class, [
                'label' => $trans->trans('admin.event.create.form.date'),
                'widget' => 'single_text',
                'required' => true,
            ])
            ->add('price', NumberType::class, [
                'label' => $trans->trans('admin.event.create.form.price'),
                'required' => true,
            ])
            ->add('invoice', TextType::class, [
                'label' => $trans->trans('admin.event.create.form.invoice'),
                'attr' => [
                    'class' => 'form-control control-file-manager'
                ],
                'required' => true,
            ])
            ->add('program', TextType::class, [
                'label' => $trans->trans('admin.event.create.form.program'),
                'attr' => [
                    'class' => 'form-control control-file-manager'
                ],
                'required' => true,
            ])
            ->add('preview', CKEditorType::class, [
                'label' => $trans->trans('admin.event.create.form.preview'),
                'config_name' => 'basic_config',
                'required' => true,
                'constraints'=>[
                    new NotBlank(),
                ]
            ])
            ->add('description', CKEditorType::class, [
                'label' => $trans->trans('admin.event.create.form.description'),
                'config_name' => 'basic_config',
                'required' => true,
                'constraints'=>[
                    new NotBlank(),
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => $trans->trans('admin.event.create.form.submit'),
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class
        ]);
    }
}