<?php

namespace App\Controller;

use App\Service\BaseTemplateHelper;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class DefaultController extends Controller {
    /**
     * @Route("/", name="home")
     */
    public function index(BaseTemplateHelper $template) {
        return $this->render('default/index.html.twig');
    }

    /**
     * @Route("/member", name="member_home")
     */
    public function member(BaseTemplateHelper $template) {
        return $this->render('default/index.html.twig');
    }
}


