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
    	
    	//if ($options['fr']) {
    		$builder->add('nom', TextType::class, 
    			array(
    					'required'=>true,
    					'label' => $translator->trans('mma.product.name'),
    					'attr' => array('class' => 'form-control form-group')
    					
    			)) ->add('categorie', ChoiceType::class,  array('required'=>true,
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
    					),'attr' => array('class' => 'form-control form-group')))
    	//}
    	//if ($options['en']) {
    		->add('name', TextType::class,
    			array(
    				'required'=>true,
    				'label' => $translator->trans('mma.product.name'),
    				'attr' => array('class' => 'form-control form-group')
    							
    			))->add('category', ChoiceType::class,  array('required'=>true,
    					'choices' => array(
    							"--select--"=>null,
    							"fruts" => "fruits",//$translator->trans('mma.product.fruts'),
    							"spice" => "épices",//$translator->trans('mma.product.spice'),
    							"vegetables" => "légumes",//$translator->trans('mma.product.vegetables'),
    							"flour and semolina" => "farines et sémoules",//$translator->trans('mma.product.flourandsemolina'),
    							"rawed meat" => "viandes crus",//$translator->trans('mma.product.rawedmeat'),
    							"smoked meat" => "viandes fumés",//$translator->trans('mma.product.smokedmeat'),
    							"shell fish" => "crustacés" ,//$translator->trans('mma.product.shellfish'),
    							"cans" => "conserves",//$translator->trans('mma.product.cans'),
    					),'attr' => array('class' => 'form-control form-group'))
    					)
    	//}
    	//if ($options['de']) {
    		->add('nameDE', TextType::class,
    				array(
    						'required'=>true,
    						'label' => $translator->trans('mma.product.name'),
    						'attr' => array('class' => 'form-control form-group')
    						
    				))->add('kategorie', ChoiceType::class,  array('required'=>true,
    						'choices' => array(
    								"--Bitte wählen--"=>null,
    								"Früchte" => "fruits",//$translator->trans('mma.product.fruts'),
    								"Gewürze" => "épices",//$translator->trans('mma.product.spice'),
    								"Gemüse" => "légumes",//$translator->trans('mma.product.vegetables'),
    								"Mehl und Gries" => "farines et sémoules",//$translator->trans('mma.product.flourandsemolina'),
    								"Roher Fleish" => "viandes crus",//$translator->trans('mma.product.rawedmeat'),
    								"Geräucherter Fleish" => "viandes fumés",//$translator->trans('mma.product.smokedmeat'),
    								"Laks" => "crustacés" ,//$translator->trans('mma.product.shellfish'),
    								"Dosen" => "conserves",//$translator->trans('mma.product.cans'),
    						),'attr' => array('class' => 'form-control form-group'))
    						)
    	//}
    	->add('prix', MoneyType::class, 
        		array('required'=>true,
        				'label' => $translator->trans('mma.product.price'),
        				'attr' => array('class' => 'form-control form-group')
        		))
        ->add('etat', ChoiceType::class,
        		array('required'=>true,
        				'label' => $translator->trans('mma.product.state'),
            'choices' => array(
                "--Selectionez--"=>null,
                "neuf" => 'Neuf',
                "solde" => 'En solde'),
        		'attr' => array('class' => 'form-control form-group')
        ))
        ->add('quantite', IntegerType::class,array(
        		'label' => $translator->trans('mma.product.quantity'),
        		'required'=>true,'attr' => array('class' => 'form-control form-group')
        ))
        ->add('disponible', CheckboxType::class,array('required'=>false,
        		'label' => $translator->trans('mma.product.available'),
        		'attr' => array('class' => 'available'),
            'value'=>false
        ))
        ->add('action', CheckboxType::class,array('required'=>false,
        		'label' => false,
        		'value'=> false,
        		'attr' => array('class' => 'produit_action_cb')
        		
        ))
        ->add('pourcentageDeRabait', PercentType::class, 
        		array('required'=>false,
        				'label' => $translator->trans('mma.product.reduction'),
        				'attr' => array('class' => 'form-control form-group produit_action_tf')))
        ->add('actionDebut', DateType::class, array('required'=>false,
        		'label' => $translator->trans('mma.product.offerstart'),
        		'attr' => array('class' => 'form-control produit_action_tf js-datepicker form-group'),
        		'html5' => false,
        		'widget' => 'single_text'
        ))
        ->add('actionFin', DateType::class, array('required'=>false,
        		'label' => $translator->trans('mma.product.offerend'),
        		'html5' => false,
        		'widget' => 'single_text',
        		// adds a class that can be selected in JavaScript
        		'attr' => array('class' => 'js-datepicker form-control form-group produit_action_tf',
        						'ng-model' => 'actionFin',
        						'value' => '{{actionFin}}')
        ))
        ->add('image', FileType::class, array('required'=>true,
        		'label' => $translator->trans('mma.product.image'),
        		'data_class' => null))
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
        $resolver->setDefaults(array(
        		'en' => true,
        		'fr' => true,
        		'de' => true
        		
        ));
    }
    
    public function convertToCorrespondingName($message, $lang) {
    	return $translator->trans($message);
    }


}
