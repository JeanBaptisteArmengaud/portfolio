<?php

namespace App\Form\Calendar;

use App\Entity\Calendar\ScreeningSchedule;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ScreeningScheduleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('weekStart', DateType::class, [
                'widget' => 'single_text',
                'input'  => 'datetime_immutable',
                'label' => false,
//                'help' => 'Indiquer la date de début de la semaine'
            ])
            ->add('weekEnd', DateType::class, [
                'widget' => 'single_text',
                'input'  => 'datetime_immutable',
                'label' => false,

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ScreeningSchedule::class,
        ]);
    }
}
