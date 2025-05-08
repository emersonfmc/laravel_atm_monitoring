<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

use App\Models\System\SystemLogs;
use App\Http\Controllers\Controller;
use App\Models\EFMain\DataUserGroup;
use App\Models\EFMain\DataDepartments;
use App\Models\Settings\ElmDocumentsAction;
use App\Models\Settings\ElmDocumentsSequence;

use Yajra\DataTables\Facades\DataTables;

class SettingsElogDocumentMonitoringController extends Controller
{
    public function documents_action_page(){
        $DataUserGroup = DataUserGroup::where('status','Active')->get();

        return view('pages.elog_settings.settings_documents_action_page',compact('DataUserGroup'));
    }

    public function documents_action_data(){
        $ElmDocumentsAction = ElmDocumentsAction::with('DocumentsSequence','DocumentsSequence.DataUserGroup')
                ->orderBy('id','asc')
                ->get();

            return DataTables::of($ElmDocumentsAction)
            ->setRowId('id')
            ->make(true);
    }

    public function documents_action_get($id){
        $ElmDocumentsAction = ElmDocumentsAction::with('DocumentsSequence','DocumentsSequence.DataUserGroup')->findOrFail($id);
        return response()->json($ElmDocumentsAction);
    }

    public function documents_action_create(Request $request){
        $DocumentSession = $request->document_session ?? '';

        $ElmDocumentsAction = ElmDocumentsAction::create([
            'document' => $request->document ?? '',
            'document_session' => $DocumentSession,
            'department_id' => $request->department_id ?? '',
            'status' => 'Active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        foreach ($request->user_group_id as $key => $value) {
            ElmDocumentsSequence::create([
                'documents_actions_id' => $ElmDocumentsAction->id,
                'user_group_id' => $value,
                'sequence_no' => $request->sequence_no[$key],
                'type' => $request->type[$key],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        // System Logs
            if($DocumentSession == '2'){
                $DocumentSessionLogs = 'Branch Transaction - Branch to HO or HO to Branch';
            } else if($DocumentSession == '3') {
                $DocumentSessionLogs = 'Head Office Transaction';
            } else if($DocumentSession == '4') {
                $DocumentSessionLogs = 'Head Office to Head Office Transaction';
            } else {
                $DocumentSessionLogs = '';
            }

            $Department = DataDepartments::where('id', $request->department_id)->first();
            $DepartmentName = $Department->name ?? 'No Department';

            SystemLogs::create([
                'module' => 'System Settings',
                'system_id' => 1,
                'action' => 'Create',
                'title' => 'Create New Document Action',
                'description_logs' => [
                    'new_details' => [
                        'Document Name' => $request->document ?? '',
                        'Document Transaction For' => $DocumentSessionLogs ?? '',
                        'Department' => $DepartmentName ?? '',
                        'Status' => 'Active',
                    ],
                ],
                'employee_id' => Auth::user()->employee_id,
                'ip_address' => $request->ip(),
                'created_at' => Carbon::now(),
                'company_id' => Auth::user()->company_id,
            ]);
        // System Logs

        return response()->json([
            'status' => 'success',
            'message' => 'Documents Transaction Action created successfully!'
        ]);
    }

    // public function documents_action_update(Request $request){
    //     // Find the user group by ID
    //     $System = System::findOrFail($request->item_id);

    //     // Create System Logs used for Auditing of Logs
    //     SystemLogs::create([
    //         'module' => 'System Settings',
    //         'system_id' => 1,
    //         'action' => 'Update',
    //         'title' => 'Update System',
    //         'description_logs' => [
    //             'new_details' => [
    //                 'System Name' => $request->name ?? '',
    //                 'System Link' => $request->link ?? '',
    //                 'Status' => $request->status ?? '',
    //             ],
    //             'old_details' => [
    //                 'Old System Name' => $System->name ?? '',
    //                 'Old System Link' => $System->link ?? '',
    //                 'Old Status' =>  $System->status ?? '',
    //             ],
    //         ],
    //         'employee_id' => Auth::user()->employee_id,
    //         'ip_address' => $request->ip(),
    //         'created_at' => Carbon::now(),
    //         'company_id' => Auth::user()->company_id,
    //     ]);

    //     $System->update([  // Update the instance instead of using the class method
    //         'name' => $request->name ?? '',
    //         'link' => $request->link ?? '',
    //         'status' => $request->status ?? '',
    //         'updated_at' => Carbon::now(),  // Updated timestamp
    //     ]);

    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'System Updated successfully!'  // Changed message to reflect update action
    //     ]);
    // }
}
