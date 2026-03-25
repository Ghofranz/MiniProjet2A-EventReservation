<?php
// src/Form/ReservationType.php
namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label'       => 'Nom complet',
                'attr'        => ['class' => 'form-control', 'placeholder' => 'Ex: Ghofran Zouaghi'],
                'constraints' => [
                    new NotBlank(message: 'Le nom est obligatoire.'),
                    new Length(min: 2, max: 255),
                ],
            ])
            ->add('email', EmailType::class, [
                'label'       => 'Adresse email',
                'attr'        => ['class' => 'form-control', 'placeholder' => 'exemple@mail.com'],
                'constraints' => [
                    new NotBlank(message: 'L\'email est obligatoire.'),
                    new Email(message: 'Email invalide.'),
                ],
            ])
            ->add('phone', TelType::class, [
                'label'       => 'Téléphone',
                'attr'        => ['class' => 'form-control', 'placeholder' => '+216 XX XXX XXX'],
                'constraints' => [
                    new NotBlank(message: 'Le téléphone est obligatoire.'),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => '✅ Confirmer ma réservation',
                'attr'  => ['class' => 'btn btn-primary w-100 mt-3'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Reservation::class]);
    }
}