<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CreerCompteType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('titre', ChoiceType::class, array('required'=>true))
        ->add('nom', TextType::class, array('required'=>true))
        ->add('prenom', TextType::class, array('required'=>true))
        ->add('dateDeNaissance', DateType::class, array('label'=>'Date de naissance', 'required'=>true))
        ;
    }


}
