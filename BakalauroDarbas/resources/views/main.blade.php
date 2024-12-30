@extends('layout2')
@section('title', '„Reabita“')

@section('content')
<link rel="stylesheet" href="{{ asset('css/mainstyle.css') }}">

<div class="main-content">
    <div class="logo-container">
        <img src="/img/mainphoto.png" class="main-logo"> 
    </div>

    <div class="greeting">
        <h1>Sveiki prisijungę prie „Reabita“ sistemos!</h1>
    </div>
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
</div>

@endsection
