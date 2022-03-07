<?php

namespace App\Form;

use App\Entity\Coin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class CoinType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nb_places')
            ->add('pays')
            ->add('img', FileType::class, [
                'label' => false,
                'multiple' => false,
                'mapped' => false,
                'required' => false])
            ->add('description_c')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Coin::class,
        ]);
    }
}
