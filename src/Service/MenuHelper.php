<?php
namespace App\Service;

use Symfony\Component\Routing\RouterInterface;

class MenuHelper {
    private $sideMenu;

    public function __construct(RouterInterface $router) {
        $this->sideMenu = [
            [
                "text" => "Home",
                "icon" => "home",
                "url" => $router->generate("home")
            ]
        ];
    }

    public function getSideMenu() {
        return $this->sideMenu;
    }

    public function addSideMenuItem(string $text, string $url, string $icon = null) {
        $this->sideMenu[] = [
            "text" => $text,
            "url" => $url,
            "icon" => $icon
        ];
    }
}