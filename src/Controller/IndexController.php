<?php


namespace App\Controller;

use App\Entity\Car;
use App\Entity\Keyword;
use App\Form\CarType;
use App\Repository\CarRepository;
use App\Services\ImageHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="home")
     *
     */
    public function index(CarRepository $carRepository)
    {
        $cars = $carRepository->findAll();

        return $this->render('home/index.html.twig', [
            'cars' => $cars,
        ]);
    }

    /**
     * @Route("/car/add", name="add")
     */
    public function add(EntityManagerInterface $manager, Request $request, ImageHandler $handler)
    {
        $path = $this->getParameter('kernel.project_dir') . '/public/images';
        $form = $this->createForm(CarType::class, null, ['path' => $path]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $car = $form->getData();
            $user = $this->getUser();

            $car->setUser($user);

            $manager->persist($car);

            $manager->flush();

            $this->addFlash(
                'notice',
                'Super ! Une nouvelle voiture à été ajoutée !!'
            );

            return $this->redirectToRoute('home');
        }

        return $this->render('home/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/car/edit/{id}", name="edit")
     */
    public function edit(Car $car, EntityManagerInterface $manager, Request $request)
    {
        $path = $this->getParameter('kernel.project_dir') . '/public/images';
        $form = $this->createForm(CarType::class, $car, ['path' => $path]);
        $this->denyAccessUnlessGranted('EDIT', $car);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $path = $this->getParameter('kernel.project_dir') . '/public/images';

            $manager->flush();
            $this->addFlash(
                'notice',
                'Super ! la voiture est bien modifiée !!'
            );

            return $this->redirectToRoute('show', [
                'id' => $car->getId(),
            ]);
        }

        return $this->render('home/edit.html.twig', [
            'car' => $car,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/car/delete/{id}", name="delete")
     */
    public function delete(Car $car, EntityManagerInterface $manager)
    {
        $this->denyAccessUnlessGranted('DELETE', $car);

        $manager->remove($car);
        $manager->flush();

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/show/{id}", name="show")
     */
    public function show(Car $car)
    {
        return $this->render('home/show.html.twig', [
            'car' => $car
        ]);
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact()
    {
        return $this->render('home/contact.html.twig');
    }

    /**
     * @Route("delete/keyword/{id}",
     *     name="delete_keyword",
     *     methods={"GET"},
     *     condition="request.headers.get('X-Requested-With') matches '/XMLHttpRequest/i'")
     */
    public function deleteKeyword(Keyword $keyword, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($keyword);
        $entityManager->flush();

        return new JsonResponse();
    }
}
