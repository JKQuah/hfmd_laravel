<?php

namespace App\Http\Controllers\District;

use App\Data;

/**
 * 
 */
trait DistrictDataTraits
{
    public function getDistrictData($district)
    {
        $datas = Data::where('district', $district)
            ->orderBy('notificationDate')
            ->get();
        $datalist = [];
        foreach ($datas as $data) {
            $birthday = date('Y-m-d', strtotime($data['birthday']));
            $notificationDate = date('Y-m-d', strtotime($data['notificationDate']));
            $onsetDate = date('Y-m-d', strtotime($data['onsetDate']));
            $age = date_diff(date_create($birthday), date_create($notificationDate));
            $diff = date_diff(date_create($notificationDate), date_create($onsetDate));
            
            $datalist[] = [
                'gender' => $data['gender'],
                'year' => $age->format('%y'),
                'month' => $age->format('%m'),
                'day' => $age->format('%d'),
                'notificationDate' => $data['notificationDate'],
                'onsetDate' => $data['onsetDate'],
                'day_diff' => $diff->format('%d'),
                'status' => $data['status']
            ]; 
        }
        return $datalist;
    }
}
