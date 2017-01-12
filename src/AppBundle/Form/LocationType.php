<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\LocationTranslation;
use AppBundle\Form\TranslatableType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class LocationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class, [
                'label'                => 'form.location.name.fr.label',
                'attr'                => [
                    'class'     => 'w3-input w3-border'
                ]
            ])
            ->add('translations', TranslatableType::class, [
                'label'                => 'form.location.name.traductions.label',
                'personal_translation' => LocationTranslation::class,
                'property_path'        => 'translations',
                'field'                =>'name'
            ])
            ->add('temperature', NumberType::class, [
                'label'               => 'form.location.temperature.label',
                'attr'                => [
                    'class'     => 'w3-input w3-border'

            ]])
            ->add('create', SubmitType::class, [
                'label'               => 'form.location.submit.label',
                'attr'                => [
                        'class'     => 'w3-btn'
    ]
            ])
            ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Location'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_location';
    }


}
