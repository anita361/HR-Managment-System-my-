<?php

Broadcast::channel('voice-call.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});



?>