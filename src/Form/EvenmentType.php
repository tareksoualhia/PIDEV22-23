<?php

namespace App\Form;

use App\Entity\Evenment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;



class EvenmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('descr', TextType::class, [
                'label' => 'description',
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])/',
                        'message' => 'Le texte doit contenir des lettres majuscules et minuscules.',
                    ]),
                ],
            ])
            ->add('date_debut')
            ->add('date_fin')
            ->add('title', TextType::class, [
                'label' => 'description',
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])/',
                        'message' => 'Le texte doit contenir des lettres majuscules et minuscules.',
                    ]),
                ],
            ])
            ->add('Envoyer',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evenment::class,
        ]);
    }
}
