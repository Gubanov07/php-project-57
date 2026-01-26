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
        if (Auth::guest()) {
             return redirect()->route('labels.index');
        }
        $validated = $request->validate();
        $label = new Label();

        $label->fill($validated);
        $label->save();

        flash(__('controllers.label_create'))->success();
        return redirect()->route('labels.index');
    }

    public function edit(Label $label)
    {
        return view('labels.edit', compact('label'));
    }

    public function update(Request $request, Label $label)
    {
        if (Auth::guest()) {
            return redirect()->route('labels.index');
        }

        $validated = $request->validate();

        $label->fill($validated);
        $label->save();

        flash(__('controllers.label_update'))->success();
        return redirect()->route('labels.index');
    }

    public function destroy(Label $label)
    {
        if ($label->tasks()->exists()) {
            flash(__('controllers.label_statuses_destroy_failed'))->error();
            return back();
        }

        $label->delete();

        flash(__('controllers.label_destroy'))->success();
        return redirect()->route('labels.index');
    }
}
