<?php
namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Notification;

class FollowController extends Controller {
    public function toggle(User $user) {
        $me = auth()->user();
        $wasFollowing = $me->isFollowing($user);
        $me->toggleFollow($user);
        if (!$wasFollowing) {
            Notification::create(['user_id'=>$user->id,'actor_id'=>$me->id,'type'=>'follow','notifiable_id'=>null,'notifiable_type'=>null]);
        }
        $followers = $user->fresh()->followers()->count();
        return response()->json(['following'=>!$wasFollowing,'followers'=>$followers]);
    }
}
