<?php


namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class FrenchToDateTimeTransformer implements DataTransformerInterface
{
    public function transform($date)
    {
        // TODO: Implement transform() method.
        if($date === null){
            return '';
        }
        return $date->format('d/m/Y');
    }

    public function reverseTransform($frenchDate)
    {
        // TODO: Implement reverseTransform() method.
        if($frenchDate === null){
            throw new TransformationFailedException("Vous devez fournir une date !");
        }

        $date = \DateTime::createFromFormat('d/m/Y', $frenchDate);
        $dateFinal = date_time_set($date, $hour = 0, $minute = 0, $second = 0);

        if($date === false){
            throw new TransformationFailedException("Le format de date n'est pas le bon !");
        }
        return $dateFinal;
    }
}
