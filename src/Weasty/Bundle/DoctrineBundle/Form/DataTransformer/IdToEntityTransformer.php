<?php
namespace Weasty\Bundle\DoctrineBundle\Form\DataTransformer;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Weasty\Doctrine\Entity\EntityInterface;

/**
 * Class IdToEntityTransformer
 * @package Weasty\Bundle\DoctrineBundle\Form\DataTransformer
 */
class IdToEntityTransformer implements DataTransformerInterface {

    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */
    protected $repository;

    /**
     * @param ObjectManager|object $em
     * @param $entityClass
     */
    function __construct(ObjectManager $em, $entityClass)
    {
        $this->repository = $em->getRepository($entityClass);
    }

    /**
     * Transforms a value from the original representation to a transformed representation.
     *
     * This method is called on two occasions inside a form field:
     *
     * 1. When the form field is initialized with the data attached from the datasource (object or array).
     * 2. When data from a request is submitted using {@link Form::submit()} to transform the new input data
     *    back into the renderable format. For example if you have a date field and submit '2009-10-10'
     *    you might accept this value because its easily parsed, but the transformer still writes back
     *    "2009/10/10" onto the form field (for further displaying or other purposes).
     *
     * This method must be able to deal with empty values. Usually this will
     * be NULL, but depending on your implementation other empty values are
     * possible as well (such as empty strings). The reasoning behind this is
     * that value transformers must be chainable. If the transform() method
     * of the first value transformer outputs NULL, the second value transformer
     * must be able to process that value.
     *
     * By convention, transform() should return an empty string if NULL is
     * passed.
     *
     * @param mixed $value The value in the original representation
     *
     * @return mixed The value in the transformed representation
     *
     * @throws TransformationFailedException When the transformation fails.
     */
    public function transform($value)
    {

        if(is_array($value) || $value instanceof Collection){

            $ids = array();
            $entities = array();

            foreach($value as $i => $val){
                if($val instanceof EntityInterface){
                    $entities[] = $val;
                    unset($value[$i]);
                } else {
                    $ids[] = (string)$val;
                }
            }

            $entities += $this->repository->findBy(array(
                'id' => $ids,
            ));

            return $entities;

        } else {

            if($value instanceof EntityInterface){

                return $value;

            } else {

                return $this->repository->findOneBy(array(
                    'id' => (string)$value,
                ));

            }

        }

    }

    /**
     * Transforms a value from the transformed representation to its original
     * representation.
     *
     * This method is called when {@link Form::submit()} is called to transform the requests tainted data
     * into an acceptable format for your data processing/model layer.
     *
     * This method must be able to deal with empty values. Usually this will
     * be an empty string, but depending on your implementation other empty
     * values are possible as well (such as empty strings). The reasoning behind
     * this is that value transformers must be chainable. If the
     * reverseTransform() method of the first value transformer outputs an
     * empty string, the second value transformer must be able to process that
     * value.
     *
     * By convention, reverseTransform() should return NULL if an empty string
     * is passed.
     *
     * @param mixed $value The value in the transformed representation
     *
     * @return mixed The value in the original representation
     *
     * @throws TransformationFailedException When the transformation fails.
     */
    public function reverseTransform($value)
    {

        if(is_array($value) || $value instanceof Collection){

            $ids = array();

            foreach($value as $val){

                if($val instanceof EntityInterface){
                    $ids[] = $val->getId();
                } else {
                    $ids[] = (string)$val;
                }

            }

            return $ids;

        } else {

            if($value instanceof EntityInterface){
                $id = $value->getId();
            } else {
                $id = (string)$value;
            }

            return $id;

        }

    }

} 