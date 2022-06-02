<?php

namespace App\Http\Middleware;

use App\Models\Group;
use App\Models\Task;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class GroupAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $task = Task::find($request['id']);
        $groupId = $task->group_id;
        $user = Auth::id();
        $groupUsers = Group::find($groupId)->users;
        foreach ($groupUsers as $groupUser) {
            if($groupUser['id'] === $user && $groupUser['role'] === 1){
                return $next($request);
            }

        }
        return response()->json([
           'message' => 'Access Denied'
        ],401);
    }
}
