<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
    use HasFactory, Notifiable;

    protected $fillable = [
        'login','name','email','password','gender','terms_accepted',
        'avatar','status','bio','birthday','location',
        'is_private','website','accent_color','banner','vk','telegram','instagram',
        'wispy_personality',
    ];
    protected $hidden   = ['password','remember_token'];
    protected $casts    = ['email_verified_at'=>'datetime','birthday'=>'date','is_private'=>'boolean'];

    public function posts()    { return $this->hasMany(Post::class); }
    public function comments() { return $this->hasMany(Comment::class); }
    public function likes()    { return $this->hasMany(Like::class); }
    public function tracks()   { return $this->hasMany(Track::class); }
    public function videos()   { return $this->hasMany(Video::class); }

    public function following() {
        return $this->belongsToMany(User::class,'follows','follower_id','following_id');
    }
    public function followers() {
        return $this->belongsToMany(User::class,'follows','following_id','follower_id');
    }
    public function toggleFollow(User $u)      { $this->following()->toggle($u->id); }
    public function isFollowing(User $u): bool { return $this->following()->where('following_id',$u->id)->exists(); }

    public function communities() {
        return $this->belongsToMany(Community::class,'community_members','user_id','community_id')
                    ->withPivot('role')->withTimestamps();
    }

    public function appNotifications() { return $this->hasMany(Notification::class)->latest(); }
    public function unreadNotificationsCount(): int {
        return Notification::where('user_id',$this->id)->where('is_read',false)->count();
    }
    public function unreadMessagesCount(): int {
        return Message::where('to_user_id',$this->id)->where('is_read',false)->count();
    }
    public function accentColor(): string {
        return $this->accent_color ?? '#7c5af5';
    }
}
