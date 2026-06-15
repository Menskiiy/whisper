@extends('layouts.app')
@section('title','Поиск — Whisper')
@section('content')
<div class="page" style="max-width:700px">

  <!-- Header -->
  <div style="margin-bottom:14px;padding:20px 22px;
    background:linear-gradient(160deg,rgba(12,13,28,.95),rgba(10,11,22,.98));
    border:1px solid var(--b1);border-radius:var(--r-lg);
    display:flex;align-items:center;gap:13px">
    <div class="icon-badge" style="width:44px;height:44px;border-radius:13px;flex-shrink:0">
      <svg width="19" height="19" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
      </svg>
    </div>
    <div>
      <div style="font-family:'Syne',sans-serif;font-weight:800;font-size:19px;color:var(--t1)">Поиск людей</div>
      <div style="font-size:12.5px;color:var(--t3);margin-top:2px">Найдите друзей и интересных пользователей</div>
    </div>
  </div>

  <div class="card">
    <!-- Search box -->
    <div style="padding:18px 20px;border-bottom:1px solid var(--b1)">
      <form method="GET" action="{{ route('search') }}">
        <div class="srch-wrap">
          <span class="srch-ico">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
            </svg>
          </span>
          <input type="text" name="q" class="srch-box" value="{{ $q }}"
                 placeholder="Найти по логину или имени..." autofocus>
        </div>
      </form>
    </div>

    @if(strlen($q) === 0)
      <div class="empty" style="padding:56px 20px">
        <div class="empty-ico">🔍</div>
        <h3>Начните поиск</h3>
        <p>Введите имя или логин пользователя</p>
      </div>
    @elseif($users->isEmpty())
      <div class="empty" style="padding:56px 20px">
        <div class="empty-ico">🤔</div>
        <h3>Никого не найдено</h3>
        <p>Попробуйте другой запрос</p>
      </div>
    @else
      <div class="sec-label">
        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
        Результаты поиска · {{ $users->count() }} {{ $users->count() === 1 ? 'пользователь' : ($users->count() < 5 ? 'пользователя' : 'пользователей') }}
        <span class="sec-label-line"></span>
      </div>

      @foreach($users as $u)
      <div style="display:flex;align-items:center;gap:13px;padding:14px 20px;border-bottom:1px solid var(--b1);transition:background .15s"
           onmouseover="this.style.background='rgba(124,90,245,.03)'"
           onmouseout="this.style.background='transparent'">
        <a href="/{{ $u->login }}">
          <img src="{{ $u->avatar ? asset('storage/avatars/'.$u->avatar) : asset('images/default.png') }}"
               style="width:48px;height:48px;border-radius:50%;object-fit:cover;
                      border:2px solid var(--b2);transition:border-color .18s"
               onmouseover="this.style.borderColor='rgba(124,90,245,.5)'"
               onmouseout="this.style.borderColor='var(--b2)'">
        </a>
        <div style="flex:1;min-width:0">
          <a href="/{{ $u->login }}" style="font-family:'Syne',sans-serif;font-weight:700;font-size:15px;color:var(--t1);text-decoration:none;display:block;transition:color .18s"
             onmouseover="this.style.color='var(--acc)'" onmouseout="this.style.color='var(--t1)'">
            {{ $u->name ?: $u->login }}
          </a>
          <div style="color:var(--t3);font-size:12.5px;margin-top:2px">{{ $u->login }}</div>
          @if($u->bio)
            <div style="color:var(--t2);font-size:12.5px;margin-top:4px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
              {{ $u->bio }}
            </div>
          @endif
        </div>
        <div style="display:flex;gap:18px;text-align:center;flex-shrink:0;margin-right:4px">
          <div>
            <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:15px;color:var(--acc)">{{ $u->posts()->count() }}</div>
            <div style="font-size:10.5px;color:var(--t3);text-transform:uppercase;letter-spacing:.04em">постов</div>
          </div>
          <div>
            <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:15px;color:var(--acc2)">{{ $u->followers()->count() }}</div>
            <div style="font-size:10.5px;color:var(--t3);text-transform:uppercase;letter-spacing:.04em">подп.</div>
          </div>
        </div>
        <button onclick="followUser(this,{{ $u->id }})"
          class="btn {{ auth()->user()->isFollowing($u)?'btn-o':'btn-p' }} btn-sm" style="flex-shrink:0">
          {{ auth()->user()->isFollowing($u) ? 'Отписаться' : '+ Подписаться' }}
        </button>
      </div>
      @endforeach
    @endif
  </div>
</div>
@endsection
