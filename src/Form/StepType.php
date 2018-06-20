<?php
namespace App\Form;

use App\Entity\Step;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StepType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$translator = $options['translator'];
    	$builder->add('description', TextareaType::class, array(
    			'label' => $translator->trans('mma.step.description'),
    			'attr' => array('class' => 'form-control')
    	))
    	->add('temps', NumberType::class, array(
    			'label' => $translator->trans('mma.step.time'),
    			'attr' => array('class' => 'form-control')
    	));
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Step::class,
        ));
        $resolver->setRequired('translator');
    }
}
