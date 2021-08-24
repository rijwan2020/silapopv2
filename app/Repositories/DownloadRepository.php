<?php
namespace App\Repositories;

use App\Model\Download;

class DownloadRepository extends BaseRepository
{
    public function __construct(Download $model)
    {
        $this->model = $model;
    }
}