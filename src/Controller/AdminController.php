<?php


namespace App\Controller;

use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     *
     */
    public function admin(UserRepository $userRepository)
    {

        return $this->render('admin/admin.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }
}
