@extends('layouts.app')

@section('content')
<div class="page" style="max-width:600px">
<div class="card">
    <div style="padding:20px 24px 16px;border-bottom:1px solid rgba(255,255,255,0.07);font-family:'Syne',sans-serif;font-weight:700;font-size:17px;color:#e8eaf6">
        Редактировать пост
    </div>
    <div style="padding:24px">
        <form method="POST" action="{{ route('posts.update', $post) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <textarea name="body" rows="5" maxlength="560" required
                      style="width:100%;background:#161b2e;border:1px solid rgba(255,255,255,0.07);border-radius:12px;padding:14px 16px;color:#e8eaf6;font-family:inherit;font-size:15px;resize:none;outline:none;transition:border-color .2s;margin-bottom:14px"
                      onfocus="this.style.borderColor='#7c5af5'" onblur="this.style.borderColor='rgba(255,255,255,0.07)'">{{ old('body', $post->body) }}</textarea>

            @if($post->image)
                <div style="margin-bottom:14px">
                    <p style="color:#7b80a0;font-size:13px;margin-bottom:8px">Текущее изображение:</p>
                    <img src="{{ asset('storage/posts/'.$post->image) }}"
                         style="max-width:100%;border-radius:12px;border:1px solid rgba(255,255,255,0.07)">
                </div>
            @endif

            <label class="file-label" style="display:inline-flex;align-items:center;gap:6px;color:#7b80a0;font-size:13px;cursor:pointer;padding:8px 12px;border-radius:8px;border:1px solid rgba(255,255,255,0.07);margin-bottom:20px;transition:all .2s"
                   onmouseover="this.style.color='#7c5af5';this.style.borderColor='rgba(124,90,245,0.4)'" onmouseout="this.style.color='#7b80a0';this.style.borderColor='rgba(255,255,255,0.07)'">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="3"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
                Заменить изображение
                <input type="file" name="image" accept="image/*" style="display:none">
            </label>

            <div style="display:flex;gap:10px">
                <button type="submit" class="btn btn-p">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/></svg>
                    Сохранить
                </button>
                <a href="{{ route('home') }}" class="btn btn-ghost">Отмена</a>
            </div>
        </form>
    </div>
</div>
</div>
@endsection
