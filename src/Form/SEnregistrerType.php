<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class SEnregistrerType extends AbstractType
{
    /**
     * {@inhertdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$translator = $options['translator'];
    	$builder->add('email', EmailType::class, array(
    			'label' => $translator->trans('mma.senregistrer.email'),
    			'required'=>true,'attr' => array('class' => 'form-control')))
    	->add('motDePass', PasswordType::class, array(
    			'label' => $translator->trans('mma.senregistrer.password'),
    			'required'=>true,'attr' => array('class' => 'form-control')))
        ->add('rememberMe', CheckboxType::class, array(
                'label' => $translator->trans('mma.senregistrer.rememberMe'),
                'required'=>true,'attr' => array('class' => 'form-group')));
    	if ($options['admin-login']) {
    		$builder->add('language', ChoiceType::class,  array('required'=>true,
    		'choices' => array(
    			"--Bitte wählen--"=>null,
    			"en" => "en",
    			"de" => "de",
    			"fr" => "fr"
    			),'attr' => array('class' => 'form-control form-group'))
    		);
    	}
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Login',
        	'admin-login' => false
        ));
        $resolver->setRequired('translator');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'App_login';
    }


}
