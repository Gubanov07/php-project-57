@extends('layouts.app')
@section('content')

@auth()
    <div class="grid col-span-full">
        <h1 class="max-w-2xl mb-4 text-4xl leading-none tracking-tight md:text-5xl xl:text-6xl dark:text-white">{{ __('layout.labels_edit') }}</h1>

        <form action="{{ route('labels.update', $label) }}" method="POST" class="w-50">
            @csrf
            @method('PUT')
            
            <div class="flex flex-col">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">
                        {{ __('layout.table_name') }}
                    </label>
                </div>
                <div class="mt-2">
                    <input type="text"
                           name="name"
                           id="name"
                           value="{{ old('name', $label->name) }}"
                           class="form-control rounded border-gray-300 w-1/3 @error('name') border-red-500 @enderror">
                </div>
                <div>
                    @error('name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mt-2">
                    <label for="description" class="block text-sm font-medium text-gray-700">
                        {{ __('layout.table_description') }}
                    </label>
                </div>
                <div class="mt-2">
                    <textarea name="description"
                              id="description"
                              class="form-control rounded border-gray-300 w-1/3 @error('description') border-red-500 @enderror">{{ old('description', $label->description) }}</textarea>
                </div>
                <div>
                    @error('description')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mt-4">
                    <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        {{ __('layout.update_button') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
@endauth

@endsection
