<?php
namespace App\Form;

use App\Entity\Login;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class EditerLoginType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$translator = $options['translator'];
    	$builder->add('email',TextType::class,array(
    			'label' => $translator->trans('mma.editlogin.email'),
    			'attr' => array('class' => 'form-control')))
    			->add('motDePass',PasswordType::class,array(
    					'label' => $translator->trans('mma.editlogin.password'),
    					'attr' => array('class' => 'form-control')));
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Login::class,
        ));
        $resolver->setRequired('translator');
    }
}
