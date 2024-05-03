<?php

namespace App\Form\Calendar;

use App\Entity\Calendar\Movie;
use App\Entity\Calendar\Version;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MovieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('duration', DateIntervalType::class, [
                'label' => 'Durée du film',
                'with_years' => false,
                'with_months' => false,
                'with_days' => false,
                'with_hours' => true,
                'with_minutes' => true,
                'hours' => range(0, 5),
                'minutes' => range(0, 59),
            ])
            ->add('releaseDate', DateType::class, [
                'widget' => 'single_text',
                'input'  => 'datetime_immutable',
            ])
            ->add('poster', UrlType::class, [

            ])
            ->add('screeningSchedules', CollectionType::class, [
                'entry_type' => ScreeningScheduleType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
            ->add('versions', EntityType::class, [
                'class' => Version::class,
                'multiple' => true,
                'expanded' => true,
                'choice_label' => 'name',
            ])


        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Movie::class,
        ]);
    }
}
