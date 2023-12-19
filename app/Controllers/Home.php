<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $query = $db->query('SELECT version()');
        $row = $query->getRow();

        echo 'Version de PostgreSQL : ' . $row->version;
    }
}
