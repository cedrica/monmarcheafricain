<?php
namespace App\Form;

use App\Entity\Compte;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;


class EditerDonneesPersoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$translator = $options['translator'];
        $builder->add('titre', ChoiceType::class, array(
            'choices' => array(
                'M' => 'Monsieur',
                'Mme' => 'Madamme'
            ),'attr' => array('class' => 'form-control'),
        		'label' => $translator->trans('mma.profil.title')
        ))
        ->add('nom', TextType::class, array(
        		'attr' => array('class' => 'form-control'),
        		'label' => $translator->trans('mma.profil.firstname')
        		
        ))
        ->add('prenom', TextType::class,array(
        		'attr' => array('class' => 'form-control'),
        		'label' => $translator->trans('mma.profil.lastname')))
        ->add('dateDeNaissance', DateType::class, array(
            // prevents rendering it as type="date", to avoid HTML5 date pickers
            'html5' => false,
            'widget' => 'single_text',
            // adds a class that can be selected in JavaScript
            'attr' => array(
               'class' => 'js-datepicker form-control'
            ),
        	'label' => $translator->trans('mma.profil.birthdate')
        		
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Compte::class
        ));
        $resolver->setRequired('translator');
    }
}
