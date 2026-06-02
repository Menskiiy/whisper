@extends('layouts.app')
@section('title','Сообщения — Whisper')
@section('content')
<div class="page" style="max-width:660px">
<div class="card">
  <div class="card-head">
    <div class="icon-badge"><svg width="15" height="15" fill="currentColor" viewBox="0 0 24 24"><path d="M20 2H4a2 2 0 00-2 2v18l4-4h14a2 2 0 002-2V4a2 2 0 00-2-2z"/></svg></div>
    Сообщения
  </div>
  @if($users->isEmpty())
    <div class="empty"><div class="empty-ico">💬</div><h3>Нет диалогов</h3><p>Напишите кому-нибудь первым — найдите людей в ленте</p></div>
  @else
    @foreach($users as $u)
    <a href="{{ route('messages.show', $u) }}" class="inbox-item">
      <img src="{{ $u->avatar ? asset('storage/avatars/'.$u->avatar) : asset('images/default.png') }}" class="inbox-av">
      <div style="flex:1;min-width:0">
        <div style="font-weight:700;font-size:14.5px;color:var(--t1)">{{ $u->name ?: $u->login }}</div>
        <div style="font-size:13px;color:var(--t3)">{{ $u->login }}</div>
      </div>
      <div class="inbox-arrow">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg>
      </div>
    </a>
    @endforeach
  @endif
</div>
</div>
@endsection
