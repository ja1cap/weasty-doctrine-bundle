<?php
namespace Weasty\Bundle\DoctrineBundle\Form\Type;

use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Weasty\Bundle\DoctrineBundle\Form\DataTransformer\EntityToChoiceTransformer;
use Weasty\Bundle\DoctrineBundle\Form\DataTransformer\IdToEntityTransformer;

/**
 * Class HiddenType
 * @package Weasty\Bundle\DoctrineBundle\Form\Type
 */
class HiddenType extends AbstractType {

    /**
     * @var ManagerRegistry
     */
    protected $registry;

    /**
     * @param ManagerRegistry $managerRegistry
     */
    function __construct(ManagerRegistry $managerRegistry)
    {
        $this->registry = $managerRegistry;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {

        $resolver->setDefaults(array(
            'map_as_id' => false,
            // hidden fields cannot have a required attribute
            'required'       => false,
            // Pass errors to the parent
            'error_bubbling' => true,
            'compound'       => false,
        ));

        $resolver->setRequired(array(
            'class',
        ));

    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->addViewTransformer(new EntityToChoiceTransformer(
                $this->registry->getManagerForClass($options['class']),
                $options['class']
            ), true)
        ;

        if($options['map_as_id']){

            $builder
                ->addModelTransformer(new IdToEntityTransformer($this->registry->getManagerForClass($options['class']), $options['class']))
            ;

        }

    }

    /**
     * @return null|string|\Symfony\Component\Form\FormTypeInterface
     */
    public function getParent(){
        return 'hidden';
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'weasty_doctrine_hidden_type';
    }

} 