<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Entity\Equip;
use App\Entity\Weamon;
use App\Entity\Usuari;

class EquipType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Usuari', EntityType::class, array('class' => Usuari::class,
            'choice_label' => 'username',
            'attr'=>['class'=>'form-select']))
            ->add('Weamons', EntityType::class, array('class' => Weamon::class,
            'choice_label' => 'nom',
            'multiple' => true,
            'attr'=>['class'=>'form-select']))
            ->add('save', SubmitType::class, array('label' => $options['submit'], 'attr'=>['class'=>'btn btn-primary']))
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