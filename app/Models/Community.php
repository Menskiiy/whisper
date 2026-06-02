<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Community extends Model {
    protected $fillable = ['owner_id','slug','name','description','rules','avatar','banner','accent_color','privacy','category','members_count'];

    public function owner()   { return $this->belongsTo(User::class, 'owner_id'); }
    public function members() { return $this->hasMany(CommunityMember::class); }
    public function posts()   { return $this->hasMany(CommunityPost::class)->latest(); }

    public function isMember(User $user): bool {
        return $this->members()->where('user_id', $user->id)->exists();
    }
    public function getMemberRole(User $user): ?string {
        return $this->members()->where('user_id', $user->id)->value('role');
    }
    public function canManage(User $user): bool {
        $role = $this->getMemberRole($user);
        return in_array($role, ['owner','admin','mod']);
    }
    public function isAdmin(User $user): bool {
        return in_array($this->getMemberRole($user), ['owner','admin']);
    }
}
