<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
    			'required'=>true,'attr' => array('class' => 'form-control')));
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Login'
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
