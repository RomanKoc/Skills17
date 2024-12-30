<?php

namespace App\Form;

use App\Entity\Comentario;
use App\Entity\Experiencia;
use App\Entity\Usuario;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ComentarioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('texto')
            ->add('fecha')
            ->add('usuario', EntityType::class, [
                'class' => Usuario::class,
'choice_label' => 'id',
            ])
            ->add('experiencia', EntityType::class, [
                'class' => Experiencia::class,
'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comentario::class,
        ]);
    }
}
