<?php

namespace App\Http\Controllers\State;

use App\ApexChart;
use Illuminate\Http\Request;
use App\Data;
use App\Counter;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

trait StateChartTraits
{
    public function getDailyData()
    {
        $daily_data = Data::get()->groupBy([
            function ($date) {
                return Carbon::parse($date->notificationDate)->format('Y');
            },
            'state',
            function ($date) {
                return Carbon::parse($date->notificationDate)->format('M d Y');
            }
        ], $preserveKeys = true);

        $daily_array = [];
        // count daily data
        foreach ($daily_data as $year => $states) {
            foreach ($states as $state => $days) {
                foreach ($days as $day => $value) {
                    $daily_case = new Counter();
                    $daily_case->set_id(Str::uuid());
                    $daily_case->set_name($state);
                    $daily_case->set_date($day);
                    if (count($value) > 1) {
                        $daily_case->set_description(count($value) . " cases");
                    } else {
                        $daily_case->set_description(count($value) . " case");
                    }
                    $daily_case->set_type('event');
                    $daily_array[] = $daily_case;
                }
            }
        }

        return $daily_array;
    }

    public function getLocalityChart(Request $request)
    {
        // Retrieve all required data
        $year = $request->year;
        $state = $request->state;
        $analysed = $this->getStateMonthlyData($year, $state);
        $data = $analysed['district_analysis'];
        $colors = ['#6FCF97', '#EB5757']; // infected (green) and death (red) color 
        $colors = ['#ffa600',  '#EB5757']; // this color is more attractive

        // Declare array to store data
        $local_array = [];
        $reversed_data = [];

        // Due to the array is not suitable for ploting, I switch the parent and children of the array
        foreach ($data as $state => $values) {
            $local_array['labels'][] = $state;
            foreach ($values as $factor => $count) {
                if ($factor == 'infected' || $factor == 'death') {
                    $reversed_data[$factor][$state] = $count;
                }
            }
        }

        $index = 0;
        foreach ($reversed_data as $factor => $states) {
            $count_array = [];
            foreach ($states as $state => $count) {
                $count_array[] = $count;
            }
            $local_case = new ApexChart(ucwords($factor), $count_array);
            $local_case->set_color($colors[$index]);
            $local_case->set_type('bar');
            $local_array['data'][] = $local_case;
            $index++;
        }

        // Customize chart
        $role = Auth::user()->role;
        if ($role == 'public') {
            $local_array['download'] = false;
        } else {
            $local_array['download'] = true;
        }
        $local_array['type'] = 'bar';
        $local_array['xlabel'] = 'Groups';
        $local_array['ylabel'] = 'Total number of cases';
        return $local_array;
    }

    public function getGenderChart(Request $request)
    {
        $local_array = [];
        $year = $request->year;
        $state = $request->state;
        $analysed = $this->getStateMonthlyData($year, $state);
        $data = $analysed['district_analysis'];
        $colors = $this->getAllColors();

        foreach ($data as $state => $info) {
            $local_array['labels'][] = $state;
            if ($request->gender == 'male') {
                $local_array['data'][] = $info['male'];
            } else {
                $local_array['data'][] = $info['female'];
            }
        }

        // Customize chart
        $local_array['type'] = 'pie'; // donut
        $local_array['colors'] = array_slice($colors, 0, count($data));

        return $local_array;
    }

    public function getDailyChart(Request $request)
    {
        $year = $request->year;
        $state = $request->state;

        $daily_data = Data::where('state', $state)->whereYear('notificationDate', $year)->get()->groupBy([
            function ($date) {
                return Carbon::parse($date->notificationDate)->format('M');
            },
            function ($date) {
                return Carbon::parse($date->notificationDate)->format('Y-m-d');
            }
        ], $preserveKeys = true);

        $date = [];
        $daily_array = [];
        $start = $year . '-01-01';
        $end = $year . '-12-31';

        // count daily data
        foreach ($daily_data as $month => $days) {
            foreach ($days as $day => $cases) {
                $daily_array[$month][$day] = count($cases);
            }
        }

        // generate date in given year
        while (strtotime($start) <= strtotime($end)) {
            $start = date("Y-m-d", strtotime($start));
            $date[date("M", strtotime($start))][$start] = 0;
            $start = date("Y-m-d", strtotime("+1 days", strtotime($start)));
        }

        // Insert count in every date
        foreach ($date as $month => $days) {
            foreach ($days as $day => $count) {
                foreach ($daily_array[$month] as $daily => $cases) {
                    if ($day == $daily) {
                        $date[$month][$day] = $cases;
                    }
                }
            }
        }

        $arr = [];
        foreach ($date as $month => $days) {
            foreach ($days as $day => $count) {
                $arr[$month][] = $count;
            }
        }
        $local_array = [];
        foreach ($arr as $month => $count) {
            $daily = new ApexChart($month, $count);
            $local_array['data'][] = (object) array_filter((array) $daily);
        }
        return $local_array;
    }

    public function getAgeGroupChart(Request $request)
    {
        $state = $request->state;
        $year = $request->year;
        $colors = ['#6FCF97', '#EB5757']; // infected (green) and death (red) color
        $colors = ['#ffa600',  '#EB5757']; // this color is more attractive 
        $ageCategories = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
        $index = 0;
        $charttpye = ['column', 'line'];
        $age_cases = [];

        $age_group = Data::whereYear('notificationDate', $year)->where('state', $state)->get()->groupBy([
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
}
