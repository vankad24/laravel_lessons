<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class DataController extends Controller
{
    /**
     * Отображает форму для регистрации.
     */
    public function showForm()
    {
        return view('data.registration_form');
    }

    /**
     * Сохраняет данные регистрации в JSON-файл.
     */
    public function saveData(Request $request)
    {
        // Валидация
        $validated = $request->validate([
            'email' => 'required|email|max:100|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ], [
            // Сообщения об ошибках
            'email.required' => 'Поле Email обязательно для заполнения.',
            'email.email' => 'Поле Email должно быть действительным адресом электронной почты.',
            'email.max' => 'Поле Email не может превышать 100 символов.',
            'email.unique' => 'Пользователь с таким Email уже существует.',
            'password.required' => 'Поле Пароль обязательно для заполнения.',
            'password.min' => 'Пароль должен содержать минимум 8 символов.',
            'password.confirmed' => 'Подтверждение пароля не совпадает.',
        ]);

        // Сохранение данных
        $data = [
            'timestamp' => now()->toDateTimeString(),
            'ip_address' => $request->ip(),
            'registration_data' => [
                'email' => $validated['email'],
                'password_hash' => Hash::make($validated['password']), // Хешируем пароль
            ],
        ];

        // Уникальное имя файла
        $filename = 'user_' . Str::uuid() . '.json';

        // Сохранение файла в директорию storage/app/private/files
        Storage::put('files/' . $filename, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        // Вывод сообщения об успехе
        return redirect()->route('data.form')->with('success', 'Регистрация прошла успешно! Данные сохранены в JSON.');
    }

    /**
     * Отображает все сохраненные данные в виде таблицы.
     */
    public function showTable()
    {
        $files = Storage::files('files');
        $allData = [];

        foreach ($files as $file) {
            $content = Storage::get($file);
            // Пытаемся декодировать
            if ($decoded = json_decode($content, true)) {
                 $allData[] = $decoded;
            }
        }

        return view('data.table', ['allData' => $allData]);
    }
}
