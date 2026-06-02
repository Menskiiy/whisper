@extends('layouts.app')
@section('title','Уведомления — Whisper')
@section('content')
<div class="page" style="max-width:660px">
<div class="card">
  <div class="card-head">
    <div class="icon-badge"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 01-3.46 0"/></svg></div>
    Уведомления
  </div>

  @forelse($notifications as $n)
  <div class="notif-item {{ !$n->is_read ? 'unread' : '' }}">
    @if($n->type === 'like')
      <div class="notif-icon like"><svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg></div>
    @elseif($n->type === 'track_like')
      <div class="notif-icon like"><svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/></svg></div>
    @elseif($n->type === 'follow')
      <div class="notif-icon follow"><svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg></div>
    @elseif($n->type === 'comment')
      <div class="notif-icon comment"><svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg></div>
    @else
      <div class="notif-icon comment"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg></div>
    @endif

    <img src="{{ $n->actor->avatar ? asset('storage/avatars/'.$n->actor->avatar) : asset('images/default.png') }}"
         style="width:36px;height:36px;border-radius:50%;object-fit:cover;border:1.5px solid rgba(255,255,255,0.08)">

    <div style="flex:1">
      <div style="font-size:14px;color:var(--t1)">
        <a href="/{{ $n->actor->login }}" style="font-weight:700;color:var(--t1);text-decoration:none"
           onmouseover="this.style.color='var(--acc)'" onmouseout="this.style.color='var(--t1)'">{{ $n->actor->name ?: $n->actor->login }}</a>
        @if($n->type === 'like') понравился ваш пост
        @elseif($n->type === 'track_like') лайкнул ваш трек
        @elseif($n->type === 'follow') подписался на вас
        @elseif($n->type === 'comment') прокомментировал ваш пост
        @else оставил активность на вашей странице
        @endif
      </div>
      <div style="font-size:12px;color:var(--t3);margin-top:3px">{{ $n->created_at->diffForHumans() }}</div>
    </div>

    @if(!$n->is_read)
      <div style="width:8px;height:8px;border-radius:50%;background:var(--acc);flex-shrink:0"></div>
    @endif
  </div>
  @empty
  <div class="empty">
    <div class="empty-ico">🔔</div>
    <h3>Пока тихо</h3>
    <p>Уведомления появятся когда кто-то отреагирует на ваши посты</p>
  </div>
  @endforelse

  @if($notifications->hasPages())
  <div style="padding:14px 20px;display:flex;justify-content:center">
    {{ $notifications->links() }}
  </div>
  @endif
</div>
</div>
@endsection
