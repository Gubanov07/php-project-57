<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskStatusRequest;
use App\Http\Requests\UpdateTaskStatusRequest;
use App\Models\TaskStatus;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class TaskStatusController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $taskStatuses = TaskStatus::paginate(10);
        return view('taskStatuses.index', compact('taskStatuses'));
    }

    public function create()
    {
        $this->authorize('create', TaskStatus::class);
        return view('taskStatuses.create');
    }

    public function store(StoreTaskStatusRequest $request)
    {
        if (Auth::guest()) {
            return redirect()->route('task_statuses.index');
        }

        $validated = $request->validated();
        $taskStatus = new TaskStatus();

        $taskStatus->fill($validated);
        $taskStatus->save();
        flash(__('controllers.task_statuses_create'))->success();
        return redirect()->route('task_statuses.index');
    }

    public function show(TaskStatus $taskStatus)
    {
        return redirect()->route('task_statuses.index');
    }

    public function edit(TaskStatus $taskStatus)
    {
        return view('taskStatuses.edit', compact('taskStatus'));
    }

    public function update(UpdateTaskStatusRequest $request, TaskStatus $taskStatus)
    {
        if (Auth::guest()) {
            return redirect()->route('task_statuses.index');
        }

        $validated = $request->validated();

        $taskStatus->update($validated);

        flash(__('controllers.task_statuses_update'))->success();
        return redirect()->route('task_statuses.index');
    }

    public function destroy(TaskStatus $taskStatus)
    {
        if ($taskStatus->tasks()->exists()) {
            flash(__('controllers.task_statuses_destroy_failed'))->error();
            return redirect()->route('task_statuses.index');
        }

        $taskStatus->delete();

        flash(__('controllers.task_statuses_destroy'))->success();
        return redirect()->route('task_statuses.index');
    }
}
