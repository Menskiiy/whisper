@extends('layouts.app')
@section('title','Поиск — Whisper')
@section('content')
<div class="page" style="max-width:660px">
<div class="card">
  <div class="card-head">
    <div class="icon-badge"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg></div>
    Поиск людей
  </div>
  <div style="padding:16px 20px;border-bottom:1px solid var(--b1)">
    <form method="GET" action="{{ route('search') }}">
      <div class="search-wrap">
        <svg class="search-ico" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
        <input type="text" name="q" class="search-box" value="{{ $q }}" placeholder="Найти по логину или имени..." autofocus>
      </div>
    </form>
  </div>

  @if(strlen($q) === 0)
    <div class="empty"><div class="empty-ico">🔍</div><h3>Введите запрос</h3><p>Поиск по логину или имени пользователя</p></div>
  @elseif($users->isEmpty())
    <div class="empty"><div class="empty-ico">🤔</div><h3>Никого не найдено</h3><p>Попробуйте другой запрос</p></div>
  @else
    @foreach($users as $u)
    <div style="display:flex;align-items:center;gap:14px;padding:14px 20px;border-bottom:1px solid var(--b1)">
      <a href="/{{ $u->login }}">
        <img src="{{ $u->avatar ? asset('storage/avatars/'.$u->avatar) : asset('images/default.png') }}"
             style="width:46px;height:46px;border-radius:50%;object-fit:cover;border:2px solid rgba(255,255,255,0.08)">
      </a>
      <div style="flex:1;min-width:0">
        <a href="/{{ $u->login }}" style="font-weight:700;font-size:14.5px;color:var(--t1);text-decoration:none;display:block"
           onmouseover="this.style.color='var(--acc)'" onmouseout="this.style.color='var(--t1)'">{{ $u->name ?: $u->login }}</a>
        <div style="color:var(--t3);font-size:13px">{{ $u->login }}</div>
        @if($u->bio)<div style="color:var(--t2);font-size:12.5px;margin-top:3px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $u->bio }}</div>@endif
      </div>
      <div style="display:flex;gap:16px;text-align:center;flex-shrink:0">
        <div><div style="font-weight:700;font-size:14px;color:var(--acc)">{{ $u->posts()->count() }}</div><div style="font-size:10px;color:var(--t3)">постов</div></div>
        <div><div style="font-weight:700;font-size:14px;color:var(--acc)">{{ $u->followers()->count() }}</div><div style="font-size:10px;color:var(--t3)">подписчиков</div></div>
      </div>
      <form method="POST" action="{{ route('follow.toggle', $u) }}">
        @csrf
        @if(auth()->user()->isFollowing($u))
          <button type="submit" class="btn btn-o btn-sm">Отписаться</button>
        @else
          <button type="submit" class="btn btn-p btn-sm">Подписаться</button>
        @endif
      </form>
    </div>
    @endforeach
    <div style="padding:12px 20px;color:var(--t3);font-size:12.5px;text-align:center">Найдено: {{ $users->count() }}</div>
  @endif
</div>
</div>
@endsection
