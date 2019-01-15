<?php


namespace App\Form;

use App\Entity\City;
use App\Faker\CarProvider;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class SearchCarType extends AbstractType
{
    const PRICE = [1000, 2000, 3000, 4000, 5000];

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('color', ChoiceType::class, [
                'choices' =>
                   array_combine(CarProvider::COLOR, CarProvider::COLOR)

            ])
            ->add('carburant', ChoiceType::class, [
                'choices' =>
                   array_combine(CarProvider::CARBURANT, CarProvider::CARBURANT)

            ])
            ->add('city', EntityType::class, [
                'class' => City::class,
                'choice_label' => 'name'
            ])
            ->add('minimumPrice', ChoiceType::class, [
                'label' => 'Prix minimum',
                'choices' => array_combine(self::PRICE, self::PRICE)
            ])
            ->add('maximumPrice', ChoiceType::class, [
                'label' => 'Prix maximum',
                'choices' => array_combine(self::PRICE, self::PRICE)
            ])
            ->add('recherche', SubmitType::class)

        ;
    }
}
