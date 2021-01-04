<?php

namespace App\Http\Controllers\District;

use App\ApexChart;
use App\Data;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * 
 */
trait DistrictChartTraits
{
    // Default: bar(infected) and line(death)
    public function getMonthlyChart(Request $request)
    {
        $district = $request->district;
        $year = $request->year;
        $all_months = $this->getAllMonths();

        // get monthly data using groupby
        $monthly_data = Data::where('district', $district)->whereYear('notificationDate', $year)->get()->groupBy([
            'status',
            function ($data) {
                return Carbon::parse($data->notificationDate)->format('M');
            }

        ]);
        $result = [];
        foreach ($all_months as $this_month) {
            $month_array[$this_month] = 0;
        }

        foreach ($monthly_data as $status => $months) {
            foreach ($months as $month => $case) {
                $result[$status][$month] = count($case);
            }
            foreach ($month_array as $month => $case) {
                if (!isset($result[$status][$month])) {
                    $result[$status][$month] = 0;
                }
            }
            // sort the data monthly count array according to month
            uksort($result[$status], function ($month1, $month2) {
                $time1 = strtotime($month1);
                $time2 = strtotime($month2);
                return $time1 - $time2;
            });
        }
        
        $chart_data = [];
        $monthly_cases = [];
        $colors = ['#6FCF97', '#EB5757']; // infected (green) and death (red) color
        $colors = ['#ffa600',  '#EB5757']; // this color is more attractive 
        $charttpye = ['bar', 'line'];
        $index = 0;
        foreach ($result as $status => $months) {
            foreach ($months as $month => $count) {
                $chart_data[$status][] = $count;
            }
            $monthly_case = new ApexChart(ucwords($status), $chart_data[$status]);
            $monthly_case->set_color($colors[$index]);
            $monthly_case->set_type($charttpye[$index++]);
            $monthly_cases['data'][] = $monthly_case;
        }

        // Customize chart
        $role = Auth::user()->role;
        if ($role == 'public') {
            $monthly_cases['download'] = false;
        } else {
            $monthly_cases['download'] = true;
        }
        $monthly_cases['categories'] = $all_months;
        $monthly_cases['xlabel'] = $year;
        $monthly_cases['ylabel'] = 'Total number of cases';
        return $monthly_cases;
    }

    // Default: Line
    public function getAgeGroupChart(Request $request)
    {
        $district = $request->district;
        $year = $request->year;
        $colors = ['#6FCF97', '#EB5757']; // infected (green) and death (red) color
        $colors = ['#ffa600',  '#EB5757']; // this color is more attractive 
        $ageCategories = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
        $index = 0;
        $charttpye = ['column', 'line'];
        $age_cases = [];

        $age_group = Data::whereYear('notificationDate', $year)->where('district', $district)->get()->groupBy([
            'status', 
            function ($data) {
                $notificationDate = date_create($data->notificationDate);
                $birthday = date_create($data->birthday);
                return date_diff($notificationDate, $birthday, true)->format('%y');
            }
        ]);
        $age_count = [];
        foreach ($age_group as $status => $ageGroups) {
            foreach($ageGroups as $age => $cases){
                $age_count[$status][$age] = count($cases);
            }
            ksort($age_count[$status]);
        }
        

        foreach ($age_group as $status => $ageGroups) {
            foreach($ageCategories as $ageCategory){
                if(!isset($age_count[$status][$ageCategory])){
                    $age_count[$status][$ageCategory] = 0;
                }
            }
            ksort($age_count[$status]);
            
            $age_result = new ApexChart(ucwords($status), array_slice($age_count[$status], 0, count($ageCategories), true));
            $age_result->set_color($colors[$index]);
            $age_result->set_type($charttpye[$index++]);
            $age_cases['data'][] = $age_result;
        }

        // Customize chart
        $role = Auth::user()->role;
        if ($role == 'public') {
            $age_cases['download'] = false;
        } else {
            $age_cases['download'] = true;
        }
        $monthly_cases['categories'] = $ageCategories;
        $age_cases['xlabel'] = 'Age Groups';
        $age_cases['ylabel'] = 'Total number of cases';
        return $age_cases;
    }

    // Default: Line
    public function getWeekylChart(Request $request)
    {
    }

    // Default: Heatmap
    public function getDailyChart(Request $request)
    {
    }
}
