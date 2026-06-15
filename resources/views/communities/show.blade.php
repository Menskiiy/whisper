@extends('layouts.app')
@section('title','{{ $community->name }} — Whisper')
@section('head')
<style>
/* ── Community show · mobile-first ── */
.c-show-layout{display:flex;gap:0}
.c-posts-col{flex:1;min-width:0}
.c-side{
  width:240px;flex-shrink:0;
  border-left:1px solid var(--b1);padding:18px;
}

/* Header row */
.c-hdr-actions{display:flex;gap:8px;flex-shrink:0;flex-wrap:wrap;justify-content:flex-end}

/* Banner */
.c-banner-wrap{height:190px;overflow:hidden;position:relative}
.c-banner-wrap img{width:100%;height:100%;object-fit:cover}
.c-banner-wrap .fallback{
  width:100%;height:100%;
  background:linear-gradient(135deg,{{ $community->accent_color ?? '#7c5af5' }}44,{{ $community->accent_color ?? '#7c5af5' }}11);
  position:relative;overflow:hidden;
}
.c-banner-wrap .fallback::before{
  content:'';position:absolute;inset:0;
  background:repeating-linear-gradient(45deg,rgba(255,255,255,.03) 0,rgba(255,255,255,.03) 1px,transparent 1px,transparent 44px);
}

/* Info row */
.c-info-row{
  padding:14px 22px 18px;
  display:flex;justify-content:space-between;align-items:flex-end;gap:12px;
}
.c-avatar-name{margin-top:-46px;display:flex;align-items:flex-end;gap:14px;flex:1;min-width:0}
.c-ava{
  width:82px;height:82px;border-radius:16px;object-fit:cover;
  border:3px solid var(--bg);
  box-shadow:0 0 0 2.5px {{ $community->accent_color ?? 'var(--acc)' }};
  flex-shrink:0;
}
.c-ava-placeholder{
  width:82px;height:82px;border-radius:16px;flex-shrink:0;
  background:{{ $community->accent_color ?? 'var(--acc)' }}22;
  border:3px solid var(--bg);
  box-shadow:0 0 0 2.5px {{ $community->accent_color ?? 'var(--acc)' }};
  display:flex;align-items:center;justify-content:center;font-size:32px;
}
.c-name-block{padding-bottom:4px;min-width:0}
.c-title{font-family:'Syne',sans-serif;font-size:20px;font-weight:800;color:var(--t1);line-height:1.25;word-break:break-word}
.c-meta{font-size:12.5px;color:var(--t3);margin-top:4px;display:flex;flex-wrap:wrap;gap:6px;align-items:center}
.c-meta-dot{opacity:.4}

/* Side panel inner cards */
.c-side-block{margin-bottom:18px}
.c-side-label{
  font-family:'Syne',sans-serif;font-size:12.5px;font-weight:700;
  color:var(--t1);margin-bottom:10px;
  display:flex;align-items:center;gap:7px;
}
.c-rules-text{font-size:13px;color:var(--t2);line-height:1.65;white-space:pre-line;word-break:break-word}

/* Mobile toggle for sidebar */
.c-side-toggle{
  display:none;
  margin:0 18px 14px;
  padding:10px 16px;border:1.5px solid rgba(124,90,245,.22);
  border-radius:12px;background:rgba(124,90,245,.06);
  color:#c4a8ff;font-size:13px;font-weight:600;font-family:inherit;
  cursor:pointer;width:calc(100% - 36px);text-align:left;
  transition:all .2s;
}
.c-side-toggle:hover{background:rgba(124,90,245,.12);border-color:rgba(124,90,245,.4)}

