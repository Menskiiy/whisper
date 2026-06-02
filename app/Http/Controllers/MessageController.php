<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{
    // Список диалогов
    public function inbox()
    {
        $conversations = Message::where('from_user_id', auth()->id())
            ->orWhere('to_user_id', auth()->id())
            ->select('from_user_id', 'to_user_id')
            ->latest()
            ->get();

        $userIds = collect();

        foreach ($conversations as $conv) {
            if ($conv->from_user_id != auth()->id()) {
                $userIds->push($conv->from_user_id);
            }
            if ($conv->to_user_id != auth()->id()) {
                $userIds->push($conv->to_user_id);
            }
        }

        $users = User::whereIn('id', $userIds->unique())->get();

        return view('messages.inbox', compact('users'));
    }

    // Открыть чат
    public function show(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('messages.inbox');
        }

        $messages = Message::where(function ($q) use ($user) {
            $q->where('from_user_id', auth()->id())->where('to_user_id', $user->id);
        })->orWhere(function ($q) use ($user) {
            $q->where('from_user_id', $user->id)->where('to_user_id', auth()->id());
        })->orderBy('created_at', 'asc')->get();

        Message::where('to_user_id', auth()->id())
               ->where('from_user_id', $user->id)
               ->where('is_read', false)
               ->update(['is_read' => true]);

        return view('messages.show', compact('user', 'messages'));
    }

    // Отправить сообщение (с картинкой/видео/GIF)
    public function store(Request $request, User $user)
    {
        $request->validate([
            'body'  => 'required_without:media|max:1000',
            'media' => 'nullable|file|max:3145728', // 3GB
        ], [
            'media.max' => 'Файл слишком большой. Максимум 3GB.',
        ]);

        $mediaPath = null;
        $mediaType = null;

        if ($request->hasFile('media')) {
            try {
                $file = $request->file('media');
                $mimeType = $file->getMimeType();
                $originalExtension = strtolower($file->getClientOriginalExtension());
                
                // Определяем правильное расширение
                $extension = $originalExtension;
                if (empty($extension)) {
                    $extension = $this->getExtensionFromMime($mimeType);
                }
                
                // Валидация типов файлов
                $allowedImageMimes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/bmp', 'image/svg+xml'];
                $allowedVideoMimes = ['video/mp4', 'video/quicktime', 'video/x-msvideo', 'video/x-ms-wmv', 'video/x-matroska', 'video/webm', 'video/x-flv', 'video/mpeg', 'video/3gpp', 'video/MP2T', 'video/x-m4v'];
                
                if (!in_array($mimeType, array_merge($allowedImageMimes, $allowedVideoMimes))) {
                    return back()->withErrors(['media' => 'Неподдерживаемый формат файла. Mime: ' . $mimeType]);
                }
                
                // Создаем уникальное имя файла
                $filename = 'msg_' . uniqid() . '_' . time() . '.' . $extension;
                $file->storeAs('messages', $filename, 'public');
                $mediaPath = $filename;
                
                // Определяем тип медиа
                $videoExtensions = ['mp4', 'mov', 'avi', 'wmv', 'mkv', 'webm', 'flv', 'm4v', '3gp', 'mpeg', 'mpg', 'ogv', 'ts'];
                $mediaType = (in_array($mimeType, $allowedVideoMimes) || in_array($extension, $videoExtensions)) ? 'video' : 'image';
                
            } catch (\Exception $e) {
                Log::error('Ошибка загрузки медиа: ' . $e->getMessage());
                return back()->withErrors(['media' => 'Ошибка загрузки файла. Попробуйте другой формат.']);
            }
        }

        Message::create([
            'from_user_id' => auth()->id(),
            'to_user_id'   => $user->id,
            'body'         => $request->body ?? '',
            'image'        => $mediaPath,
            'media_type'   => $mediaType,
        ]);

        return back()->with('success', 'Сообщение отправлено');
    }

    // Редактировать сообщение
    public function update(Request $request, Message $message)
    {
        if ($message->from_user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'body'  => 'required_without:media|max:1000',
            'media' => 'nullable|file|max:3145728', // 3GB
        ], [
            'media.max' => 'Файл слишком большой. Максимум 3GB.',
        ]);

        $data = ['body' => $request->body ?? $message->body];

        if ($request->hasFile('media')) {
            try {
                // Удаляем старое медиа
                if ($message->image) {
                    Storage::disk('public')->delete('messages/' . $message->image);
                }
                
                $file = $request->file('media');
                $mimeType = $file->getMimeType();
                $extension = strtolower($file->getClientOriginalExtension());
                
                if (empty($extension)) {
                    $extension = $this->getExtensionFromMime($mimeType);
                }
                
                $filename = 'msg_' . $message->id . '_' . time() . '.' . $extension;
                $file->storeAs('messages', $filename, 'public');
                
                $videoExtensions = ['mp4', 'mov', 'avi', 'wmv', 'mkv', 'webm', 'flv', 'm4v', '3gp', 'mpeg', 'mpg', 'ogv', 'ts'];
                $videoMimes = ['video/mp4', 'video/quicktime', 'video/x-msvideo', 'video/x-ms-wmv', 'video/x-matroska', 'video/webm', 'video/x-flv', 'video/mpeg', 'video/3gpp', 'video/MP2T', 'video/x-m4v'];
                $mediaType = (in_array($mimeType, $videoMimes) || in_array($extension, $videoExtensions)) ? 'video' : 'image';
                
                $data['image'] = $filename;
                $data['media_type'] = $mediaType;
                
            } catch (\Exception $e) {
                Log::error('Ошибка обновления медиа: ' . $e->getMessage());
                return back()->withErrors(['media' => 'Ошибка загрузки файла.']);
            }
        }

        $message->update($data);

        return back()->with('success', 'Сообщение обновлено');
    }

    // Удалить сообщение
    public function destroy(Message $message)
    {
        if ($message->from_user_id !== auth()->id()) {
            abort(403);
        }

        if ($message->image) {
            Storage::disk('public')->delete('messages/' . $message->image);
        }

        $message->delete();

        return back()->with('success', 'Сообщение удалено');
    }

    // Вспомогательный метод для определения расширения по mime-type
    private function getExtensionFromMime($mimeType)
    {
        $mimeMap = [
            'image/jpeg' => 'jpg',
            'image/jpg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
            'image/bmp' => 'bmp',
            'image/svg+xml' => 'svg',
            'video/mp4' => 'mp4',
            'video/quicktime' => 'mov',
            'video/x-msvideo' => 'avi',
            'video/x-ms-wmv' => 'wmv',
            'video/x-matroska' => 'mkv',
            'video/webm' => 'webm',
            'video/mpeg' => 'mpeg',
            'video/3gpp' => '3gp',
            'video/x-flv' => 'flv',
            'video/x-m4v' => 'm4v',
            'video/MP2T' => 'ts',
        ];
        
        return $mimeMap[$mimeType] ?? 'bin';
    }
}