<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\DataUserGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Models\DataDistrict;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function users_page()
    {
        $user_groups = DataUserGroup::whereNull('deleted_at')
            ->where('status','Active')
            ->get();

        $DataDistrict = DataDistrict::whereNull('deleted_at')
            ->get();

        return view('pages.pages_backend.settings.users_page',compact('user_groups','DataDistrict'));
    }

    public function users_data()
    {
        $userGroup = Auth::user()->user_types;

        $users = User::with('Branch', 'District', 'Area', 'UserGroup')
            ->when($userGroup !== 'Developer', function($query) {
                // Exclude users with user_types 'Developer' if the authenticated user is not a Developer
                $query->where('user_types', '!=', 'Developer');
            })
            ->latest('updated_at')
            ->get();

        return DataTables::of($users)
        ->addColumn('password', function($user) {
            // Return the hashed password for display (Not recommended for security reasons)
            return $user->password;
        })
        ->setRowId('id')
        ->make(true);
    }

    public function users_get($id)
    {
        $User = User::with('Branch','District','Area','UserGroup')->findOrFail($id);
        return response()->json($User);
    }

    public function users_create(Request $request)
    {
        // Check if the password and confirm password match
        if ($request->password !== $request->confirm_password) {
            return response()->json(['error' => 'Passwords do not match!']);
        }
        else
        {
            // Check if the email already exists
            if (User::where('email', $request->email)->exists()) {
                return response()->json(['error' => 'Email already exists!']);
            }

            // Check if the employee number already exists
            if (User::where('employee_id', $request->employee_id)->exists()) {
                return response()->json(['error' => 'Employee number already exists!']);
            }

            if($request->user_type === 'Head Office')
            {
                $user_group_id = $request->user_group_id;
                $district_id = NULL;
                $branch_id = NULL;
                $area_id = NULL;
            }
            else if($request->user_type === 'District')
            {
                $user_group_id = 7;
                $district_id = $request->district_id;
                $branch_id = NULL;
                $area_id = NULL;
            }
            else if($request->user_type === 'Area')
            {
                $user_group_id = 6;
                $district_id = $request->district_manager_area_id;
                $branch_id = NULL;
                $area_id = $request->area_supervisor_id;
            }
            else if($request->user_type === 'Branch')
            {
                $user_group_id = $request->user_group_branch_id;
                $branch_id = $request->branch_id;
                $district_id = $request->district_manager_id;
                $area_id = $request->area_id;
            }
            else if($request->user_type === 'Admin')
            {
                $user_group_id = 1;
                $branch_id = NULL;
                $district_id = NULL;
                $area_id = NULL;
            }
            else if($request->user_type === 'Developer')
            {
                $user_group_id = 56;
                $branch_id = NULL;
                $district_id = NULL;
                $area_id = NULL;
            }
            else
            {
            }

            $contact_no = preg_replace('/[^0-9]/', '', $request->contact_no);

                    // Create the user
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'employee_id' => $request->employee_id,
                'contact_no' => $contact_no,
                'address' => $request->address,
                'username' => $request->username,
                'password' => Hash::make($request->password), // Hash the password
                'email_verified_at' => Carbon::now(),
                'session' => 'Offline',
                'user_types' => $request->user_type,
                'user_group_id' => $user_group_id,
                'branch_id' => $branch_id,
                'district_code_id' => $district_id,
                'area_code_id' => $area_id,
                'status' => 'Active',
                'company_id' => 2,
                'dob' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        //     return response()->json(['success' => 'User created successfully!']);

        // }
    }

    public function users_update(Request $request)
    {
        $User = User::findOrFail($request->item_id);

        // Check if the password and confirm password match
        if ($request->password !== $request->confirm_password) {
            return response()->json(['error' => 'Passwords do not match!']);
        }
        else
        {
            if (User::where('email', $request->email)->where('id', '!=', $User->id)->exists()) {
                return response()->json(['error' => 'Email already exists!']);
            }

            // Check if the employee number already exists but exclude the current user's employee number
            if (User::where('employee_id', $request->employee_id)->where('id', '!=', $User->id)->exists()) {
                return response()->json(['error' => 'Employee number already exists!']);
            }

            if($request->user_type === 'Head_Office')
            {
                $user_group_id = $request->user_group_id;
                $district_id = NULL;
                $branch_id = NULL;
                $area_id = NULL;
            }
            else if($request->user_type === 'District')
            {
                $user_group_id = 7;
                $district_id = $request->district_id;
                $branch_id = NULL;
                $area_id = NULL;
            }
            else if($request->user_type === 'Area')
            {
                $user_group_id = 6;
                $district_id = $request->district_manager_area_id;
                $branch_id = NULL;
                $area_id = $request->area_supervisor_id;
            }
            else if($request->user_type === 'Branch')
            {
                $user_group_id = $request->user_group_branch_id;
                $branch_id = $request->branch_id;
                $district_id = $request->district_manager_id;
                $area_id = $request->area_id;
            }
            else if($request->user_type === 'Admin')
            {
                $user_group_id = 1;
                $branch_id = NULL;
                $district_id = NULL;
                $area_id = NULL;
            }
            else if($request->user_type === 'Developer')
            {
                $user_group_id = 56;
                $branch_id = NULL;
                $district_id = NULL;
                $area_id = NULL;
            }
            else
            {
            }

            $contact_no = preg_replace('/[^0-9]/', '', $request->contact_no);

            $User = User::findOrFail($request->item_id);

            // If password is provided, hash and update it; otherwise, keep the old password
            $passwordUpdate = $request->password ? Hash::make($request->password) : $User->password;

            // Update the user
            $User->update([
                'name' => $request->name,
                'email' => $request->email,
                'employee_id' => $request->employee_id,

                'contact_no' => $contact_no,
                'address' => $request->address,
                'username' => $request->username,

                'password' => $passwordUpdate, // Update with the hashed new password or keep the old one
                'user_types' => $request->user_type,
                'user_group_id' => $user_group_id,
                'branch_id' => $branch_id,
                'district_code_id' => $district_id,
                'area_code_id' => $area_id,
                'status' => 'Active',
                'dob' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

        }

        //     return response()->json(['success' => 'User created successfully!']);

        // }
    }




    // public function users_update($id)
    // {
    //     $update_user = User::findOrFail($id)->whereNull('deleted_at')->get();
    //     return response()->json($update_user);

    //     $update_user->update([
    //         'quantity' => $request->quantity_received,
    //         'status' => 'Completed',
    //         'received_by_id' => Auth::user()->id,
    //         'loss_quantity' =>  $request->loss_quantity,
    //         'damage_quantity' =>  $request->damage_quantity,
    //     ]);
    // }



}
