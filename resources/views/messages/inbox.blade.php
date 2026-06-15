@extends('layouts.app')
@section('title','Сообщения — Whisper')
@section('content')
<div class="page" style="max-width:720px">

  <!-- Header card -->
  <div class="card" style="margin-bottom:14px">
    <div style="padding:20px 22px;display:flex;align-items:center;justify-content:space-between;
      background:linear-gradient(135deg,rgba(124,90,245,.1) 0%,rgba(255,95,135,.05) 100%)">
      <div style="display:flex;align-items:center;gap:12px">
        <div class="icon-badge" style="width:40px;height:40px;border-radius:12px">
          <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24">
            <path d="M20 2H4a2 2 0 00-2 2v18l4-4h14a2 2 0 002-2V4a2 2 0 00-2-2z"/>
          </svg>
        </div>
        <div>
          <div style="font-family:'Syne',sans-serif;font-weight:800;font-size:18px;color:var(--t1)">Сообщения</div>
          <div style="font-size:12px;color:var(--t3);margin-top:1px">
            {{ $users->count() }} {{ $users->count() === 1 ? 'диалог' : ($users->count() < 5 ? 'диалога' : 'диалогов') }}
          </div>
        </div>
      </div>
      <a href="{{ route('search') }}" class="btn btn-p btn-sm">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
        </svg>
        Найти собеседника
      </a>
    </div>
  </div>

  <div class="card">
    @if($users->isEmpty())
      <div class="empty" style="padding:64px 20px">
        <div class="empty-ico">💬</div>
        <h3>Нет диалогов</h3>
        <p>Напишите кому-нибудь первым!<br>Найдите людей в ленте или поиске.</p>
        <a href="{{ route('search') }}" class="btn btn-p btn-sm" style="margin-top:18px">
          Найти людей
        </a>
      </div>
    @else
      @foreach($users as $u)
      <a href="{{ route('messages.show', $u) }}" class="inbox-item {{ isset($u->unread_count) && $u->unread_count > 0 ? 'unread' : '' }}">
        <div class="inbox-av-wrap">
          <img src="{{ $u->avatar ? asset('storage/avatars/'.$u->avatar) : asset('images/default.png') }}" class="inbox-av">
        </div>
        <div style="flex:1;min-width:0">
          <div style="display:flex;align-items:center;gap:8px;margin-bottom:3px">
            <div class="inbox-name">{{ $u->name ?: $u->login }}</div>
            <div style="font-size:11px;color:var(--t3);margin-left:auto;flex-shrink:0">
              @if(isset($u->last_message_time))
                {{ $u->last_message_time->diffForHumans() }}
              @endif
            </div>
          </div>
          <div class="inbox-preview">
            @if(isset($u->last_message))
              {{ Str::limit($u->last_message, 60) }}
            @else
              <span style="color:var(--acc);font-size:12px">Начните разговор ✨</span>
            @endif
          </div>
        </div>
        <div style="display:flex;align-items:center;gap:8px;flex-shrink:0;margin-left:8px">
          @if(isset($u->unread_count) && $u->unread_count > 0)
            <div class="inbox-badge">{{ $u->unread_count > 9 ? '9+' : $u->unread_count }}</div>
          @endif
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:var(--t3)">
            <path d="M9 18l6-6-6-6"/>
          </svg>
        </div>
      </a>
      @endforeach
    @endif
  </div>
</div>
@endsection
