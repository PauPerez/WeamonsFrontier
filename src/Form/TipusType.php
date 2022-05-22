<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\BooleanType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use App\Entity\Tipus;

class TipusType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Nom', TextType::class, ['attr'=>['class'=>'form-control']])
            ->add('debilidad', EntityType::class, array('class' => Tipus::class,
            'choice_label' => 'nom',
            'attr'=>['class'=>'form-select']))
            ->add('fortaleza', EntityType::class, array('class' => Tipus::class,
            'choice_label' => 'nom',
            'attr'=>['class'=>'form-select']))
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