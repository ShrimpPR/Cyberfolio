<?php

namespace App\Form;

use App\Entity\Project;
use App\Entity\Technology;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Project Title',
            ])
            ->add('created_at', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Creation Date',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Project Description',
            ])
            ->add('screenshot', FileType::class, [
                'label' => 'Screenshot (Image)',
                'required' => false,
                'mapped' => false,
                'attr' => ['accept' => 'image/*']
            ])
            ->add('url', TextType::class, [
                'label' => 'Project URL',
                'required' => false,
            ])
            ->add('users', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'firstname',
                'label' => 'Users',
                'multiple' => true,
                'expanded' => true,
            ]);
//            ->add('technology', EntityType::class, [
//                'class' => Technology::class,
//                'choice_label' => 'name',
//                'placeholder' => 'Select a Technology',
//            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
