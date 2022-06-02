<?php

namespace App\Http\Controllers;

use App\Http\Requests\Company\CompanyRequest;
use App\Http\Requests\Company\UpdateCompanyRequest;
use App\Models\Company;
use App\Models\Fileable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CompanyController extends Controller
{

    public function companies(Request $request): JsonResponse
    {
        $companies = Company::query();
        if (!isset($request['limit'])) {
            $request['limit'] = 10;
        }

        if ($request['name']) {
            $companies->where('name', 'like', '%' . $request['name'] . '%');
        }

        if ($request['email']) {
            $companies->where('email', 'like', '%' . $request['email'] . '%');
        }

        if ($request['phone']) {
            $companies->where('phone', 'like', '%' . $request['phone'] . '%');
        }
        if ($request['address']) {
            $companies->where('address', 'like', '%' . $request['address'] . '%');
        }
        if ($request['status']) {
            $companies->where('status', $request['status']);
        }


        $companies->orderByDesc('id')->with('logo');
        $data = $companies->paginate($request['limit']);
        foreach ($data as $datum) {
            if (count($datum->departments) !== 0 || count($datum->groups) !== 0) {
                $datum['is_deletable'] = false;
            } else {
                $datum['is_deletable'] = true;
            }
            unset($datum->departments);
            unset($datum->groups);
        }

        return response()->json($data, 200);

    }

    public function companyOptions(Request $request): JsonResponse
    {
        $companies = Company::where('status' , \Status::ACTIVE)->get();
        return response()->json(['data' => $companies], 200);
    }

    public function show($id): JsonResponse
    {

        $company = Company::find($id);

        if (!$company) {
            return response()->json([
                'message' => 'Company not found'
            ], 404);
        } else {
            $company->logo;
            return response()->json($company, 200);
        }

    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'string'],
            'phone' => ['required', 'max:9', 'min:9', 'string'],
            'address' => ['required', 'string'],
            'logo' => ['image', 'mimes:jpg,jpeg,png'],
            'status' => ['integer', 'min:0', 'max:1']
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }else{
            $validated = $request->all();
            $logo = $request['logo'];
            unset($validated['logo']);
            $validated['status'] = 1;
            $newCompany = new Company($validated);
            $newCompany->save();
            if ($request->hasFile('logo')) {
//            uploadImage($logo, $newCompany, 'logo', 'create', Auth::id());
                $originalName = $logo->getClientOriginalName();
                $originalExt = $logo->getClientOriginalExtension();
                $mimeType = $logo->getMimeType();
                $changeLogoName = uniqid('img_');
                $destinationPath = 'api/v1/uploads';
                $path = 'uploads/' . $changeLogoName . '.' . $originalExt;
                $logo->move($destinationPath, $changeLogoName . '.' . $originalExt);

                $file = new Fileable([
                    'path' => $path,
                    'original_name' => $originalName,
                    'mime_type' => $mimeType,
                    'user_id' => Auth::id()
                ]);
                if ($newCompany->logo !== null) {
                    $newCompany->logo()->delete();
                }
                $newCompany->logo()->save($file);
            }
            $responseData = Company::find($newCompany->id);
            $responseData->logo;
            return response()->json($responseData, 201);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => ['string'],
            'email' => ['email', 'string'],
            'phone' => ['max:9', 'min:9', 'string'],
            'address' => ['string'],
            'logo' => ['image', 'mimes:jpg,jpeg,png'],
            'status' => ['integer', 'min:0', 'max:1']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }else{
            $validated = $request->all();
            $company = Company::find($id);
            if (!$company) {
                return response()->json([
                    'message' => 'Company not found'
                ], 404);
            }

            if ($request->has('status')) {
                $request['status'] = (int)$validated['status'];
            }
            if ($request['logo']) {
                $logo = $request['logo'];
                unset($validated['logo']);
            }

            $company->fill($request->all());
            $saved = $company->save();

            //Logo api/v1/upload
            if ($request->hasFile('logo')) {
                uploadImage($logo, $company, 'logo', 'update', 1);
            }

            if ($saved) {
                return response()->json($company, 200);
            }

            return response()->json([
                'status' => 'err',
                'msg' => "Error while inserting data to database"
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        $company = Company::find($id);
        if (!$company) {
            return response()->json([
                'message' => 'Company not found'
            ], 404);
        } else {
            if (count($company->departments) !== 0 && count($company->groups) !== 0) {
                return response()->json([
                    'message' => 'This company is not deleted'
                ], 400);
            } else {
                $deleted = $company->delete();
                if ($deleted) {
                    return response()->json([
                        'message' => 'This company is deleted'
                    ], 200);
                }

            }
        }
    }

    public function users(Request $request, $id): JsonResponse
    {
        if (!isset($request['limit'])) {
            $request['limit'] = 10;
        }
        $company = Company::find($id)->users;

        if (!$company) {
            return response()->json([
                'message' => 'Company not found'
            ], 404);
        }
        return response()->json([
            'data' => $company
        ], 200);
    }

    public function departments(Request $request, $id): JsonResponse
    {
        $company = Company::find($id)->departments;
        if (!$company) {
            return response()->json([
                'message' => 'Company not found'
            ]);
        }
        return response()->json($company);
    }

    public function groups(Request $request, $id): JsonResponse
    {
        $groups = Company::find($id)->groups;
        if (!$groups) {
            return response()->json([
                'message' => 'Company not found'
            ]);
        }
        return response()->json($groups, 200);
    }

}
