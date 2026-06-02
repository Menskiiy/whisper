@extends('layouts.app')
@section('title','{{ $community->name }} — Whisper')
@section('content')
<div class="page" style="max-width:900px">
<div class="card">
  <!-- Banner -->
  <div style="height:150px;overflow:hidden">
    @if($community->banner)<img src="{{ asset('storage/communities/'.$community->banner) }}" style="width:100%;height:100%;object-fit:cover">
    @else<div style="width:100%;height:100%;background:linear-gradient(135deg,{{ $community->accent_color ?? '#7c5af5' }}44,{{ $community->accent_color ?? '#7c5af5' }}11)"></div>@endif
  </div>

  <!-- Info row -->
  <div style="padding:14px 22px 18px;display:flex;justify-content:space-between;align-items:flex-end">
    <div style="margin-top:-46px;display:flex;align-items:flex-end;gap:16px">
      @if($community->avatar)
        <img src="{{ asset('storage/communities/'.$community->avatar) }}" style="width:82px;height:82px;border-radius:16px;object-fit:cover;border:3px solid var(--bg);box-shadow:0 0 0 2.5px {{ $community->accent_color ?? '#7c5af5' }}">
      @else
        <div style="width:82px;height:82px;border-radius:16px;background:{{ $community->accent_color ?? '#7c5af5' }}22;border:3px solid var(--bg);box-shadow:0 0 0 2.5px {{ $community->accent_color ?? '#7c5af5' }};display:flex;align-items:center;justify-content:center;font-size:32px">🌐</div>
      @endif
      <div style="padding-bottom:4px">
        <h1 style="font-family:'Syne',sans-serif;font-size:22px;font-weight:800;color:var(--t1)">{{ $community->name }}</h1>
        <div style="font-size:13px;color:var(--t3)">{{ $community->members_count }} участников · @if($community->privacy=='private')🔒 Закрытое@else🌐 Публичное@endif @if($community->category)· {{ $community->category }}@endif</div>
      </div>
    </div>
    <div style="display:flex;gap:9px;flex-shrink:0">
      @auth
        @if($community->isAdmin(auth()->user()))
          <a href="{{ route('communities.edit', $community) }}" class="btn btn-ghost btn-sm">⚙ Настройки</a>
          <a href="{{ route('communities.members', $community) }}" class="btn btn-ghost btn-sm">👥 Участники</a>
        @endif
        <button id="join-btn" onclick="toggleJoin(this,{{ $community->id }})"
          class="btn {{ $isMember?'btn-o':'btn-p' }} btn-sm">
          {{ $isMember ? 'Вступил' : 'Вступить' }}
        </button>
      @endauth
    </div>
  </div>

  @if($community->description)
    <div style="padding:0 22px 14px;font-size:14px;color:var(--t2);line-height:1.6;border-bottom:1px solid var(--b1)">{{ $community->description }}</div>
  @endif

  <div style="display:flex;gap:0">
    <!-- Posts column -->
    <div style="flex:1;min-width:0">
      @if($isMember || $community->privacy==='public')
      <!-- Post composer -->
      <div class="composer" style="border-bottom:1px solid var(--b1)">
        <form method="POST" action="{{ route('communities.posts.store', $community) }}" enctype="multipart/form-data">
          @csrf
          <div class="composer-row">
            <img src="{{ auth()->user()->avatar ? asset('storage/avatars/'.auth()->user()->avatar) : asset('images/default.png') }}" class="composer-av">
            <div class="composer-body">
              <textarea name="body" rows="2" placeholder="Написать в сообщество..." maxlength="2000"></textarea>
              <div id="cpost-prev"></div>
              <div class="composer-foot">
                <label class="attach-btn">📷 Фото<input type="file" name="image" accept="image/*" onchange="previewImg(this,'cpost-prev')"></label>
                <button type="submit" class="btn btn-p btn-sm" style="background:linear-gradient(135deg,{{ $community->accent_color ?? 'var(--acc)' }},{{ $community->accent_color ?? 'var(--acc)' }}cc)">Опубликовать</button>
              </div>
            </div>
          </div>
        </form>
      </div>
      @endif

      @forelse($posts as $post)
      <div class="post" style="{{ $post->is_pinned ? 'border-left:3px solid '.($community->accent_color??'var(--acc)').';background:rgba(124,90,245,.03)':'' }}">
        @if($post->is_pinned)<div style="font-size:11px;color:var(--acc);margin-bottom:6px;display:flex;align-items:center;gap:4px">📌 Закреплено</div>@endif
        <div class="post-row">
          <a href="/{{ $post->user->login }}">
            <img src="{{ $post->user->avatar ? asset('storage/avatars/'.$post->user->avatar) : asset('images/default.png') }}" class="post-av">
          </a>
          <div class="post-content">
            <div class="post-meta">
              <a href="/{{ $post->user->login }}" class="post-name">{{ $post->user->name ?: $post->user->login }}</a>
              @php $mr = $community->getMemberRole($post->user) @endphp
              @if($mr && $mr !== 'member')<span class="tag" style="font-size:10px;padding:1px 6px;color:{{ $community->accent_color??'var(--acc)' }}">{{ $mr }}</span>@endif
              <span class="post-dot">·</span><span class="post-time">{{ $post->created_at->diffForHumans() }}</span>
            </div>
            <div class="post-body">{{ $post->body }}</div>
            @if($post->image)<img src="{{ asset('storage/community_posts/'.$post->image) }}" class="post-img" onclick="openImg(this.src)">@endif
            <div class="post-actions">
              <button type="button" class="act like {{ $post->isLikedBy(auth()->user()) ? 'liked':'' }}"
                      onclick="likeCPost(this,{{ $post->id }})">
                @if($post->isLikedBy(auth()->user()))<svg width="13" height="13" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                @else<svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>@endif
                <span class="like-c">{{ $post->likes_count }}</span>
              </button>
              <span class="act-sep"></span>
              @if($community->canManage(auth()->user()) || $post->user_id==auth()->id())
                @if($community->canManage(auth()->user()))
                <form method="POST" action="{{ route('communities.posts.pin', $post) }}" style="display:inline">@csrf<button type="submit" class="act btn-xs" title="{{ $post->is_pinned?'Открепить':'Закрепить' }}">{{ $post->is_pinned?'📌':'📍' }}</button></form>
                @endif
                <form method="POST" action="{{ route('communities.posts.delete', $post) }}" style="display:inline" onsubmit="return confirm('Удалить?')">@csrf @method('DELETE')<button type="submit" class="act" style="color:var(--acc2)">✕</button></form>
              @endif
            </div>
          </div>
        </div>
      </div>
      @empty
      <div class="empty"><div class="empty-ico">📝</div><h3>Нет записей</h3><p>Будьте первым!</p></div>
      @endforelse
      @if($posts->hasPages())<div style="padding:12px 18px">{{ $posts->links() }}</div>@endif
    </div>

    <!-- Right sidebar -->
    <div style="width:240px;flex-shrink:0;border-left:1px solid var(--b1);padding:16px">
      @if($community->rules)
      <div style="margin-bottom:16px">
        <div style="font-family:'Syne',sans-serif;font-size:13px;font-weight:700;margin-bottom:8px;color:var(--t1)">📋 Правила</div>
        <div style="font-size:12.5px;color:var(--t2);line-height:1.6;white-space:pre-line">{{ $community->rules }}</div>
      </div>
      @endif
      @if($topMembers->isNotEmpty())
      <div>
        <div style="font-family:'Syne',sans-serif;font-size:13px;font-weight:700;margin-bottom:8px;color:var(--t1)">👥 Участники</div>
        @foreach($topMembers as $m)
        <div class="who-item">
          <a href="/{{ $m->user->login }}" class="who-user">
            <img src="{{ $m->user->avatar ? asset('storage/avatars/'.$m->user->avatar) : asset('images/default.png') }}" class="who-av">
            <div style="min-width:0"><div class="who-name">{{ $m->user->name ?: $m->user->login }}</div><div class="who-login">{{ $m->role }}</div></div>
          </a>
        </div>
        @endforeach
      </div>
      @endif
    </div>
  </div>
</div>
</div>
<script>
function toggleJoin(btn, cid) {
  post('/communities/'+cid+'/join').then(r=>r.json()).then(d=>{
    btn.textContent = d.joined ? 'Вступил' : 'Вступить';
    btn.classList.toggle('btn-o', d.joined);
    btn.classList.toggle('btn-p', !d.joined);
  });
}
function likeCPost(btn, pid) {
  const c = btn.querySelector('.like-c');
  post('/community-posts/'+pid+'/like').then(r=>r.json()).then(d=>{
    c.textContent = d.likes_count;
    btn.classList.toggle('liked', d.liked);
  });
}
</script>
@endsection
