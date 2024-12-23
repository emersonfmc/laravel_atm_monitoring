<?php

namespace App\Http\Controllers;

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
        $SystemAnnouncements = SystemAnnouncements::findOrFail($id);
        return response()->json($SystemAnnouncements);
    }

    public function system_annoucement_create(Request $request)
    {
        SystemAnnouncements::create([
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
        $systemAnnouncements = SystemAnnouncements::with('Employee')
            ->orderBy('updated_at', 'desc') // Explicitly set order here
            ->limit(5)
            ->get();

        return DataTables::of($systemAnnouncements)
        ->setRowId('id')
        ->make(true);
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
            ->orderBy('updated_at', 'desc') // Explicitly set order here
            ->get()
            ->map(function ($log) {
                // Add custom formatted differForHumans
                $now = Carbon::now();
                $diffInMinutes = $log->created_at->diffInMinutes($now);
                $hours = intdiv($diffInMinutes, 60);
                $minutes = $diffInMinutes % 60;

                if ($hours > 0) {
                    $log->differForHumans = $hours . ' hr and ' . $minutes . ' mins ago';
                } else {
                    $log->differForHumans = $minutes . ' mins ago';
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
