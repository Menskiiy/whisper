@extends('layouts.app')
@section('title','{{ $user->login }} — Whisper')
@section('content')
<div class="page" style="max-width:780px">
<div class="card">

  <!-- Banner -->
  <div class="profile-banner" style="height:240px">
    <div class="pbn-inner">
      @if($user->banner)
        <img src="{{ asset('storage/banners/'.$user->banner) }}">
      @endif
      <!-- Декоративные элементы поверх баннера -->
      <div style="position:absolute;inset:0;z-index:2;
        background:linear-gradient(to bottom,transparent 30%,rgba(10,11,22,.85) 100%),
        radial-gradient(ellipse 40% 60% at 30% 40%,rgba(124,90,245,.18),transparent)">
      </div>
    </div>
  </div>

  <!-- Avatar + action buttons -->
  <div style="padding:0 22px 0;display:flex;justify-content:space-between;align-items:flex-end;margin-top:-56px;position:relative;z-index:3">
    <div>
      <div style="position:relative;display:inline-block">
        <img src="{{ $user->avatar ? asset('storage/avatars/'.$user->avatar) : asset('images/default.png') }}"
             style="width:110px;height:110px;border-radius:50%;object-fit:cover;
                    border:4px solid var(--bg);
                    box-shadow:0 0 0 3px {{ $user->accentColor() ?? 'var(--acc)' }},0 10px 40px rgba(0,0,0,.6)">
        @if($user->is_private)
          <div style="position:absolute;bottom:4px;right:4px;width:22px;height:22px;
            background:var(--s1);border-radius:50%;border:2px solid var(--bg);
            display:flex;align-items:center;justify-content:center;font-size:11px">🔒</div>
        @endif
      </div>
    </div>
    @auth
    <div style="display:flex;gap:9px;align-items:center;padding-bottom:12px">
      @if(auth()->id() === $user->id)
        <a href="{{ route('profile.edit') }}" class="btn btn-ghost btn-sm" style="border:1px solid var(--b1)!important">
          <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
          Редактировать
        </a>
      @else
        <a href="{{ route('messages.show', $user) }}" class="btn btn-ghost btn-sm" style="border:1px solid var(--b1)!important">
          <svg width="13" height="13" fill="currentColor" viewBox="0 0 24 24"><path d="M20 2H4a2 2 0 00-2 2v18l4-4h14a2 2 0 002-2V4a2 2 0 00-2-2z"/></svg>
          Написать
        </a>
        <button id="follow-btn" onclick="followUser(this,{{ $user->id }})"
          class="btn {{ $isFollowing?'btn-o':'btn-p' }} btn-sm">
          @if($isFollowing)
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
            Отписаться
          @else
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
            Подписаться
          @endif
        </button>
      @endif
    </div>
    @endauth
  </div>

  <!-- User info -->
  <div style="padding:14px 22px 0">
    <div style="display:flex;align-items:flex-start;gap:10px;flex-wrap:wrap">
      <div>
        <div class="p-name">{{ $user->name ?: $user->login }}</div>
        <div class="p-login">{{ $user->login }}</div>
      </div>
      @if($user->status)
        <div class="p-status" style="margin-top:6px">{{ $user->status }}</div>
      @endif
    </div>

    @if($canView && $user->bio)
      <p class="p-bio">{{ $user->bio }}</p>
    @endif

    @if($canView)
    <div class="p-meta" style="margin-top:10px">
      @if($user->location)
        <span class="p-meta-i">
          <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
          {{ $user->location }}
        </span>
      @endif
      @if($user->birthday)
        <span class="p-meta-i">
          <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
          {{ $user->birthday->format('d.m.Y') }}
        </span>
      @endif
      @if($user->website)
        <span class="p-meta-i">
          <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 010 20"/></svg>
          <a href="{{ $user->website }}" target="_blank">{{ parse_url($user->website, PHP_URL_HOST) }}</a>
        </span>
      @endif
      @if($user->telegram)
        <span class="p-meta-i">
          <svg width="13" height="13" fill="currentColor" viewBox="0 0 24 24"><path d="M22 2L11 13m11-11L15 22l-4-9-9-4 20-7z"/></svg>
          <a href="https://t.me/{{ $user->telegram }}" target="_blank">{{ $user->telegram }}</a>
        </span>
      @endif
    </div>
    @endif
  </div>

  <!-- Stats row -->
  <div class="pstats" style="margin-top:18px">
    <div class="pstat">
      <div class="pstat-n">{{ $user->posts()->count() }}</div>
      <div class="pstat-l">Постов</div>
    </div>
    <div class="pstat">
      <div class="pstat-n followers-count" data-uid="{{ $user->id }}" style="background:linear-gradient(135deg,var(--acc2),#ff8fae);-webkit-background-clip:text;-webkit-text-fill-color:transparent">{{ $user->followers()->count() }}</div>
      <div class="pstat-l">Подписчиков</div>
    </div>
    <div class="pstat">
      <div class="pstat-n" style="background:linear-gradient(135deg,var(--acc3),#6aecda);-webkit-background-clip:text;-webkit-text-fill-color:transparent">{{ $user->following()->count() }}</div>
      <div class="pstat-l">Подписок</div>
    </div>
    <div class="pstat">
      <div class="pstat-n" style="background:linear-gradient(135deg,var(--acc4),#f6c47e);-webkit-background-clip:text;-webkit-text-fill-color:transparent">{{ $user->posts()->sum('likes_count') }}</div>
      <div class="pstat-l">Лайков</div>
    </div>
    <div class="pstat">
      <div class="pstat-n" style="background:linear-gradient(135deg,#a78bfa,#c4b5fd);-webkit-background-clip:text;-webkit-text-fill-color:transparent">{{ $user->tracks()->count() }}</div>
      <div class="pstat-l">Треков</div>
    </div>
    <div class="pstat">
      <div class="pstat-n" style="background:linear-gradient(135deg,#5dd6f5,#38bdf8);-webkit-background-clip:text;-webkit-text-fill-color:transparent">{{ $user->videos()->count() }}</div>
      <div class="pstat-l">Видео</div>
    </div>
  </div>

  <!-- Content section label -->
  <div class="sec-label" style="margin-top:4px">
    <svg width="13" height="13" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
    Публикации
    <span class="sec-label-line"></span>
  </div>

  @if(!$canView)
  <div class="empty" style="padding:52px 20px">
    <div class="empty-ico">🔒</div>
    <h3>Закрытый профиль</h3>
    <p>Подпишитесь, чтобы увидеть посты этого пользователя</p>
    @auth
    @if(auth()->id() !== $user->id)
    <button onclick="followUser(document.getElementById('follow-btn'),{{ $user->id }})"
      class="btn btn-p btn-sm" style="margin-top:18px">
      Подписаться
    </button>
    @endif
    @endauth
  </div>
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
          <video src="{{ asset('storage/posts/'.$post->video) }}" class="post-img" controls style="max-width:100%;border-radius:12px;margin-top:10px"></video>
        @elseif($post->image)
          <img src="{{ asset('storage/posts/'.$post->image) }}" class="post-img" onclick="openImg(this.src)">
        @endif
        <div class="post-actions">
          @auth
          <button type="button" class="act like {{ $post->isLikedBy(auth()->user()) ? 'liked':'' }}"
                  onclick="likePost(this,{{ $post->id }})">
            @if($post->isLikedBy(auth()->user()))
              <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
            @else
              <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
            @endif
            <span class="like-c">{{ $post->likes_count }}</span>
          </button>
          @endauth

          @if(auth()->id() === $user->id)
          <span class="act-sep"></span>
          <a href="{{ route('posts.edit', $post) }}" class="act">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
          </a>
          <form method="POST" action="{{ route('posts.destroy', $post) }}" style="display:inline" onsubmit="return confirm('Удалить?')">
            @csrf @method('DELETE')
            <button type="submit" class="act" style="color:var(--acc2)">
              <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/></svg>
            </button>
          </form>
          @endif
        </div>
      </div>
    </div>
  </div>
  @empty
  <div class="empty" style="padding:52px 20px">
    <div class="empty-ico">📝</div>
    <h3>Нет постов</h3>
    <p>{{ auth()->id() === $user->id ? 'Опубликуйте первый пост!' : 'Пользователь ещё ничего не публиковал' }}</p>
  </div>
  @endforelse
  @endif
</div>
</div>
<div id="lb" onclick="closeLb()"><img id="lb-img" src=""></div>
@endsection
