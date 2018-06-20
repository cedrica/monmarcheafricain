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
    	$translator = $options['translator'];
    	$builder->add('nom', TextType::class, 
    			array(
    					'required'=>true,
    					'label' => $translator->trans('mma.product.name'),
    					'attr' => array('class' => 'form-control')
    					
    			))
        ->add('prix', MoneyType::class, 
        		array('required'=>true,
        				'label' => $translator->trans('mma.product.price'),
        				'attr' => array('class' => 'form-control')
        		))
        ->add('etat', ChoiceType::class,
        		array('required'=>true,
        				'label' => $translator->trans('mma.product.state'),
            'choices' => array(
                "--Selectionez--"=>null,
                "neuf" => 'Neuf',
                "solde" => 'En solde')
        		,
        				'attr' => array('class' => 'form-control')
        ))
        ->add('categorie', ChoiceType::class,  array('required'=>true,
        		'label' => $translator->trans('mma.product.category'),
            'choices' => array(
                "--Selectionez--"=>null,
            		"fruits" => "fruits",//$translator->trans('mma.product.fruts'),
            		"épices" => "épices",//$translator->trans('mma.product.spice'),
            		"légumes" => "légumes",//$translator->trans('mma.product.vegetables'),
            		"farines et sémoules" => "farines et sémoules",//$translator->trans('mma.product.flourandsemolina'),
            		"viandes crus" => "viandes crus",//$translator->trans('mma.product.rawedmeat'),
            		"viandes fumés" => "viandes fumés",//$translator->trans('mma.product.smokedmeat'),
            		"crustacés" => "crustacés" ,//$translator->trans('mma.product.shellfish'),
            		"conserves" => "conserves",//$translator->trans('mma.product.cans'),
				),'attr' => array('class' => 'form-control'))
        )->add('quantite', IntegerType::class,array(
        		'label' => $translator->trans('mma.product.quantity'),
        		'required'=>true,'attr' => array('class' => 'form-control')
        ))
        ->add('disponible', CheckboxType::class,array('required'=>false,
        		'label' => $translator->trans('mma.product.available'),
            'value'=>false
        ))
        ->add('action', CheckboxType::class,array('required'=>false,
        		'label' => $translator->trans('mma.product.offer'),
        		'value'=>false
        ))
        ->add('pourcentageDeRabait', PercentType::class, 
        		array('required'=>false,
        				'label' => $translator->trans('mma.product.reduction'),
        				'attr' => array('class' => 'form-control')))
        ->add('actionDebut', DateType::class, array('required'=>false,
        		'label' => $translator->trans('mma.product.offerstart'),
        		'attr' => array('class' => 'form-control'),
        		'html5' => false,
        		'widget' => 'single_text',
        		// adds a class that can be selected in JavaScript
        		'attr' => array('class' => 'js-datepicker form-control')
        ))
        ->add('actionFin', DateType::class, array('required'=>false,
        		'label' => $translator->trans('mma.product.offerend'),
        		'html5' => false,
        		'widget' => 'single_text',
        		// adds a class that can be selected in JavaScript
        		'attr' => array('class' => 'js-datepicker form-control')
        ))
        ->add('image', FileType::class, array('required'=>true,
        		'label' => $translator->trans('mma.product.image'),
        		'data_class' => null))
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
        $resolver->setRequired('translator');
    }


}
