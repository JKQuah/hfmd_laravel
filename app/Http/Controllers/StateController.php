<?php

namespace App\Http\Controllers;

use App\ApexChart;
use App\Data;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class StateController extends Controller
{
    public function show($state)
    {
        if(!$this->verifyState($state)){
            return redirect()->back()->with("missing", "State doesn\'t not exist");
        }

        $districts = $this->getAllDistricts($state);
        $yearlyData = Data::where('state', $state)->get()->groupBy([
            function ($data) {
                return Carbon::parse($data->notificationDate)->format('Y');
            },
        ]);
        foreach ($yearlyData as $year => $cases) {
            $results[$year] = count($cases);
        }

        $counter = 0;
        foreach($results as $result => $count){
            if ($counter != 0) {
                $next = next($results);
                $prev = prev($results);

                if($next > $prev){
                    $grow = 'larger';
                } elseif($next < $prev){
                    $grow = 'smaller';
                } else {
                    $grow = 'equal';
                }
                $current = next($results);

                $summary[] = [
                    'year' => $result,
                    'count' => $count,
                    'grow' => $grow
                ];
            } else {
                $summary[] = [
                    'year' => $result,
                    'count' => $count,
                    'grow' => 'equal'
                ];
            }
            $counter++;
        }
        
        $sum = 0;
        foreach($results as $year => $count){
            $sum += $count;
        }
        
        return view('state.show', compact('state', 'districts', 'summary', 'sum'));
    }

    public function getStateOverYear(Request $request)
    {

        $state = $request->state;
        $yearlyData = Data::where('state', $state)->get()->groupBy([
            function ($data) {
                return Carbon::parse($data->notificationDate)->format('Y');
            }, 'status'
        ]);
        foreach ($yearlyData as $year => $status) {
            foreach ($status as $stat => $cases) {
                $results[$stat][] = count($cases);
            }
        }

        $colors = ['#ffa600',  '#EB5757']; // this color is more attractive
        $index = 0;
        foreach ($results as $stats => $data) {
            $chart = new ApexChart(ucwords($stats), $data);
            $chart->set_color($colors[$index++]);
            $chart->set_type('area');
            $chart_result['data'][] = $chart;
            $chart_result['category'] = $this->getAllYears();
            $chart_result['xlabel'] = $state;
            $chart_result['ylabel'] = 'Total number of cases';
        }
        return $chart_result;
    }

    public function getDistrictOverYear(Request $request)
    {
        $district = $request->district;
        $years = $this->getAllYears();
        $yearlyData = Data::where('district', $district)->get()->groupBy([
            function ($data) {
                return Carbon::parse($data->notificationDate)->format('Y');
            }, 'status'
        ]);
        foreach ($yearlyData as $year => $status) {
            foreach ($status as $stat => $cases) {
                $results[$stat][$year] = count($cases);
            }
        }
        foreach ($results as $status => $dataCount) {
            foreach ($years as $year) {
                if (!isset($results[$status][$year])) {
                    $results[$status][$year] = 0;
                }
            }
            ksort($results[$status]);
        }
        foreach ($results as $status => $datacount) {
            foreach ($datacount as $y => $count) {
                $final_results[$status][] = $count;
            }
        }
        $colors = ['#ffa600',  '#EB5757']; // this color is more attractive
        $index = 0;
        foreach ($final_results as $stats => $data) {
            $chart = new ApexChart(ucwords($stats), $data);
            $chart->set_color($colors[$index++]);
            $chart->set_type('area');
            $chart_result['data'][] = $chart;
            $chart_result['category'] = $this->getAllYears();
            $chart_result['xlabel'] = $district;
            $chart_result['ylabel'] = 'Total number of cases';
        }
        return $chart_result;
    }

    public function getAgeGroupOverYear(Request $request)
    {
        $district = $request->district;
        $years = $this->getAllYears();
        // $colors = ['#6FCF97', '#EB5757']; // infected (green) and death (red) color
        // $colors = ['#ffa600',  '#EB5757']; // this color is more attractive 
        $colors = $this->getAllColors();
        $ageCategories = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'];
        $index = 0;
        $charttpye = ['area', 'line'];
        $age_cases = [];

        $age_group = Data::where('district', $district)->get()->groupBy([
            function ($data) {
                $notificationDate = date_create($data->notificationDate);
                $birthday = date_create($data->birthday);
                return date_diff($notificationDate, $birthday, true)->format('%y');
            },
            function ($data) {
                return Carbon::parse($data->notificationDate)->format('Y');
            },
        ]);
        $age_group = $age_group->sortKeys();
        $age_count = [];
        foreach ($age_group as $age => $yearlyData) {
            foreach ($yearlyData as $year => $cases) {
                $age_count[$age][$year] = count($cases);
            }
        }

        foreach ($age_count as $age => $yearlyData) {
            foreach ($years as $y) {
                if (!isset($age_count[$age][$y])) {
                    $age_count[$age][$y] = 0;
                }
            }
            ksort($age_count[$age]);
        }

        foreach ($age_count as $age => $yearlyData) {
            foreach ($yearlyData as $yearly => $cases) {
                $all_age_count[$age][] = $cases;
            }
        }
        ksort($all_age_count);

        foreach ($ageCategories as $ageCategory) {
            if (!isset($all_age_count[$ageCategory])) {
                $all_age_count[$ageCategory] = [0, 0, 0, 0, 0, 0, 0];
            }
            ksort($all_age_count);
        }

        foreach ($ageCategories as $ageCat) {
            $age_result = new ApexChart("Age " . $ageCat, array_slice($all_age_count[$ageCat], 0, count($ageCategories), true));
            $age_result->set_color($colors[$index++]);
            $age_result->set_type('column');
            $age_cases['data'][] = $age_result;
        }
        // Customize chart
        $role = Auth::user()->role;
        if ($role == 'public') {
            $age_cases['download'] = false;
        } else {
            $age_cases['download'] = true;
        }
        $age_cases['xlabel'] = 'Age Groups';
        $age_cases['ylabel'] = 'Total number of cases';
        $age_cases['category'] = $years;
        return $age_cases;
    }
}
