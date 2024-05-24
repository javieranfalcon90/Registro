<?php

namespace App\Form;

use App\Entity\Solicitud;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;


class SolicitudType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('iscd')
            ->add('tipoproducto')
            ->add('tipotramite')
            ->add('codigo') 
            ->add('producto')
            ->add('fechaentrada', DateTimeType::class, [
            'format' => 'dd-MM-yyyy',
            'widget' => 'single_text',
            'html5' => false,
            'empty_data' => null
            ])

            /*Medicamento y Biologico*/
            ->add('ff')
            ->add('ifa')
            ->add('fortaleza')
            ->add('categoria')
            ->add('parteaevaluar')

            /*Diagnosticadores y Equipos Medicos*/
            ->add('clasederiesgo')

            /*Diagnosticadores*/
            ->add('denominacion')


            ->add('solicitante')
            ->add('paissolicitante')
            ->add('fabricante')
            ->add('paisfabricante')
            ->add('personacontacto')

            ->add('muestra')

            ->add('observaciones')            
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Solicitud::class,
        ]);
    }
}
