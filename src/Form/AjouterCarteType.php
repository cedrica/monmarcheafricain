<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class AjouterCarteType extends AbstractType
{

    /**
     *
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$translator = $options['translator'];
        $builder->add('nomDeLaCarte', TextType::class, array(
            'required' => true,
        		'label' => $translator->trans('mma.addcard.addnameoncard'),
        		'attr' => array('class' => 'form-control')
        ))
            ->add('numeroDeLaCarte', TextType::class, array(
            'required' => true,
            		'label' => $translator->trans('mma.addcard.cardnumber'),
            		'attr' => array('class' => 'form-control')
        ))
            ->add('dateDExpiration', DateType::class, array(
            		'label' => $translator->trans('mma.addcard.expiredate'),
            'widget' => 'choice',
            'html5' => false,
                'by_reference'=> true,
            'attr' => [
            		'class' => 'js-datepicker'
            ]
        ))
            -> add('defaultCarte', CheckboxType::class, array(
            		'label' => 'mma.addcard.useasdefaultcard'
            ));
    }
    

    /**
     *
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\CarteDeCredit'
        ));
        $resolver->setRequired('translator');
    }

    /**
     *
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'App_login';
    }
}
