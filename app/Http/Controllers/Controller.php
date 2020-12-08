<?php

namespace App\Http\Controllers;

use App\Data;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function getAllData()
    {
        return Data::get();
    }

    public function getAllStates()
    {
        $states = $this->getAllData()->map(function ($data) {
            return collect($data->toArray())
                ->only('state')
                ->all();
        })->unique('state')->flatten()->sort();
        return $states;
    }

    public function getAllDistricts($state)
    {
        $district = $this->getAllData()->where('state', $state)
            ->map(function ($data) {
                return collect($data->toArray())
                    ->only('district')
                    ->all();
            })->unique('district')->flatten();
        return $district;
    }

    public function getAllYears()
    {
        $years = $this->getAllData()->unique(function($item){
            return date('Y', strtotime($item['notificationDate']));
        })->map(function($item){
            return date('Y', strtotime($item['notificationDate']));
        })->sort()->values()->toArray();
        return $years;
    }

    public function getAllMonths()
    {
        return ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    }

    public function getAllWeeks()
    {
        $all_weeks = [];
        for ($i = 1; $i <= 53; $i++) {
            $all_weeks[] = $i;
        }
        return $all_weeks;
    }

    public function getAllColors()
    {
        $colors = ["#ffa600","#ff7c43","#f95d6a", "#d45087", "#a05195", 
                    "#665191","#2f4b7c", "#003f5c", "#488f31", "#89b050",
                    "#c5d275","#fff59f","#f8bc6c","#ec8052","#de425b",
                    
                    "#D9CEB2","#948C75","#D5DED9","#7A6A53","#99B2B7",
		            "#FFFFFF","#CBE86B","#F2E9E1","#1C140D","#CBE86B",
		            "#EFFFCD","#DCE9BE","#555152","#2E2633","#99173C"];
        return $colors;
    }
}
