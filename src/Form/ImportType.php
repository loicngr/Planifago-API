<?php

namespace App\Form;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImportType extends AbstractType
{
    private $classes = [];

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em ;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $metas = $this->em->getMetadataFactory()->getAllMetadata();
        if(!empty($metas)):
            foreach($metas as $meta):
                $this->classes[$meta->getName()] = $meta->getName();
            endforeach;
        endif;

        $builder
            ->add('file', FileType::class, [
                'label' => 'File',
                'label_attr' => [
                    'class' => 'form-control-label'
                ],
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('entity', ChoiceType::class, [
                'choices' => $this->classes,
                'label' => 'Entity',
                'label_attr' => [
                    'class' => 'form-control-label'
                ],
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('offset', NumberType::class, [
                'data' => 0,
                'label' => 'Offset',
                'label_attr' => [
                    'class' => 'form-control-label'
                ],
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('limit', NumberType::class, [
                'data' => 100000,
                'label' => 'Limit',
                'label_attr' => [
                    'class' => 'form-control-label'
                ],
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([]);
    }
}
