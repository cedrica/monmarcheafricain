<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;

class ProduitType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$builder->add('nom', TextType::class, 
    			array(
    					'required'=>true,'attr' => array('class' => 'form-control')
    					
    			))
        ->add('prix', MoneyType::class, 
        		array('required'=>true,
        				'attr' => array('class' => 'form-control')
        		))
        ->add('etat', ChoiceType::class,
        		array('required'=>true,
            'choices' => array(
                "--Selectionez--"=>null,
                "neuf" => 'Neuf',
                "solde" => 'En solde')
        		,
        				'attr' => array('class' => 'form-control')
        ))
        ->add('categorie', ChoiceType::class,  array('required'=>true,
            'choices' => array(
                "--Selectionez--"=>null,
                "fruits" => 'fruits', 
                "épices" => 'épices',
                "légumes" => 'légumes',
                "farines et sémoules" => 'farines et sémoules',
                "viandes crus" => 'viandes crus',
                "viandes fumés" => 'viandes fumés',
                "crustacés" => 'crustacés', 
            		"conserves" => 'conserves'),'attr' => array('class' => 'form-control'))
        )->add('quantite', IntegerType::class,array(
        		'required'=>true,'attr' => array('class' => 'form-control')
        ))
        ->add('disponible', CheckboxType::class,array('required'=>false,
            'value'=>false
        ))
        ->add('action', CheckboxType::class,array('required'=>false,
        		'value'=>false
        ))
        ->add('pourcentageDeRabait', PercentType::class, 
        		array('required'=>false,'attr' => array('class' => 'form-control')))
        ->add('actionDebut', DateType::class, array('required'=>false,
        		'attr' => array('class' => 'form-control'),
        		'html5' => false,
        		'widget' => 'single_text',
        		// adds a class that can be selected in JavaScript
        		'attr' => array('class' => 'js-datepicker form-control')
        ))
        ->add('actionFin', DateType::class, array('required'=>false,
        		'html5' => false,
        		'widget' => 'single_text',
        		// adds a class that can be selected in JavaScript
        		'attr' => array('class' => 'js-datepicker form-control')
        ))
        ->add('image', FileType::class, array('required'=>true,'data_class' => null))
       // ->add('Editer', SubmitType::class, array(
       // 		'attr' => array('class' => 'btn michou-btn'),
        //))
        ;
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Produit'
        ));
    }


}
