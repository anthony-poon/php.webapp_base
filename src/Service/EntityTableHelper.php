<?php

namespace App\Service;

class EntityTableHelper {
    public const COL_DATE = "date";
    public const COL_NUM = "num";
    public const COL_NUM_FMT = "num_fmt";
    public const COL_HTML_NUM = "html_num";
    public const COL_HTML_NUM_FMT = "html_num_fmt";
    public const COL_HTML = "html";
    public const COL_STRING = "string";
    private $header = [];
    private $body = [];
    private $router;
    private $columnType = [];
    private $btn = [];
    private $haveToolbar = false;
    public function __construct(\Symfony\Component\Routing\RouterInterface $router) {
        $this->router = $router;
    }

    public function addButton(string $name, string $path, ?array $param = null): EntityTableHelper {
        $this->btn[] = [
            "name" => $name,
            "path" => $this->router->getRouteCollection()->get($path)->getPath(),
            "param" => $param
        ];
        return $this;
    }

    public function addRow(array $row, ?array $param = []) {
        if (!$this->haveToolbar && !empty($param["read"]) || !empty($param["update"]) || !empty($param["delete"])) {
            $this->haveToolbar = true;
        }
        $this->body[] = [
            "content" => $row,
            "param" => $param
        ];
    }

    /**
     * @param array $header
     * @return EntityTableHelper
     */
    public function setHeader(array $header): EntityTableHelper {
        $this->header = $header;
        return $this;
    }

    public function setColumnType(int $index, $type) {
        $this->columnType[$index] = $type;
    }

    public function compile(): array {
        $column = [];
        for ($i = 0; $i < count($this->header); $i++) {
            $attr = [];
            if (!empty($this->columnType[$i])) {
                $attr["type"] = $this->columnType[$i];
            }
            if ($attr) {
                $column[] = $attr;
            } else {
                $column[] = null;
            }
        }
        return [
            "haveToolbar" => $this->haveToolbar,
            "column" => json_encode($column),
            "btn" => json_encode($this->btn),
            "body" => $this->body,
            "header" => $this->header,
        ];
    }
}