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

// Broadcast::channel('user.{userId}', function ($user, $userId) {
//     Log::info('Auth checked for user: ', ['user' => $user, 'userId' => $userId]);
//     return true;
//     // return (string) $user->user_id === (string) $userId;
// });
// Broadcast::channel('user.{userId}', function ($user, $userId) {
//     return (int) $user->user_id === (int) $userId;
// });
// Menggunakan suer_id contoh AD0002 yang berbentuk string sebagai parameter pencocok
Broadcast::channel('user.{userId}', function ($user, $userId) {
    \Log::info("Autentikasi channel: userId dari frontend = {$userId}, user_id di DB = {$user->user_id}");
    return (string) $user->user_id === (string) $userId; // Gunakan user_id yang sesuai
});

