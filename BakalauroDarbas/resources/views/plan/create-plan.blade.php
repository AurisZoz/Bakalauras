@extends('layout2')
@section('title', 'Reabilitacijos plano kūrimas')
@section('content')

<link rel="stylesheet" href="{{ asset('css/plancrud.css') }}">
<div class="container mt-4">
    <div class="form-container">
        <h2>Naujo įrašo kūrimas</h2>
        <form action="{{ route('plan.store') }}" method="POST" enctype="multipart/form-data" id="plan-form">
            @csrf
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-row">
                <div class="mb-3 col-md-6">
                    <label for="username" class="form-label">Autorius</label>
                    <input type="text" class="form-control" id="username" value="{{ Auth::user()->name . ' ' . Auth::user()->surname }}" readonly onfocus="this.blur()">
                </div>
                <div class="mb-3 col-md-6">
                    <label for="created_at" class="form-label">Sukūrimo data</label>
                    <input type="text" class="form-control" id="created_at" value="{{ now()->toDateString() }}" readonly onfocus="this.blur()">
                </div>
            </div>

            <div class="mb-3">
                <label for="title" class="form-label">Reabilitacijos plano pavadinimas <span style="color: red;">*</span></label>
                <input type="text" class="form-control" id="title" name="title" placeholder="Reabilitacijos plano pavadinimas" value="{{ old('title') }}">
                @error('title')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Vaizdo įrašų įkėlimas</label>
                <div id="video-preview" class="file-preview"></div>
                <label for="video-upload" class="btn btn-primary">Pasirinkite vaizdo įrašus</label>
                <input type="file" id="video-upload" name="videos[]" multiple accept="video/*" style="display: none;">
                @error('videos')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="content" class="form-label">Turinys <span style="color: red;">*</span></label>
                <textarea id="content" name="content" class="form-control" rows="8">{{ old('content') }}</textarea>
                @error('content')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Nuotraukų įkėlimas</label>
                <div id="photo-preview" class="file-preview"></div>
                <label for="photo-upload" class="btn btn-primary">Pasirinkite nuotraukas</label>
                <input type="file" id="photo-upload" name="photos[]" multiple accept="image/*" style="display: none;">
                @error('photos')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
    <label class="form-label">Papildomų failų įkėlimas</label>
    <div id="file-preview" class="file-preview"></div>
    <label for="file-upload" class="btn btn-primary">Pasirinkite papildomus failus</label>
    <input type="file" id="file-upload" name="media[]" multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.zip" style="display: none;">
    @error('media')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>
        <button type="submit" class="btn btn-success">Išsaugoti</button>
        <a href="{{ route('main') }}" class="btn btn-danger">Atšaukti</a>
        </form>
    </div>
</div>

<script src="https://cdn.tiny.cloud/1/58cnvixgx6taf6ngihczpgw6rdok3w9q3ge8d9s1nfflier8/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<script src="/js/createplan.js"></script>
@endsection
