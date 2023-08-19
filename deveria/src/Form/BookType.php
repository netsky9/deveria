<?php

namespace App\Form;

use App\Entity\Book;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, array(
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('cover', FileType::class, [
                'label' => 'Cover',
                'required' => true,
            ])
            ->add('publishAt', null, array(
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('author', null, array(
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('save', SubmitType::class, array(
                'attr' => array(
                    'class' => 'btn btn-success'
                )
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
