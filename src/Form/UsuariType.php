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
            ->add('username', TextType::class)
            ->add('email', TextType::class)
            ->add('password', PasswordType::class)
            ->add('img', FileType::class, [
                'label' => 'Imatge de perfil',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg'
                        ],
                        'mimeTypesMessage' => "Només s'accepten imatges png/jpeg de 1024k o menys",
                    ])
                ],
            ])
            ->add('Roles', ChoiceType::class, [
                'choices'  => [
                    'Admin' => "ROLE_ADMIN",
                    'Usuari' => "ROLE_USER",
                ],
                'multiple' => true,
            ])
            ->add('is_verified', CheckboxType::class, [
                'label'    => 'verificar el usuario?',
                'required' => false,
            ])
            ->add('save', SubmitType::class, array('label' => $options['submit']))
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