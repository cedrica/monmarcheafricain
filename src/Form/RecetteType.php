<?php

namespace App\Form;

use App\Entity\Recette;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecetteType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$translator = $options['translator'];
    	$builder->add('nom', TextType::class, array(
    			'label' => $translator->trans('mma.recette.name'),
    			'required'=>true,'attr' => array('class' => 'form-control')
        ))
        ->add('image', FileType::class, array('required'=>true,
        		'label' => $translator->trans('mma.recette.image'),
        		'data_class' => null))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
    	$resolver->setDefaults(array(
    			'data_class' => 'App\Entity\Recette'
    	));
    	$resolver->setRequired('translator');
    }

}
