<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\FoodRepository;
use App\Repository\CategoryRepository;
use App\Form\FoodFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;

class HomeController extends AbstractController
{
    /**
     * @Route("/about", name="about")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function about(Request $request, EntityManagerInterface $entityManager)
    {
        $user = $this->getUser();

        return $this->render(
            'home/index.html.twig',
            [
                'form' => 'Bla'
            ]
        );
    }

    /**
     * @Route("/admin", name="admin")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function admin(Request $request, EntityManagerInterface $entityManager)
    {
        $user = $this->getUser();

        return $this->render(
            'home/index.html.twig',
            [
                'form' => 'Bla'
            ]
        );
    }
}