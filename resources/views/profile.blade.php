@extends('layouts.app')
@section('title','Мой профиль — Whisper')
@section('content')
<div class="page" style="max-width:740px">
<div class="card">
  <!-- Баннер -->
  <div class="profile-banner" style="height:220px;position:relative;overflow:hidden;">
    <div class="pbn-inner" style="height:100%;width:100%;">
      @if(auth()->user()->banner)
        <img src="{{ asset('storage/banners/'.auth()->user()->banner) }}" style="width:100%;height:100%;object-fit:cover;object-position:center;">
      @endif
    </div>
  </div>

  <!-- Аватарка и кнопки -->
  <div class="p-info-row" style="display:flex;justify-content:space-between;align-items:flex-start;padding:0 20px 16px;">
    <div class="p-av-wrap" style="width:144px;height:144px;margin-top:-72px;flex-shrink:0;position:relative;z-index:2;">
      <img src="{{ auth()->user()->avatar ? asset('storage/avatars/'.auth()->user()->avatar) : asset('images/default.png') }}"
           class="p-av"
           style="width:100%;height:100%;object-fit:cover;box-shadow:0 0 0 2.5px {{ auth()->user()->accentColor() }};">
    </div>
    <div style="display:flex;gap:9px;align-items:center;padding-top:20px;">
      <a href="{{ route('profile.edit') }}" class="btn btn-o btn-sm">⚙ Настройки</a>
    </div>
  </div>

  <div style="padding:2px 20px 16px">
    <div class="p-name">{{ auth()->user()->name ?: auth()->user()->login }}</div>
    <div class="p-login">{{ auth()->user()->login }}
      @if(auth()->user()->is_private)
        <span class="tag" style="font-size:10px;padding:1px 7px;margin-left:7px">🔒 закрытый</span>
      @endif
    </div>
    @if(auth()->user()->status)
      <div class="p-status">{{ auth()->user()->status }}</div>
    @endif
    @if(auth()->user()->bio)
      <p class="p-bio">{{ auth()->user()->bio }}</p>
    @endif
    <div class="p-meta">
      @if(auth()->user()->location)
        <span class="p-meta-i">
          <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
          {{ auth()->user()->location }}
        </span>
      @endif
      @if(auth()->user()->birthday)
        <span class="p-meta-i">
          <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
          {{ auth()->user()->birthday->format('d.m.Y') }}
        </span>
      @endif
      @if(auth()->user()->website)
        <span class="p-meta-i">🌐 <a href="{{ auth()->user()->website }}" target="_blank">{{ parse_url(auth()->user()->website, PHP_URL_HOST) }}</a></span>
      @endif
      @if(auth()->user()->telegram)
        <span class="p-meta-i">✈ <a href="https://t.me/{{ auth()->user()->telegram }}" target="_blank">{{ auth()->user()->telegram }}</a></span>
      @endif
      @if(auth()->user()->vk)
        <span class="p-meta-i">🔵 <a href="https://vk.com/{{ auth()->user()->vk }}" target="_blank">{{ auth()->user()->vk }}</a></span>
      @endif
      @if(auth()->user()->instagram)
        <span class="p-meta-i">📷 <a href="https://instagram.com/{{ auth()->user()->instagram }}" target="_blank">{{ auth()->user()->instagram }}</a></span>
      @endif
      <span class="p-meta-i" style="font-size:11px;color:var(--t3)">На Whisper с {{ auth()->user()->created_at->format('M Y') }}</span>
    </div>
  </div>

  <div class="pstats">
    <div class="pstat"><div class="pstat-n">{{ auth()->user()->posts()->count() }}</div><div class="pstat-l">Постов</div></div>
    <div class="pstat"><div class="pstat-n">{{ auth()->user()->followers()->count() }}</div><div class="pstat-l">Подписчиков</div></div>
    <div class="pstat"><div class="pstat-n">{{ auth()->user()->following()->count() }}</div><div class="pstat-l">Подписок</div></div>
    <div class="pstat"><div class="pstat-n">{{ auth()->user()->posts()->sum('likes_count') }}</div><div class="pstat-l">Лайков</div></div>
    <div class="pstat"><div class="pstat-n">{{ auth()->user()->tracks()->count() }}</div><div class="pstat-l">Треков</div></div>
    <div class="pstat"><div class="pstat-n">{{ auth()->user()->videos()->count() }}</div><div class="pstat-l">Видео</div></div>
  </div>

  @forelse(auth()->user()->posts()->latest()->get() as $post)
  <div class="post">
    <div class="post-row">
      <img src="{{ auth()->user()->avatar ? asset('storage/avatars/'.auth()->user()->avatar) : asset('images/default.png') }}" class="post-av">
      <div class="post-content">
        <div class="post-meta">
          <span class="post-name">{{ auth()->user()->name ?: auth()->user()->login }}</span>
          <span class="post-dot">·</span>
          <span class="post-time">{{ $post->created_at->diffForHumans() }}</span>
        </div>
        <div class="post-body">{{ $post->body }}</div>
        @if($post->media_type === 'video' && $post->video)
          <video src="{{ asset('storage/posts/'.$post->video) }}" class="post-img" controls style="max-width:100%;border-radius:10px;margin-top:8px"></video>
        @elseif($post->image)
          <img src="{{ asset('storage/posts/'.$post->image) }}" class="post-img" onclick="openImg(this.src)">
        @endif
        <div class="post-actions">
          <span class="act" style="cursor:default;color:var(--acc2)">❤ {{ $post->likes_count }}</span>
          <span class="act" style="cursor:default;color:var(--t3)">💬 {{ $post->comments->count() }}</span>
          <span class="act-sep"></span>
          <a href="{{ route('posts.edit', $post) }}" class="act">✏</a>
          <form method="POST" action="{{ route('posts.destroy', $post) }}" style="display:inline" onsubmit="return confirm('Удалить?')">
            @csrf @method('DELETE')
            <button type="submit" class="act" style="color:var(--acc2)">✕</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  @empty
  <div class="empty"><div class="empty-ico">✍️</div><h3>Нет постов</h3><p>Напишите что-нибудь в ленте!</p></div>
  @endforelse
</div>
</div>
@endsection
