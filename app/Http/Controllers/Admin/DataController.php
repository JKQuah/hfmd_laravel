<?php

namespace App\Http\Controllers\Admin;

use App\Data;
use App\Http\Controllers\Controller;
use App\Imports\DataImport;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use InvalidArgumentException;
use Maatwebsite\Excel\Facades\Excel;
use PDOException;

class DataController extends Controller
{
    //
    public function index()
    {
        $years = $this->getAllYearsWithTrashed();
        $states = $this->getAllStatesWithTrashed()->all();
        $states = array_chunk($states, 8);
        $disabled_years = Data::onlyTrashed()->get()->groupBy(function ($item) {
            return date('Y', strtotime($item['notificationDate']));
        })->keys()->all();

        return view('admin.data', compact('years', 'states', 'disabled_years'));
    }

    public function enableYear(Request $request)
    {
        $year = $request->year;
        try {
            Data::onlyTrashed()->whereYear('notificationDate', $year)->restore();
            return response()->json(['status' => true, 'success' => 'The data in ' . $year . ' is enabled.']);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'error' => 'There is some error occurs. Contact the Helpdesk']);
        }
    }

    public function disableYear(Request $request)
    {
        $year = $request->year;
        try {
            $data = Data::whereYear('notificationDate', $year);
            $data->delete();
            return response()->json(['status' => true, 'success' => 'The data in ' . $year . ' is disabled.']);
        } catch (\Throwable $th) {
            return $th;
            return response()->json(['status' => false, 'error' => 'There is some error occurs. Contact the Helpdesk']);
        }
    }

    public function enableState(Request $request)
    {
        $state = $request->state;
        try {
            Data::onlyTrashed()->where('state', $state)->restore();
            return response()->json(['status' => true, 'success' => 'The data in ' . $state . ' is enabled.']);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'error' => 'There is some error occurs. Contact the Helpdesk']);
        }
    }

    public function disableState(Request $request)
    {
        $state = $request->state;
        try {
            $data = Data::where('state', $state);
            $data->delete();
            return response()->json(['status' => true, 'success' => 'The data in ' . $state . ' is disabled.']);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'error' => 'There is some error occurs. Contact the Helpdesk']);
        }
    }

    public function import(Request $request)
    {
        $this->validate($request, [
            'importfile'  => 'required|mimes:xls,xlsx'
        ]);
        try {
            $path = $request->importfile->path();
            Excel::import(new DataImport, $path);
            return redirect()->back()->with('success', 'All data uploaded and saved successfully');
        } catch (QueryException $e) {
            return redirect()->back()->with('error', $e->errorInfo[2]);
        } catch (PDOException $e) {

            return redirect()->back()->with('error', 'PDO error has occured.');
        } catch (InvalidArgumentException $x) {
            dd($x->getMessage());
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Unknown import error has occured.');
        }
    }
}
