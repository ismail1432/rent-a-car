<?php


namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
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

    /**
     * @Route("/admin/delete/{id}", name="delete_user")
     *
     */
    public function deleteUser(User $user, EntityManagerInterface $entityManager, UserRepository $userRepository)
    {
        $entityManager->remove($user);
        $entityManager->flush();

        $this->addFlash(
            'notice',
            'Utilisateur Supprimé'
        );

        return $this->render('admin/admin.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }
}
