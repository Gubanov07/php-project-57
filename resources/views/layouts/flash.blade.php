@if(session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
@endif

@include('flash::message')