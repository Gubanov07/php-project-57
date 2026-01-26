@extends('layouts.app')
@section('content')

<div class="grid col-span-full">
    <h1 class="max-w-2xl mb-4 text-4xl leading-none tracking-tight md:text-5xl xl:text-6xl dark:text-white">
        {{ __('layout.labels_header') }} </h1>
    @auth()
    <div>
        @csrf
        <a href="{{ route('labels.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            {{ __('layout.create_button_label') }}            </a>
    </div>
    @endauth
    <table class="mt-4">
        <thead class="border-b-2 border-solid border-black text-left" style="text-align: left">
        <tr>
            <th>{{ __('layout.table_id') }}</th>
            <th>{{ __('layout.table_name') }}</th>
            <th>{{ __('layout.table_description') }}</th>
            <th>{{ __('layout.table_date_of_creation') }}</th>
            @auth()
            <th>{{ __('layout.table_actions') }}</th>
            @endauth
        </tr>
        </thead>
        <tbody>
        @foreach($labels as $label)
        <tr class="border-b border-dashed text-left">
            <td>{{ $label->id }}</td>
            <td>{{ $label->name }}</td>
            <td>{{ $label->description }}</td>
            <td>{{ $label->created_at }}</td>
            @auth()
            <td class="flex space-x-2">
                <form action="{{ route('labels.destroy', $label) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="text-red-600 hover:text-red-900"
                            dusk="delete-label-btn-{{ $label->id }}"
                            onclick="return confirm('{{ __('layout.table_delete_question') }}')">
                        {{ __('layout.table_delete') }}
                    </button>
                </form>
                
                <a class="text-blue-600 hover:text-blue-900"
                   href="{{ route('labels.edit', $label) }}">
                    {{ __('layout.table_edit') }}
                </a>
            </td>
            @endauth
        </tr>
            @endforeach
        </tbody>
    </table>
</div>
@auth()
    <div class="mt-4 grid col-span-full">{{ $labels->links() }}</div>
@endauth
@endsection
