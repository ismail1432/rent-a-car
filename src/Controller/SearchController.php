<?php


namespace App\Controller;

use App\Form\SearchCarType;
use App\Repository\CarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    /**
     * @Route("/search/car", name="search_car")
     */
    public function searchCar(Request $request, CarRepository $carRepository)
    {
        $cars = [];

        $searchCarForm = $this->createForm(SearchCarType::class);

        if ($searchCarForm->handleRequest($request)->isSubmitted() && $searchCarForm->isValid()) {
            $criteria = $searchCarForm->getData();

            $cars = $carRepository->searchCar($criteria);
        }

        return $this->render('search/car.html.twig', [
            'search_form' => $searchCarForm->createView(),
            'cars' => $cars,
        ]);
    }
}
