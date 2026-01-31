<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLabelRequest;
use App\Http\Requests\UpdateLabelRequest;
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
        $this->authorize('create', Label::class);
        return view('labels.create');
    }

    public function store(StoreLabelRequest $request)
    {
        $this->authorize('create', Label::class);
        $validated = $request->validated();
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

    public function show(Label $label)
    {
        return redirect()->route('labels.index');
    }

    public function update(UpdateLabelRequest $request, Label $label)
    {
        $this->authorize('update', $label);
        $validated = $request->validated();

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
