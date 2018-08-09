<?php

namespace App\Controller\Base;

use App\Service\BaseTemplateHelper;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller {
    /**
     * @Route("/", name="home")
     */
    public function index(BaseTemplateHelper $template) {
        return $this->render('render/index.html.twig');
    }

    /**
     * @Route("/member", name="member_home")
     */
    public function member(BaseTemplateHelper $template) {
        return $this->render('render/index.html.twig');
    }
}


