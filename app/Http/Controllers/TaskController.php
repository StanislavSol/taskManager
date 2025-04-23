<?php

namespace App\Http\Controllers;

use App\Models\Label;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tasks = Task::paginate();
        $data = $request->validate([
            'filter' => "nullable|array"
        ]);
        $status_id = $data['filter']['status_id'] ?? null;
        $created_by_id = $data['filter']['created_by_id'] ?? null;
        $assigned_to_id = $data['filter']['assigned_to_id'] ?? null;

        $filterTasks = QueryBuilder::for(Task::where(function ($query) use ($status_id, $created_by_id) {
            $query->where('status_id', $status_id)
                  ->orWhere('created_by_id', $created_by_id);
        })->orWhere('assigned_to_id', $assigned_to_id))->paginate();

        var_dump($filterTasks->all());
        $tasks = $filterTasks;
        
        $taskStatuses = new TaskStatus();
        $users = new User();
        return view('tasks.index', compact('tasks', 'taskStatuses', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $task = new Task();
        $taskStatuses = new TaskStatus();
        $users = new User();
        $labels = new Label();

        return view('tasks.create', compact('task', 'taskStatuses', 'users', 'labels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => "required|unique:tasks",
            'description' => "max:1000",
            'status_id' => "required|string",
            'assigned_by_id' => "nullable|string",
            'labels' => "nullable|array"
        ]);
        $task = new Task();
        $task->fill($data);
        $task->creator_by_id = Auth::user()->id;
        $task->save();

        if (array_key_exists('labels', $data)) {
            $task->labels()->attach($data['labels']);
        }

        flash('Задача успешно создана')->success();

        return redirect()->route('tasks.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        $taskStatus = TaskStatus::findOrFail($task->status_id)->name;

        return view('tasks.show', compact('task', 'taskStatus'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        $taskStatuses = new TaskStatus();
        $users = new User();
        $labels = new Label();

        return view('tasks.edit', compact('task', 'taskStatuses', 'users', 'labels'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $data = $request->validate([
            'name' => "required|unique:tasks,name,{$task->id}",
            'description' => "max:1000",
            'status_id' => "required|string",
            'assigned_by_id' => "nullable|string",
            'labels' => "nullable|array"

        ]);
        $task->fill($data);
        $task->save();
        if (array_key_exists('labels', $data)) {
            $task->labels()->sync($data['labels']);
        } else {
            $task->labels()->sync([]);
        }
        flash(__('Task successfully changed'))->success();
        return redirect()->route('tasks.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $task = Task::find($id);
        if (Auth::user()->id === $task->creator_by_id) {
            $task->delete();
            flash('Задача успешно удалена')->success();
        } else {
            flash('Не удалось удалить задачу')->error();
        }

        return redirect()->route('tasks.index');
    }
}
