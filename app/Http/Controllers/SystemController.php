<?php

namespace App\Http\Controllers;

use Log;
use App\Models\SystemLogs;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\SystemAnnouncements;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class SystemController extends Controller
{
    public function system_annoucement_pages()
    {
        $user = Auth::user();
        $user_types = $user->user_types;

        return view('pages.pages_backend.settings.annoucements_pages', compact('user_types'));
    }

    public function system_annoucement_data()
    {
        $systemAnnouncements = SystemAnnouncements::with('Employee')
            ->orderBy('updated_at', 'desc') // Explicitly set order here
            ->get();

        return DataTables::of($systemAnnouncements)
        ->setRowId('id')
        ->make(true);
    }

    public function system_annoucement_get($id)
    {
        $SystemAnnouncements = SystemAnnouncements::with('Employee')->findOrFail($id);
        return response()->json($SystemAnnouncements);
    }

    public function system_annoucement_specific($id)
    {
        $SystemAnnouncements = SystemAnnouncements::with('Employee')->findOrFail($id);
        return view('pages.pages_backend.settings.annoucements_display', compact('SystemAnnouncements'));
    }

    public function system_annoucement_fetch()
    {
        $user = Auth::user();
        $user_types = $user->user_types;

        return view('pages.pages_backend.settings.announcements_fetch', compact('user_types'));
    }

    public function system_annoucement_fetch_data()
    {
        $SystemAnnouncements = SystemAnnouncements::with('Employee')->get();
        return response()->json($SystemAnnouncements);
    }


    public function system_annoucement_create(Request $request)
    {
        // Determine the prefix based on the type
        if ($request->type == 'New Features') {
            $FirstID = 'NF';
        } elseif ($request->type == 'Notification') {
            $FirstID = 'NO';
        } elseif ($request->type == 'Enhancements') {
            $FirstID = 'EN';
        } elseif ($request->type == 'Maintenance') {
            $FirstID = 'MN';
        } else {
            $FirstID = 'XX'; // Default or handle this case accordingly
        }

        // Get the current date components
        $date = Carbon::now();
        $month = $date->format('m');
        $day = $date->format('d');
        $year = $date->format('y');

        // Retrieve the last created announcement ID for generating the next ID
        $lastAnnouncement = SystemAnnouncements::orderBy('id', 'desc')->first();
        $nextId = $lastAnnouncement ? $lastAnnouncement->id + 1 : 1;

        // Combine the components to create the announcement ID
        $announcementId = $FirstID . '-' . $month . $day . $year . '-' . $nextId;

        SystemAnnouncements::create([
            'announcement_id' => $announcementId,
            'type' => $request->type,
            'title' => $request->title,
            'description' => $request->description,
            'employee_id' => Auth::user()->employee_id,
            'date_start' => $request->date_start,
            'date_end' => $request->date_end,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Create System Logs used for Auditing of Logs
        SystemLogs::create([
            'system' => 'ATM Monitoring',
            'action' => 'Create',
            'title' => 'Create Announcements',
            'description' => 'Creation of Announcements | ' .  $request->title,
            'employee_id' => Auth::user()->employee_id,
            'ip_address' => $request->ip(),
            'created_at' => Carbon::now(),
            'company_id' => Auth::user()->company_id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Announcements Created successfully!'
        ]);
    }

    public function system_annoucement_update(Request $request)
    {
        $SystemAnnouncements = SystemAnnouncements::findOrFail($request->item_id);
        $SystemAnnouncements->update([  // Update the instance instead of using the class method
            'type' => $request->type,
            'title' => $request->title,
            'description' => $request->description,
            'employee_id' => Auth::user()->employee_id,
            'date_start' => $request->date_start,
            'date_end' => $request->date_end,
            'updated_at' => Carbon::now(),
        ]);

        // Create System Logs used for Auditing of Logs
        SystemLogs::create([
            'system' => 'ATM Monitoring',
            'action' => 'Update',
            'title' => 'Update Announcement Page',
            'description' => 'Updating of Announcement | ' .  $SystemAnnouncements->title,
            'employee_id' => Auth::user()->employee_id,
            'ip_address' => $request->ip(),
            'created_at' => Carbon::now(),
            'company_id' => Auth::user()->company_id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Announcement Updated Successfully!'  // Changed message to reflect update action
        ]);
    }

    public function system_annoucement_display()
    {
        $systemAnnouncements = SystemAnnouncements::with('employee')
            ->orderBy('date_end', 'asc')
            ->limit(5)
            ->get();

        return response()->json($systemAnnouncements);

    }

    public function system_annoucement_counts()
    {
        $today = now()->toDateString();
        $SystemAnnouncements = SystemAnnouncements::where('date_end', '>=', $today)->count();
        return response()->json($SystemAnnouncements);
    }


    public function system_logs_pages()
    {
        $user = Auth::user();
        $user_types = $user->user_types;

        return view('pages.pages_backend.settings.system_logs', compact('user_types'));
    }

    public function system_logs_data()
    {
        $systemLogs = SystemLogs::with('Employee')
            ->orderBy('created_at', 'desc') // Explicitly set order here
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
            ->rawColumns(['differForHumans'])
            ->make(true);
    }





}
