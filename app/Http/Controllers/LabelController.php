<?php

namespace App\Http\Controllers;

use App\Models\Label;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LabelController extends Controller
{
   public function index()
    {
        $labels = Label::paginate(10);
        return view('labels.index', compact('labels'));
    }
    
    public function create()
    {
        return view('labels.create');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:labels|max:255|min:1',
            'description' => 'nullable|string',
        ]);
        
        Label::create($validated);
        
        flash(__('label.created'))->success();
        return redirect()->route('labels.index');
    }
    
    public function edit(Label $label)
    {
        return view('labels.edit', compact('label'));
    }
    
    public function update(Request $request, Label $label)
    {
        $validated = $request->validate([
            'name' => 'required|max:255|min:1|unique:labels,name,' . $label->id,
            'description' => 'nullable|string',
        ]);
        
        $label->update($validated);
        
        flash(__('label.updated'))->success();
        return redirect()->route('labels.index');
    }
    
    public function destroy(Label $label)
    {
        if ($label->tasks()->exists()) {
            flash(__('label.cannot_delete'))->error();
            return redirect()->route('labels.index');
        }
        
        $label->delete();
        
        flash(__('label.deleted'))->success();
        return redirect()->route('labels.index');
    }
}
