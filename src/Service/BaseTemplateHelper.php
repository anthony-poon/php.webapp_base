<?php
namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Routing\RouterInterface;

class BaseTemplateHelper {
    private $sideMenu = [];
    private $title = "Web Application";
    private $jsParam = [];
    private $params;
    public function __construct(ParameterBagInterface $paramsBag, RouterInterface $router) {
        $this->params = $paramsBag->get("side_menu");
        foreach ($this->params as $p) {
            $item = [
                "text" => $p["text"],
                "icon" => $p["icon"] ?? null,
                "url" => $router->generate($p["route"]),
            ];
            if ($p["child"] ?? false) {
                $item["child"][] = [
                    "text" => $p["child"]["text"],
                    "icon" => $p["child"]["icon"] ?? null,
                    "url" => $router->generate($p["child"]["route"])
                ];
            }
            $this->sideMenu[] = $item;
        }
    }

    /**
     * @return array
     */
    public function getSideMenu(): array {
        return $this->sideMenu;
    }

    public function addSideMenuItem(array $item) {
        $this->sideMenu[] = $item;
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