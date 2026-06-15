@extends('layouts.app')
@section('title','Лента — Whisper')
@section('content')
<div class="page">
<div class="two-col">

  <!-- MAIN -->
  <div class="main-col">
    <div class="card">

      <!-- Quick-access strip -->
      <div class="quick-strip">
        <a href="{{ route('music.index') }}" class="qs-item">
          <div class="qs-item-ico" style="background:linear-gradient(135deg,rgba(124,90,245,.2),rgba(124,90,245,.08))">🎵</div>
          <span class="qs-item-lbl">Музыка</span>
        </a>
        <a href="{{ route('videos.index') }}" class="qs-item">
          <div class="qs-item-ico" style="background:linear-gradient(135deg,rgba(255,95,135,.18),rgba(255,95,135,.07))">🎬</div>
          <span class="qs-item-lbl">Видео</span>
        </a>
        <a href="{{ route('photos.index') }}" class="qs-item">
          <div class="qs-item-ico" style="background:linear-gradient(135deg,rgba(54,207,181,.18),rgba(54,207,181,.07))">📷</div>
          <span class="qs-item-lbl">Фото</span>
        </a>
        <a href="{{ route('communities.index') }}" class="qs-item">
          <div class="qs-item-ico" style="background:linear-gradient(135deg,rgba(240,160,80,.18),rgba(240,160,80,.07))">🌐</div>
          <span class="qs-item-lbl">Группы</span>
        </a>
        <a href="{{ route('search') }}" class="qs-item">
          <div class="qs-item-ico" style="background:linear-gradient(135deg,rgba(168,139,250,.18),rgba(168,139,250,.07))">🔍</div>
          <span class="qs-item-lbl">Поиск</span>
        </a>
      </div>

      <!-- Composer -->
      <div class="composer">
        <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
          @csrf
          <div class="composer-row">
            <img src="{{ auth()->user()->avatar ? asset('storage/avatars/'.auth()->user()->avatar) : asset('images/default.png') }}" class="composer-av">
            <div class="composer-body">
              <textarea name="body" rows="3" placeholder="Что у вас новенького? Поделитесь с Виспером..." maxlength="560" required
                        oninput="document.getElementById('pc').textContent=this.value.length+' / 560';document.getElementById('pc').classList.toggle('warn',this.value.length>450)">{{ old('body') }}</textarea>
              <div id="img-prev"></div>
              <div id="vid-prev"></div>
              <div class="composer-foot">
                <label class="attach-btn">
                  <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="3"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
                  Фото
                  <input type="file" name="image" accept="image/*,.gif" onchange="previewImg(this,'img-prev');document.querySelector('[name=video]').value='';">
                </label>
                <label class="attach-btn">
                  <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2"/></svg>
                  Видео
                  <input type="file" name="video" accept="video/*" onchange="previewVid(this,'vid-prev');document.querySelector('[name=image]').value='';">
                </label>
                <span class="char-c" id="pc">0 / 560</span>
                <button type="submit" class="btn btn-p btn-sm">
                  <svg width="12" height="12" fill="currentColor" viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
                  Опубликовать
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>

      <!-- Feed label -->
      <div class="sec-label">
        <svg width="13" height="13" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
        Последние публикации
        <span class="sec-label-line"></span>
      </div>

      @forelse($posts as $post)
      <div class="post" id="post-{{ $post->id }}">
        <div class="post-row">
          <a href="/{{ $post->user->login }}">
            <img src="{{ $post->user->avatar ? asset('storage/avatars/'.$post->user->avatar) : asset('images/default.png') }}" class="post-av">
          </a>
          <div class="post-content">
            <div class="post-meta">
              <a href="/{{ $post->user->login }}" class="post-name">{{ $post->user->name ?: $post->user->login }}</a>
              <a href="/{{ $post->user->login }}" class="post-login">{{ $post->user->login }}</a>
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
              <button type="button" class="act like {{ $post->isLikedBy(auth()->user()) ? 'liked':'' }}"
                      onclick="likePost(this,{{ $post->id }})">
                @if($post->isLikedBy(auth()->user()))
                  <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                @else
                  <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                @endif
                <span class="like-c">{{ $post->likes_count }}</span>
              </button>

              <button type="button" class="act comment" onclick="toggleComments({{ $post->id }})">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
                <span class="cmt-count" data-pid="{{ $post->id }}">{{ $post->comments->count() }}</span>
              </button>

              <span class="act-sep"></span>

              @if($post->user_id == auth()->id())
              <a href="{{ route('posts.edit', $post) }}" class="act" style="font-size:12px">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
              </a>
              <form method="POST" action="{{ route('posts.destroy', $post) }}" style="display:inline" onsubmit="return confirm('Удалить?')">
                @csrf @method('DELETE')
                <button type="submit" class="act" style="color:var(--acc2)">
                  <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6M10 11v6M14 11v6M9 6V4h6v2"/></svg>
                </button>
              </form>
              @endif
            </div>

            <div id="cmts-{{ $post->id }}" style="display:none">
              <div class="cmts">
                <form class="cmt-fr" onsubmit="submitComment(event,{{ $post->id }})" enctype="multipart/form-data">
                  @csrf
                  <img src="{{ auth()->user()->avatar ? asset('storage/avatars/'.auth()->user()->avatar) : asset('images/default.png') }}" class="cmt-av">
                  <textarea name="body" class="cmt-ta" rows="1" placeholder="Написать комментарий..." oninput="autoH(this)"></textarea>
                  <button type="submit" class="btn btn-p btn-xs" style="flex-shrink:0;align-self:flex-end">
                    <svg width="11" height="11" fill="currentColor" viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
                  </button>
                </form>
                <div id="cmt-list-{{ $post->id }}">
                  @foreach($post->comments as $c)
                  <div class="cmt-item" id="cmt-{{ $c->id }}">
                    <img src="{{ $c->user->avatar ? asset('storage/avatars/'.$c->user->avatar) : asset('images/default.png') }}" class="cmt-av">
                    <div class="cmt-bubble">
                      <div style="display:flex;align-items:center;justify-content:space-between;gap:8px">
                        <div>
                          <a href="/{{ $c->user->login }}" class="cmt-name">{{ $c->user->name ?: $c->user->login }}</a>
                          <span style="color:var(--t3);font-size:11px;margin-left:6px">{{ $c->created_at->diffForHumans() }}</span>
                        </div>
                        @if($c->user_id==auth()->id())
                        <button onclick="deleteCmt(this,{{ $c->id }})" style="background:none;border:none;color:var(--t3);cursor:pointer;font-size:14px;transition:color .15s" onmouseover="this.style.color='var(--acc2)'" onmouseout="this.style.color='var(--t3)'">×</button>
                        @endif
                      </div>
                      <div class="cmt-text">{{ $c->body }}</div>
                      @if($c->image)<img src="{{ asset('storage/comments/'.$c->image) }}" style="max-width:150px;border-radius:8px;margin-top:6px">@endif
                    </div>
                  </div>
                  @endforeach
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
      @empty
      <div class="empty">
        <div class="empty-ico">💬</div>
        <h3>Лента пуста</h3>
        <p>Подпишитесь на других пользователей или опубликуйте первый пост!</p>
        <div style="margin-top:18px;display:flex;gap:10px;justify-content:center;flex-wrap:wrap">
          <a href="{{ route('search') }}" class="btn btn-p btn-sm">Найти людей</a>
          <a href="{{ route('communities.index') }}" class="btn btn-o btn-sm">Открыть группы</a>
        </div>
      </div>
      @endforelse
    </div>
  </div>

  <!-- SIDEBAR -->
  <div class="side-col">

    <!-- My profile mini-card -->
    <div class="card" style="margin-bottom:14px">
      <div style="padding:18px">
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px">
          <a href="{{ route('profile') }}">
            <img src="{{ auth()->user()->avatar ? asset('storage/avatars/'.auth()->user()->avatar) : asset('images/default.png') }}"
                 style="width:52px;height:52px;border-radius:50%;object-fit:cover;border:2.5px solid var(--acc);box-shadow:0 0 0 3px rgba(124,90,245,.15)">
          </a>
          <div style="min-width:0">
            <a href="{{ route('profile') }}" style="font-family:'Syne',sans-serif;font-weight:700;font-size:15px;color:var(--t1);text-decoration:none;display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ auth()->user()->name ?: auth()->user()->login }}</a>
            <div style="font-size:12px;color:var(--t3);margin-top:2px">{{ auth()->user()->login }}</div>
          </div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1px;background:var(--b1);border-radius:12px;overflow:hidden">
          @php $u = auth()->user(); @endphp
          <div style="background:var(--s1);text-align:center;padding:10px 6px">
            <div style="font-family:'Syne',sans-serif;font-size:17px;font-weight:800;color:var(--acc)">{{ $u->posts()->count() }}</div>
            <div style="font-size:10px;color:var(--t3);margin-top:1px;text-transform:uppercase;letter-spacing:.05em">Постов</div>
          </div>
          <div style="background:var(--s1);text-align:center;padding:10px 6px">
            <div style="font-family:'Syne',sans-serif;font-size:17px;font-weight:800;color:var(--acc2)">{{ $u->followers()->count() }}</div>
            <div style="font-size:10px;color:var(--t3);margin-top:1px;text-transform:uppercase;letter-spacing:.05em">Подп.</div>
          </div>
          <div style="background:var(--s1);text-align:center;padding:10px 6px">
            <div style="font-family:'Syne',sans-serif;font-size:17px;font-weight:800;color:var(--acc3)">{{ $u->following()->count() }}</div>
            <div style="font-size:10px;color:var(--t3);margin-top:1px;text-transform:uppercase;letter-spacing:.05em">Подписок</div>
          </div>
        </div>
        <a href="{{ route('profile.edit') }}" class="btn btn-ghost btn-sm" style="width:100%;justify-content:center;margin-top:12px;border:1px solid var(--b1)!important">
          <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
          Редактировать профиль
        </a>
      </div>
    </div>

    <!-- Suggested users -->
    <div class="card">
      <div class="card-head">
        <div class="icon-badge">
          <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
        </div>
        Люди
      </div>
      <div style="padding:8px 16px 14px">
        @foreach(\App\Models\User::where('id','!=',auth()->id())->inRandomOrder()->limit(6)->get() as $u)
        <div class="who-item">
          <a href="/{{ $u->login }}" class="who-user">
            <img src="{{ $u->avatar ? asset('storage/avatars/'.$u->avatar) : asset('images/default.png') }}" class="who-av">
            <div style="min-width:0">
              <div class="who-name">{{ $u->name ?: $u->login }}</div>
              <div class="who-login">{{ $u->login }}</div>
            </div>
          </a>
          <button onclick="followUser(this,{{ $u->id }})" class="btn {{ auth()->user()->isFollowing($u)?'btn-o':'btn-p' }} btn-xs">
            {{ auth()->user()->isFollowing($u)?'Отписаться':'+ Подписаться' }}
          </button>
        </div>
        @endforeach
      </div>
    </div>

    <!-- Active communities -->
    <div class="card">
      <div class="card-head">
        <div class="icon-badge">
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 010 20"/></svg>
        </div>
        Сообщества
      </div>
      <div style="padding:8px 16px 14px">
        @foreach(\App\Models\Community::where('privacy','public')->withCount('members')->orderByDesc('members_count')->limit(5)->get() as $c)
        <div class="who-item">
          <a href="{{ route('communities.show',$c->slug) }}" class="who-user">
            @if($c->avatar)
              <img src="{{ asset('storage/communities/'.$c->avatar) }}" class="who-av" style="border-radius:9px">
            @else
              <div class="who-av" style="border-radius:9px;background:linear-gradient(135deg,rgba(124,90,245,.2),rgba(255,95,135,.1));display:flex;align-items:center;justify-content:center;font-size:16px">🌐</div>
            @endif
            <div style="min-width:0">
              <div class="who-name">{{ $c->name }}</div>
              <div class="who-login">{{ $c->members_count }} участников</div>
            </div>
          </a>
          <div class="activity-dot" title="Активное"></div>
        </div>
        @endforeach
        <div style="padding-top:10px">
          <a href="{{ route('communities.index') }}" class="btn btn-ghost btn-xs" style="width:100%;justify-content:center;border:1px solid var(--b1)!important">
            Все сообщества →
          </a>
        </div>
      </div>
    </div>

    <!-- App info -->
    <div style="padding:0 4px;font-size:11px;color:var(--t3);line-height:1.8">
      <div style="display:flex;flex-wrap:wrap;gap:6px;margin-bottom:8px">
        <a href="#" style="color:var(--t3);text-decoration:none" onmouseover="this.style.color='var(--acc)'" onmouseout="this.style.color='var(--t3)'">О нас</a>
        <span>·</span>
        <a href="#" style="color:var(--t3);text-decoration:none" onmouseover="this.style.color='var(--acc)'" onmouseout="this.style.color='var(--t3)'">Правила</a>
        <span>·</span>
        <a href="#" style="color:var(--t3);text-decoration:none" onmouseover="this.style.color='var(--acc)'" onmouseout="this.style.color='var(--t3)'">Конфиденциальность</a>
      </div>
      <div style="color:var(--t3);opacity:.6">© 2026 Whisper · Сделано с 💜</div>
    </div>
  </div>

</div>
</div>
@endsection
