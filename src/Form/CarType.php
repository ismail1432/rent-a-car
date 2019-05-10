<?php

namespace App\Form;

use App\Entity\Car;
use App\Entity\Category;
use App\Entity\City;
use App\Entity\Image;
use App\Faker\CarProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CarType extends AbstractType
{
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('model', TextType::class, [

            ])
            ->add('price', NumberType::class, [
            ])
            ->add('image', ImageType::class, ['label'=> false])

            ->add('color', ChoiceType::class, [
                'label' => false,
                'choices' =>
                    array_combine(CarProvider::COLOR, CarProvider::COLOR)
            ])
            ->add('carburant', ChoiceType::class, [
                'label' => false,
                'choices' =>
                    array_combine(CarProvider::CARBURANT, CarProvider::CARBURANT)
            ])
            ->add('keywords', CollectionType::class, [
                'label' => false,
                'entry_type' => KeywordType::class,
                'allow_add' => true,
                'by_reference' => false,
            ])
            ->add('cities', EntityType::class, [
                'label' => 'Ville',
                'class' => City::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => false,
            ])

            // Input caché, Avec du JS on mettra sa valeur à 'deleteImage' si on clique sur supprimer
            ->add('deleteImage', HiddenType::class, [
                // option pour dire que le champs ne correspond pas a une propriété de l'entité
                'mapped' => false
            ])
        ;

        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($options) {
                $car = $event->getData();

                // Récupère le fichier soumis, retourne null si aucun fichier n'est soumis
                $submittedFile = $event->getForm()->get('image')->get('file')->getData();

                 // la valeur doit être == 'deleteImage' si on a cliqué sur supprimer
                 $hiddenInput = $event->getForm()->get('deleteImage')->getData();

                 // Si on a cliqué sur supprimer ET si aucune image n'est soumise
                 if ($hiddenInput == 'deleteImage' && $submittedFile == null) {
                     // supprime l'image
                     $this->deleteImage($event->getData()->getImage(), $options['path']);
                     $car->setImage(null);
                     return;
                 }

                 // Si aucune image n'est soumise on met l'objet $image a null
                if($submittedFile == null) {
                    $car->setImage(null);
                    return;
                }

                $image = $car->getImage();
                $image->setPath($options['path']);
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Car::class,
            'path' => null,
        ]);
    }

    // Supprime l'image en base et surppime le fichier du serveur
    private function deleteImage(Image $image, $projectDir)
    {
        unlink($projectDir.'/'.$image->getPath().'/'.$image->getName());
        $this->manager->remove($image);
    }
}
