@extends('layouts.app')

@section('title', 'Просмотр данных регистрации')

@section('content')
    <h2 class="mb-4">Сохраненные данные Регистрации</h2>

    @if (empty($allData))
        {{-- ... (код остается прежним) --}}
        <div class="alert alert-info" role="alert">
            Нет сохраненных данных для отображения.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Email</th>
                        <th>Хеш Пароля</th> {{-- Выводим хеш вместо самого пароля --}}
                        <th>Временная метка (Irkutsk)</th>
                        <th>IP Адрес</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($allData as $dataItem)
                        <tr>
                            <td>{{ $dataItem['registration_data']['email'] ?? 'N/A' }}</td>
                            <td style="font-size: 0.8em; max-width: 300px; overflow: hidden; text-overflow: ellipsis;">
                                {{ $dataItem['registration_data']['password_hash'] ?? 'N/A' }}
                            </td>
                            <td>{{ $dataItem['timestamp'] ?? 'N/A' }}</td>
                            <td>{{ $dataItem['ip_address'] ?? 'N/A' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
