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
    	$builder
        ->add('titre', ChoiceType::class,array('required'=>true,
            'choices' => array(
                "--Selectionez--"=>null,
                "monsieur" => 'Ms',
                "madame" => 'Mme')
        		,'attr' => array('class' => 'form-control')
        ))->add('nom', TextType::class, array('required'=>true,'attr' => array('class' => 'form-control')))
        ->add('prenom', TextType::class, array('required'=>true,'attr' => array('class' => 'form-control')))
        ->add('dateDeNaissance', DateType::class,  array('required'=>true)
        )->add('role', IntegerType::class,array(
        		'required'=>true,'attr' => array('class' => 'form-control')
        ))
        ->add('disponible', CheckboxType::class,array('required'=>false,
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
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'App_produit';
    }


}
