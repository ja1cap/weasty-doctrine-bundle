<?php
namespace Weasty\Bundle\DoctrineBundle\Form\Type;

use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Weasty\Bundle\DoctrineBundle\Form\DataTransformer\EntityToChoiceTransformer;

/**
 * Class HiddenType
 * @package Weasty\Bundle\DoctrineBundle\Form\Type
 */
class HiddenType extends AbstractType {

    /**
     * @var ManagerRegistry
     */
    protected $managerRegistry;

    /**
     * @param ManagerRegistry $managerRegistry
     */
    function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {

        $resolver->setDefaults(array(
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

        $builder->addViewTransformer(new EntityToChoiceTransformer(
            $this->managerRegistry->getManagerForClass($options['class']),
            $options['class']
        ), true);

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