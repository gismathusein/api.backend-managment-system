<?php

namespace App\Http\Controllers;

use App\Http\Requests\Position\PositionRequest;
use App\Http\Requests\Position\UpdatePositionRequest;
use App\Models\Position;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PositionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        if ($request['limit']) {
            $positions = Position::query()
                ->select('positions.id', 'positions.department_id', 'positions.name', 'positions.status', 'departments.company_id')
                ->leftJoin("departments", 'departments.id', "=", "positions.department_id")
                ->orderByDesc('id')
                ->paginate($request['limit']);
            $collection = $positions->getCollection();
            return collectionResponse('data', $collection, $positions);
        } else {
            $positions = Position::query()
                ->select('positions.id', 'positions.department_id', 'positions.name', 'positions.status', 'departments.company_id')
                ->leftJoin("departments", 'departments.id', "=", "positions.department_id")
                ->orderByDesc('id')->get();

            foreach ($positions as $position) {
                if(count($position->users) !== 0){
                    $position['is_deletable'] = false;
                }else{
                    $position['is_deletable'] = true;
                }
                unset($position->users);
            }
            return response()->json(['data' => $positions], 200);
        }
    }

    public function positionOptions(): JsonResponse
    {
        $positions = Position::where('status' , \Status::ACTIVE)->get();
        return response()->json(['data' => $positions], 200);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'department_id' => ['required', 'integer','exists:App\Models\Department,id'],
            'name' => ['required', 'string'],
            'status' => ['integer','max:1','min:0']
        ]);
        if ($validator->fails()){
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }else{
            $validated = $request->all();
            $position = Position::create($validated);
            return response()->json($position, 201);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'department_id' => ['integer','exists:App\Models\Department,id'],
            'name' => ['string'],
            'status' => ['integer']
        ]);
        if ($validator->fails()){
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }else{
            $validated = $request->all();
            $position = Position::find($id);
            $position->name = $validated['name'];
            $position->status = (int)$validated['status'];
            $position->department_id = (int)$validated['department_id'];
            $position->save();
            return response()->json($position, 201);
        }
    }

    public function show($id): JsonResponse
    {
        $position = Position::find($id);
        if (!$position) {
            return response()->json([
                'message' => 'Position Not found'
            ], 404);
        }
        return response()->json($position, 200);
    }

    public function destroy($id): JsonResponse
    {
        $position = Position::findOrFail($id);

        if (count($position->users) !== 0) {
            return response()->json([
                'message' => 'Position not deleted,because have users'
            ], 404);
        } else {
            $position->delete();
            return response()->json([
                'message' => 'Position deleted'
            ]);
        }

    }


}
