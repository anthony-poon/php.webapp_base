<?php

namespace App\Controller;

use App\Service\MenuHelper;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller {
    /**
     * @Route("/", name="home")
     */
    public function index(MenuHelper $menu) {
        return $this->render('default/index.html.twig');
    }
}
