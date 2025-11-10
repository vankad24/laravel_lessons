@extends('layouts.app')

@section('title', 'Регистрация')

@section('content')
    <h2 class="mb-4">Форма Регистрации</h2>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('data.save') }}">
        @csrf

        {{-- Поле Email --}}
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}">
            @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        {{-- Поле Пароль --}}
        <div class="mb-3">
            <label for="password" class="form-label">Пароль</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
            @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        {{-- Поле Подтверждение Пароля (обязательно для правила 'confirmed') --}}
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Подтверждение Пароля</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
        </div>

        <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
    </form>
@endsection
