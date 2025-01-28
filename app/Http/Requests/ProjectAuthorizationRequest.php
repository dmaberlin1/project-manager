<?php

namespace App\Http\Requests;

use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProjectAuthorizationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */

    public function authorize()
    {
        // Разрешаем доступ, если роль пользователя 'admin'
        if (Auth::user()->role === 'admin') {
            return true;
        }

        // Для других пользователей проверяем, есть ли у них задачи в проекте
        $projectQuery = Project::when(Auth::user()->role !== 'admin', function ($query) {
            return $query->whereHas('tasks', function ($q) {
                $q->where('user_id', Auth::id());
            });
        });

        // Если есть доступные проекты, разрешаем
        return $projectQuery->exists();
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public
    function rules(): array
    {
        return [
            //
        ];
    }
}
