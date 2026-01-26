@extends('layouts.app')
@section('content')

@auth()
    <div class="grid col-span-full">
        <h1 class="max-w-2xl mb-4 text-4xl leading-none tracking-tight md:text-5xl xl:text-6xl dark:text-white">{{ __('layout.tasks_create') }}</h1>

        <form method="POST" action="{{ route('tasks.store') }}" class="w-50">
            @csrf
            <div class="flex flex-col">
                <div>
                    <label for="name">{{ __('layout.table_name') }}</label>
                </div>
                <div class="mt-2">
                    <input type="text" name="name" id="name" class="form-control rounded border-gray-300 w-1/3" value="{{ old('name') }}">
                </div>
                <div>
                    @if ($errors->has('name'))
                        <span class="text-red-500">{{ $errors->first('name') }}</span>
                    @endif
                </div>
                <div class="mt-4">
                    <label for="description">{{ __('layout.table_description') }}</label>
                </div>
                <div class="mt-2">
                    <textarea name="description" id="description" class="rounded border-gray-300 w-1/3 h-32">{{ old('description') }}</textarea>
                </div>
                <div>
                    @if ($errors->has('description'))
                        <span class="text-red-500">{{ $errors->first('description') }}</span>
                    @endif
                </div>
                <div class="mt-4">
                    <label for="status_id">{{ __('layout.table_status') }}</label>
                </div>
                <div class="mt-2">
                    <select name="status_id" id="status_id" class="form-control rounded border-gray-300 w-1/3">
                        <option value="" selected disabled>----------</option>
                        @foreach($statuses as $id => $name)
                            <option value="{{ $id }}" {{ old('status_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    @if ($errors->has('status_id'))
                        <span class="text-red-500">{{ $errors->first('status_id') }}</span>
                    @endif
                </div>
                <div class="mt-4">
                    <label for="assigned_to_id">{{ __('layout.table_assigned') }}</label>
                </div>
                <div class="mt-2">
                    <select name="assigned_to_id" id="assigned_to_id" class="form-control rounded border-gray-300 w-1/3">
                        <option value="" selected>----------</option>
                        @foreach($users as $id => $name)
                            <option value="{{ $id }}" {{ old('assigned_to_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    @if ($errors->has('assigned_to_id'))
                        <span class="text-red-500">{{ $errors->first('assigned_to_id') }}</span>
                    @endif
                </div>
                <div class="mt-4">
                    <label for="labels">{{ __('layout.labels') }}</label>
                </div>
                <div class="mt-2">
                    <select name="labels[]" id="labels" class="form-control rounded border-gray-300 w-1/3 h-32" multiple>
                        @foreach($labels as $id => $name)
                            <option value="{{ $id }}" {{ in_array($id, old('labels', [])) ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    @if ($errors->has('labels'))
                        <span class="text-red-500">{{ $errors->first('labels') }}</span>
                    @endif
                </div>
                <div class="mt-4">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        {{ __('layout.create_button') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
@endauth
