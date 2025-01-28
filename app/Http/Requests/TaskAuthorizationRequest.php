<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class TaskAuthorizationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Для метода index проверяем роль пользователя
        if ($this->route()->getName() === 'tasks.index' && Auth::user()->role === 'user') {
            return true; // Пользователь может просматривать только свои задачи
        }
        // Для других случаев проверяем доступ по задаче
        $task=$this->route('task');
        if(Auth::user()->role==='user' && $task->user_id !== Auth::id()){
            return false;
        }
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}
