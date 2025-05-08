<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\EFMain\SystemLogs;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

use App\Models\EFMain\DataUserGroup;

use App\Models\settings\ElmBankLists;
use App\Models\settings\ElmTransactionSequence;
use App\Models\settings\ElmBorrowOption;

use App\Models\settings\ElmReleaseOption;
use App\Models\settings\ElmCollectionDate;
use App\Models\settings\ElmPensionTypesLists;
use App\Models\settings\ElmTransactionAction;
use Yajra\DataTables\Facades\DataTables;

class SettingsElogCardMonitoringController extends Controller
{
    public function bank_page(){
        // dd(Auth::user()->employee_id);
        return view('pages.elog_settings.settings_bank_lists_page');
    }

    public function bank_data(){
       $branch = ElmBankLists::orderBy('id','asc')->get();
        return DataTables::of($branch)
        ->setRowId('id')
        ->make(true);
    }

    public function bankGet($id){
        $AtmBankLists = ElmBankLists::findOrFail($id);
        return response()->json($AtmBankLists);
    }

    public function bankCreate(Request $request){
        ElmBankLists::create([
            'bank_name' => $request->bank_name,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        SystemLogs::create([
            'module' => 'System Settings',
            'system_id' => 1,
            'action' => 'Create',
            'title' => 'Create New Bank',
            'description_logs' => [
                'new_details' => [
                    'Bank Name' => $request->bank_name ?? '',
                    'Status' => 'Active',
                ],
            ],
            'employee_id' => Auth::user()->employee_id ?? NULL,
            'ip_address' => $request->ip(),
            'created_at' => Carbon::now(),
            'company_id' => Auth::user()->company_id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Bank created successfully!'
        ]);
    }

    public function bankUpdate(Request $request){
        // Find the user group by ID
        $AtmBankLists = ElmBankLists::findOrFail($request->item_id);

        // Create System Logs used for Auditing of Logs
        SystemLogs::create([
            'module' => 'System Settings',
            'system_id' => 1,
            'action' => 'Update',
            'title' => 'Update Bank',
            'description_logs' => [
                'new_details' => [
                    'Bank Name' => $request->bank_name ?? '',
                    'Status' => $request->status ?? '',
                ],
                'old_details' => [
                    'Bank Name' => $AtmBankLists->bank_name ?? '',
                    'Status' =>  $AtmBankLists->status ?? '',
                ],
            ],
            'employee_id' => Auth::user()->employee_id ?? '',
            'ip_address' => $request->ip(),
            'created_at' => Carbon::now(),
            'company_id' => Auth::user()->company_id,
        ]);

        // Proceed with update if validation passes
        $AtmBankLists->update([  // Update the instance instead of using the class method
            'bank_name' => $request->bank_name,
            'updated_at' => Carbon::now(),  // Updated timestamp
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Bank updated successfully!'  // Changed message to reflect update action
        ]);
    }

    public function pension_types_page(){
        return view('pages.elog_settings.settings_pension_types_page');
    }

    public function pension_types_data(){
       $DataPensionTypesLists = ElmPensionTypesLists::orderBy('id','asc')->get();

        return DataTables::of($DataPensionTypesLists)
        ->setRowId('id')
        ->make(true);
    }

    public function pension_typesGet($id){
        $AtmPensionTypesLists = ElmPensionTypesLists::findOrFail($id);
        return response()->json($AtmPensionTypesLists);
    }

    public function pension_typesCreate(Request $request){
        ElmPensionTypesLists::create([
            'pension_name' => $request->pension_name,
            'types' => $request->types,
            'status' => 'Active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Create System Logs used for Auditing of Logs
        SystemLogs::create([
            'module' => 'System Settings',
            'system_id' => 1,
            'action' => 'Create',
            'title' => 'Create Pension Types',
            'description_logs' => [
                'new_details' => [
                    'Account Type' => $request->types ?? '',
                    'Pension Type' => $request->pension_name ?? '',
                    'Status' => 'Active',
                ],
            ],
            'employee_id' => Auth::user()->employee_id,
            'ip_address' => $request->ip(),
            'created_at' => Carbon::now(),
            'company_id' => Auth::user()->company_id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Pension Types Created successfully!'
        ]);
    }

    public function pension_typesUpdate(Request $request){
        $ElmPensionTypesLists = ElmPensionTypesLists::findOrFail($request->item_id);

        // Create System Logs used for Auditing of Logs
            SystemLogs::create([
                'module' => 'System Settings',
            'system_id' => 1,
                'action' => 'Update',
                'title' => 'Update Pension Types',
                'description_logs' => [
                    'new_details' => [
                        'Account Type' => $request->types ?? '',
                        'Pension Type' => $request->pension_name ?? '',
                        'Status' => 'Active',
                    ],
                    'old_details' => [
                        'Old Account Type' => $ElmPensionTypesLists->types ?? '',
                        'Old Pension Type' => $ElmPensionTypesLists->pension_name ?? '',
                        'Old Status' => 'Active',
                    ],
                ],
                'employee_id' => Auth::user()->employee_id,
                'ip_address' => $request->ip(),
                'created_at' => Carbon::now(),
                'company_id' => Auth::user()->company_id,
            ]);
        // Create System Logs used for Auditing of Logs

        $ElmPensionTypesLists->update([
            'pension_name' => $request->pension_name,
            'types' => $request->types,
            'status' => $request->status,
            'updated_at' => Carbon::now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Pension Types updated successfully!'  // Changed message to reflect update action
        ]);
    }

    public function transaction_action_page(){
        $DataUserGroup = DataUserGroup::where('status','Active')->get();

        return view('pages.elog_settings.settings_transaction_action_page', compact('DataUserGroup'));
    }

    public function transaction_action_data(){
       $DataTransactionAction = ElmTransactionAction::with('DataTransactionSequence','DataTransactionSequence.DataUserGroup')->latest('updated_at')
            ->get();

        return DataTables::of($DataTransactionAction)
            ->setRowId('id')
            ->make(true);
    }

    public function transaction_typesGet($id){
        $ElmTransactionAction = ElmTransactionAction::with('DataTransactionSequence','DataTransactionSequence.DataUserGroup')->findOrFail($id);
        return response()->json($ElmTransactionAction);
    }

    public function transaction_typesCreate(Request $request){
        $ElmTransactionAction = ElmTransactionAction::create([
            'name' => $request->name,
            'status' => 'Active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        foreach ($request->user_group_id as $key => $value) {
            ElmTransactionSequence::create([
                'atm_transaction_actions_id' => $ElmTransactionAction->id,
                'user_group_id' => $value,
                'sequence_no' => $request->sequence_no[$key],
                'type' => $request->type[$key],
                'updated_at' => Carbon::now(),
            ]);
        }

        // Create System Logs used for Auditing of Logs
        SystemLogs::create([
            'module' => 'System Settings',
            'system_id' => 1,
            'action' => 'Create',
            'title' => 'Create Transaction Action',
            'description' => 'Creation of Transaction Action - ' .  $request->name,
            'employee_id' => Auth::user()->employee_id,
            'ip_address' => $request->ip(),
            'created_at' => Carbon::now(),
            'company_id' => Auth::user()->company_id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Transaction Action Created successfully!'
        ]);
    }

    public function transaction_typesUpdate(Request $request){
        $ElmTransactionAction = ElmTransactionAction::findOrFail($request->item_id);

        // Create System Logs used for Auditing of Logs
            SystemLogs::create([
                'module' => 'System Settings',
            'system_id' => 1,
                'action' => 'Update',
                'title' => 'Update Transaction Action',
                'description' => 'Updating of Transaction Action - ' .  $ElmTransactionAction->name,
                'employee_id' => Auth::user()->employee_id,
                'ip_address' => $request->ip(),
                'created_at' => Carbon::now(),
                'company_id' => Auth::user()->company_id,
            ]);
        // Create System Logs used for Auditing of Logs

        $ElmTransactionAction->update([  // Update the instance instead of using the class method
            'name' => $request->name,
            'status' => $request->status,
            'updated_at' => Carbon::now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Transaction Action updated successfully!'  // Changed message to reflect update action
        ]);
    }

    public function release_reason_page(){
        return view('pages.elog_settings.settings_release_reason_page');
    }

    public function release_reason_data(){
       $ElmReleaseOption = ElmReleaseOption::where('status','Active')
            ->latest('updated_at')
            ->whereNull('deleted_at')
            ->get();

        return DataTables::of($ElmReleaseOption)
            ->setRowId('id')
            ->make(true);
    }

    public function release_reason_get($id){
        $ElmReleaseOption = ElmReleaseOption::findOrFail($id);
        return response()->json($ElmReleaseOption);
    }

    public function release_reason_create(Request $request){
        ElmReleaseOption::create([
            'reason' => $request->reason,
            'description' => $request->description,
            'status' => 'Active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Create System Logs used for Auditing of Logs
        SystemLogs::create([
            'module' => 'System Settings',
            'system_id' => 1,
            'action' => 'Create',
            'title' => 'Create Release Reason',
            'description_logs' => [
                'new_details' => [
                    'Reason' => $request->reason,
                    'Description' => $request->description,
                    'Status' => 'Active',
                ],
            ],
            'employee_id' => Auth::user()->employee_id,
            'ip_address' => $request->ip(),
            'created_at' => Carbon::now(),
            'company_id' => Auth::user()->company_id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Release Reason Created successfully!'
        ]);
    }

    public function release_reason_update(Request $request){
        $ElmReleaseOption = ElmReleaseOption::findOrFail($request->item_id);

        SystemLogs::create([
            'module' => 'System Settings',
            'system_id' => 1,
            'action' => 'Update',
            'title' => 'Update Release Reason',
            'description_logs' => [
                'new_details' => [
                    'Reason' => $request->reason,
                    'Description' => $request->description,
                    'Status' => $request->status,
                ],
                'old_details' => [
                    'Old Reason' => $ElmReleaseOption->reason,
                    'Old Description' => $ElmReleaseOption->description,
                    'Old Status' => $ElmReleaseOption->status,
                ],
            ],
            'employee_id' => Auth::user()->employee_id,
            'ip_address' => $request->ip(),
            'created_at' => Carbon::now(),
            'company_id' => Auth::user()->company_id,
        ]);

        // Proceed with update if validation passes
        $ElmReleaseOption->update([  // Update the instance instead of using the class method
            'reason' => $request->reason,
            'description' => $request->description,
            'status' => $request->status,
            'updated_at' => Carbon::now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Release Reason Updated Successfully!'  // Changed message to reflect update action
        ]);
    }

    public function collection_date_page(){
        return view('pages.elog_settings.settings_collection_date');
    }

    public function collection_date_data(){
       $ElmCollectionDate = ElmCollectionDate::latest('updated_at')
            ->whereNull('deleted_at')
            ->get();

        return DataTables::of($ElmCollectionDate)
            ->setRowId('id')
            ->make(true);
    }

    public function collection_date_get($id){
        $ElmCollectionDate = ElmCollectionDate::findOrFail($id);
        return response()->json($ElmCollectionDate);
    }

    public function collection_date_create(Request $request){
        ElmCollectionDate::create([
            'collection_date' => $request->collection_date,
            'status' => 'Active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Create System Logs used for Auditing of Logs
        SystemLogs::create([
            'module' => 'System Settings',
            'system_id' => 1,
            'action' => 'Create',
            'title' => 'Create Collection Date',
            'description_logs' => [
                'new_details' => [
                    'Collection Date' => $request->collection_date,
                    'Status' => 'Active',
                ],
            ],
            'employee_id' => Auth::user()->employee_id,
            'ip_address' => $request->ip(),
            'created_at' => Carbon::now(),
            'company_id' => Auth::user()->company_id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Collection Date Created successfully!'
        ]);
    }

    public function collection_date_update(Request $request){
        $ElmCollectionDate = ElmCollectionDate::findOrFail($request->item_id);

        SystemLogs::create([
            'module' => 'System Settings',
            'system_id' => 1,
            'action' => 'Update',
            'title' => 'Update Collection Date',
            'description_logs' => [
                'new_details' => [
                    'Collection Date' => $request->collection_date,
                    'Status' => $request->status,
                ],
                'old_details' => [
                    'Collection Date' => $ElmCollectionDate->collection_date,
                    'Status' => $ElmCollectionDate->status,
                ],
            ],
            'employee_id' => Auth::user()->employee_id,
            'ip_address' => $request->ip(),
            'created_at' => Carbon::now(),
            'company_id' => Auth::user()->company_id,
        ]);

        $ElmCollectionDate->update([
            'collection_date' => $request->collection_date,
            'status' => $request->status,
            'updated_at' => Carbon::now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Collection Date Updated Successfully!'  // Changed message to reflect update action
        ]);
    }

    public function borrow_reason_page(){
        return view('pages.elog_settings.settings_borrow_reason');
    }

    public function borrow_reason_data(){
       $ElmBorrowOption = ElmBorrowOption::latest('updated_at')
            ->whereNull('deleted_at')
            ->get();

        return DataTables::of($ElmBorrowOption)
            ->setRowId('id')
            ->make(true);
    }

    public function borrow_reason_get($id){
        $ElmBorrowOption = ElmBorrowOption::findOrFail($id);
        return response()->json($ElmBorrowOption);
    }

    public function borrow_reason_create(Request $request){
        ElmBorrowOption::create([
            'reason' => $request->name,
            'status' => 'Active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Create System Logs used for Auditing of Logs
        SystemLogs::create([
            'module' => 'System Settings',
            'system_id' => 1,
            'action' => 'Create',
            'title' => 'Create Borrow Reason',
            'description_logs' => [
                'new_details' => [
                    'Borrow Reason' => $request->name,
                    'Status' => 'Active',
                ],
            ],
            'employee_id' => Auth::user()->employee_id,
            'ip_address' => $request->ip(),
            'created_at' => Carbon::now(),
            'company_id' => Auth::user()->company_id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Borrow Reason Has Been Created successfully!'
        ]);
    }

    public function borrow_reason_update(Request $request){
        $ElmBorrowOption = ElmBorrowOption::findOrFail($request->item_id);

        // Create System Logs used for Auditing of Logs
        SystemLogs::create([
            'module' => 'System Settings',
            'system_id' => 1,
            'action' => 'Update',
            'title' => 'Update Borrow Reason',
            'description_logs' => [
                'new_details' => [
                    'Borrow Reason' => $request->name,
                    'Status' => $request->status,
                ],
                'old_details' => [
                    'Old Borrow Reason' => $ElmBorrowOption->reason,
                    'Old Status' => $ElmBorrowOption->status,
                ],
            ],
            'employee_id' => Auth::user()->employee_id,
            'ip_address' => $request->ip(),
            'created_at' => Carbon::now(),
            'company_id' => Auth::user()->company_id,
        ]);

        $ElmBorrowOption->update([  // Update the instance instead of using the class method
            'reason' => $request->name,
            'status' => $request->status,
            'updated_at' => Carbon::now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Borrow Reason Updated Successfully!'  // Changed message to reflect update action
        ]);
    }

}
