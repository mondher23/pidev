<?php

namespace App\Form;

use App\Entity\Plat;
use App\Entity\Coin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class PlatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            
            ->add('nom_p')
            ->add('prix')
            ->add('img_p', FileType::class, [
                'label' => false,
                'multiple' => false,
                'mapped' => false,
                'required' => false])
            ->add('description')
            ->add('dispo')
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
            'data_class' => Plat::class,
        ]);
    }
}
