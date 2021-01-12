<?php

namespace App\Imports;

use App\Data;
use Illuminate\Support\Facades\Date;
use Maatwebsite\Excel\Concerns\ToModel;

class DataImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Data([
            //
            'dataID' => $row[0],
            'state' => $row[1],
            'district' => $row[2],
            'gender' => $row[3],
            'birthday' => date('Y-m-d', ($row[4] - 25569) * 86400),
            'notificationDate' => date('Y-m-d', ($row[5] - 25569) * 86400),
            'onsetDate' => date('Y-m-d', ($row[6] - 25569) * 86400),
            'status' => $row[7],
            'deleted_at' => null,
            'updated_at' => date('Y-m-d'),
        ]);
    }
}
