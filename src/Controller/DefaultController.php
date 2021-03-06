<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function index()
    {
        return $this->render('default/index.html.twig');
    }
}
