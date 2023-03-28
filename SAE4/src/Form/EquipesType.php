<?php

namespace App\Form;

use App\Entity\Equipes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EquipesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle')
            ->add('entraineur')
            ->add('creneaux')
            ->add('url_photo')
            ->add('url_result_calendrier')
            ->add('commentaire')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Equipes::class,
        ]);
    }
}
