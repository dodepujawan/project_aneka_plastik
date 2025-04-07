<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

// Menggunakan suer_id contoh AD0002 yang berbentuk string sebagai parameter pencocok
// Broadcast::channel('user.{userId}', function ($user, $userId) {
//     \Log::info("Autentikasi channel: userId dari frontend = {$userId}, user_id di DB = {$user->id}");
//     return (int) $user->id === (int) $userId; // Pastikan kedua tipe sama
// });
// Broadcast::channel('user.{userId}', function ($user, $userId) {
//     return (int) $user->id === (int) $userId;
// }, ['guards' => ['web']]);  // <- Pakai guard web
Broadcast::channel('private-private-user.{id}', function ($user, $id) {
    \Log::info('Authenticating user: ' . $id);
    return (int) $user->id === (int) $id;
});

