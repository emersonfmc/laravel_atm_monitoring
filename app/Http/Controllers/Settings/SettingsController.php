<?php

namespace App\Http\Controllers\Settings;

use App\Models\User;
use Illuminate\Http\Request;

use App\Models\DataBankLists;
use Illuminate\Support\Carbon;
use App\Models\EFMain\DataArea;
use App\Models\EFMain\DataBranch;

use App\Models\System\SystemLogs;
use Illuminate\Support\Facades\DB;
use App\Models\EFMain\DataDistrict;

use App\Http\Controllers\Controller;
use App\Models\EFMain\DataUserGroup;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\Facades\DataTables;

class SettingsController extends Controller
{
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

    public function validateAccess(Request $request)
    {
        $token = $request->query('token');
        try {
            $payload = json_decode(Crypt::decryptString($token), true);

            $timestamp = Carbon::createFromTimestamp($payload['timestamp']);
            if ($timestamp->diffInSeconds(now()) > 60) {
                return abort(403, 'Token expired.');
            }

            $employee_id = $payload['employee_id'];

            $elogUser = User::where('employee_id', $employee_id)->first();

            if ($elogUser) {
                Auth::login($elogUser);
                return view('pages.pages_backend.main_dashboard'); // Blade view you create
            }

            return abort(403, 'User not found.');
        } catch (\Exception $e) {
            return abort(403, 'Invalid token.');
        }
    }


}



