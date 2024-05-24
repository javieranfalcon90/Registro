<?php

namespace App\Form;

use App\Entity\Ls;
use App\Entity\Solicitud;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Doctrine\ORM\EntityRepository;

class LsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numero')
            ->add('fecha', DateTimeType::class, [
            'format' => 'dd-MM-yyyy',
            'widget' => 'single_text',
            'html5' => false
            ])
            ->add('solicitudes', EntityType::class, [
                'class' => Solicitud::class,
                'multiple' => true,
                'by_reference' => false,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                    ->where('(s.ls IS null)')

                    ->andwhere('s.pagado = :estado')->setParameter('estado', true)
                    ;
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ls::class,
        ]);
    }
}
