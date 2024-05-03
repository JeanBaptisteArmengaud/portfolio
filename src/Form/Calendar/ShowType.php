<?php

namespace App\Form\Calendar;

use App\Entity\Calendar\Movie;
use App\Entity\Calendar\Screen;
use App\Entity\Calendar\Show;
use App\Entity\Calendar\Version;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShowType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('showtime', DateTimeType::class, [
                'label' => 'Date et heure de la séance',
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'input' => 'datetime_immutable',
            ])
            ->add('trailersDuration', DateIntervalType::class, [
                'label'  => 'Durée des bandes-annonces',
                'with_years' => false,
                'with_months' => false,
                'with_days' => false,
                'with_minutes' => true,
                'minutes' => range(0,20),
            ])
            ->add('presentationDuration', DateIntervalType::class, [
                'label'  => 'Durée de la présentation',
                'with_years' => false,
                'with_months' => false,
                'with_days' => false,
                'with_minutes' => true,
                'minutes' => array_combine(range(0,30, 5), range(0,30, 5)),
            ])
            ->add('debateDuration', DateIntervalType::class, [
                'label'  => 'Durée du débat',
                'with_years' => false,
                'with_months' => false,
                'with_days' => false,
                'with_minutes' => true,
                'minutes' => array_combine(range(0,60, 5), range(0,60, 5)),
            ])
//            ->add('movie', EntityType::class, [
//                'label' => 'Film',
//                'class' => Movie::class,
//            ])
            ->add('screen', EntityType::class, [
                'label' => 'Salle',
                'class' => Screen::class,
            ])
            ->add('versions', EntityType::class, [
                'class' => Version::class,
                'multiple' => true,
                'expanded' => true,
                'choice_label' => 'name',
                'label_attr' => ['class' => 'checkbox-inline'],
                'choices' => $options['movie']->getVersions(),
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Show::class,
            'movie' => false,
        ]);
    }

}
