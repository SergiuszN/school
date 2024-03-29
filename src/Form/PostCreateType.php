<?php

namespace App\Form;

use App\Entity\Post;
use App\Entity\PostCategory;
use App\Util\FakeTranslator;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class PostCreateType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $trans = new FakeTranslator();

        $builder
            ->add('name', TextType::class, [
                'label' => $trans->trans('admin.blog.post.create.form.name'),
                'required' => true,
            ])
            ->add('description', TextareaType::class, [
                'label' => $trans->trans('admin.blog.post.create.form.description'),
                'attr' => [
                    'maxlength' => 160,
                ],
                'required' => true,
            ])
            ->add('category', EntityType::class, [
                'class' => PostCategory::class,
                'label' => $trans->trans('admin.blog.post.create.form.category'),
                'required' => true,
            ])
            ->add('image', TextType::class, [
                'label' => $trans->trans('admin.blog.post.create.form.image'),
                'attr' => [
                    'class' => 'form-control control-file-manager'
                ],
                'required' => false,
            ])
            ->add('isActive', CheckboxType::class, [
                'label' => $trans->trans('admin.blog.post.create.form.isActive'),
                'required' => false,
            ])
            ->add('preview', CKEditorType::class, [
                'label' => $trans->trans('admin.blog.post.create.form.preview'),
                'config_name' => 'basic_config',
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('content', CKEditorType::class, [
                'label' => $trans->trans('admin.blog.post.create.form.content'),
                'config_name' => 'basic_config',
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => $trans->trans('admin.blog.post.create.form.submit'),
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class
        ]);
    }
}