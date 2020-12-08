<?php
use App\Data;
use Illuminate\Support\Facades\DB;

class DataController
{
    public function index()
    {


        /* -------------------------------------------------------------------------------------------------------------------- */
        /* -------------------------------------------------------------------------------------------------------------------- */
        /* -------------------------------------------------------------------------------------------------------------------- */


        /** retrieve all available distinct years, states, months */
        $datas = Data::get();
        $states = Data::select('state')->orderBy('state')->groupBy('state')->get();
        $years = Data::select(DB::raw('YEAR(notificationDate) as year'))->distinct()->orderBy('year')->get();
        $months = Data::select(DB::raw('MONTH(notificationDate) as month'))->distinct()->orderBy('month')->get();

        /** retrieve all data by existing years, states, months */
        $data_array = [];

        foreach ($datas as $data) {
            $data_array[date('Y', strtotime($data->notificationDate))][$data->state][intval(date('m', strtotime($data->notificationDate)))][date('d', strtotime($data->notificationDate))] = $data;
        }


        /** create an empty 3-multideminsional array to make sure all states and months are included */
        $view_array = [];
        foreach ($years as $year) {
            foreach ($states as $state) {
                foreach ($months as $month) {
                    $view_array[$year->year][$state->state][$month->month] = 0;
                }
            }
        }

        $datalist = array();
        /** insert the case count from data_array into view_array */
        foreach ($data_array as $data_years => $details) {
            foreach ($details as $data_states => $data_months) {
                foreach ($data_months as $month => $day) {
                    foreach (array_keys($view_array[$data_years][$data_states]) as $key => $value) {
                        if ($value == $month) {
                            $view_array[$data_years][$data_states][$month] = count($day);
                        }
                    }
                }
            }
        }

        /** insert all required data to display in data.index */
        foreach ($years as $year) {
            $state_per_year = [];
            foreach ($states as $state) {
                $total_case_per_state = 0;
                foreach ($months as $month) {
                    $total_case_per_state += $view_array[$year->year][$state->state][$month->month];
                }
                $state_per_year += [
                    $state->state => $total_case_per_state,
                ];
            }
            $total_infected = Data::select('notificationDate', 'status')
                ->where('status', 'infected')
                ->whereYear('notificationDate', $year->year)
                ->get()->count();
            $total_deaths = Data::select('notificationDate', 'status')
                ->where('status', 'death')
                ->whereYear('notificationDate', $year->year)
                ->get()->count();
            $male = Data::select('notificationDate', 'gender')
                ->where('gender', '=', 'male')
                ->whereYear('notificationDate', $year->year)
                ->get()->count();
            $female = Data::select('notificationDate', 'gender')
                ->where('gender', '=', 'female')
                ->whereYear('notificationDate', $year->year)
                ->get()->count();

            $datalist[] = array(
                'year' =>  $year->year,
                'total_infected' => $total_infected,
                'total_deaths' => $total_deaths,
                'total_male' => $male,
                'total_female' => $female,
                'data' => $view_array[$year->year],
                'maxkey' => array_keys($state_per_year,  max($state_per_year))[0],
                'maxvalue' => max($state_per_year),
                'minkey' => array_keys($state_per_year, min($state_per_year))[0],
                'minvalue' => min($state_per_year),

            );
        }
        // dd($datalist);
        /** return data back into index */
        return view('data.index', [
            'years'  => $years,
            'months' => $months,
            'datalist' => $datalist,
        ]);
    }
}
