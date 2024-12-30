@extends('layout2')
@section('title', 'Reabilitacijos plano peržiūra')
@section('content')

<link rel="stylesheet" href="{{ asset('css/plancrud.css') }}">
<div class="container mt-4">
    <div class="form-container">
        <h2>Reabilitacijos planas</h2>
        <div class="form-row">
            <div class="mb-3 col-md-6">
                <label class="form-label">Autorius</label>
                <p class="form-control">{{ $plan->user->name }} {{ $plan->user->surname }}</p>
            </div>
            <div class="mb-3 col-md-6">
                <label class="form-label">Sukūrimo data</label>
                <p class="form-control">{{ $plan->created_at->format('Y-m-d') }}</p>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Reabilitacijos plano pavadinimas</label>
            <p class="form-control">{{ $plan->title }}</p>
        </div>

        @if($plan->files->contains(fn($file) => preg_match('/\.(mp4|avi|mov)$/i', $file->file_path)))
        <div class="mb-3">
            <label class="form-label">Vaizdo įrašai</label>
            <div>
                @foreach($plan->files as $file)
                    @if(preg_match('/\.(mp4|avi|mov)$/i', $file->file_path))
                        <div class="file-item mb-3">
                            <video class="file-item-video" controls>
                                <source src="{{ $file->file_path }}" type="video/mp4">
                            </video>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
        @endif

        <div class="mb-3">
            <label class="form-label">Turinys</label>
            <div class="form-control" style="height: auto;" id="content">
                {!! $plan->content !!}
            </div>
        </div>

        @if($plan->files->contains(fn($file) => preg_match('/\.(jpg|jpeg|png|gif)$/i', $file->file_path)))
        <div class="mb-3">
            <label class="form-label">Nuotraukos</label>
            <div>
                @foreach($plan->files as $file)
                    @if(preg_match('/\.(jpg|jpeg|png|gif)$/i', $file->file_path))
                        <div class="file-item mb-3">
                            <img class="file-item-img" src="{{ $file->file_path }}" alt="{{ $file->original_file_name }}">
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
        @endif

        @if($plan->files->contains(fn($file) => !preg_match('/\.(jpg|jpeg|png|gif|mp4|avi|mov)$/i', $file->file_path)))
        <div class="mb-3">
            <label class="form-label">Papildomi failai</label>
            <div>
                @foreach($plan->files as $file)
                    @if(!preg_match('/\.(jpg|jpeg|png|gif|mp4|avi|mov)$/i', $file->file_path))
                        <div class="file-item mb-3">
                            @php
                                $fileType = pathinfo($file->file_path, PATHINFO_EXTENSION);
                            @endphp
                            
                            @if(in_array($fileType, ['doc', 'docx']))
                                <i class="fas fa-file-word"></i>
                            @elseif(in_array($fileType, ['xls', 'xlsx']))
                                <i class="fas fa-file-excel"></i>
                            @elseif(in_array($fileType, ['ppt', 'pptx']))
                                <i class="fas fa-file-powerpoint"></i>
                            @elseif($fileType === 'pdf')
                                <i class="fas fa-file-pdf"></i>
                            @elseif($fileType === 'zip')
                                <i class="fas fa-file-archive"></i>
                            @else
                                <i class="fas fa-file"></i>
                            @endif
                            <a href="{{ route('file.download', $file->id) }}" target="_blank">{{ $file->original_file_name ?? basename($file->file_path) }}</a>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
        @endif

        <a href="{{ request()->query('referrer', route('plan.index')) }}" class="btn btn-secondary btn-block atgal-button">Atgal</a>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<script src="https://cdn.tiny.cloud/1/58cnvixgx6taf6ngihczpgw6rdok3w9q3ge8d9s1nfflier8/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '#content',
        plugins: 'lists link image preview',
        toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image preview',
        menubar: false,
        readonly: true
    });
</script>
@endsection
