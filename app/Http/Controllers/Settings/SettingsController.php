<?php

namespace App\Http\Controllers\Settings;

use App\Models\User;
use App\Models\DataBankLists;

use App\Models\EFMain\DataArea;
use App\Models\EFMain\DataDistrict;
use App\Models\EFMain\DataBranch;
use App\Models\EFMain\DataUserGroup;

use App\Models\System\SystemLogs;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function settings_monitoring_dashboard_data(Request $request){
        $UserCount = User::where('status', 'Active')->count();
        $AreaCount = DataArea::where('status', 'Active')->count();
        $DistrictCount = DataDistrict::where('status', '1')->count();
        $BranchCount = DataBranch::where('status', 'Active')->count();
        $UserGroupCount = DataUserGroup::where('status', 'Active')->count();
        $BanksCount = DataBankLists::where('status', 'Active')->count();

        // Return the counts as a JSON response
        return response()->json([
            'UserCount' => $UserCount,
            'AreaCount' => $AreaCount,
            'DistrictCount' => $DistrictCount,
            'BranchCount' => $BranchCount,
            'UserGroupCount' => $UserGroupCount,
            'BanksCount' => $BanksCount,
        ]);
    }

    public function settings_system_logs_page(){
        return view('pages.pages_backend.settings.settings_system_logs');
    }

    public function settings_system_logs_data(){
        $systemLogs = SystemLogs::with('Employee')
            ->orderBy('id', 'desc') // Explicitly set order here
            ->get()
            ->map(function ($log) {
                $now = Carbon::now();
                $diffInMinutes = $log->created_at->diffInMinutes($now);
                $days = intdiv($diffInMinutes, 1440); // 1440 minutes in a day
                $remainingMinutes = $diffInMinutes % 1440;
                $hours = intdiv($remainingMinutes, 60);
                $minutes = $remainingMinutes % 60;

                if ($days > 0) {
                    $log->differForHumans = $days . ' day' . ($days > 1 ? 's' : '') .
                        ' and ' . $hours . ' hr' . ($hours > 1 ? 's' : '') . ' ago';
                } elseif ($hours > 0) {
                    $log->differForHumans = $hours . ' hr' . ($hours > 1 ? 's' : '') .
                        ' and ' . $minutes . ' min' . ($minutes > 1 ? 's' : '') . ' ago';
                } else {
                    $log->differForHumans = $minutes . ' min' . ($minutes > 1 ? 's' : '') . ' ago';
                }

                return $log;
            });

        return DataTables::of($systemLogs)
            ->setRowId('id')
            ->addColumn('differForHumans', function ($row) {
                // Ensure the 'differForHumans' field exists
                return $row->differForHumans;
            })
            ->addColumn('user_logs', function ($row) {
                return optional($row->Employee)->name ?? '';
            })
            ->rawColumns(['differForHumans','user_logs'])
            ->make(true);
    }

    public function settings_dashboard(){
        return view('pages.pages_backend.settings_dashboard');
    }

}



