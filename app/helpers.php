<?php

use App\Models\Fileable;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class Roles
{
    const ADMIN = 1;
    const USER = 0;
}

class Status
{
    const ACTIVE = 1;
    const DEACTIVE = 0;
}

class TaskStatus
{
    const UNKNOWN = 0;
    const TODO = 1;
    const INPROGRESS = 2;
    const FORWARDED = 3;
    const APPROVE = 4;
    const DONE = 5;
}

class TaskTag
{
    const UNKNOWN = 0;
    const REJECTED = 1;
    const ACCEPTED = 2;
}

class SubTaskStatus
{
    const DONE = 1;
    const NOTDONE = 0;
}

class NoteType {
    const REJECT = 1;
    const FORWARD = 0;
}

function uploadImage($image, $relationModel, $method, $type, $userId)
{
    $originalName = $image->getClientOriginalName();
    $originalExt = $image->getClientOriginalExtension();
    $mimeType = $image->getMimeType();
    $changeLogoName = uniqid('img_');
    $destinationPath = 'api/v1/uploads';
    $path = 'uploads/' . $changeLogoName . '.' . $originalExt;
    $image->move($destinationPath, $changeLogoName . '.' . $originalExt);

    $file = new Fileable([
        'path' => $path,
        'original_name' => $originalName,
        'mime_type' => $mimeType,
        'user_id' => $userId
    ]);
    if ($relationModel->$method !== null) {
        $relationModel->$method()->delete();
    }

    $relationModel->$method()->save($file);
    return $relationModel->$method;
}

function findOrNot($model, $id)
{
    $data = $model::find($id);
    if (!$data) {
        return response()->json([
            'message' => explode("\\", $model)[2] . ' not found'
        ], 404);
    } else {
        return $data;
    }
}

function collectionResponse($keyword, $collection, $data): JsonResponse
{
    return response()->json([
        $keyword => $collection,
        'current_page' => $data->currentPage(),
        'total' => $data->total(),
        'last_page' => $data->lastPage(),
        'per_page' => $data->perPage(),
    ]);
}

function uploadAttachment($attachments, $relationModel, $method, $userId)
{
    $entities = [];
    foreach ($attachments as $attachment) {
        $originalName = $attachment->getClientOriginalName();
        $originalExt = $attachment->getClientOriginalExtension();
        $mimeType = $attachment->getMimeType();
        $changeFileName = time() . rand(100, 999);
        $destinationPath = 'api/v1/uploads/tasks';
        $path = 'uploads/tasks/' . $changeFileName . '.' . $originalExt;
        $attachment->move($destinationPath, $changeFileName . '.' . $originalExt);

        $file = new Fileable([
            'path' => $path,
            'original_name' => $originalName,
            'mime_type' => $mimeType,
            'user_id' => $userId
        ]);

        $entities[] = $file;
    }
    $relationModel->$method()->saveMany($entities);
    return $relationModel->$method;
}

function stringToInt($variable): int
{
    return (int)$variable;

}

function deleteAttachment($attachments, $userId)
{
    $path = 'api/v1/';
    $attachmentsArr = explode(',', $attachments);
    foreach ($attachmentsArr as $item) {
        $data = Fileable::where('file_id', $item)->first();
        if ($data->user_id === $userId) {
            if (!$data) {
                return response()->json([
                    'message' => 'File Not found'
                ], 404);
            }
            if (File::exists($path . $data->path)) {
                File::delete($path . $data->path);
            }
            $data->where('file_id', $item)->where('user_id', $userId)->delete();
        }
    }
}

function reportCount($report, $name, $userId, $userGroupId, $status, int $multiple): int
{
    return $report->$name = DB::table('user_tasks')
        ->from('user_tasks', 'ut')
        ->leftJoin('tasks as t', 'ut.task_id', 't.id')
        ->where('ut.user_id', $userId)
        ->where('t.group_id', $userGroupId)
        ->where(function ($q) use ($status, $multiple) {
            if ($multiple === 1) $q->whereIn('t.status', [1, 2, 4, 5]);
            else $q->where('t.status', $status);
        })->count();
}





