@if (session()->has('success'))
<div>
        <div class="alert alert-success solid text-black" role="alert">
            {{ session('success') }}
        </div>
</div>
@endif

@if ($errors->any())
    <div class="alert alert-danger solid">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
