<?php

namespace App\Form;

use App\Entity\Matches;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MatchesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('equipe_locale')
            ->add('domicile_exterieur')
            ->add('equipe_adverse')
            ->add('hote')
            ->add('date_heure')
            ->add('num_semaine')
            ->add('num_journee')
            ->add('gymnase')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Matches::class,
        ]);
    }
}