/* ─── Responsive ─── */
@media(max-width:780px){
  .c-show-layout{flex-direction:column}
  .c-side{
    width:100%;border-left:none;border-top:1px solid var(--b1);
    padding:16px 18px;display:none;
  }
  .c-side.open{display:block}
  .c-side-toggle{display:block}
  .c-info-row{flex-direction:column;align-items:flex-start;gap:14px}
  .c-hdr-actions{width:100%;justify-content:flex-start}
  .c-avatar-name{margin-top:-40px}
  .c-banner-wrap{height:140px}
  .c-ava,.c-ava-placeholder{width:68px;height:68px;border-radius:13px}
  .c-title{font-size:17px}
}
@media(max-width:480px){
  .c-info-row{padding:12px 14px 14px}
  .c-hdr-actions .btn{font-size:12px;padding:7px 12px}
  .c-banner-wrap{height:120px}
}
</style>
@endsection
@section('content')
<div class="page" style="max-width:900px">
<div class="card">

  {{-- ── Banner ── --}}
  <div class="c-banner-wrap">
    @if($community->banner)
      <img src="{{ asset('storage/communities/'.$community->banner) }}" alt="">
    @else
      <div class="fallback"></div>
    @endif
  </div>

  {{-- ── Info row ── --}}
  <div class="c-info-row">
    <div class="c-avatar-name">
      @if($community->avatar)
        <img src="{{ asset('storage/communities/'.$community->avatar) }}" class="c-ava" alt="">
      @else
        <div class="c-ava-placeholder">🌐</div>
      @endif
      <div class="c-name-block">
        <div class="c-title">{{ $community->name }}</div>
        <div class="c-meta">
          <span>👥 {{ $community->members_count }} участников</span>
          <span class="c-meta-dot">·</span>
          @if($community->privacy === 'private')
            <span>🔒 Закрытое</span>
          @else
            <span>🌐 Публичное</span>
          @endif
          @if($community->category)
            <span class="c-meta-dot">·</span>
            <span class="tag" style="font-size:11px;padding:2px 9px">{{ $community->category }}</span>
          @endif
        </div>
      </div>
    </div>

    <div class="c-hdr-actions">
      @auth
        @if($community->isAdmin(auth()->user()))
          <a href="{{ route('communities.edit', $community) }}" class="btn btn-ghost btn-sm" style="border:1px solid var(--b1)!important">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/></svg>
            Настройки
          </a>
          <a href="{{ route('communities.members', $community) }}" class="btn btn-ghost btn-sm" style="border:1px solid var(--b1)!important">
            <svg width="13" height="13" fill="currentColor" viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
            Участники
          </a>
        @endif
        <button id="join-btn" onclick="toggleJoin(this,{{ $community->id }})"
          class="btn {{ $isMember ? 'btn-o' : 'btn-p' }} btn-sm">
          @if($isMember)
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
            Вступил
          @else
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
            Вступить
          @endif
        </button>
      @endauth
    </div>
  </div>

  @if($community->description)
    <div style="padding:0 22px 14px;font-size:14px;color:var(--t2);line-height:1.65;border-bottom:1px solid var(--b1)">
      {{ $community->description }}
    </div>
  @endif

  {{-- ── Mobile sidebar toggle ── --}}
  <button class="c-side-toggle" onclick="toggleSide(this)">
    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:inline;vertical-align:-2px;margin-right:6px"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
    Правила и участники
    <span id="side-arrow" style="float:right;transition:transform .2s">▼</span>
  </button>

  {{-- ── Two-column layout ── --}}
  <div class="c-show-layout">

    {{-- Posts column --}}
    <div class="c-posts-col">
      @if($isMember || $community->privacy === 'public')
      <div class="composer" style="border-bottom:1px solid var(--b1)">
        <form method="POST" action="{{ route('communities.posts.store', $community) }}" enctype="multipart/form-data">
          @csrf
          <div class="composer-row">
            <img src="{{ auth()->user()->avatar ? asset('storage/avatars/'.auth()->user()->avatar) : asset('images/default.png') }}" class="composer-av">
            <div class="composer-body">
              <textarea name="body" rows="2" placeholder="Написать в {{ $community->name }}..." maxlength="2000" required></textarea>
              <div id="cpost-prev"></div>
              <div class="composer-foot">
                <label class="attach-btn">
                  <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="3"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
                  Фото
                  <input type="file" name="image" accept="image/*" onchange="previewImg(this,'cpost-prev')">
                </label>
                <button type="submit" class="btn btn-p btn-sm"
                  style="background:linear-gradient(135deg,{{ $community->accent_color ?? 'var(--acc)' }},{{ $community->accent_color ?? '#9d6bf0' }})">
                  <svg width="12" height="12" fill="currentColor" viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
                  Опубликовать
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
      @endif

      @forelse($posts as $post)
      <div class="post" style="{{ $post->is_pinned ? 'border-left:3px solid '.($community->accent_color ?? 'var(--acc)').';background:rgba(124,90,245,.03)' : '' }}">
        @if($post->is_pinned)
          <div style="font-size:11px;color:{{ $community->accent_color ?? 'var(--acc)' }};margin-bottom:6px;display:flex;align-items:center;gap:4px;font-weight:600">
            📌 Закреплено
          </div>
        @endif
        <div class="post-row">
          <a href="/{{ $post->user->login }}">
            <img src="{{ $post->user->avatar ? asset('storage/avatars/'.$post->user->avatar) : asset('images/default.png') }}" class="post-av">
          </a>
          <div class="post-content">
            <div class="post-meta">
              <a href="/{{ $post->user->login }}" class="post-name">{{ $post->user->name ?: $post->user->login }}</a>
              @php $mr = $community->getMemberRole($post->user) @endphp
              @if($mr && $mr !== 'member')
                <span class="tag" style="font-size:10px;padding:1px 6px;color:{{ $community->accent_color ?? 'var(--acc)' }}">{{ $mr }}</span>
              @endif
              <span class="post-dot">·</span>
              <span class="post-time">{{ $post->created_at->diffForHumans() }}</span>
            </div>
            <div class="post-body">{{ $post->body }}</div>
            @if($post->image)
              <img src="{{ asset('storage/community_posts/'.$post->image) }}" class="post-img" onclick="openImg(this.src)">
            @endif
            <div class="post-actions">
              <button type="button" class="act like {{ $post->isLikedBy(auth()->user()) ? 'liked' : '' }}"
                      onclick="likeCPost(this,{{ $post->id }})">
                @if($post->isLikedBy(auth()->user()))
                  <svg width="13" height="13" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                @else
                  <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                @endif
                <span class="like-c">{{ $post->likes_count }}</span>
              </button>
              <span class="act-sep"></span>
              @if($community->canManage(auth()->user()) || $post->user_id == auth()->id())
                @if($community->canManage(auth()->user()))
                  <form method="POST" action="{{ route('communities.posts.pin', $post) }}" style="display:inline">
                    @csrf
                    <button type="submit" class="act" title="{{ $post->is_pinned ? 'Открепить' : 'Закрепить' }}" style="font-size:14px">
                      {{ $post->is_pinned ? '📌' : '📍' }}
                    </button>
                  </form>
                @endif
                <form method="POST" action="{{ route('communities.posts.delete', $post) }}" style="display:inline" onsubmit="return confirm('Удалить пост?')">
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
          <h3>Записей ещё нет</h3>
          <p>{{ $isMember ? 'Будьте первым, кто напишет!' : 'Вступите в сообщество, чтобы писать' }}</p>
        </div>
      @endforelse

      @if($posts->hasPages())
        <div style="padding:14px 20px">{{ $posts->links() }}</div>
      @endif
    </div>

    {{-- Sidebar --}}
    <div class="c-side" id="c-side">

      @if($community->rules)
        <div class="c-side-block">
          <div class="c-side-label">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/>
            </svg>
            Правила сообщества
          </div>
          <div class="c-rules-text">{{ $community->rules }}</div>
        </div>
        <div class="divider"></div>
      @endif

      @if($topMembers->isNotEmpty())
        <div class="c-side-block">
          <div class="c-side-label">
            <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24">
              <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
            </svg>
            Участники ({{ $community->members_count }})
          </div>
          @foreach($topMembers as $m)
            <div class="who-item">
              <a href="/{{ $m->user->login }}" class="who-user">
                <img src="{{ $m->user->avatar ? asset('storage/avatars/'.$m->user->avatar) : asset('images/default.png') }}" class="who-av">
                <div style="min-width:0">
                  <div class="who-name">{{ $m->user->name ?: $m->user->login }}</div>
                  <div class="who-login">{{ $m->role }}</div>
                </div>
              </a>
            </div>
          @endforeach
        </div>
      @endif

      {{-- Stats mini block --}}
      <div style="background:rgba(124,90,245,.05);border:1px solid rgba(124,90,245,.12);border-radius:13px;padding:14px 16px">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
          <div style="text-align:center">
            <div style="font-family:'Syne',sans-serif;font-size:20px;font-weight:800;color:{{ $community->accent_color ?? 'var(--acc)' }}">{{ $community->members_count }}</div>
            <div style="font-size:10.5px;color:var(--t3);text-transform:uppercase;letter-spacing:.05em;margin-top:2px">Участников</div>
          </div>
          <div style="text-align:center">
            <div style="font-family:'Syne',sans-serif;font-size:20px;font-weight:800;background:linear-gradient(135deg,var(--acc2),#ff8fae);-webkit-background-clip:text;-webkit-text-fill-color:transparent">{{ $posts->total() ?? $posts->count() }}</div>
            <div style="font-size:10.5px;color:var(--t3);text-transform:uppercase;letter-spacing:.05em;margin-top:2px">Записей</div>
          </div>
        </div>
      </div>
    </div>

  </div>{{-- .c-show-layout --}}
</div>{{-- .card --}}
</div>{{-- .page --}}

<script>
function toggleJoin(btn, cid) {
  post('/communities/' + cid + '/join').then(r => r.json()).then(d => {
    const svg_check = '<svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>';
    const svg_plus = '<svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>';
    btn.innerHTML = d.joined
      ? svg_check + ' Вступил'
      : svg_plus + ' Вступить';
    btn.classList.toggle('btn-o', d.joined);
    btn.classList.toggle('btn-p', !d.joined);
  });
}
function likeCPost(btn, pid) {
  const c = btn.querySelector('.like-c');
  post('/community-posts/' + pid + '/like').then(r => r.json()).then(d => {
    c.textContent = d.likes_count;
    btn.classList.toggle('liked', d.liked);
  });
}
function toggleSide(btn) {
  const side = document.getElementById('c-side');
  const arrow = document.getElementById('side-arrow');
  const open = side.classList.toggle('open');
  arrow.style.transform = open ? 'rotate(180deg)' : '';
}
</script>
@endsection
