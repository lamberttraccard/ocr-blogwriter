<?php

namespace BlogWriter\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class EpisodeType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class)
            ->add('subtitle', TextType::class)
            ->add('content', TextareaType::class, array(
                'required' => false,
                'attr'     => array(
                    'class' => 'tinymce'
                )
            ))
            ->add('style', ChoiceType::class, array(
                'choices' => array('2 colonnes' => 1, '1 colonne' => 0)
            ));

    }

    public function getName()
    {
        return 'episode';
    }
}