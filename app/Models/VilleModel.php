<?php namespace App\Models;

use CodeIgniter\Model;

class VilleModel extends Model
{
    protected $table ="Ville";

    protected $returnType = Ville::Class;

    protected $allowedFields = [
        'insee_code',
        'city_code',
        'latitude',
        'longitude',
        'department_number',
    ];

}

?>