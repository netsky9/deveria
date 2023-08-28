<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Book;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, [
                    'required' => false
            ])
            ->add('publishAtFrom', NumberType::class, [
                'attr' => [
                        'min' => 1000,
                        'max' => date('Y'),
                ],
                'required' => false,
            ])
            ->add('publishAtTo', NumberType::class, [
                'attr' => [
                        'min' => 1000,
                        'max' => date('Y'),
                ],
                'required' => false,
            ])
            ->add('author', EntityType::class, [
                'class' => Author::class,
                'choice_label' => 'name',
            ])
            ->add('filter', SubmitType::class, array(
                    'attr' => array(
                            'class' => 'btn btn-success'
                    )
            ))
        ;
    }
}
