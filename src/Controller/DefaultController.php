<?php

namespace App\Controller;

use App\Service\BaseTemplateHelper;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller {
    /**
     * @Route("/", name="home")
     */
    public function index(BaseTemplateHelper $template) {
        return $this->render('default/index.html.twig');
    }
}
