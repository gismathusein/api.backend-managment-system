<?php

namespace App\Http\Controllers;

use App\Http\Requests\Department\DepartmentRequest;
use App\Http\Requests\Department\UpdateDepartmentRequest;
use App\Models\Department;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class DepartmentController extends Controller
{

    public function index(Request $request): JsonResponse
    {

        if ($request['limit']) {
            $departments = Department::with('company')->orderByDesc('id')->paginate($request['limit']);
            $collection = $departments->getCollection();
            foreach ($collection as $item) {
                if ($item->positions) $item['is_deletable'] = false;
                if (!$item->positions) $item['is_deletable'] = true;
            }
            return collectionResponse('data', $collection, $departments);
        } else {
            $departments = Department::with('company')->orderByDesc('id')->get();

            foreach ($departments as $item) {
                if (count($item->positions) !== 0) $item['is_deletable'] = false;
                if (count($item->positions) === 0) $item['is_deletable'] = true;
                unset($item->positions);
            }
            return response()->json(['data' => $departments], 200);

        }
    }

    public function departmentOptions(): JsonResponse
    {
        $departments = Department::where('status' , \Status::ACTIVE)->get();
        return response()->json(['data' => $departments], 200);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'company_id' => ['required','integer','exists:App\Models\Company,id'],
            'name' => ['required','string'],
            'status' => ['integer','max:1','min:0']
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }else{
            $validated = $request->all();
            $department = Department::create($validated);
            return response()->json($department, 201);
        }
    }

    public function show($id): JsonResponse
    {
        $department = Department::find($id);
        $department->company;
        return response()->json([
            'department' => $department
        ]);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $department = Department::find($id);
        $validator = Validator::make($request->all(), [
            'company_id' => ['integer', 'exists:App\Models\Company,id'],
            'name' => ['string'],
            'status' => ['integer', 'max:1', 'min:0']
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }else{
            $validated = $request->all();
            if (isset($validated['name'])) $department->name = $validated['name'];
            if (isset($validated['status'])) $department->status = (int)$validated['status'];
            $department->save();
            return response()->json($department, 200);
        }
    }

    public function destroy($id): JsonResponse
    {
        $department = Department::find($id);
        if (!$department) {
            return response()->json([
                'message' => 'Department not found'
            ], 404);
        } else {
            if (count($department->positions) !== 0) {
                return response()->json([
                    'message' => 'Department not deleted '
                ], 400);
            } else {
                $department->delete();
                return response()->json([
                    'message' => 'Department deleted'
                ], 200);

            }
        }
    }

}
