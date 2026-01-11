<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::with(['status', 'creator', 'assignee'])->paginate(10);
        return view('tasks.index', compact('tasks'));
    }
    
    public function create()
    {
        $statuses = TaskStatus::pluck('name', 'id');
        $users = User::pluck('name', 'id');
        
        return view('tasks.create', compact('statuses', 'users'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255|min:1',
            'description' => 'nullable|string',
            'status_id' => 'required|exists:task_statuses,id',
            'assigned_to_id' => 'nullable|exists:users,id',
        ]);
        
        $task = Task::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'status_id' => $validated['status_id'],
            'created_by_id' => Auth::id(),
            'assigned_to_id' => $validated['assigned_to_id'] ?? null,
        ]);
        
        flash(__('task.created'))->success();
        return redirect()->route('tasks.index');
    }
    
    public function show(Task $task)
    {
        $task->load(['status', 'creator', 'assignee']);
        return view('tasks.show', compact('task'));
    }
    
    public function edit(Task $task)
    {
        $statuses = TaskStatus::pluck('name', 'id');
        $users = User::pluck('name', 'id');
        
        return view('tasks.edit', compact('task', 'statuses', 'users'));
    }
    
    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'name' => 'required|max:255|min:1',
            'description' => 'nullable|string',
            'status_id' => 'required|exists:task_statuses,id',
            'assigned_to_id' => 'nullable|exists:users,id',
        ]);
        
        $task->update($validated);
        
        flash(__('task.updated'))->success();
        return redirect()->route('tasks.index');
    }
    
    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        $task->delete();
        
        flash(__('task.deleted'))->success();
        return redirect()->route('tasks.index');
    }
}
