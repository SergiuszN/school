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
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class PostEditType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $trans = new FakeTranslator();

        $builder
            ->add('name', TextType::class, [
                'label' => $trans->trans('admin.blog.post.edit.form.name'),
                'required' => true,
            ])
            ->add('category', EntityType::class, [
                'class' => PostCategory::class,
                'label' => $trans->trans('admin.blog.post.edit.form.category'),
                'required' => true,
            ])
            ->add('image', TextType::class, [
                'label' => $trans->trans('admin.blog.post.edit.form.image'),
                'required' => true,
            ])
            ->add('isActive', CheckboxType::class, [
                'label' => $trans->trans('admin.blog.post.create.form.isActive'),
            ])
            ->add('preview', CKEditorType::class, [
                'label' => $trans->trans('admin.blog.post.edit.form.preview'),
                'config_name' => 'basic_config',
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('content', CKEditorType::class, [
                'label' => $trans->trans('admin.blog.post.edit.form.content'),
                'config_name' => 'basic_config',
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => $trans->trans('admin.blog.post.edit.form.submit'),
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