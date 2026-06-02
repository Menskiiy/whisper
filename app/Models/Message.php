<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    protected $fillable = [
        'from_user_id',
        'to_user_id',
        'body',
        'image',
        'media_type',
        'is_read'
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    /**
     * Отправитель сообщения
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    /**
     * Получатель сообщения
     */
    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

    /**
     * Проверка, является ли медиа видео
     */
    public function isVideo(): bool
    {
        return $this->media_type === 'video';
    }

    /**
     * Проверка, является ли медиа изображением
     */
    public function isImage(): bool
    {
        return $this->media_type === 'image';
    }

    /**
     * Получить полный URL медиа
     */
    public function getMediaUrl(): ?string
    {
        if (!$this->image) {
            return null;
        }

        return asset('storage/messages/' . $this->image);
    }
}