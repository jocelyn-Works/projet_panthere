<?php

namespace App\Form;

use App\Entity\Position;
use Symfony\Component\Form\AbstractType;
use App\Entity\Team;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class TeamType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [  // firstname = le nom de la table , TextType = ce sera du text
                'attr' => [
                    'class' => 'form-control',  // la class de l'input
                    
                ],
                'label' => 'Prenom :',  // son label
                'label_attr' => [
                    'class' => 'form-label mt-4'  // la class du label
                ],
            ])
            ->add('lastname', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    
                ],
                'label' => 'Nom :',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
            ])
            ->add('Age', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    
                ],
                'label' => 'Age :',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
            ])
            ->add('Adresse', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    
                ],
                'label' => 'Adresse :',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
            ])
            ->add('Phone', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    
                ],
                'label' => 'Numero de télephone :',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
            ])
            ->add('Email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control',
                    
                ],
                'label' => 'Email :',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
            ])
            ->add('hierarchie', ChoiceType::class, [
                'attr' => [
                    // 'class' => 'form-control',
                ],
                'label' => 'Poste : ',
                'label_attr' => [
                    'class' => 'form-label mt-4 mx-2'
                ],
                'choices'  => [
                    'Gérant' => "1",
                    'Lead Dev' => "4",
                    'Commercial' => "2",
                    'Comptabillité' => "3",
                    'Dev' => "5",
                    'UI/UX' => "9",
                    'Stagiaire' => "8",
                ],
                ])
            ->add('CV', FileType::class, [
                'attr' => [
                    'class' => 'form-control',
                    
                ],
                'label' => 'CV : (PDF)',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],

                // unmapped means that this field is not associated to any entity property
                // non mappé signifie que ce champ n'est associé à aucune propriété d'entité
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                // rendez-le facultatif afin que vous n'ayez pas à télécharger à nouveau le fichier PDF
                 // chaque fois que vous modifiez les détails du produit
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'mimeTypesMessage' => "Veuillez télécharger un document PDF valide",
                        'maxSize' => '1024k',
                        'maxSizeMessage' => 'Votre PDF fait {{size}} {{suffix}},  La limite est de {{ limit }} {{suffix}}, Veuillez télécharger un document PDF valide',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                        ],
                        
                    ])
                ],
            ])
          
            ->add('imageFile',FileType::class,[
                'label' => 'Image de Profile',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new Image([
                        'mimeTypesMessage' => "Veuillez télécharger une image valide",
                        "maxSize" => '2M',
                        'maxSizeMessage' => "Votre image fait {{size}} {{suffix}}, La limite est de {{ limit }} {{suffix}}"
                    ])
                ],
                'label_attr' => [
                            'class' => 'form-label mt-4'
                        ],
            ] )
            // ->add('positions', TextType::class, [  // firstname = le nom de la table , TextType = ce sera du text
            //     'attr' => [
            //         'class' => 'form-control',  // la class de l'input
                    
            //     ],
            //     'label' => 'Positions :',  // son label
            //     'label_attr' => [
            //         'class' => 'form-label mt-4'  // la class du label
            //     ],
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Team::class,
        ]);
    }
}
