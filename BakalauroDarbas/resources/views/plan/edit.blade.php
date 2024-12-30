@extends('layout2')
@section('title', 'Reabilitacijos plano atnaujinimas')
@section('content')

<link rel="stylesheet" href="{{ asset('css/plancrud.css') }}">

<div class="container mt-4">
    <div class="form-container">
        <h2>Įrašo atnaujinimas</h2>
        <form action="{{ route('plan.update', $plan->id) }}" method="POST" enctype="multipart/form-data" id="edit-plan-form">
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
            @method('PUT')
            <input type="hidden" name="deleted_files" id="deleted-files">

            <div class="form-row">
                <div class="mb-3 col-md-6">
                    <label for="username" class="form-label">Autorius</label>
                    <input type="text" class="form-control" id="username" value="{{ $plan->user->name . ' ' . $plan->user->surname }}" readonly>
                </div>
                <div class="mb-3 col-md-6">
                    <label for="created_at" class="form-label">Sukūrimo data</label>
                    <input type="text" class="form-control" id="created_at" value="{{ $plan->created_at->toDateString() }}" readonly>
                </div>
            </div>

            <div class="mb-3">
                <label for="title" class="form-label">Reabilitacijos plano pavadinimas <span style="color: red;">*</span></label>
                <input type="text" id="title" name="title" class="form-control" value="{{ old('title', $plan->title) }}">
                @error('title')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Vaizdo įrašų įkėlimas</label>
                <div id="video-preview" class="file-preview">
                    @foreach ($plan->files->filter(fn($file) => Str::contains($file->file_path, ['.mp4', '.avi', '.mkv'])) as $media)
                        <div class="file-item" data-file-id="{{ $media->id }}">
                            <video class="file-item-video" controls>
                                <source src="{{ $media->file_path }}" type="video/mp4">
                            </video>
                            <i class="fas fa-trash-alt text-danger ms-2 delete-icon" onclick="removeFile('{{ $media->id }}', this)"></i>
                        </div>
                    @endforeach
                </div>
                <label for="video-upload" class="btn btn-primary mt-2">Pasirinkite vaizdo įrašus</label>
                <input type="file" id="video-upload" name="media[]" multiple accept="video/*" style="display: none;" onchange="previewFile(this, 'video')">
            </div>

            <div class="mb-3">
                <label for="content" class="form-label">Turinys <span style="color: red;">*</span></label>
                <textarea id="content" name="content" class="form-control" rows="8">{{ old('content', $plan->content) }}</textarea>
                @error('content')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Nuotraukų įkėlimas</label>
                <div id="photo-preview" class="file-preview">
                    @foreach ($plan->files->filter(fn($file) => Str::contains($file->file_path, ['.jpg', '.jpeg', '.png', '.gif'])) as $media)
                        <div class="file-item" data-file-id="{{ $media->id }}">
                            <img class="file-item-img" src="{{ $media->file_path }}" alt="{{ $media->original_file_name }}">
                            <i class="fas fa-trash-alt text-danger ms-2 delete-icon" onclick="removeFile('{{ $media->id }}', this)"></i>
                        </div>
                    @endforeach
                </div>
                <label for="photo-upload" class="btn btn-primary mt-2">Pasirinkite nuotraukas</label>
                <input type="file" id="photo-upload" name="media[]" multiple accept="image/*" style="display: none;" onchange="previewFile(this, 'photo')">
            </div>

            <div class="mb-3">
                <label class="form-label">Papildomų failų įkėlimas</label>
                <div id="file-preview" class="file-preview">
                    @foreach ($plan->files->reject(fn($file) => Str::contains($file->file_path, ['.jpg', '.jpeg', '.png', '.gif', '.mp4', '.avi', '.mkv'])) as $media)
                        <div class="file-item" data-file-id="{{ $media->id }}">
                            <i class="fas fa-file"></i> {{ $media->original_file_name }}
                            <i class="fas fa-trash-alt text-danger ms-2 delete-icon" onclick="removeFile('{{ $media->id }}', this)"></i>
                        </div>
                    @endforeach
                </div>
                <label for="fileInput" class="btn btn-primary">Pasirinkite papildomus failus</label>
                <input type="file" id="fileInput" name="media[]" multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.zip" style="display: none;">
            </div>

            <button type="submit" class="btn btn-success">Išsaugoti</button>
            <a href="{{ route('plan.index') }}" class="btn btn-danger">Atšaukti</a>
        </form>
    </div>
</div>

<script src="https://cdn.tiny.cloud/1/58cnvixgx6taf6ngihczpgw6rdok3w9q3ge8d9s1nfflier8/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script src="/js/edit.js"></script>
@endsection
