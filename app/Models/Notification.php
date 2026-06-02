<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model {
    protected $fillable = ['user_id','actor_id','type','notifiable_id','notifiable_type','is_read'];
    protected $casts = ['is_read' => 'boolean'];
    public function actor() { return $this->belongsTo(User::class, 'actor_id'); }
    public function notifiable() { return $this->morphTo(); }
}
