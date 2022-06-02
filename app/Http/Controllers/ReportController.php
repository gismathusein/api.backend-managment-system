<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{

    public function report(Request $request): JsonResponse
    {
        $userIDS = [];
        $companyId = '';
        $startDate = '';
        $endDate = '';
        $check = false;

        if ($request->has('user_id')) {
            $userIDS = $request->user_id;
        }
        if ($request->has('company_id')) {
            $companyId = $request->company_id;
        }
        if ($request->has('start_date')) {
            $startDate = $request->start_date;
        } else {
            $startDate = Carbon::parse(now()->startOfMonth());
        }
        if ($request->has('end_date')) {
            $endDate = $request->end_date;
        } else {
            $endDate = Carbon::parse(now());
        }


        $reportQuery = DB::table('user_tasks')
            ->from('user_tasks', 'ut');

        $reportQuery
            ->leftJoin('users as u', 'u.id', 'ut.user_id')
            ->leftJoin('user_group as ug', 'ug.user_id', 'u.id')
            ->leftJoin('groups as g', 'g.id', 'ug.group_id')
            ->leftJoin('companies as c', 'c.id', 'u.company_id')
            ->leftJoin('tasks as t', 't.id', 'ut.task_id')
            ->where(function ($qCompany) use ($companyId) {
                if ($companyId !== '') $qCompany->where('c.id', $companyId);
            })
            ->where(function ($qUser) use ($userIDS) {
                if (!empty($userIDS)) $qUser->whereIn('u.id', $userIDS);
            })
            ->whereBetween('t.created_at', [$startDate, $endDate])
            ->select([
                DB::raw("CONCAT(u.name, ' ', u.surname) as fullName"),
                'g.name as groupName',
                'ug.group_id as userGroupId',
                'u.id as userId'
            ]);


        $initialData = $reportQuery->get();
//       dd($initialData);

        foreach ($initialData as $report) {
            reportCount($report, 'todo', $report->userId, $report->userGroupId, 1, 0);
            reportCount($report, 'inProgress', $report->userId, $report->userGroupId, 2, 0);
            reportCount($report, 'pending', $report->userId, $report->userGroupId, 4, 0);
            reportCount($report, 'approved', $report->userId, $report->userGroupId, 5, 0);
            reportCount($report, 'total', $report->userId, $report->userGroupId, null, 1);

            unset($report->userGroupId);
            unset($report->userId);
        }

        return response()->json(['data' => $initialData], 200);


    }
}
