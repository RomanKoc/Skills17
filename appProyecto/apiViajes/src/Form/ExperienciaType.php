<?php

namespace App\Form;

use App\Entity\Experiencia;
use App\Entity\Localizacion;
use App\Entity\Subcategoria;
use App\Entity\Usuario;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExperienciaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titulo')
            ->add('texto')
            ->add('puntuacion')
            ->add('fecha')
            ->add('usuario', EntityType::class, [
                'class' => Usuario::class,
'choice_label' => 'id',
            ])
            ->add('localizacion', EntityType::class, [
                'class' => Localizacion::class,
'choice_label' => 'id',
            ])
            ->add('subcategoria', EntityType::class, [
                'class' => Subcategoria::class,
'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Experiencia::class,
        ]);
    }
}
