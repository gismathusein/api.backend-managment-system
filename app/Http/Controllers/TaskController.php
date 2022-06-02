<?php

namespace App\Http\Controllers;


use App\Http\Requests\Task\UpdateTaskRequest;
use App\Models\Group;
use App\Models\Note;
use App\Models\Task;
use App\Models\UserGroup;
use App\Models\UserTask;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use TaskStatus;
use TaskTag;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        if ($request['limit']) {

            $tasks = Task::select('*')
                ->with('users')
                ->with('attachments')
                ->orderByDesc('id')
                ->paginate($request['limit']);
            $collection = $tasks->getCollection();
            return collectionResponse('data', $collection, $tasks);
        } else {
            $tasks = Task::select('*')
                ->with('users')
                ->with('attachments')
                ->orderByDesc('id')
                ->get();
            return response()->json(['data' => $tasks], 200);
        }


    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string'],
            'description' => ['string'],
            'status' => ['integer'],
            'group_id' => ['required', 'exists:App\Models\Group,id'],
            'user_id' => ['integer', 'exists:App\Models\User,id'],
            'checklists' => ['string'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 400);
        }
        if ($validator->validated()) {
            $validated = $validator->validated();
        }
        $validated['created_by'] = Auth::id();
        if ($request['user_id']) {
            $validated['status'] = TaskStatus::TODO;
        } else {
            $validated['status'] = TaskStatus::UNKNOWN;
        }

        $newTask = Task::create($validated);
        if ($request['user_id']) {

            UserTask::create([
                'user_id' => $validated['user_id'],
                'task_id' => $newTask['id'],
            ]);
            $newTask->users;
        }
        if ($request->attachments) {
            uploadAttachment($request->attachments, $newTask, 'attachments', Auth::id());
        }

        return response()->json($newTask, 201);
    }

    public function show($id): JsonResponse
    {
        $taskFind = Task::find($id);
        if (!$taskFind) return response()->json(['message' => 'Task not found']);

        $task = Task::select('*')
            ->with('users')
            ->with('attachments')
            ->orderByDesc('id')
            ->where('id', $id)->first();
        return response()->json($task, 200);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $task = Task::find($id);
        if (!$task) return response()->json(['message' => 'Task not found']);
        $validator = Validator::make($request->all(), [
            'title' => ['string'],
            'description' => ['string'],
            'status' => ['integer'],
            'group_id' => ['integer', 'exists:App\Models\Group,id'],
            'ids.*' => ['integer'],
            'checklists' => ['string']
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 400);
        }else{
            if ($request->has('status')) {
                if ($task->status == 0 && $request->status == 1) {
                    UserTask::create([
                        'user_id' => Auth::id(),
                        'task_id' => $id
                    ]);
                    $task->users;
                }elseif ($task->status == 2 && $request->status == 4 && $task->tag == 1){
                    $task->update(['tag' => TaskTag::UNKNOWN]);
                }

            }
            $task->update($request->all());
            if ($request->attachments) {
                uploadAttachment($request->attachments, $task, 'attachments', Auth::id());
            }
            if (!empty($updateTaskRequest->ids)) {
                deleteAttachment($updateTaskRequest->ids, Auth::id());
            }
            return response()->json([
                'task' => $task,
            ], 200);

        }
    }

    public function getUserTasks($groupId): JsonResponse
    {
        $user = Auth::user();

        $todoTasks = $user->tasks()
            ->wherePivot('is_rejected', 0)
            ->wherePivot('is_forwarded', 0)
            ->where('group_id', $groupId)
            ->where('status', TaskStatus::TODO)
//            ->where('tag', '!=', 1)
            ->get();

        foreach ($todoTasks as $todoTask) {
            $todoTask['users'] = DB::table('user_tasks')
                ->select('user_id')
                ->where('task_id', $todoTask['id'])
                ->get();
            $todoTask['notes'] = DB::table('notes')
                ->where('task_id', $todoTask['id'])
                ->get();
        }

        $inProgressTasks = $user->tasks()
            ->where('group_id', $groupId)
            ->where('status', TaskStatus::INPROGRESS)
//            ->where('tag', '!=', 1)
            ->get();

        foreach ($inProgressTasks as $key => $inProgressTask) {
            $check = UserTask::where('task_id', $inProgressTask['id'])->where('user_id', Auth::id())->where('is_rejected', 0)->where('is_forwarded', 1)->count();
            if ($check === 1) {
                unset($inProgressTasks[$key]);
            }
            $inProgressTask['users'] = DB::table('user_tasks')
                ->select('user_id')
                ->where('task_id', $inProgressTask['id'])
                ->get();
            $inProgressTask['notes'] = DB::table('notes')
                ->where('task_id', $inProgressTask['id'])
                ->get();

        }

        $approveTasks = $user->tasks()
//            ->wherePivot('is_rejected', 0)
            ->where('group_id', $groupId)
            ->where('status', TaskStatus::APPROVE)
//            ->where('tag', '!=', 1)
            ->get();

        foreach ($approveTasks as $approveTask) {
            $approveTask['users'] = DB::table('user_tasks')
                ->select('user_id')
                ->where('task_id', $approveTask['id'])
                ->get();
            $approveTask['notes'] = DB::table('notes')
                ->where('task_id', $approveTask['id'])
                ->get();
        }

        $groupTasks = Group::findOrFail($groupId)->groupTasks;
        foreach ($groupTasks as $groupTask) {
            $groupTask['users'] = DB::table('user_tasks')
                ->select('user_id')
                ->where('task_id', $groupTask['id'])
                ->get();
            $groupTask['notes'] = DB::table('notes')
                ->where('task_id', $groupTask['id'])
                ->get();
        }


        $forwardedTasks = $user->tasks()
            ->wherePivot('is_forwarded', 1)
            ->wherePivot('is_rejected', 0)
            ->where('group_id', $groupId)
            ->where('status', '!=', TaskStatus::APPROVE)
            ->where('tasks.status', '!=', \TaskStatus::DONE)
//            ->where('tag', '!=', 1)
            ->get();

        foreach ($forwardedTasks as $forwardedTask) {
            $forwardedTask['users'] = DB::table('user_tasks')
                ->select('user_id')
                ->where('task_id', $forwardedTask['id'])
                ->get();
            $forwardedTask['notes'] = DB::table('notes')
                ->where('task_id', $forwardedTask['id'])
                ->get();
        }


        return response()->json([
            'todo' => $todoTasks,
            'in_progress' => $inProgressTasks,
            'approve' => $approveTasks,
            'forwarded' => $forwardedTasks,
            'group' => $groupTasks,
        ]);


    }

    public function getCreatedTasks(): JsonResponse
    {
        $createdTasks = Task::where('created_by', Auth::id())->get();
        foreach ($createdTasks as $createdTask) {
            $count = DB::table('user_tasks')->where('task_id', $createdTask->id)->count();
            $currentAssignee = DB::table('user_tasks')->where('task_id', $createdTask->id)->latest()->first();
            $firstAssignee = DB::table('user_tasks')->where('task_id', $createdTask->id)->first();
            if (($count === 1 || $count === 0) && $createdTask->status < TaskStatus::TODO) {
                $createdTask['is_deletable'] = true;
            } else {
                $createdTask['is_deletable'] = false;
            }
            $createdTask['users'] = DB::table('user_tasks')
                ->select('user_id')
                ->where('task_id', $createdTask['id'])
                ->get();
        }


        return response()->json([
            'data' => $createdTasks,
        ]);


    }
    public function getGroupTasks($groupID): JsonResponse
    {
        $groupTasks = Task::where('group_id', $groupID)->get();
        foreach ($groupTasks as $groupTask) {
            $count = DB::table('user_tasks')->where('task_id', $groupTask->id)->count();
            if (($count === 1 || $count === 0) && $groupTask->status < TaskStatus::TODO) {
                $groupTask['is_deletable'] = true;
            } else {
                $groupTask['is_deletable'] = false;
            }
            $groupTask['users'] = DB::table('user_tasks')
                ->select('user_id')
                ->where('task_id', $groupTask['id'])
                ->get();
        }

        return response()->json([
            'data' => $groupTasks,
        ]);


    }

    public function getIsGroupAdmin($groupID): JsonResponse
    {
        $group = Group::find($groupID);
        $groupUsers = $group->users();
        $adminRoleAvailable = $groupUsers
            ->where('user_id', Auth::id())
            ->where('user_group.role', 1)
            ->count();

        return response()->json(['isAdmin' => $adminRoleAvailable > 0]);
    }

    public function assigned($assignedId, $id)
    {
        $task = Task::find($id);
        $group = Group::find($task->group_id)->users->toArray();
        $check = false;
        foreach ($group as $item) {

            if ((int)$assignedId === $item['id']) {
                $check = true;
            }
        }
        if ($check === true) {
            $count = UserTask::where([
                ['user_id', $assignedId],
                ['task_id', $id]
            ])->count();

            $countTask = UserTask::where([
                ['task_id', $id]
            ])->count();

            if ($count === 0 && $countTask === 0) {
                UserTask::create([
                    'user_id' => $assignedId,
                    'task_id' => $id,
                ], 201);
                $task->update(['status' => 1]);
                return response()->json([
                    'message' => 'user assigned to task'
                ], 200);
            } else {
                return response()->json([
                    'message' => 'bu user bu taska assigned olunmusdu'
                ], 200);
            }
        }

        if ($check === false) {
            return response()->json([
                'mesasge' => 'Bu user bu grupda yoxdu'
            ], 404);
        }


    }

    public function reassign(Request $request, $id)
    {
        $creatorId = Auth::id();
        $task = Task::find($id);
        if ($creatorId != $task->created_by) return response(['message' => 'Only task creator can do this action'], 401);

        $validator = Validator::make($request->all(), [
            'task_id' => ['integer', 'exists:App\Models\Task,id'],
            'group_id' => ['integer', 'exists:App\Models\Group,id'],
            'user_id' => ['integer', 'exists:App\Models\User,id'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 400);
        }else{
            $task->update(['tag' => 0 ,'group_id' => $request->group_id]);
            if ($request->has('user_id')){
                UserTask::create([
                    'user_id' => $request->user_id,
                    'task_id' => $id,
                ], 201);
                $task->update(['status' => 1 ]);
            }
            return response()->json([
                'message' => 'User assigned to task.'
            ], 200);
        }
    }

    public function forwarded(Request $request)
    {
        $group_id = Task::find($request->task_id)->group_id;
        $exists = Group::find($group_id)->users->contains($request->forwarded_id);
//        $auth = Task::find($request->task_id)->users->contains(Auth::id());
        $forwarder = UserTask::where([['task_id', $request->task_id], ['user_id', Auth::id()]])->first();
        $acceptee = UserTask::where([['task_id', $request->task_id], ['user_id', $request->forwarded_id]])->first();
        if (!$exists) {
            return response()->json([
                'mesasge' => 'Forwarded user must be in same group with you.'
            ], 404);
        }

        if (Task::find($request->task_id)->status !== TaskStatus::INPROGRESS) {
            return response()->json([
                "mesasge" => "Forwarded task's status must be in progress."
            ], 404);
        }
        if (!$acceptee) {
            if ($forwarder->is_forwarded === 0) {
                $forwarder->is_forwarded = 1;
                $forwarder->save();
            }else{
                $forwarder->is_rejected = 0;
                $forwarder->save();
            }
            $acceptee = UserTask::create([
                'user_id' => $request->forwarded_id,
                'task_id' => $request->task_id,
            ]);
            if (isset($request->note)) {
                Note::query()->create([
                    'user_id' => $request->forwarded_id,
                    'task_id' => $request->task_id,
                    'note' => $request->note,
                    'type' => \NoteType::FORWARD
                ]);
            }
            Task::find($request->task_id)->update(['status' => TaskStatus::TODO]);
            return response()->json($acceptee, 201);
        }
    }

    public function reject(Request $request): JsonResponse
    {
        $task = Task::find($request->task_id);
        $groupId = $task->group_id;
        if ($task->status === TaskStatus::UNKNOWN) {
            $authUser = UserGroup::query()->where('user_id', Auth::id())->where('group_id', $groupId)->first();
            if ($authUser->role === 1) {
                if (isset($request->note)) {
                    Note::query()->create([
                        'user_id' => $authUser->user_id,
                        'task_id' => $request->task_id,
                        'note' => $request->note,
                        'type' => \NoteType::REJECT
                    ]);
                } else {
                    return response()->json(['message' => 'Note is required'], 400);
                }
                $task->tag = TaskTag::REJECTED;
                $task->save();
                return response()->json(['task' => 'Task rejected successfully'], 200);
            } else {
                return response()->json(['message' => 'Only group admins can reject group tasks'], 401);
            }
        } else {
            $count = UserTask::where('task_id', $request->task_id)->count();
            $accepting = Auth::id();
            if ($count > 1) {
                $acceptingTask = UserTask::where([['task_id', $request->task_id], ['user_id', $accepting], ['is_forwarded', 0]])->first();
                $forwarderTask = UserTask::where([['task_id', $request->task_id], ['is_forwarded', 1]])->latest()->limit(1)->first();
                if (isset($request->note)) {
                    Note::query()->create([
                        'user_id' => $accepting,
                        'task_id' => $request->task_id,
                        'note' => $request->note,
                        'type' => \NoteType::REJECT
                    ]);
                } else {
                    return response()->json(['message' => 'Note is required'], 400);
                }
                $forwarderTask->is_rejected = 1;
                $forwarderTask->save();
                $acceptingTask->delete();
                $task->update(['status' => TaskStatus::INPROGRESS]);
            } else {
                $acceptingTask = UserTask::where([['task_id', $request->task_id], ['user_id', $accepting]])->first();
                if (isset($request->note)) {
                    Note::query()->create([
                        'user_id' => $accepting,
                        'task_id' => $request->task_id,
                        'note' => $request->note,
                        'type' => \NoteType::REJECT
                    ]);
                } else {
                    return response()->json(['message' => 'Note is required'], 400);
                }
                $acceptingTask->delete();
                $task->update(['status' => TaskStatus::UNKNOWN]);
            }
        }
        return response()->json(['message' => 'Task rejected successfully']);
    }

    public function decision(Request $request)
    {
        $userId = Auth::id();
        $task = Task::find($request->task_id);
        if ($userId != $task->created_by) {
            return response(['message' => 'Only task creator can do this action'], 401);
        } else {
            if ($request->tag == 2) {
                if (isset($request->note)) {
                    Note::query()->create([
                        'user_id' => $userId,
                        'task_id' => $request->task_id,
                        'note' => $request->note,
                        'type' => \NoteType::REJECT
                    ]);
                } else {
                    return response()->json(['message' => 'Note is required'], 400);
                }
                $task->tag = 2;
                $task->status = TaskStatus::DONE;
                $task->save();
            } else if ($request->tag == 1) {
                $task->tag = 1;
                $task->status = TaskStatus::INPROGRESS;
                $task->save();
            }
        }
        return response(['message' => 'Success'], 200);
    }

    public function destroy($id): JsonResponse
    {
        $task = Task::findOrFail($id);
        $deleteRelation = UserTask::where('task_id', $id)->delete();
        $delete = $task->delete();
        if ($delete || $deleteRelation) {
            return response()->json([
                'message' => 'Task is delete'
            ], 200);
        } else {
            return response()->json([
                'message' => 'Error while prosessing to database...'
            ], 400);
        }
    }
}
