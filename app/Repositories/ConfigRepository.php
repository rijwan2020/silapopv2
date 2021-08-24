<?php
namespace App\Repositories;

use App\Model\Config;

class ConfigRepository extends BaseRepository
{
    public function __construct(Config $model)
    {
        $this->model = $model;
    }
}