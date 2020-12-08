<?php

namespace App\Http\Controllers;

use App\Data;
use Illuminate\Support\Carbon;
use App\Http\Controllers\State\StateChartTraits;
use App\Http\Controllers\State\StateDataTraits;

class DataController extends Controller
{
    use StateChartTraits;
    use StateDataTraits;

    public function index()
    {
        $data_analysed = $this->getYearlyAnalysedData();
        return view('data.index', compact('data_analysed'));
    }

    public function show($year, $state)
    {
        $data_weekly_count = $this->getStateWeeklyData($year, $state);
        $analysed = $this->getStateMonthlyData($year, $state);
        $all_weeks = $this->getAllWeeks();
        $all_months = $this->getAllMonths();

        $data_monthly_count = $analysed['data_monthly_count'];
        $district_analysis = $analysed['district_analysis'];      

        $total_infected_per_year = $analysed['total_infected_per_year'];
        $total_death_per_year  = $analysed['total_death_per_year'];
        $total_male_per_year = $analysed['total_male_per_year'];
        $total_female_per_year = $analysed['total_female_per_year'];

        // Declare categories
        $category_A = $analysed['category_A'];
        $range_A = 100;
        $category_B = $analysed['category_B'];
        $range_B = 200;
        $category_C = $analysed['category_C'];
        $range_C = 300;
        $category_D = $analysed['category_D'];

        // get analysed result
        $analysed_result = [
            'highest' => $this->getStatisticsResult($district_analysis, 'max'),
            'lowest' => $this->getStatisticsResult($district_analysis, 'min'),
            'average' => $this->getStatisticsResult($district_analysis, 'avg'),
        ];


        $category_data = [];
        $category_data[] = array(
            'type' => 'A',
            'range' => '<' . $range_A,
            'count' => count($category_A),
            'data' => $category_A,
        );
        $category_data[] = array(
            'type' => 'B',
            'range' => $range_A . '-' . $range_B,
            'count' => count($category_B),
            'data' => $category_B,
        );
        $category_data[] = array(
            'type' => 'C',
            'range' => ($range_B + 1) . '-' . $range_C,
            'count' => count($category_C),
            'data' => $category_C,
        );
        $category_data[] = array(
            'type' => 'D',
            'range' => '>' . $range_C,
            'count' => count($category_D),
            'data' => $category_D,
        );

        return view('data.show', [
            'this_state' => $state,
            'this_year' => $year,
            'states' => $this->getAllStates(),
            'years' => $this->getAllYears(),
            'months' => $all_months,
            'weeks' => $all_weeks,
            'total_infected' => $total_infected_per_year,
            'total_deaths' => $total_death_per_year,
            'total_male' => $total_male_per_year,
            'total_female' => $total_female_per_year,
            'monthly_details' => $data_monthly_count,
            'monthly_analysis' => $district_analysis,
            'analysed_result' => $analysed_result,
            'weekly_details' => $data_weekly_count,
            'category_data' => $category_data,
        ]);
    }

    public function getStatisticsResult($array, $action)
    {
        $variables = ['total', 'infected', 'death', 'male', 'female'];
        $output = [];
        foreach ($variables as $variable) {
            $arr = array_column($array, $variable);
            $result = 0;

            // Done all calucaltion according to action
            switch ($action) {
                case 'max':
                    $result = max($arr);
                    break;
                case 'min':
                    $result = min($arr);
                    break;
                case 'avg':
                    $result = array_sum($arr) / count($arr);
                    return $output[$variable] = [
                        'count' => $result
                    ];
                    break;
                default:
                    $result = -1;
                    break;
            }
            // search the state name
            $resultState = array_search($result, $arr);
            $output[$variable] = [
                'state' => array_keys($array)[$resultState],
                'count' => $result
            ];
        }
        return $output;
    }

