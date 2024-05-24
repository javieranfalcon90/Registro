<?php

namespace App\Form;

use App\Entity\Vale;
use App\Entity\Solicitud;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Doctrine\ORM\EntityRepository;

class ValeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {


        $builder
            ->add('vale')
            ->add('fechavale', DateTimeType::class, [
            'format' => 'dd-MM-yyyy',
            'widget' => 'single_text',
            'html5' => false
            ])
            ->add('solicitudes', EntityType::class, [
                'class' => Solicitud::class,
                'multiple' => true,
                'by_reference' => false,
                'query_builder' => function (EntityRepository $er) use ($options) {
                    $vale = $options['vale'];
                    return $er->createQueryBuilder('s')
                    ->where('(s.vale IS null) or (s.vale = :vale)')
                    ->setParameter('vale', $vale)

                    ->andwhere('s.estado = :estado')->setParameter('estado', 'En Archivo')
                    ;
                },
            ])
            ->add('factura')
            ->add('fechafactura', DateTimeType::class, [
            'format' => 'dd-MM-yyyy',
            'widget' => 'single_text',
            'html5' => false
            ])
            ->add('observaciones')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vale::class,
            'vale' => null
        ]);
    }
}
