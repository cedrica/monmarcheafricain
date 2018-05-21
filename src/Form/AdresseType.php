<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AdresseType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('pays', TextType::class, array('required'=>true,'attr' => array('class' => 'form-control')))
        ->add('ville', TextType::class, array('required'=>true,'attr' => array('class' => 'form-control')))
        ->add('boitePostale', NumberType::class, array('required'=>true,'attr' => array('class' => 'form-control')))
        ->add('rueEtNr', TextType::class, array('required'=>true,'attr' => array('class' => 'form-control')))
        ;
    }


}
