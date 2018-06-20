<?php

namespace App\Form;
use App\Entity\Adresse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Translation\Translator;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdresseType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$translator = $options['translator'];
        $builder
        ->add('pays', TextType::class, array('required'=>true,
        		'attr' => array('class' => 'form-control'),
        		'label' => $translator->trans('mma.editadress.land')
        		
        ))
        ->add('ville', TextType::class, array('required'=>true,
        		'label' => $translator->trans('mma.editadress.city'),
        		'attr' => array('class' => 'form-control')))
        ->add('boitePostale', NumberType::class, array('required'=>true,
        		'label' => $translator->trans('mma.editadress.postalcode'),
        		'attr' => array('class' => 'form-control')))
        ->add('rueEtNr', TextType::class, array('required'=>true,
        		'label' => $translator->trans('mma.editadress.streetnr'),
        		'attr' => array('class' => 'form-control')))
        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
    	$resolver->setDefaults(array(
    			'data_class' => Adresse::class
    	));
    	$resolver->setRequired('translator');
    }

}
