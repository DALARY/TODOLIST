<?php

namespace App\Form;

use App\Entity\Todo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TodoFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('stillTodo', CheckboxType::class, [
                'label' => "Afficher uniquement les tâches qui restent à faire",
                'mapped' => false,
                'required' => false,
            ])
            ->add('search', SearchType::class, [
                'label' => 'Rechercher',
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Rechercher ...'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Todo::class,
        ]);
    }
}
