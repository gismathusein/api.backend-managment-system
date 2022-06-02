<?php

namespace App\Http\Controllers;

use App\Http\Requests\Company\UpdateCompanyRequest;
use App\Http\Requests\Group\GroupRequest;
use App\Models\Company;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class GroupController extends Controller
{

    public function groups(Request $request): JsonResponse
    {
        $groups = Group::query();

        if (!isset($request['limit'])) {
            $request['limit'] = 10;
        }

        if ($request['name']) {
            $groups->where('name', 'like', '%' . $request['name'] . '%');
        }
//        $groups->where('status',1);

        $groups->orderByDesc('id')->with(['companies', 'users']);
        $datas = $groups->get();

        foreach ($datas as $group) {
            $count = DB::table('tasks')->where('group_id', $group->id)->count();
            if ($count === 0) {
                $group['is_deletable'] = true;
            } else {
                $group['is_deletable'] = false;
            }
        }

        return response()->json(['data' => $datas], 200);

    }

    public function groupOptions(): JsonResponse
    {
        $groups = Group::where('status' , \Status::ACTIVE)->with(['companies', 'users'])->get();
        return response()->json(['data' => $groups], 200);
    }

    public function tasks($id): JsonResponse
    {
        $task = Group::find($id)->tasks;
        return response()->json(['data' => $task], 200);
    }

    public function show($id): JsonResponse
    {
        $group = Group::find($id);
        if (!$group) {
            return response()->json([
                'message' => 'Group not found'
            ], 404);
        } else {
            return response()->json($group, 200);
        }

    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required','string','min:3'],
            'group_type' => ['required','integer','min:0','max:1'],
            'status' => ['required','integer','min:0','max:1'],
            'users'=>['required','exists:App\Models\User,id'],
            'admins'=>['required','exists:App\Models\User,id'],
        ]);
        if ($validator->fails()){
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }else{
            $validated = $request->all();
            $newGroup = Group::create($validated);
            $usersArr = $request->users;
            $adminsArr = $request->admins;
            $admins = User::find($adminsArr);
            $companyArr = $request['companies'];
            $company = Company::find($companyArr);
            $newGroup->companies()->attach($company);
            $newGroup->users()->attach($admins, ['role' => 1]);
            foreach ($usersArr as $user) {
                $exist = $newGroup->users()->where('user_id', $user)->exists();
                $newUser = User::find($user);
                if ($exist == false) {
                    $newGroup->users()->attach($newUser, ['role' => 0]);
                }
            }

            return response()->json($newGroup, 201);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        $group = Group::find($id);
        $validator = Validator::make($request->all(), [
            'name' => ['string' , 'min:3'],
            'group_type' => ['integer','min:0','max:1'],
            'status' => ['integer','min:0','max:1'],
            'users'=>['exists:App\Models\User,id'],
            'admins'=>['exists:App\Models\User,id'],
        ]);
        if ($validator->fails()){
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }else{
            if (!$group) {
                return response()->json([
                    'message' => 'Group not found'
                ], 404);
            } else {
                $group->fill($request->all());
                $saved = $group->save();
                $group->users()->detach();
                $usersArr = $request->users;
                $adminsArr = $request->admins;
                $companyArr = $request['companies'];
                $users = User::find($usersArr);
                $admins = User::find($adminsArr);
                $company = Company::find($companyArr);
                $group->companies()->sync($company);
                $group->users()->attach($admins, ['role' => 1]);
                foreach ($usersArr as $user) {
                    $exist = $group->users()->where('user_id', $user)->exists();
                    $newUser = User::find($user);
                    if ($exist == false) {
                        $group->users()->attach($newUser, ['role' => 0]);
                    }
                }

                if ($saved) {
                    return response()->json($group, 201);
                } else {
                    return response()->json([
                        'status' => 'err',
                        'msg' => "Error while inserting data to database"
                    ], 500);
                }
            }
        }
    }

    public function destroy($id): JsonResponse
    {
        $group = Group::find($id);
        if (!$group) {
            return response()->json([
                'message' => 'Group not found'
            ], 404);
        }
        if (count($group->groupTasks) !== 0) {
            return response()->json([
                'message' => 'Group not deleted'
            ], 200);
        } else {
            $group->delete();
            $group->users()->detach();
            $group->companies()->detach();
            return response()->json([
                'message' => 'Group deleted'
            ], 200);
        }
    }

    public function users(Request $request, $id): JsonResponse
    {
        $group = Group::find($id);
        $users = $group->groupUsers;
        return response()->json([
            'data' => $users
        ], 200);
    }

    public function companies(Request $request, $id): JsonResponse
    {
        $company = Company::find($id)->departments;
        if (!$company) {
            return response()->json([
                'message' => 'Company not found'
            ]);
        }
        return response()->json($company);
    }
}
