<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;


use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use App\Entity\Usuari;

class UsuariType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, ['attr'=>['class'=>'form-control']])
            ->add('email', TextType::class, ['attr'=>['class'=>'form-control']])
            ->add('password', PasswordType::class, ['attr'=>['class'=>'form-control']])
            ->add('Roles', ChoiceType::class, [
                'choices'  => [
                    'Admin' => "ROLE_ADMIN",
                    'Usuari' => "ROLE_USER",
                ],
                'multiple' => true,
                'attr'=>['class'=>'form-select']
            ])
            ->add('is_verified', CheckboxType::class, [
                'label'    => 'verificar el usuario?',
                'required' => false,
                'attr'=>['class'=>'form-check']
            ])
            ->add('save', SubmitType::class, array('label' => $options['submit'],'attr'=>['class'=>'btn btn-primary']))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
            'submit' => 'Enviar',
        ]);
    }

}