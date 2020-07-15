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

class EventEditType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $trans = new FakeTranslator();

        $builder
            ->add('name', TextType::class, [
                'label' => $trans->trans('admin.event.edit.form.name')
            ])
            ->add('date', DateType::class, [
                'label' => $trans->trans('admin.event.edit.form.date'),
                'widget' => 'single_text',
            ])
            ->add('price', NumberType::class, [
                'label' => $trans->trans('admin.event.edit.form.price'),
            ])
            ->add('preview', CKEditorType::class, [
                'label' => $trans->trans('admin.event.edit.form.preview'),
            ])
            ->add('description', CKEditorType::class, [
                'label' => $trans->trans('admin.event.edit.form.description'),
            ])
            ->add('submit', SubmitType::class, [
                'label' => $trans->trans('admin.event.edit.form.submit'),
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class
        ]);
    }
}