<?php

namespace App\Http\Controllers\Settings;

use App\Models\User;
use App\Models\Branch;
use App\Models\Company;
use App\Models\DataArea;
use App\Models\SystemLogs;
use App\Models\DataDistrict;
use Illuminate\Http\Request;
use App\Models\DataBankLists;
use App\Models\DataUserGroup;
use Illuminate\Support\Carbon;
use App\Models\DataDepartments;
use App\Models\MaintenancePage;
use App\Models\DataReleaseOption;
use App\Models\DataCollectionDate;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\AtmTransactionAction;
use Illuminate\Support\Facades\Auth;

use App\Models\DataPensionTypesLists;
use App\Models\DataTransactionAction;
use App\Models\AtmTransactionSequence;
use App\Models\DataTransactionSequence;
use Yajra\DataTables\Facades\DataTables;

class SettingsController extends Controller
{
    public function settings_monitoring_dashboard_data(Request $request)
    {
        $UserCount = User::where('status', 'Active')->count();
        $AreaCount = DataArea::where('status', 'Active')->count();
        $DistrictCount = DataDistrict::where('status', '1')->count();
        $BranchCount = Branch::where('status', 'Active')->count();
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

    public function settings_system_logs_data()
    {
        // Fetch only 5 records ordered by id in descending order
        $systemLogs = SystemLogs::with('Employee')
            ->orderBy('id', 'desc')
            ->take(5); // Limit to 5 records

        return DataTables::of($systemLogs)
            ->setRowId('id')
            ->addColumn('differForHumans', function ($log) {
                $now = Carbon::now();
                $diffInMinutes = $log->created_at->diffInMinutes($now);
                $days = intdiv($diffInMinutes, 1440);
                $remainingMinutes = $diffInMinutes % 1440;
                $hours = intdiv($remainingMinutes, 60);
                $minutes = $remainingMinutes % 60;

                if ($days > 0) {
                    return $days . ' day' . ($days > 1 ? 's' : '') . ' and ' . $hours . ' hr' . ($hours > 1 ? 's' : '') . ' ago';
                } elseif ($hours > 0) {
                    return $hours . ' hr' . ($hours > 1 ? 's' : '') . ' and ' . $minutes . ' min' . ($minutes > 1 ? 's' : '') . ' ago';
                } else {
                    return $minutes . ' min' . ($minutes > 1 ? 's' : '') . ' ago';
                }
            })
            ->orderColumn('id', 'id $1') // Allows DataTables to control ordering
            ->rawColumns(['differForHumans'])
            ->make(true);
    }



    public function settings_dashboard(){
        return view('pages.pages_backend.settings_dashboard');
    }

    public function users_group_page(){
        $user = Auth::user();
        $user_types = $user->user_types;

        return view('pages.pages_backend.settings.settings_users_group_page', compact('user_types'));
    }

    public function users_group_data(){
       $user_group = DataUserGroup::with('Company')
            ->orderBy('updated_at', 'desc') // Explicitly set order here
            ->get();

        return DataTables::of($user_group)
        ->setRowId('id')
        ->make(true);
    }

    public function users_group_get($id){
        $TblUserGroup = DataUserGroup::with('Company')->findOrFail($id);
        return response()->json($TblUserGroup);
    }

    public function users_group_create(Request $request){
        DB::beginTransaction();
        try
        {
            DataUserGroup::create([
                'group_name' => $request->user_group,
                'company_id' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            // Create a system logs
            SystemLogs::create([
                'system' => 'ATM Monitoring',
                'action' => 'Create',
                'title' => 'Create New Usergroup',
                'description' => 'Creation of New Usergroup - ' . $request->user_group,
                'employee_id' => Auth::user()->employee_id,
                'ip_address' => $request->ip(),
                'created_at' => Carbon::now(),
                'company_id' => Auth::user()->company_id,
            ]);

            DB::commit();  // Commit the transaction if successful
        }
        catch (\Exception $e)
        {
            DB::rollBack();  // Roll back the transaction on error
            return response()->json([
                'status' => 'error',
                'message' => 'An Error Occurred, Please Check and Repeat!'
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'User group Created successfully!'  // Changed message to reflect update action
        ]);
    }

    public function users_group_update(Request $request){
        DB::beginTransaction();
        try
        {
            // Find the user group by ID
            $TblUserGroup = DataUserGroup::findOrFail($request->item_id);

            // Proceed with update if validation passes
            $TblUserGroup->update([  // Update the instance instead of using the class method
                'group_name' => $request->user_group,
                'updated_at' => Carbon::now(),  // Updated timestamp
            ]);

            // Create a Logs for System
            SystemLogs::create([
                'system' => 'ATM Monitoring',
                'action' => 'Update',
                'title' => 'Update Usergroup',
                'description' => 'Updating of Usergroup - ' . $TblUserGroup->user_group,
                'employee_id' => Auth::user()->employee_id,
                'ip_address' => $request->ip(),
                'created_at' => Carbon::now(),
                'company_id' => Auth::user()->company_id,
            ]);

            DB::commit();  // Commit the transaction if successful
        }
        catch (\Exception $e)
        {
            DB::rollBack();  // Roll back the transaction on error
            return response()->json([
                'status' => 'error',
                'message' => 'An Error Occurred, Please Check and Repeat!'
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'User group updated successfully!'  // Changed message to reflect update action
        ]);
    }

    public function districts_page(){
        return view('pages.pages_backend.settings.settings_district_page');
    }

    public function districts_data(){
       $district = DataDistrict::with('Company')
            ->latest('updated_at')
            ->get();

        return DataTables::of($district)
        ->setRowId('id')
        ->make(true);
    }

    public function districtsGet($id){
        $TblDistrict = DataDistrict::with('Company')->findOrFail($id);
        return response()->json($TblDistrict);
    }

    public function districtsCreate(Request $request){

        // Proceed with inserting if validation passes
        DataDistrict::create([
            'district_name' => $request->district_name,
            'district_number' => $request->district_number,
            'email' => $request->email,
            'company_id' => 2,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Create System Logs used for Auditing of Logs
        SystemLogs::create([
            'system' => 'ATM Monitoring',
            'action' => 'Create',
            'title' => 'Create New District',
            'description' => 'Creation of New District - ' .  $request->district_number .' - '.$request->district_name,
            'employee_id' => Auth::user()->employee_id,
            'ip_address' => $request->ip(),
            'created_at' => Carbon::now(),
            'company_id' => Auth::user()->company_id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'District created successfully!'
        ]);
    }

    public function districtsUpdate(Request $request){
        // Find the user group by ID
        $TblDistrict = DataDistrict::findOrFail($request->item_id);

        // Proceed with update if validation passes
        $TblDistrict->update([  // Update the instance instead of using the class method
            'district_name' => $request->district_name,
            'district_number' => $request->district_number,
            'email' => $request->email,
            'updated_at' => Carbon::now(),  // Updated timestamp
        ]);

        // Create System Logs used for Auditing of Logs
        SystemLogs::create([
            'system' => 'ATM Monitoring',
            'action' => 'Update',
            'title' => 'Update District',
            'description' => 'Updating of District - ' .  $TblDistrict->district_number .' - '.$TblDistrict->district_name,
            'employee_id' => Auth::user()->employee_id,
            'ip_address' => $request->ip(),
            'created_at' => Carbon::now(),
            'company_id' => Auth::user()->company_id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'District updated successfully!'  // Changed message to reflect update action
        ]);
    }

    public function area_page(){
        $districts = DataDistrict::latest('updated_at')->get();

        return view('pages.pages_backend.settings.settings_area_page', compact('districts'));
    }

    public function area_data(){
        $DataArea = DataArea::with('Company','District')
             ->latest('updated_at')
             ->get();

         return DataTables::of($DataArea)
         ->setRowId('id')
         ->addColumn('district_details', function($row) {
             return '<span class="fw-bold text-primary">' . $row->District->district_number . '</span> - <span class="fw-bold">' . $row->District->district_name . '</span>';
         })
         ->rawColumns(['district_details']) // Render HTML in both the action and pending_to columns
         ->make(true);
    }


    public function areaGet($id){
        $TblArea = DataArea::findOrFail($id);
        return response()->json($TblArea);
    }

    public function areaCreate(Request $request){

        // Proceed with inserting if validation passes
        DataArea::create([
            'area_no' => $request->area_no,
            'area_supervisor' => $request->area_supervisor,
            'district_id' => $request->district_id,
            'status' => 'Active',
            'company_id' => 2,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Create System Logs used for Auditing of Logs
        SystemLogs::create([
            'system' => 'ATM Monitoring',
            'action' => 'Create',
            'title' => 'Create Area',
            'description' => 'Creation of New Area - ' .  $request->area_no .' - '.$request->area_supervisor,
            'employee_id' => Auth::user()->employee_id,
            'ip_address' => $request->ip(),
            'created_at' => Carbon::now(),
            'company_id' => Auth::user()->company_id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Area created successfully!'
        ]);
    }

    public function areaUpdate(Request $request){
        // Find the user group by ID
        $TblArea = DataArea::findOrFail($request->item_id);
        $TblArea->update([  // Update the instance instead of using the class method
            'area_no' => $request->area_no,
            'area_supervisor' => $request->area_supervisor,
            'district_id' => $request->district_id,
            'status' => $request->status,
            'updated_at' => Carbon::now(),
        ]);

        // Create System Logs used for Auditing of Logs
        SystemLogs::create([
            'system' => 'ATM Monitoring',
            'action' => 'Update',
            'title' => 'Update Area',
            'description' => 'Updating of Area - ' .  $TblArea->area_no .' - '.$TblArea->area_supervisor,
            'employee_id' => Auth::user()->employee_id,
            'ip_address' => $request->ip(),
            'created_at' => Carbon::now(),
            'company_id' => Auth::user()->company_id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Area updated successfully!'  // Changed message to reflect update action
        ]);
    }

    public function branch_page(){
        $TblDistrict = DataDistrict::latest('updated_at')
            ->get();

        return view('pages.pages_backend.settings.settings_branch_page', compact('TblDistrict'));
    }

    public function branch_data(){
       $branch = Branch::with('Company','District','Area')
            ->latest('updated_at')
            ->get();

        return DataTables::of($branch)
        ->setRowId('id')
        ->make(true);
    }

    public function branchGet($id){
        $Branch = Branch::with('Company','District','Area')->findOrFail($id);
        return response()->json($Branch);
    }

    public function areaGetBydistrict(Request $request){
        // $district_id = $request->district_id;
        $TblArea = DataArea::where('district_id', $request->district_id)->get(); // get() instead of first()
        return response()->json($TblArea);
    }

    public function branchGetByarea(Request $request){
        // $district_id = $request->district_id;
        $Branch = Branch::where('area_id', $request->area_id)->get(); // get() instead of first()
        return response()->json($Branch);
    }

    public function branchCreate(Request $request){
        // Extract the first two letters of branch_location and convert to uppercase
        $branchAbbreviation = strtoupper(substr($request->branch_location, 0, 2));

        // Proceed with inserting if validation passes
        Branch::create([
            'district_id' => $request->district_id,
            'area_id' => $request->area_id,
            'branch_location' => $request->branch_location,
            'branch_head' => $request->branch_head,
            'branch_abbreviation' => $branchAbbreviation,
            'company_id' => 2,
            'status' => 'Active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Create System Logs used for Auditing of Logs
        SystemLogs::create([
            'system' => 'ATM Monitoring',
            'action' => 'Create New Branch',
            'description' => 'Creation of New Branch - ' .  $request->branch_location,
            'employee_id' => Auth::user()->employee_id,
            'ip_address' => $request->ip(),
            'created_at' => Carbon::now(),
            'company_id' => Auth::user()->company_id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Branch created successfully!'
        ]);
    }

    public function branchUpdate(Request $request){
        // Find the user group by ID
        $AtmBankLists = Branch::findOrFail($request->item_id);

        // Proceed with update if validation passes
        $AtmBankLists->update([  // Update the instance instead of using the class method
            'branch_abbreviation' => $request->branch_abbreviation,
            'branch_location' => $request->branch_location,
            'branch_head' => $request->branch_head,
            'district_id' => $request->district_id,
            'area_id' => $request->area_id,
            'status' => $request->status,
            'updated_at' => Carbon::now(),
        ]);

        // Create System Logs used for Auditing of Logs
        SystemLogs::create([
            'system' => 'ATM Monitoring',
            'action' => 'Update',
            'title' => 'Update Branch',
            'description' => 'Updating of Branch - ' .  $AtmBankLists->branch_location,
            'employee_id' => Auth::user()->employee_id,
            'ip_address' => $request->ip(),
            'created_at' => Carbon::now(),
            'company_id' => Auth::user()->company_id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Branch updated successfully!'  // Changed message to reflect update action
        ]);
    }

    public function bank_page(){
        return view('pages.pages_backend.settings.settings_bank_lists_page');
    }

    public function bank_data(){
       $branch = DataBankLists::latest('updated_at')
            ->get();

        return DataTables::of($branch)
        ->setRowId('id')
        ->make(true);
    }

    public function bankGet($id){
        $AtmBankLists = DataBankLists::findOrFail($id);
        return response()->json($AtmBankLists);
    }

    public function bankCreate(Request $request){

        // Proceed with inserting if validation passes
        DataBankLists::create([
            'bank_name' => $request->bank_name,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Create System Logs used for Auditing of Logs
        SystemLogs::create([
            'system' => 'ATM Monitoring',
            'action' => 'Create',
            'title' => 'Create New Bank',
            'description' => 'Creation of New Bank - ' .  $request->bank_name,
            'employee_id' => Auth::user()->employee_id,
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
        $AtmBankLists = DataBankLists::findOrFail($request->item_id);

        // Proceed with update if validation passes
        $AtmBankLists->update([  // Update the instance instead of using the class method
            'bank_name' => $request->bank_name,
            'updated_at' => Carbon::now(),  // Updated timestamp
        ]);

        // Create System Logs used for Auditing of Logs
        SystemLogs::create([
            'system' => 'ATM Monitoring',
            'action' => 'Update',
            'title' => 'Update Bank',
            'description' => 'Updating of Bank - ' .  $AtmBankLists->bank_name,
            'employee_id' => Auth::user()->employee_id,
            'ip_address' => $request->ip(),
            'created_at' => Carbon::now(),
            'company_id' => Auth::user()->company_id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'District updated successfully!'  // Changed message to reflect update action
        ]);
    }

    public function pension_types_page(){
        return view('pages.pages_backend.settings.settings_pension_types_page');
    }

    public function pension_types_data(){
       $branch = DataPensionTypesLists::latest('updated_at')
            ->get();

        return DataTables::of($branch)
        ->setRowId('id')
        ->make(true);
    }

    public function pension_typesGet($id){
        $AtmPensionTypesLists = DataPensionTypesLists::findOrFail($id);
        return response()->json($AtmPensionTypesLists);
    }

    public function pension_typesCreate(Request $request)
    {
        DataPensionTypesLists::create([
            'pension_name' => $request->pension_name,
            'types' => $request->types,
            'status' => 'Active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Create System Logs used for Auditing of Logs
        SystemLogs::create([
            'system' => 'ATM Monitoring',
            'action' => 'Create',
            'title' => 'Create Pension Types',
            'description' => 'Creation of New Pension Types - ' .  $request->types . ' - ' . $request->pension_name,
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

    public function pension_typesUpdate(Request $request)
    {
        // Find the user group by ID
        $AtmPensionTypesLists = DataPensionTypesLists::findOrFail($request->item_id);

        // Proceed with update if validation passes
        $AtmPensionTypesLists->update([  // Update the instance instead of using the class method
            'pension_name' => $request->pension_name,
            'types' => $request->types,
            'status' => $request->status,
            'updated_at' => Carbon::now(),
        ]);

        // Create System Logs used for Auditing of Logs
        SystemLogs::create([
            'system' => 'ATM Monitoring',
            'action' => 'Update',
            'title' => 'Update Pension Types',
            'description' => 'Updating of Pension Types - ' .  $AtmPensionTypesLists->types . ' - ' . $AtmPensionTypesLists->pension_name,
            'employee_id' => Auth::user()->employee_id,
            'ip_address' => $request->ip(),
            'created_at' => Carbon::now(),
            'company_id' => Auth::user()->company_id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Pension Types updated successfully!'  // Changed message to reflect update action
        ]);
    }

    public function transaction_action_page()
    {

        $DataUserGroup = DataUserGroup::where('status','Active')->get();

        return view('pages.pages_backend.settings.settings_transaction_action_page', compact('DataUserGroup'));
    }

    public function transaction_action_data()
    {
       $DataTransactionAction = DataTransactionAction::with('DataTransactionSequence','DataTransactionSequence.DataUserGroup')->latest('updated_at')
            ->get();

        return DataTables::of($DataTransactionAction)
            ->setRowId('id')
            ->make(true);
    }

    public function transaction_typesGet($id)
    {
        $DataTransactionAction = DataTransactionAction::with('DataTransactionSequence','DataTransactionSequence.DataUserGroup')->findOrFail($id);
        return response()->json($DataTransactionAction);
    }

    public function transaction_typesCreate(Request $request)
    {
        $DataTransactionAction = DataTransactionAction::create([
            'name' => $request->name,
            'status' => 'Active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        foreach ($request->user_group_id as $key => $value) {
            DataTransactionSequence::create([
                'atm_transaction_actions_id' =>$DataTransactionAction->id,
                'user_group_id' => $value,
                'sequence_no' => $request->sequence_no[$key],
                'type' => $request->type[$key],
                'updated_at' => Carbon::now(),
            ]);
        }

        // Create System Logs used for Auditing of Logs
        SystemLogs::create([
            'system' => 'ATM Monitoring',
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

    public function transaction_typesUpdate(Request $request)
    {
        // Find the user group by ID
        $DataTransactionAction = DataTransactionAction::findOrFail($request->item_id);

        // Proceed with update if validation passes
        $DataTransactionAction->update([  // Update the instance instead of using the class method
            'name' => $request->name,
            'status' => $request->status,
            'updated_at' => Carbon::now(),
        ]);

        // Create System Logs used for Auditing of Logs
        SystemLogs::create([
            'system' => 'ATM Monitoring',
            'action' => 'Update',
            'title' => 'Update Transaction Action',
            'description' => 'Updating of Transaction Action - ' .  $DataTransactionAction->name,
            'employee_id' => Auth::user()->employee_id,
            'ip_address' => $request->ip(),
            'created_at' => Carbon::now(),
            'company_id' => Auth::user()->company_id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Transaction Action updated successfully!'  // Changed message to reflect update action
        ]);
    }

    public function release_reason_page()
    {
        return view('pages.pages_backend.settings.settings_release_reason_page');
    }

    public function release_reason_data()
    {
       $DataReleaseOption = DataReleaseOption::where('status','Active')
            ->latest('updated_at')
            ->whereNull('deleted_at')
            ->get();

        return DataTables::of($DataReleaseOption)
            ->setRowId('id')
            ->make(true);
    }

    public function release_reason_get($id)
    {
        $DataReleaseOption = DataReleaseOption::findOrFail($id);
        return response()->json($DataReleaseOption);
    }

    public function release_reason_create(Request $request)
    {
        DataReleaseOption::create([
            'reason' => $request->reason,
            'description' => $request->description,
            'status' => 'Active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Create System Logs used for Auditing of Logs
        SystemLogs::create([
            'system' => 'ATM Monitoring',
            'action' => 'Create',
            'title' => 'Create Release Reason',
            'description' => 'Creation of Release Reason | ' .  $request->reason . ' - ' . $request->description,
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

    public function release_reason_update(Request $request)
    {
        // Find the user group by ID
        $DataReleaseOption = DataReleaseOption::findOrFail($request->item_id);

        // Proceed with update if validation passes
        $DataReleaseOption->update([  // Update the instance instead of using the class method
            'reason' => $request->reason,
            'description' => $request->description,
            'status' => $request->status,
            'updated_at' => Carbon::now(),
        ]);

        // Create System Logs used for Auditing of Logs
        SystemLogs::create([
            'system' => 'ATM Monitoring',
            'action' => 'Update',
            'title' => 'Update Release Reason',
            'description' => 'Updating of Release Reason | ' .  $DataReleaseOption->reason . ' - ' . $DataReleaseOption->description .' into '. $request->reason .' - ' . $request->description,
            'employee_id' => Auth::user()->employee_id,
            'ip_address' => $request->ip(),
            'created_at' => Carbon::now(),
            'company_id' => Auth::user()->company_id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Release Reason Updated Successfully!'  // Changed message to reflect update action
        ]);
    }

    public function collection_date_page()
    {
        return view('pages.pages_backend.settings.settings_collection_date');
    }

    public function collection_date_data()
    {
       $DataReleaseOption = DataCollectionDate::latest('updated_at')
            ->whereNull('deleted_at')
            ->get();

        return DataTables::of($DataReleaseOption)
            ->setRowId('id')
            ->make(true);
    }

    public function collection_date_get($id)
    {
        $DataCollectionDate = DataCollectionDate::findOrFail($id);
        return response()->json($DataCollectionDate);
    }

    public function collection_date_create(Request $request)
    {
        DataCollectionDate::create([
            'collection_date' => $request->collection_date,
            'status' => 'Active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Create System Logs used for Auditing of Logs
        SystemLogs::create([
            'system' => 'ATM Monitoring',
            'action' => 'Create',
            'title' => 'Create Collection Date',
            'description' => 'Creation of Collection Date - ' .  $request->collection_date,
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

    public function collection_date_update(Request $request)
    {
        // Find the user group by ID
        $DataCollectionDate = DataCollectionDate::findOrFail($request->item_id);

        // Proceed with update if validation passes
        $DataCollectionDate->update([  // Update the instance instead of using the class method
            'collection_date' => $request->collection_date,
            'status' => $request->status,
            'updated_at' => Carbon::now(),
        ]);

        // Create System Logs used for Auditing of Logs
        SystemLogs::create([
            'system' => 'ATM Monitoring',
            'action' => 'Update',
            'title' => 'Update Collection Date',
            'description' => 'Updating of Collection Date | ' .  $DataCollectionDate->collection_date .' into '. $request->collection_date,
            'employee_id' => Auth::user()->employee_id,
            'ip_address' => $request->ip(),
            'created_at' => Carbon::now(),
            'company_id' => Auth::user()->company_id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Collection Date Updated Successfully!'  // Changed message to reflect update action
        ]);
    }

    public function maintenance_page()
    {
        return view('pages.pages_backend.settings.settings_maintenance_page');
    }

    public function maintenance_data()
    {
       $MaintenancePage = MaintenancePage::latest('updated_at')
            ->whereNull('deleted_at')
            ->get();

        return DataTables::of($MaintenancePage)
            ->setRowId('id')
            ->make(true);
    }

    public function maintenance_get($id)
    {
        $MaintenancePage = MaintenancePage::findOrFail($id);
        return response()->json($MaintenancePage);
    }

    public function maintenance_create(Request $request)
    {
        MaintenancePage::create([
            'pages_name' => $request->pages_name,
            'status' => 'no',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Create System Logs used for Auditing of Logs
        SystemLogs::create([
            'system' => 'ATM Monitoring',
            'action' => 'Create',
            'title' => 'Create Maintenance Page',
            'description' => 'Creation of Maintenance Page - ' .  $request->pages_name,
            'employee_id' => Auth::user()->employee_id,
            'ip_address' => $request->ip(),
            'created_at' => Carbon::now(),
            'company_id' => Auth::user()->company_id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Maintenance Page Created successfully!'
        ]);
    }

    public function maintenance_update(Request $request)
    {
        $MaintenancePage = MaintenancePage::findOrFail($request->item_id);
        $MaintenancePage->update([  // Update the instance instead of using the class method
            'pages_name' => $request->pages_name,
            'status' => $request->status,
            'updated_at' => Carbon::now(),
        ]);

        // Create System Logs used for Auditing of Logs
        SystemLogs::create([
            'system' => 'ATM Monitoring',
            'action' => 'Update',
            'title' => 'Update Maintenance Page',
            'description' => 'Updating of Maintenance Page | ' .  $MaintenancePage->pages_name .' into '. $request->pages_name,
            'employee_id' => Auth::user()->employee_id,
            'ip_address' => $request->ip(),
            'created_at' => Carbon::now(),
            'company_id' => Auth::user()->company_id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Maintenance Page Updated Successfully!'  // Changed message to reflect update action
        ]);
    }

    public function departmentsPage()
    {
        return view('pages.pages_backend.settings.settings_departments_page');
    }

    public function departmentsData()
    {
       $DataDepartments = DataDepartments::latest('updated_at')
            ->whereNull('deleted_at')
            ->get();

        return DataTables::of($DataDepartments)
            ->setRowId('id')
            ->make(true);
    }

    public function departmentsGet($id)
    {
        $DataDepartments = DataDepartments::findOrFail($id);
        return response()->json($DataDepartments);
    }

    public function departmentsCreate(Request $request)
    {
        DataDepartments::create([
            'name' => $request->name,
            'status' => 'active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Create System Logs used for Auditing of Logs
        SystemLogs::create([
            'system' => 'ATM Monitoring',
            'action' => 'Create',
            'title' => 'Create Departments Page',
            'description' => 'Creation of Departments Page - ' .  $request->name,
            'employee_id' => Auth::user()->employee_id,
            'ip_address' => $request->ip(),
            'created_at' => Carbon::now(),
            'company_id' => Auth::user()->company_id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Departments Page Created successfully!'
        ]);
    }

    public function departmentsUpdate(Request $request)
    {
        $DataDepartments = DataDepartments::findOrFail($request->item_id);
        $DataDepartments->update([  // Update the instance instead of using the class method
            'name' => $request->name,
            'status' => $request->status,
            'updated_at' => Carbon::now(),
        ]);

        // Create System Logs used for Auditing of Logs
        SystemLogs::create([
            'system' => 'ATM Monitoring',
            'action' => 'Update',
            'title' => 'Update Departments Page',
            'description' => 'Updating of Departments Page | ' .  $DataDepartments->name .' into '. $request->name,
            'employee_id' => Auth::user()->employee_id,
            'ip_address' => $request->ip(),
            'created_at' => Carbon::now(),
            'company_id' => Auth::user()->company_id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Departments Page Updated Successfully!'  // Changed message to reflect update action
        ]);
    }

    public function companyPage(){
        return view('pages.pages_backend.settings.settings_company');
    }

    public function companyData(){
       $DataCompany = Company::latest('updated_at')
            ->whereNull('deleted_at')
            ->get();

        return DataTables::of($DataCompany)
            ->setRowId('id')
            ->make(true);
    }

    public function companyGet($id){
        $DataCompany = Company::findOrFail($id);
        return response()->json($DataCompany);
    }

    public function companyCreate(Request $request){
        Company::create([
            'company_name' => $request->name,
            'status' => 'Active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Create System Logs used for Auditing of Logs
        SystemLogs::create([
            'system' => 'ATM Monitoring',
            'action' => 'Create',
            'title' => 'Create Company',
            'description' => 'Creation of Company - ' .  $request->name,
            'employee_id' => Auth::user()->employee_id,
            'ip_address' => $request->ip(),
            'created_at' => Carbon::now(),
            'company_id' => Auth::user()->company_id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Company Has Been Created successfully!'
        ]);
    }

    public function companyUpdate(Request $request){
        $DataCompany = Company::findOrFail($request->item_id);
        $DataCompany->update([  // Update the instance instead of using the class method
            'company_name' => $request->name,
            'status' => $request->status,
            'updated_at' => Carbon::now(),
        ]);

        // Create System Logs used for Auditing of Logs
        SystemLogs::create([
            'system' => 'ATM Monitoring',
            'action' => 'Update',
            'title' => 'Update Company',
            'description' => 'Updating of Company | ' .  $DataCompany->name .' into '. $request->name,
            'employee_id' => Auth::user()->employee_id,
            'ip_address' => $request->ip(),
            'created_at' => Carbon::now(),
            'company_id' => Auth::user()->company_id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Company Updated Successfully!'  // Changed message to reflect update action
        ]);
    }

    public function login_page()
    {
        return view('auth.login_page');
    }
















}

