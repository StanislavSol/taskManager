<?php

namespace App\Http\Controllers;

use App\Models\TaskStatus;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskStatusController extends Controller
{
    public function index()
    {
        $taskStatuses = TaskStatus::paginate();

        return view('task_statuses.index',  compact('taskStatuses'));
    }

    public function create()
    {
        if (Auth::guest()) {
            return abort(403);
        }
        return view('task_statuses.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|unique:task_statuses',
        ]);

        $taskStatus = new TaskStatus();
        $taskStatus->fill($data);
        $taskStatus->save();
        flash(__('controllers.task_statuses_create'))->success();

        return redirect()->route('task_statuses.index');
    }

    public function edit(TaskStatus $taskStatus)
    {
        return view('task_statuses.edit', compact('taskStatus'));
    }

    public function update(Request $request, TaskStatus $taskStatus)
    {
        $data = $request->validate([
            'name' => "required|unique:task_statuses,name,{$taskStatus->id}",
        ]);
        $taskStatus->fill($data);
        $taskStatus->save();
        flash(__('controllers.task_statuses_update'))->success();
        return redirect()->route('task_statuses.index');
    }

    public function destroy(TaskStatus $taskStatus)
    {
        if ($taskStatus->tasks()->exists()) {
            flash(__('controllers.task_statuses_destroy_failed'))->error();
            return back();
        }
        $taskStatus->delete();

        flash(__('controllers.task_statuses_destroy'))->success();
        return redirect()->route('task_statuses.index');
    }
}
