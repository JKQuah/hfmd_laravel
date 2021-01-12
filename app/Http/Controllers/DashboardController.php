<?php

namespace App\Http\Controllers;

use App\ApexChart;
use Illuminate\Http\Request;
use App\Data;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDO;

class DashboardController extends Controller
{
    public function index()
    {

        $datas = Data::get();
        $years = $this->getAllYears();
        $states = $this->getAllStates();

        /** get total cases from available years */
        $total_infected = $datas->where('status', 'infected')->count();
        $total_deaths = $datas->where('status', 'death')->count();
        $total_cases = $total_infected + $total_deaths;
        $overall = [
            'min_year' => $years[0],
            'max_year' => end($years),
            'total' => $total_cases,
            'infected' => $total_infected,
            'death' => $total_deaths,
        ];

        rsort($years);
        /** get total cases for every years */
        $summary_by_year = [];
        foreach ($years as $year) {
            $total_infected_per_year = Data::select('notificationDate', 'status')
                ->where('status', 'infected')
                ->whereYear('notificationDate', $year)
                ->get()->count();
            $total_deaths_per_year = Data::select('notificationDate', 'status')
                ->where('status', 'death')
                ->whereYear('notificationDate', $year)
                ->get()->count();
            $summary_by_year[] = [
                'year' => $year,
                'total' => $total_infected_per_year + $total_deaths_per_year,
                'infected' => $total_infected_per_year,
                'death' => $total_deaths_per_year
            ];
        }

        $colors = array_slice($this->getAllColors(), 0, 7);

        return view('dashboard.index', [
            'overall' => $overall,
            'summary' => array_chunk($summary_by_year, 5),
            'years' => $years,
            'states' => $states,
            'colors' => $colors,
        ]);
    }

    public function getLineChart()
    {
        $years = Data::select(DB::raw('YEAR(notificationDate) as year'))->distinct()->orderBy('year')->get();
        $total = [];
        $infected = [];
        $death = [];
        $years_exist = [];

        foreach ($years as $year) {
            $total_infected_per_year = Data::select('notificationDate', 'status')
                ->where('status', 'infected')
                ->whereYear('notificationDate', $year->year)
                ->get()->count();
            $total_deaths_per_year = Data::select('notificationDate', 'status')
                ->where('status', 'death')
                ->whereYear('notificationDate', $year->year)
                ->get()->count();
            $infected[] += $total_infected_per_year;
            $death[] += $total_deaths_per_year;
            $total[] += $total_infected_per_year + $total_deaths_per_year;
            $years_exist[] += $year->year;
        }
        $role = Auth::user()->role;
        if ($role == 'public') {
            $download = false;
        } else {
            $download = true;
        }
        return response()->json([
            'years' => $years_exist,
            'total' => $total,
            'infected' => $infected,
            'death' => $death,
            'download' => $download
        ]);
    }

    public function getAgeChart()
    {

        $age_groups = Data::get()->groupBy([
            function ($data) {
                return Carbon::parse($data->notificationDate)->format('Y');
            },
            function ($data) {
                $notificationDate = date_create($data->notificationDate);
                $birthday = date_create($data->birthday);
                return date_diff($notificationDate, $birthday, true)->format('%y');
            }
        ]);

        foreach ($age_groups as $year => $age_group) {
            foreach ($age_group as $age => $cases) {
                if (intval($age) <= 12) {
                    $age_array[$age][] = count($cases);
                }
            }
            ksort($age_array);
        }

        $results = [];
        $colors = $this->getAllColors();
        $index = 0;
        foreach ($age_array as $age => $count) {
            $age_chart = new ApexChart("Age " . $age, $count);
            $age_chart->set_color($colors[$index++]);
            $results['data'][] = (object) array_filter((array) $age_chart);
        }

        //Customize chart
        $role = Auth::user()->role;
        if ($role == 'public') {
            $results['download'] = false;
        } else {
            $results['download'] = true;
        }
        $results['category'] = $this->getAllYears();
        $results['xlabel'] = "Age Group";
        $results['ylabel'] = "Number of cases in Percentage(%)";
        return $results;
    }

    public function getDistrictDetails(Request $request)
    {
        $state = $request->state;
        $district = $request->district;
        $year = $request->year;
        return redirect()->route('district.index', [$year, $state, $district]);
    }

    public function getGeographicData(Request $request)
    {
        $year = $request->year;
        $data = Data::whereYear('notificationDate', $year)->get()->groupBy([
            'state',
            'status'
        ]);
        $index = 0;
        foreach ($data as $state => $status) {
            $results[$index]['state'] = ucwords(strtolower($state));
            $total = 0;
            foreach ($status as $stat => $cases) {
                $results[$index][$stat] = count($cases);
                $total += count($cases);
            }
            if(!isset($results[$index]['death'])){
                $results[$index]['death'] = 0;
            }

            $results[$index]['total'] = $total;
            $index++;
        }
        $colors = array_slice($this->getAllColors(), 0, 7);
        $hover_colors = ["#b37400", "#f64a00", "#f61326", "#ad2b61", "#6d3766",  "#433660", "#1a2945"];
        foreach ($results as $result) {
            $sum = $result['total'];
            switch ($sum) {
                case ($sum < 500):
                    $color = $colors[0];
                    $hover_color = $hover_colors[0];
                    break;
                case ($sum < 1000):
                    $color = $colors[1];
                    $hover_color = $hover_colors[1];
                    break;
                case ($sum < 1500):
                    $color = $colors[2];
                    $hover_color = $hover_colors[2];
                    break;
                case ($sum < 2000):
                    $color = $colors[3];
                    $hover_color = $hover_colors[3];
                    break;
                case ($sum < 2500):
                    $color = $colors[4];
                    $hover_color = $hover_colors[4];
                    break;
                case ($sum < 3000):
                    $color = $colors[5];
                    $hover_color = $hover_colors[5];
                    break;
                default:
                    $color = $colors[6];
                    $hover_color = $hover_colors[6];
                    break;
            }
            $geodata[$result['state']] = (object)[
                'name' => $result['state'],
                'description' => '<img src="img/states/'. strtoupper($result['state']). '.png" class="border border-dark" style="height:25px; width: 50px"><ul class="map-state-label pl-0"><li>Infected: ' . $result['infected'] . ' cases</li><li>Death: ' . $result['death'] . ' cases</li></ul>',
                'color' => $color, 
                'hover_color' => $hover_color,
                'url' => route('data.show', ['year'=>$year, 'state'=>strtoupper($result['state'])]),
            ];
        }
        return $geodata;
    }
}
