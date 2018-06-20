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
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class ClientType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$translator = $options['translator'];
    	$builder
        ->add('titre', ChoiceType::class,array('required'=>true,
            'choices' => array(
                "--Selectionez--"=>null,
            		"monsieur" => $translator->trans('mma.client.ms'),
            		"madame" => $translator->trans('mma.client.mme'))
        		,'attr' => array('class' => 'form-control')
        ))->add('nom', TextType::class, array('required'=>true,
        		'label' => $translator->trans('mma.client.firstname'),
        		'attr' => array('class' => 'form-control')))
        ->add('prenom', TextType::class, array('required'=>true,
        				'label' => $translator->trans('mma.client.lastname'),'attr' => array('class' => 'form-control')))
        ->add('dateDeNaissance', DateType::class,  array('required'=>true,
        						'label' => $translator->trans('mma.client.birthdate'))
        )->add('role', IntegerType::class,array(
        		'required'=>true,
        		'label' => $translator->trans('mma.client.role'),'attr' => array('class' => 'form-control')
        ))
        ->add('disponible', CheckboxType::class,array('required'=>false,
        		'label' => $translator->trans('mma.client.available'),
            'value'=>false
        ))
        ->add('image', FileType::class, array('required'=>true,'data_class' => null));
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

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'App_produit';
    }


}
