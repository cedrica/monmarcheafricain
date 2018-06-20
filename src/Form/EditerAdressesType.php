<?php
namespace App\Form;

use App\Entity\Adresse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class EditerAdressesType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$translator = $options['translator'];
        $builder->add('id', HiddenType::class)
        ->add('boitePostale', TextType::class,
        		array('label' => $translator->trans('mma.editadresse.postalcode')))
        ->add('pays', TextType::class,
        				array('label' => $translator->trans('mma.editadresse.land')))
		->add('ville', TextType::class,
        				array('label' => $translator->trans('mma.editadresse.city')))
        ->add('rueEtNr', TextType::class,
        				array('label' => $translator->trans('mma.editadresse.streetnr')));
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Adresse::class,
        ));
        $resolver->setRequired('translator');
    }
}
