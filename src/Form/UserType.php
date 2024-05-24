<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Especialista;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, array(
                'required' => true
            ))
            ->add('password', RepeatedType::class, array(
                'required' => false,
                'type' => PasswordType::class,
                'first_options' => array('label' => 'password'),
                'second_options' => array('label' => 'repetir_password'),
            ))
            ->add('email')
            ->add('role',ChoiceType::class, array(
                'placeholder' => '',
                'required' => true,
                'choices' => array(
                    'ROLE_ADMINISTRADOR' => 'ROLE_ADMINISTRADOR',
                    'ROLE_ESPECIALISTA' => 'ROLE_ESPECIALISTA',
                    'ROLE_J.DEPARTAMENTO' => 'ROLE_J.DEPARTAMENTO',
                    'ROLE_CONSULTOR' => 'ROLE_CONSULTOR',
                ))
            )
            ->add('estado', CheckboxType::class, array('required' => false))
            ->add('especialista', EntityType::class, array(
                'class' => Especialista::class,
                'required' => false))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
