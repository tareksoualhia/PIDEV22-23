<?php

namespace App\Form;
use App\Entity\Reclamation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;

class ReclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Numero')
            ->add('Nom')
            ->add('Email')
            ->add('Type',ChoiceType::class, [
                'choices' => [
                   
                    'Reclamation Entraineur' => 'Reclamation Entraineur',
                    'Reclamation Evenement' => 'Reclamation Evenement',
                    'Reclamation competition' => 'Reclamation competition'

                ],
                'placeholder' => 'Selectionner une option ',
             ])
            ->add('Description',TextareaType::class);
           /* ->add('Envoyer',SubmitType::class)
            ->add('Annuler', ResetType::class, array(
                'label' => 'Annuler',
                'attr' => array('class' => 'btn btn-default'),
            ));*/
            
            
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,'csrf_protection' => false,
        ]);
    }
}
