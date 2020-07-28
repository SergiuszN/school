<?php

namespace App\Form;

use App\Entity\PostCategory;
use App\Util\FakeTranslator;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostCategoryEditType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $trans = new FakeTranslator();

        $builder
            ->add('name', TextType::class, [
                'label' => $trans->trans('admin.blog.category.edit.form.name'),
                'required' => true,
            ])
            ->add('isActive', TextType::class, [
                'label' => $trans->trans('admin.blog.category.edit.form.isActive'),
                'required' => true,
            ])
            ->add('submit', SubmitType::class, [
                'label' => $trans->trans('admin.blog.category.edit.form.submit'),
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PostCategory::class
        ]);
    }
}