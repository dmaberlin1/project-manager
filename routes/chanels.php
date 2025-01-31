<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Task;
use App\Models\Project;

Broadcast::channel('tasks', function ($user) {
    return Auth::check();
});

Broadcast::channel('projects', function ($user) {
    return Auth::check();
});
