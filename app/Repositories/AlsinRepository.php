<?php
namespace App\Repositories;

use App\Model\Area;
use App\Model\Opsin;

class AlsinRepository extends BaseRepository
{
    private $area;
    public function __construct(Opsin $model, Area $area)
    {
        $this->model = $model;
        $this->area = $area;
    }

}