    public function getYearlyAnalysedData()
    {
        // get monthly data using groupby
        $monthly_data = Data::get()->groupBy([
            function ($date) {
                return Carbon::parse($date->notificationDate)->format('Y');
            },
            'state',
            function ($date) {
                return Carbon::parse($date->notificationDate)->format('M');
            }
        ], $preserveKeys = true);

        // get weekly data using groupby
        $weekly_data = Data::get()->groupBy([
            function ($date) {
                return Carbon::parse($date->notificationDate)->format('Y');
            },
            'state',
            function ($date) {
                return Carbon::parse($date->notificationDate)->format('W');
            }
        ], $preserveKeys = true);


        $data_monthly_count = [];
        $data_weekly_count = [];
        $state_analysis = [];
        $all_months = $this->getAllMonths();
        $all_weeks = $this->getAllWeeks();

        // Loop availble years
        foreach ($monthly_data as $year => $states) {
            // Declare variables to store values per year
            $total_case_per_year = 0;
            $total_infected_per_year = 0;
            $total_death_per_year = 0;
            $total_male_per_year = 0;
            $total_female_per_year = 0;
            $total_infected_male_per_year = 0;
            $total_infected_female_per_year = 0;
            $total_death_male_per_year = 0;
            $total_death_female_per_year = 0;

            // Loop availble states
            foreach ($states as $state => $months) {
                // Declare variables to store values per month
                $total_case_per_state = 0;
                $total_infected_per_state = 0;
                $total_death_per_state = 0;
                $total_male_per_state = 0;
                $total_female_per_state = 0;
                $total_infected_male_per_state = 0;
                $total_infected_female_per_state = 0;
                $total_death_male_per_state = 0;
                $total_death_female_per_state = 0;

                // Loop existing months
                foreach ($months as $month => $cases) {
                    $data_monthly_count[$state][$month] = count($cases);
                    $total_case_per_state += count($cases);
                    $total_infected_per_state += count($cases->where('status', 'infected'));
                    $total_death_per_state += count($cases->where('status', 'death'));
                    $total_male_per_state += count($cases->where('gender', 'Male'));
                    $total_female_per_state += count($cases->where('gender', 'Female'));
                    $total_infected_male_per_state += count($cases->where('status', 'infected')->where('gender', 'Male'));
                    $total_infected_female_per_state += count($cases->where('status', 'infected')->where('gender', 'Female'));
                    $total_death_male_per_state += count($cases->where('status', 'death')->where('gender', 'Male'));
                    $total_death_female_per_state += count($cases->where('status', 'death')->where('gender', 'Female'));
                }

                // Insert 'zero' to non-exisiting months
                foreach ($all_months as $month) {
                    if (!isset($data_monthly_count[$state][$month])) {
                        $data_monthly_count[$state][$month] = 0;
                    }
                }

                // sort the data monthly count array according to month
                uksort($data_monthly_count[$state], function ($month1, $month2) {
                    $time1 = strtotime($month1);
                    $time2 = strtotime($month2);
                    return $time1 - $time2;
                });

                $state_analysis[$year][$state]['total'] = $total_case_per_state;
                $state_analysis[$year][$state]['infected'] = $total_infected_per_state;
                $state_analysis[$year][$state]['death'] = $total_death_per_state;
                $state_analysis[$year][$state]['male'] = $total_male_per_state;
                $state_analysis[$year][$state]['female'] = $total_female_per_state;
                // $state_analysis[$year][$state]['infected_male'] = $total_infected_male_per_state;
                // $state_analysis[$year][$state]['infected_female'] = $total_infected_female_per_state;
                // $state_analysis[$year][$state]['death_male'] = $total_death_male_per_state;
                // $state_analysis[$year][$state]['death_female'] = $total_death_female_per_state;


                // Accumulate the data from state into year
                $total_case_per_year += $total_case_per_state;
                $total_infected_per_year += $total_infected_per_state;
                $total_death_per_year += $total_death_per_state;
                $total_male_per_year += $total_male_per_state;
                $total_female_per_year += $total_female_per_state;
                $total_infected_male_per_year += $total_infected_male_per_state;
                $total_infected_female_per_year += $total_infected_female_per_state;
                $total_death_male_per_year += $total_death_male_per_state;
                $total_death_female_per_year += $total_death_female_per_state;
            }

            // get analysed result
            $analysed_result = [
                'highest' => $this->getStatisticsResult($state_analysis[$year], 'max'),
                'lowest' => $this->getStatisticsResult($state_analysis[$year], 'min'),
                'average' => $this->getStatisticsResult($state_analysis[$year], 'avg'),
            ];

            // count weekly data
            foreach ($weekly_data as $yr => $states) {
                foreach ($states as $state => $weeks) {
                    foreach ($weeks as $week => $value) {
                        $data_weekly_count[$yr][$state][intval($week)] = count($value);
                    }
                    foreach ($all_weeks as $week) {
                        if (!isset($data_weekly_count[$yr][$state][$week])) {
                            $data_weekly_count[$yr][$state][$week] = 0;
                        }
                    }
                    ksort($data_weekly_count[$yr][$state]);
                }
            }

            // arrange required information
            $data_analysed[] = [
                'year' =>  $year,
                'total_infected' => $total_infected_per_year,
                'total_infected_gender' => ['male' => $total_infected_male_per_year, 'female' => $total_infected_female_per_year],
                'total_deaths' => $total_death_per_year,
                'total_death_gender' => ['male' => $total_death_male_per_year, 'female' => $total_death_female_per_year],
                'total_male' => $total_male_per_year,
                'total_female' => $total_female_per_year,
                'monthly_details' => $data_monthly_count,
                'monthly_analysis' => $state_analysis[$year],
                'analysed_result' => $analysed_result,
                'weekly_details' => $data_weekly_count[$year],
            ];
        }
        return $data_analysed;
    }


}
