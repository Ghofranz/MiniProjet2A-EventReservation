<?php
// src/Form/EventType.php
namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'attr'  => ['class' => 'form-control'],
                'constraints' => [new NotBlank()],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr'  => ['class' => 'form-control', 'rows' => 4],
                'constraints' => [new NotBlank()],
            ])
            ->add('date', DateTimeType::class, [
                'label'  => 'Date et heure',
                'widget' => 'single_text',
                'attr'   => ['class' => 'form-control'],
                'constraints' => [new NotBlank()],
            ])
            ->add('location', TextType::class, [
                'label' => 'Lieu',
                'attr'  => ['class' => 'form-control'],
                'constraints' => [new NotBlank()],
            ])
            ->add('seats', IntegerType::class, [
                'label' => 'Nombre de places',
                'attr'  => ['class' => 'form-control', 'min' => 1],
                'constraints' => [new NotBlank(), new Positive()],
            ])
            // Champ image : on uploade un fichier, pas une string
            ->add('imageFile', FileType::class, [
                'label'    => 'Image (jpg/png)',
                'mapped'   => false,   // pas lié à l'entité directement
                'required' => false,
                'attr'     => ['class' => 'form-control'],
                'constraints' => [
                    new File([
                        'maxSize'   => '2M',
                        'mimeTypes' => ['image/jpeg', 'image/png', 'image/webp'],
                        'mimeTypesMessage' => 'Fichier image invalide.',
                    ]),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => '💾 Enregistrer',
                'attr'  => ['class' => 'btn btn-success mt-3'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Event::class]);
    }
}