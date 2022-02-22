<?php

namespace App\Form;

use App\Entity\Voyage;
use App\Entity\Offre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\OptionsResolver\OptionsResolver;


use Symfony\Component\Form\Extension\Core\Type\FileType;


class VoyageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id_u')
            ->add('offre',EntityType::class,[
                'class'=>Offre::class,
                'choice_label'=>'titre',
                'expanded'=>false,
                'multiple'=>false
            ])
            ->add('date_dep', DateType::class)
            ->add('heure_dep')
            ->add('destination')
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Voyage::class,
        ]);
    }
}
