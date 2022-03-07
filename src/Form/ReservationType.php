<?php

namespace App\Form;
use App\Entity\Coin;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            
           
            ->add('date')
            ->add('heure')
            ->add('nb_personnes')
            ->add('coin',EntityType::class,[
                'class'=>Coin::class,
                'choice_label'=>'pays',
                'expanded'=>false,
                'multiple'=>false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
