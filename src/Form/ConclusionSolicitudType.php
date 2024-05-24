<?php

namespace App\Form;

use App\Entity\Solicitud;
use App\Entity\Conclusion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ConclusionSolicitudType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('estado', ChoiceType::class, [
                'choices'  => [
                    'Concluido' => 'Concluido',
                    'CD' => 'CD',
                ],
                'expanded' => true,
                'multiple' => false
            ])
            ->add('fechacierre', DateTimeType::class, [
            'format' => 'dd-MM-yyyy',
            'widget' => 'single_text',
            'html5' => false,
            'empty_data' => null
            ])
            ->add('conclusiones', EntityType::class, [
                'class' => Conclusion::class,
                'multiple' => true,
                'required' => false,
                'by_reference' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Solicitud::class,
        ]);
    }
}
