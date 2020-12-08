<?php

namespace App\Http\Controllers;

use App\Data;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PDO;

class AnalyticsController extends Controller
{
    public function index()
    {
        $months = $this->getAllMonths();
        $years = $this->getAllYears();
        $states = $this->getAllStates();
        $firstHalfYears = array_slice($months, 0, 6);
        $secondHalfYears = array_slice($months, 6);
        // $states_array = Data::select('state', 'district')
        //     ->get()
        //     ->groupBy(['state', 'district'])
        //     ->toArray();
        // foreach ($states_array as $states => $districts) {
        //     foreach ($districts as $district => $data) {
        //         $state_collections[$states][] = $district;
        //     }
        // }

        return view('analytics.index', compact(
            'years',
            'months',
            'states',
            // 'state_collections',
            'firstHalfYears',
            'secondHalfYears'
        ));
    }

    public function getDistrict(Request $request)
    {
        $state = $request->state;
        $districts = Data::where('state', $state)->get()->groupBy('district')->keys();
        foreach ($districts as $district) {
            $district_obj[] = (object)[
                'label' => $district,
                'title' => $district,
                'value' => $district
            ];
        }
        return $district_obj;
    }

    public function getAnalyticsResult(Request $request)
    {
        $requests = $request['request'];
        $result = [];
        // dd($requests);
        foreach ($requests as $index => $req) {
            foreach ($req as $variable => $value) {
                switch ($variable) {
                    case 'state':
                        $result[$index]['state'] = $value;
                        break;
                    case 'district':
                        foreach ($value as $district) {
                            // $result[$index]['district'][$district] = Data::where('state', $req['state'])->where('district', $district)->get()->count();
                        }
                        break;
                    case 'year':
                        foreach ($req['district'] as $district) {
                            foreach ($value as $year) {
                                $result[$index]['district'][$district]['yearly']['year'][] = $year;
                                $result[$index]['district'][$district]['yearly']['data'][] = Data::where('district', $district)->whereYear('notificationDate', $year)->get()->count();
                            }
                        }
                        break;
                    case 'month':
                        foreach ($req['district'] as $district) {
                            foreach ($req['year'] as $year) {
                                foreach ($value as $month) {
                                    $result[$index]['district'][$district]['monthly'][$year]['month'][] = $month;
                                    $result[$index]['district'][$district]['monthly'][$year]['data'][] = Data::where('district', $district)
                                        ->whereYear('notificationDate', $year)
                                        ->whereMonth('notificationDate', $month)->get()->count();
                                }
                            }
                        }
                        break;
                    case 'week':
                        $weekly = null;
                        if($value != null){
                            foreach ($req['district'] as $district) {
                                foreach ($req['year'] as $year) {
                                    $weekly[$index]['district'][$district]['yearly'][$year] = Data::where('district', $district)->whereYear('notificationDate', $year)->get()->groupBy([
                                        function ($data) use ($value) {
                                            return Carbon::parse($data->notificationDate)->format('W') == $value ?? '';
                                        }
                                    ]);
                                }
                            }
                        }
                        break;
                    default:
                        # code...
                        break;
                }
            }

            // $data[$req['state']][] = $req['state'];
            // $districts = collect($req['district']);
            // $data[$req['state']]['data'] = $districts->crossJoin($req['year'], $req['month']);
        }
        dd($result);
    }
}
