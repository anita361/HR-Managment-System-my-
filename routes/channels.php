<?php

Broadcast::channel('voice-call.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});
Broadcast::channel('group-call.{groupId}', function ($user, $groupId) {
    $group = Group::find($groupId);
    return $group && $group->users->contains($user->id);
});




?>