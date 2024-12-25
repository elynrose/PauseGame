<?php

namespace App\Observers;

use App\Models\Score;
use App\Notifications\DataChangeEmailNotification;
use Illuminate\Support\Facades\Notification;

class ScoreActionObserver
{
    public function created(Score $model)
    {
        $data  = ['action' => 'created', 'model_name' => 'Score'];
        $users = \App\Models\User::whereHas('roles', function ($q) {
            return $q->where('title', 'Admin');
        })->get();
        Notification::send($users, new DataChangeEmailNotification($data));
    }
}
