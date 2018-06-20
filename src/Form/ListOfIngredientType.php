<?php

namespace App\Form;
use App\Entity\ListOfIngredient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ListOfIngredientType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildFormListOfIngredient(FormBuilderInterface $builder, array $options)
    {
    	$translator = $options['translator'];
        $builder->add('ingredients', CollectionType::class, array(
            // each entry in the array will be an "email" field
            'entry_type'   => IngredientType::class,
            'allow_add' => true,
            'allow_delete'  => true,
            'prototype' => true,
        		'label' => $translator->trans('mma.listofingredient.ingredients'),
            // these options are passed to each "field" type
            'entry_options'  => array(
                'required'      => false,
                'label' => false
            ),
        ));
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => ListOfIngredient::class,
        ));
        $resolver->setRequired('translator');
    }
}