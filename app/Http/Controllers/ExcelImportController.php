<?php

namespace App\Http\Controllers;

use App\Exports\PathExport;
use App\Imports\YourImportClass;
use App\Models\Path;
use App\Models\PathMain;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;


class ExcelImportController extends Controller
{
    public function get()
    {
        return view('welcome');
    }

    public function post(Request $request)
    {
//        dd($request->file('excel'));
        // Validate the uploaded file
        $request->validate([
            'excel' => 'required|mimes:xlsx,xls,csv',
        ]);

        // Get the uploaded file
        $file = $request->file('excel');
//        dd($file);
        // Process the Excel file
        Excel::import(new YourImportClass, $file);

        return redirect()->back()->with('success', 'Excel file imported successfully!');
    }

    public function temp()
    {
//        $wholePaths = Path::all()->take(100);
//        foreach ($wholePaths as $wholePath) {
//            $pathsList = explode('?', $wholePath->path);
//            for ($i = 0; $i < count($pathsList) - 1; $i++) {
//                if(!PathMain::where('origin',trim($pathsList[$i]))->where('destination',trim($pathsList[$i+1]))->count()){
//                    $mainPath = new PathMain();
//                    $mainPath->origin = trim($pathsList[$i]);
//                    $mainPath->destination = trim($pathsList[$i + 1]);
//                    $mainPath->save();
//                }
//            }
//            $wholePath->delete();
//        }
        $wholePaths = Path::all()->where('checked', 0)->take(1000);
        foreach ($wholePaths as $wholePath) {
            $pathsList = explode('?', $wholePath->path);
            $totalKM = 0;
            for ($i = 0; $i < count($pathsList) - 1; $i++) {
                $mainPath = PathMain::where('origin',trim($pathsList[$i]))->where('destination',trim($pathsList[$i + 1]))->first();
                $totalKM +=$mainPath->info;
            }
            $wholePath->km=$totalKM;
            $wholePath->checked=1;
            $wholePath->save();
        }
    }

    public function temp1()
    {
        return Excel::download(new PathExport, 'users.xlsx');
    }
}
