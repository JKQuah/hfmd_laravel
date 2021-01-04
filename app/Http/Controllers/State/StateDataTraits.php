<?php

namespace App\Http\Controllers\State;

use App\Data;
use Illuminate\Support\Carbon;

/**
 * Retrieve required data for state according to year and state
 */
trait StateDataTraits
{
    public function getStateYearlyData()
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

    public function getStateMonthlyData($year, $state)
    {
        // get monthly data using groupby
        $monthly_data = Data::where('state', $state)->whereYear('notificationDate', $year)->get()->groupBy([
            'district',
            function ($data) {
                return Carbon::parse($data->notificationDate)->format('M');
            }
        ], $preserveKeys = true);

        $all_months = $this->getAllMonths();
        // declare variables
        $district_analysis = [];
        $total_case_per_year = 0;
        $total_infected_per_year = 0;
        $total_death_per_year  = 0;
        $total_male_per_year = 0;
        $total_female_per_year = 0;

        // Declare categories
        $category_A = [];
        $range_A = 100;
        $category_B = [];
        $range_B = 200;
        $category_C = [];
        $range_C = 300;
        $category_D = [];

        foreach ($monthly_data as $district => $months) {
            $total_case_per_district = 0;
            $total_infected_per_district = 0;
            $total_death_per_district = 0;
            $total_male_per_district = 0;
            $total_female_per_district = 0;

            foreach ($months as $month => $cases) {
                $data_monthly_count[$district][$month] = count($cases);
                $total_case_per_district += count($cases);
                $total_infected_per_district += count($cases->where('status', 'infected'));
                $total_death_per_district += count($cases->where('status', 'death'));
                $total_male_per_district += count($cases->where('gender', 'Male'));
                $total_female_per_district += count($cases->where('gender', 'Female'));
            }
            foreach ($all_months as $months => $month) {
                if (!isset($data_monthly_count[$district][$month])) {
                    $data_monthly_count[$district][$month] = 0;
                }
            }
            // sort the data monthly count array according to month
            uksort($data_monthly_count[$district], function ($month1, $month2) {
                $time1 = strtotime($month1);
                $time2 = strtotime($month2);
                return $time1 - $time2;
            });

            $district_analysis[$district]['total'] = $total_case_per_district;
            $district_analysis[$district]['infected'] = $total_infected_per_district;
            $district_analysis[$district]['death'] = $total_death_per_district;
            $district_analysis[$district]['male'] = $total_male_per_district;
            $district_analysis[$district]['female'] = $total_female_per_district;

            // categories district
            if ($total_case_per_district < $range_A) {
                $category_A += [$district => $total_case_per_district];
            } else if ($total_case_per_district < $range_B) {
                $category_B += [$district => $total_case_per_district];
            } else if ($total_case_per_district < $range_C) {
                $category_C += [$district => $total_case_per_district];
            } else {
                $category_D += [$district => $total_case_per_district];
            }

            // Accumulate the data from state into year
            $total_infected_per_year += $total_infected_per_district;
            $total_death_per_year += $total_death_per_district;
            $total_male_per_year += $total_male_per_district;
            $total_female_per_year += $total_female_per_district;
        }

        return [
            'data_monthly_count' => $data_monthly_count,
            'district_analysis' => $district_analysis, 
            'category_A' => $category_A,
            'category_B' => $category_B,
            'category_C' => $category_C,
            'category_D' => $category_D, 
            'total_infected_per_year' => $total_infected_per_year,
            'total_death_per_year' => $total_death_per_year,
            'total_male_per_year' => $total_male_per_year,
            'total_female_per_year' => $total_female_per_year,
        ];
    }

    public function getStateWeeklyData($year, $state){
        // get weekly data using groupby
        $weekly_data = Data::where('state', $state)->whereYear('notificationDate', $year)->get()->groupBy([
            'district',
            function ($data) {
                return Carbon::parse($data->notificationDate)->format('W');
            }
        ], $preserveKeys = true);

        $all_weeks = $this->getAllWeeks();

        // count weekly data
        foreach ($weekly_data as $district => $weeks) {
            foreach ($weeks as $week => $value) {
                $data_weekly_count[$district][intval($week)] = count($value);
            }
            foreach ($all_weeks as $week) {
                if (!isset($data_weekly_count[$district][$week])) {
                    $data_weekly_count[$district][$week] = 0;
                }
            }
            ksort($data_weekly_count[$district]);
        }
        return $data_weekly_count;
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
}
