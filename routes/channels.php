<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\User;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat-channel.{userId}', function (User $user, $userId) {
    return (int) $user->id === (int) $userId;
});

Broadcast::channel('global-chat', function (User $user) {
    return ['id' => $user->id, 'name' => $user->name];
});
