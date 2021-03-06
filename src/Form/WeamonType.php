<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use App\Entity\Weamon;
use App\Entity\Moviment;
use App\Entity\Tipus;

class WeamonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Tipus', EntityType::class, array('class' => Tipus::class,
            'choice_label' => 'nom',
            'attr'=>['attr'=>'form-select']))
            ->add('Nom', TextType::class, ['attr'=>['class'=>'form-control']])
            ->add('Vida', IntegerType::class, ['attr'=>['class'=>'form-control']])
            ->add('Atac', IntegerType::class, ['attr'=>['class'=>'form-control']])
            ->add('Velocitat', IntegerType::class, ['attr'=>['class'=>'form-control']])
            ->add('Shiny', CheckboxType::class, [
                'label'    => 'es shiny?',
                'required' => false,
                'attr'=>['class'=>'form-check']
            ])
            ->add('nEvolucion', ChoiceType::class, [
                'choices'  => [
                    '1' => "1",
                    '2' => "2",
                    '3' => '3'
                ],
                'attr'=>['class'=>'form-select']
            ])
            ->add('Img', FileType::class, [
                'label' => 'Sprite del weamon',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg'
                        ],
                        'mimeTypesMessage' => "Nom??s s'accepten imatges png/jpeg de 1024k o menys",
                    ])
                ],
            ])
            ->add('ImgB', FileType::class, [
                'label' => 'Back sprite del weamon',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg'
                        ],
                        'mimeTypesMessage' => "Nom??s s'accepten imatges png/jpeg de 1024k o menys",
                    ])
                ],
            ])
            ->add('Moviments', EntityType::class, array('class' => Moviment::class,
            'choice_label' => 'nom',
            'multiple' => true))
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