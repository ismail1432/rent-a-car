<?php

namespace App\Command;

use App\Repository\CarRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CleanCarCommand extends Command
{
    protected static $defaultName = 'app:clean:car';
    private $carRepository;
    private $manager;

    public function __construct(CarRepository $carRepository, EntityManagerInterface $manager)
    {
        $this->manager = $manager;
        $this->carRepository = $carRepository;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Supprime les voitures sans utilisateurs')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $cars = $this->carRepository->findBy(['user' => null]);

        $total = count($cars);

        $output->writeln("<info>Il y a $total Voitures !</info>");

        foreach ($cars as $car) {
            $model = $car->getModel();

            $output->writeln("<error>Suppression de $model</error>");
            $this->manager->remove($car);
        }

        $this->manager->flush();

        $output->writeln("<info>Les voitures ont bien étaient supprimées</info>");
    }
}
