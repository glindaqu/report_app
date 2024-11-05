<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/report/core/controllers/StatisticController.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/report/core/controllers/AttendanceController.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/report/core/View.php";

class Router
{
    private string $controller = "statistic";
    private string $action = "index";

    public function __construct()
    {
        $params = [];
        $splitted = explode("/", $_SERVER['REQUEST_URI']);
        $given_action = trim($splitted[3]);
        $given_controller = trim($splitted[2]);

        if ($given_action !== "") {
            $this->action = $given_action;
        }

        if ($given_controller !== "") {
            $this->controller = $given_controller;
        }

        if ($splitted[4]) {
            foreach (explode("&", $splitted[4]) as $param_str) {
                $params[explode("=", $param_str)[0]] = explode("=", $param_str)[1];
            }
        }

        $this->navigate($params);
    }

    public function navigate(array $params): void
    {
        $path = ucfirst($this->controller . 'Controller');
        if (!class_exists($path) || !method_exists($path, $this->action)) {
            View::page_not_found();
            exit;
        }
        $controller = new $path();
        $action = $this->action;
        $controller->$action($params);
    }

    public function route_dump(): string
    {
        return "action: $this->action, controller: $this->controller";
    }
}