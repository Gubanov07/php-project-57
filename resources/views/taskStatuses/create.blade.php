@extends('layouts.app')
@section('content')

@auth()
    <div class="grid col-span-full">
        <h1 class="max-w-2xl mb-4 text-4xl leading-none tracking-tight md:text-5xl xl:text-6xl dark:text-white">{{ __('layout.task_statuses_create') }}</h1>

        {!! html()->form('POST', route('task_statuses.store'))->class('w-50')->open() !!}
        
            <div class="flex flex-col">
                <div>
                    {!! html()->label(__('layout.table_name'), 'name') !!}
                </div>
                <div class="mt-2">
                    {!! html()->text('name', old('name'))->class('form-control rounded border-gray-300 w-1/3') !!}
                </div>
                <div>
                    @error('name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mt-4">
                    {!! html()->submit(__('layout.create_button'))->class('bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded') !!}
                </div>
            </div>
            
        {!! html()->form()->close() !!}
    </div>
@endauth

@endsection
