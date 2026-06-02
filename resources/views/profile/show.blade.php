@extends('layouts.app')
@section('title','{{ $user->login }} — Whisper')
@section('content')
<div class="page" style="max-width:740px">
<div class="card">
  <!-- Баннер -->
  <div class="profile-banner" style="height:220px;position:relative;overflow:hidden;">
    <div class="pbn-inner" style="height:100%;width:100%;">
      @if($user->banner)
        <img src="{{ asset('storage/banners/'.$user->banner) }}" style="width:100%;height:100%;object-fit:cover;object-position:center;">
      @endif
    </div>
  </div>

  <!-- Аватарка и кнопки -->
  <div class="p-info-row" style="display:flex;justify-content:space-between;align-items:flex-start;padding:0 20px 16px;">
    <div class="p-av-wrap" style="width:144px;height:144px;margin-top:-72px;flex-shrink:0;position:relative;z-index:2;">
      <img src="{{ $user->avatar ? asset('storage/avatars/'.$user->avatar) : asset('images/default.png') }}"
           class="p-av"
           style="width:100%;height:100%;object-fit:cover;box-shadow:0 0 0 2.5px {{ $user->accentColor() }};">
    </div>

    @auth
    @if(auth()->id() !== $user->id)
    <div style="display:flex;gap:9px;align-items:center;padding-top:20px;">
      <a href="{{ route('messages.show', $user) }}" class="btn btn-ghost btn-sm">💬 Написать</a>
      <button id="follow-btn" onclick="followUser(this,{{ $user->id }})"
        class="btn {{ $isFollowing?'btn-o':'btn-p' }} btn-sm">
        {{ $isFollowing ? 'Отписаться' : 'Подписаться' }}
      </button>
    </div>
    @endif
    @endauth
  </div>

  <div style="padding:2px 20px 16px">
    <div class="p-name">{{ $user->name ?: $user->login }}</div>
    <div class="p-login">{{ $user->login }}
      @if($user->is_private)
        <span class="tag" style="font-size:10px;padding:1px 7px;margin-left:7px">🔒 закрытый</span>
      @endif
    </div>
    @if($user->status)
      <div class="p-status">✦ {{ $user->status }}</div>
    @endif
    @if($canView && $user->bio)
      <p class="p-bio">{{ $user->bio }}</p>
    @endif
    @if($canView)
    <div class="p-meta">
      @if($user->location)
        <span class="p-meta-i">
          <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
          {{ $user->location }}
        </span>
      @endif
      @if($user->birthday)
        <span class="p-meta-i">
          <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
          {{ $user->birthday->format('d.m.Y') }}
        </span>
      @endif
      @if($user->website)
        <span class="p-meta-i">🌐 <a href="{{ $user->website }}" target="_blank">{{ parse_url($user->website, PHP_URL_HOST) }}</a></span>
      @endif
      @if($user->telegram)
        <span class="p-meta-i">✈ <a href="https://t.me/{{ $user->telegram }}" target="_blank">@{{ $user->telegram }}</a></span>
      @endif
      @if($user->vk)
        <span class="p-meta-i">🔵 <a href="https://vk.com/{{ $user->vk }}" target="_blank">{{ $user->vk }}</a></span>
      @endif
      @if($user->instagram)
        <span class="p-meta-i">📷 <a href="https://instagram.com/{{ $user->instagram }}" target="_blank">@{{ $user->instagram }}</a></span>
      @endif
    </div>
    @endif
  </div>

  <div class="pstats">
    <div class="pstat"><div class="pstat-n">{{ $user->posts()->count() }}</div><div class="pstat-l">Постов</div></div>
    <div class="pstat"><div class="pstat-n followers-count" data-uid="{{ $user->id }}">{{ $user->followers()->count() }}</div><div class="pstat-l">Подписчиков</div></div>
    <div class="pstat"><div class="pstat-n">{{ $user->following()->count() }}</div><div class="pstat-l">Подписок</div></div>
    <div class="pstat"><div class="pstat-n">{{ $user->posts()->sum('likes_count') }}</div><div class="pstat-l">Лайков</div></div>
    <div class="pstat"><div class="pstat-n">{{ $user->tracks()->count() }}</div><div class="pstat-l">Треков</div></div>
    <div class="pstat"><div class="pstat-n">{{ $user->videos()->count() }}</div><div class="pstat-l">Видео</div></div>
  </div>

  @if(!$canView)
  <div class="empty"><div class="empty-ico">🔒</div><h3>Закрытый профиль</h3><p>Подпишитесь, чтобы увидеть посты</p></div>
  @else
  @forelse($posts as $post)
  <div class="post">
    <div class="post-row">
      <img src="{{ $user->avatar ? asset('storage/avatars/'.$user->avatar) : asset('images/default.png') }}" class="post-av">
      <div class="post-content">
        <div class="post-meta">
          <span class="post-name">{{ $user->name ?: $user->login }}</span>
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
          @auth
          <button type="button" class="act like {{ $post->isLikedBy(auth()->user()) ? 'liked':'' }}"
                  onclick="likePost(this,{{ $post->id }})">
            @if($post->isLikedBy(auth()->user()))
              <svg width="13" height="13" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41 .81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
            @else
              <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
            @endif
            <span class="like-c">{{ $post->likes_count }}</span>
          </button>
          @endauth
        </div>
      </div>
    </div>
  </div>
  @empty
  <div class="empty"><div class="empty-ico">📝</div><h3>Нет постов</h3></div>
  @endforelse
  @endif
</div>
</div>
<div id="lb" onclick="closeLb()"><img id="lb-img" src=""></div>
@endsection
