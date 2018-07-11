<?php
namespace App\Service;

use Symfony\Component\Routing\RouterInterface;

class BaseTemplateHelper {
    private $sideMenu = [];
    private $title = "Web Application";
    private $jsParam = [];
    public function __construct(RouterInterface $router) {
        $this->sideMenu = [
            [
                "text" => "Home",
                "icon" => "home",
                "url" => $router->generate("home")
            ]
        ];
    }

    /**
     * @return array
     */
    public function getSideMenu(): array {
        return $this->sideMenu;
    }

    public function addSideMenuItem(string $text, string $url, string $icon = null) {
        $this->sideMenu[] = [
            "text" => $text,
            "url" => $url,
            "icon" => $icon
        ];
    }

    /**
     * @return string
     */
    public function getTitle(): string {
        return $this->title;
    }

    /**
     * @param string $title
     * @return BaseTemplateHelper
     */
    public function setTitle(string $title): BaseTemplateHelper {
        $this->title = $title;
        return $this;
    }

    /**
     * @return array
     */
    public function getJsParam(): array {
        return $this->jsParam;
    }

    /**
     * @param array $jsParam
     * @return BaseTemplateHelper
     */
    public function setJsParam(array $jsParam): BaseTemplateHelper {
        $this->jsParam = $jsParam;
        return $this;
    }

}