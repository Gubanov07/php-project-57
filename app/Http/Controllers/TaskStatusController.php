<?php

namespace App\Http\Controllers;

use App\Models\TaskStatus;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class TaskStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $taskStatuses = TaskStatus::paginate(10);
        return view('taskStatuses.index', compact('taskStatuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('taskStatuses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:task_statuses|max:255|min:1',
        ]);

        $taskStatus = TaskStatus::create($validated);

        flash(__('controllers.task_statuses_create'))->success();
        return redirect()->route('task_statuses.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(TaskStatus $taskStatus)
    {
        return view('taskStatuses.show', compact('taskStatus'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TaskStatus $taskStatus)
    {
        return view('taskStatuses.edit', compact('taskStatus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TaskStatus $taskStatus)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'max:255',
                'min:1',
                Rule::unique('taskStatuses')->ignore($taskStatus->id),
            ],
        ]);

        $taskStatus->update($validated);

        flash(__('controllers.task_statuses_update'))->success();
        return redirect()->route('taskStatuses.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TaskStatus $taskStatus)
    {
        if ($taskStatus->tasks()->exists()) {
            flash(__('controllers.task_statuses_destroy_failed'))->error();
            return redirect()->route('taskStatuses.index');
        }

        $taskStatus->delete();

        flash(__('controllers.task_statuses_destroy'))->success();
        return redirect()->route('taskStatuses.index');
    }
}
