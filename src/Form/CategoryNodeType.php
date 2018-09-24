<?php

namespace App\Form;
use App\Entity\Adresse;
use App\Service\CategoryNode;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Translation\Translator;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryNodeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$categories = $options['categories'];
        $builder
        ->add('fr', TextType::class, array('required'=>true,
        		'attr' => array('class' => 'form-control form-group'),
        		'label' => 'FR'
        		
        ))
        ->add('en', TextType::class, array('required'=>true,
        		'attr' => array('class' => 'form-control form-group'),
        		'label' => 'EN'
        		
        ))
        ->add('de', TextType::class, array('required'=>true,
        		'label' => 'DE',
        		'attr' => array('class' => 'form-control form-group')
        		
        ))
        ->add('parentId', ChoiceType::class, array('required'=>true,
        		'label' => 'Parent',
        		'attr' => array('class' => 'form-control form-group'),
        		'choices' => $categories
        		
        ));
    }
    public function configureOptions(OptionsResolver $resolver)
    {
    	$resolver->setDefaults(array(
    			'data_class' => CategoryNode::class
    	));
    	$resolver->setRequired('categories');
    }

}
