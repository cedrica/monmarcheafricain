<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreerCompteType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$translator = $options['translator'];
        $builder
        ->add('titre', ChoiceType::class, array('required'=>true,
        		'label' => $translator->trans('mma.profil.title')
        ))
        ->add('nom', TextType::class, array('required'=>true,
        		'label' => $translator->trans('mma.profil.firstname')
        ))
        ->add('prenom', TextType::class, array('required'=>true,
        		'label' => $translator->trans('mma.profil.lastname')
        ))
        ->add('dateDeNaissance', DateType::class, array(
        		'label' => $translator->trans('mma.profil.birthdate'),
        		'required'=>true))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
    	$resolver->setDefaults(array(
    			'data_class' => Compte::class
    	));
    	$resolver->setRequired('translator');
    }
}
