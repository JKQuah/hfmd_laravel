<?php

namespace App\Http\Controllers\District;

use App\ApexChart;
use App\Data;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

/**
 * 
 */
trait DistrictChartTraits
{
    // Default Bar
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

        $monthly_cases['categories'] = $all_months;
        $monthly_cases['xlabel'] = $year;
        $monthly_cases['ylabel'] = 'Total number of cases';
        return $monthly_cases;
    }

    // Default: Line
    public function getAgeGroupChart(Request $request)
    {
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
