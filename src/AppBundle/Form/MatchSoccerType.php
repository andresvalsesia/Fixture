<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class MatchSoccerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // $builder->getData() => Accede a la data representada por el formulario

        $builder
            ->add(
                "datematch",
                DateTimeType::class,
                [
                    'widget'        => "choice",
                    'format'        => "yyyy-MM-dd HH:mm:ss",
                ]
            )
            ->add(
                "home",
                EntityType::class,
                [
                    'disabled'      => $builder->getData() && $builder->getData()->getId(),
                    'class'         => "AppBundle\Entity\Team",
                    'choice_label'  => "name",
                    'placeholder'   => "Seleccione equipo local",
                    'query_builder' => function($er)
                    {
                        return $er->createQueryBuilder("t")
                            ->orderBy("t.name", "ASC");
                    }
                ]
            )
            ->add(
                "visitor",
                EntityType::class,
                [
                    'disabled'      => $builder->getData() && $builder->getData()->getId(),
                    'class'         => "AppBundle\Entity\Team",
                    'choice_label'  => "name",
                    'placeholder'   => "Seleccione equipo visitante",
                    'query_builder' => function($er)
                    {
                        return $er->createQueryBuilder('t')
                            ->orderBy('t.name', 'ASC');
                    }
                ]
            )
            ->add(
                "goalsHome",
                IntegerType::class,
                [
                    'attr'          => ['min' => 0],
                    'required'      => false
                ]
            )
            ->add(
                "goalsVisitor",
                IntegerType::class,
                [
                    'attr'          => ['min' => 0],
                    'required'      => false
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => "AppBundle\Entity\MatchSoccer"
        ]);
    }

    public function getBlockPrefix()
    {
        return 'appbundle_matchsoccer';
    }
}