<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\UpdateUserRequest;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class AuthController extends Controller
{


    public function users(Request $request): JsonResponse
    {
        $users = User::query();

        if ($request['fin_code']) {
            $users->where('fin_code', 'like', '%' . $request['fin_code'] . '%');
        }

        if ($request['serial_number']) {
            $users->where('serial_number', 'like', '%' . $request['serial_number'] . '%');
        }

        if ($request['name']) {
            $users->where('name', 'like', '%' . $request['name'] . '%');
        }
        if ($request['surname']) {
            $users->where('surname', 'like', '%' . $request['surname'] . '%');
        }

        if ($request['email']) {
            $users->where('email', 'like', '%' . $request['email'] . '%');
        }

        if ($request['phone']) {
            $users->where('phone', 'like', '%' . $request['phone'] . '%');
        }
        if ($request['address']) {
            $users->where('address', 'like', '%' . $request['address'] . '%');
        }
        $totalUsers = $users->count();
        $limit = $users->count();
        if ($request['limit']) $limit = (int)$request['limit'];
        $users->orderByDesc('id')->with('photo');
        $data = $users->paginate($limit)->items();

        foreach ($data as $item) {
            if (count($item->tasks) !== 0) $item['is_deletable'] = false;
            if (count($item->tasks) === 0) $item['is_deletable'] = true;
            unset($item->tasks);
        }


        return response()->json([
            'data' => $data,
            'total' => $totalUsers
        ], 200);
    }


    public function getUsers(Request $request): JsonResponse
    {
        $users = User::query();


        if ($request['name']) {
            $users->where('name', 'like', '%' . $request['name'] . '%');
        }
        if ($request['surname']) {
            $users->where('surname', 'like', '%' . $request['surname'] . '%');
        }

        if ($request['email']) {
            $users->where('email', 'like', '%' . $request['email'] . '%');
        }

        if ($request['phone']) {
            $users->where('phone', 'like', '%' . $request['phone'] . '%');
        }
        if ($request['address']) {
            $users->where('address', 'like', '%' . $request['address'] . '%');
        }
        if ($request['company_id']) {
            $users->whereIn('users.company_id', $request['company_id']);
        }

        if ($request['department_id']) {
            $users->whereIn('users.department_id', $request['department_id']);
        }
        if ($request['position_id']) {
            $users->whereIn('users.position_id', $request['position_id']);
        }
        if ($request['status']) {
            $users->where('users.status', $request['status']);
        }
        $totalUsers = $users->count();
        $limit = $users->count();
        if ($request['limit']) $limit = (int)$request['limit'];
        $users->leftJoin('companies', 'companies.id', 'users.company_id')
            ->leftJoin('departments', 'departments.id', 'users.department_id')
            ->leftJoin('positions', 'positions.id', 'users.position_id')
            ->select('users.*','companies.name as company','departments.name as department','positions.name as position');
        $users->orderByDesc('users.id')->with(['photo']);
        $data = $users->paginate($limit)->items();

        foreach ($data as $item) {
            if (count($item->tasks) !== 0) $item['is_deletable'] = false;
            if (count($item->tasks) === 0) $item['is_deletable'] = true;
            unset($item->tasks);
        }


        return response()->json([
            'data' => $data,
            'total' => $totalUsers
        ], 200);
    }

    public function groupUsers(): JsonResponse
    {
        $users = User::where('status' , \Status::ACTIVE)->get();
        return response()->json(['data' => $users], 200);
    }

    public function show($id): JsonResponse
    {
        $user = User::find($id);

        $user->photo;
        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        } else {
            return response()->json($user, 200);
        }

    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'company_id'=> ['integer' , 'exists:App\Models\Company,id'],
            'department_id'=> ['integer','exists:App\Models\Department,id'],
            'position_id'=> ['integer','exists:App\Models\Position,id'],
            'fin_code'=> ['string','min:7','max:7', Rule::unique('users')->ignore($id)],
            'serial_number'=> ['string','min:6','max:13' , Rule::unique('users')->ignore($id)],
            'serial_code'=> ['string'],
            'name' => ['string','between:2,100'],
            'surname' => ['string','between:2,100'],
            'phone'=> ['string','max:14' , Rule::unique('users')->ignore($id)],
            'address'=> ['string','between:3,100'],
            'email' => ['string','email','max:100',Rule::unique('users')->ignore($id)],
            'password' => ['string','min:6'],
            'photo' => ['image','mimes:jpg,jpeg,png'],
            'status' => ['integer','min:0','max:1']
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }else{
            $validated = $request->all();
            $user = User::findOrFail($id);
            if (!$user) {
                return response()->json([
                    'message' => 'User not found'
                ], 404);
            } else {
                if ($request->photo !== null) {

                    if ($request->hasFile('photo')) {
                        $photo = $validated['photo'];
                        uploadImage($photo, $user, 'photo', 'update', Auth::id());
                    }
                }
                unset($validated['photo']);
                $user->fill($validated);
                $saved = $user->save();
                if ($saved) {
                    return response()->json($user, 200);
                } else {
                    return response()->json([
                        'status' => 'err',
                        'message' => "Error while inserting data to database"
                    ], 500);
                }
            }
        }

    }

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request): JsonResponse
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        if (!$token = auth()->attempt($validator->validated())) {
            return response()->json(['message' => 'Username or password incorrect'], 400);
        }
        $companyId = User::where('email', $request['email'])->first();
        $companyStatus = Company::where('id', $companyId['company_id'])->first();
        if ($companyStatus['status'] == 1) {
            return $this->createNewToken($token);
        } else {
            return response()->json([
                'error' => 'Sizin girişiniz qadağan edilmişdir!'
            ], 403);
        }

    }

    protected function createNewToken($token): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 36000000000,
            'user' => auth()->user()
        ]);
    }

    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'company_id' => 'required|integer|exists:App\Models\Company,id',
            'department_id' => 'required|integer|exists:App\Models\Department,id',
            'position_id' => 'required|integer|exists:App\Models\Position,id',
            'fin_code' => 'required|string|min:7|max:7|unique:users',
            'serial_number' => 'required|string|min:6|max:13|unique:users',
            'serial_code' => 'required|string',
            'name' => 'required|string|between:2,100',
            'surname' => 'required|string|between:2,100',
            'phone' => 'required|string|max:14|unique:users',
            'address' => 'required|string|between:3,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6',
            'photo' => 'image|mimes:jpg,jpeg,png',
            'status' => 'integer|min:0|max:1'
        ]);


        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        } else {
            $validated = $request->all();
            $photo = $request['photo'];
            unset($validated['photo']);
            $validated['password'] = bcrypt($validated['password']);
            $user = User::create($validated);
            if ($request->hasFile('photo')) {
                uploadImage($photo, $user, 'photo', 'create', $user->id);
            }

            //Send Email
            Artisan::call('optis:send:email --password=' . $request->password . ' --toemail=' . $validated['email']);
            return response()->json([
                'message' => 'User successfully registered',
                'user' => $user,
            ], 201);
        }


        //Photo api/v1/uploads

    }

    public function logout(): JsonResponse
    {
        auth()->logout();

        return response()->json(['message' => 'User successfully signed out']);
    }

    public function refresh(): JsonResponse
    {
        return $this->createNewToken(auth()->refresh());
    }

    public function userProfile(): JsonResponse
    {
        $user = User::find(Auth::id());
        $user->tasks;
        $user->groups;

        return response()->json($user);
    }

    public function resetPassword($id, Request $request): JsonResponse
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ]);
        }
        $user->update([
            'password' => Hash::make($request->password)
        ]);
        Artisan::call('optis:send:email --password=' . $request->password . ' --toemail=' . $user->email);
        return response()->json(['message' => 'password reset']);
    }


    public function destroy($id): JsonResponse
    {
        $user = User::findorFail($id);
        if (count($user->tasks) !== 0) {
            return response()->json([
                'error' => 'User not deleted because have tasks'
            ], 400);
        } else {
            $user->delete();
            return response()->json([
                'message' => 'User deleted'
            ], 200);
        }
    }


}
