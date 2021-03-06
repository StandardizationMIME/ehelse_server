<?php

require_once __DIR__ . '/../models/Status.php';
require_once __DIR__ . '/../responses/Response.php';
require_once __DIR__ . '/../responses/ErrorResponse.php';
require_once __DIR__ . '/../dbmappers/StatusDBMapper.php';
require_once __DIR__ . '/../responses/ResponseController.php';

class StatusController extends ResponseController
{

    /**
     * StatusController constructor.
     * @param $path
     * @param $method
     * @param $body
     */
    public function __construct($path, $method, $body)
    {
        $this->init($path, $method, $body);
        $this->db_mapper = 'StatusDBMapper';
        $this->list_name = 'status';
        $this->model = 'Status';

    }
}