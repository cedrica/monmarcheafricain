<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Ingredient;

class IngredientType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$translator = $options['translator'];
    	$builder->add('nom', TextType::class, array(
    			'attr' => array('class' => 'form-control'),
    			'label' => $translator->trans('mma.ingredient.name')
    	));
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Ingredient::class,
        ));
        $resolver->setRequired('translator');
    }
}
