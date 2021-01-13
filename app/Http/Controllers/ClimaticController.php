<?php

namespace App\Http\Controllers;

use App\ApexChart;
use App\Climatic;
use App\Data;
use App\Http\Controllers\State\StateDataTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ClimaticController extends Controller
{
    use StateDataTraits;

    public function index($year, $state)
    {

        if (!$this->verifyState($state)) {
            return redirect()->back()->with("missing", "State doesn\'t not exist in the database");
        }

        if (!$this->verifyYear($year)) {
            return redirect()->back()->with("missing", "Year " . $year . " doesn\'t not exist in the database.");
        }
        $this_year = $year;
        $this_state = $state;
        $years = $this->getAllYears();
        $states = $this->getAllStates();
        // if(!in_array($this_year, $years)){
        //     return abort(404);
        // }
        $climatic_data = Climatic::where('year', $year)->where('state', $state)->get();
        $data_temp = [];
        $data_rainAmount = [];
        $data_rainDay = [];
        $data_humidity = [];

        foreach ($climatic_data as $value) {
            $data_temp[] += $value->temperature;
            $data_rainAmount[] += $value->rainAmount;
            $data_rainDay[] += $value->rainDay;
            $data_humidity[] += $value->humidity;
        }
        $mean_temp = collect($data_temp)->avg();
        $mean_rainAmount = collect($data_rainAmount)->avg();
        $mean_rainDay = collect($data_rainDay)->avg();
        $mean_humidity = collect($data_humidity)->avg();

        return view('climatic.index', compact(
            'years',
            'states',
            'this_year',
            'this_state',
            'mean_temp',
            'mean_rainAmount',
            'mean_rainDay',
            'mean_humidity',
        ));
    }

    public function getStateYearlyData($year, $state)
    {
        // get monthly data using groupby
        $monthly_data = Data::whereYear('notificationDate', $year)->where('state', $state)->get()->groupBy([
            function ($date) {
                return Carbon::parse($date->notificationDate)->format('M');
            }
        ]);
        foreach ($monthly_data as $month => $cases) {
            $result[] = count($cases);
        }
        return $result;
    }

    public function createApexChart($name, $data, $color, $type)
    {
        $chart = new ApexChart($name, $data);
        $chart->set_color($color);
        $chart->set_type($type);
        return $chart;
    }

    public function getClimaticChart(Request $request)
    {
        $year = $request->year;
        $state = $request->state;
        $type = $request->type;
        $monthly_data = $this->getStateYearlyData($year, $state);
        $climatic_data = Climatic::where('year', $year)->where('state', $state)->get();
        $data_temp = [];
        $data_rainAmount = [];
        $data_rainDay = [];
        $data_humidity = [];

        foreach ($climatic_data as $value) {
            $data_temp[] += $value->temperature;
            $data_rainAmount[] += $value->rainAmount;
            $data_rainDay[] += $value->rainDay;
            $data_humidity[] += $value->humidity;
        }

        $total = $this->createApexChart('Total Number of Cases', $monthly_data, '#ffa600', 'bar');
        switch ($type) {
            case 'temperature':
                $res = $this->createApexChart('24h Mean Temperature', $data_temp, '#eb5757', 'line');
                break;
            case 'rainfall':
                $res = $this->createApexChart('Rainfall Amount', $data_rainAmount, '#004c97', 'line');
                break;
            case 'rainday':
                $res = $this->createApexChart('Number of Rain day', $data_rainDay, '#2dbecd', 'line');
                break;
            case 'humidity':
                $res = $this->createApexChart('Relative Humidity', $data_humidity, '#27ae60', 'line');
                break;
            default:
                $res = null;
                break;
        }
        $result['data'] = [$total, $res];
        $result['category'] = $this->getAllMonths();
        $role = Auth::user()->role;
        if ($role == 'public') {
            $result['download'] = false;
        } else {
            $result['download'] = true;
        }
        return $result;
    }
}
