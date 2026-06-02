@extends('layouts.app')
@section('title','Группы — Whisper')
@section('content')
<div class="page">
<div class="two-col">
  <div class="main-col">
    <div class="card">
      <div class="card-head">
        <div class="icon-badge"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 010 20"/></svg></div>
        Сообщества
        <a href="{{ route('communities.create') }}" class="btn btn-p btn-sm" style="margin-left:auto">+ Создать</a>
      </div>
      <div style="padding:16px">
        <div class="comm-grid">
          @forelse($communities as $c)
          <a href="{{ route('communities.show', $c->slug) }}" style="text-decoration:none">
            <div class="c-card">
              <div class="c-banner"><div class="c-banner-in">@if($c->banner)<img src="{{ asset('storage/communities/'.$c->banner) }}" alt="">@endif</div></div>
              <div style="padding:14px 16px 16px;margin-top:-28px;position:relative">
                @if($c->avatar)
                  <img src="{{ asset('storage/communities/'.$c->avatar) }}" style="width:52px;height:52px;border-radius:12px;object-fit:cover;border:3px solid var(--s1);box-shadow:0 0 0 2px {{ $c->accent_color }};display:block;margin-bottom:10px">
                @else
                  <div style="width:52px;height:52px;border-radius:12px;background:rgba(124,90,245,.15);border:3px solid var(--s1);display:flex;align-items:center;justify-content:center;font-size:22px;margin-bottom:10px">🌐</div>
                @endif
                <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:14px;color:var(--t1)">{{ $c->name }}</div>
                @if($c->description)
                  <div style="font-size:12px;color:var(--t2);margin-top:4px;line-height:1.5;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical">{{ $c->description }}</div>
                @endif
                <div style="display:flex;align-items:center;justify-content:space-between;margin-top:9px">
                  <span style="font-size:11.5px;color:var(--t3)">{{ $c->members_count }} участников</span>
                  @if($c->category)<span class="tag" style="font-size:11px;padding:2px 8px">{{ $c->category }}</span>@endif
                </div>
              </div>
            </div>
          </a>
          @empty
          <div class="empty" style="grid-column:1/-1"><div class="empty-ico">🌐</div><h3>Нет сообществ</h3><p>Создайте первое!</p></div>
          @endforelse
        </div>
      </div>
    </div>
  </div>
  <div class="side-col">
    @if($mine->isNotEmpty())
    <div class="card">
      <div class="card-head">Мои группы</div>
      <div style="padding:10px 14px">
        @foreach($mine as $c)
        <div class="who-item">
          <a href="{{ route('communities.show', $c->slug) }}" class="who-user">
            @if($c->avatar)<img src="{{ asset('storage/communities/'.$c->avatar) }}" class="who-av" style="border-radius:8px">@else<div class="who-av" style="border-radius:8px;background:rgba(124,90,245,.15);display:flex;align-items:center;justify-content:center">🌐</div>@endif
            <div style="min-width:0"><div class="who-name">{{ $c->name }}</div><div class="who-login">{{ $c->pivot->role }}</div></div>
          </a>
        </div>
        @endforeach
      </div>
    </div>
    @endif
    <div class="card"><div style="padding:18px;text-align:center">
      <div style="font-size:30px;margin-bottom:10px">🌐</div>
      <div style="font-family:'Syne',sans-serif;font-size:14px;font-weight:700;margin-bottom:6px">Создать сообщество</div>
      <div style="font-size:12px;color:var(--t2);margin-bottom:14px">Объединитесь с людьми по интересам</div>
      <a href="{{ route('communities.create') }}" class="btn btn-p btn-sm" style="width:100%;justify-content:center">Создать</a>
    </div></div>
  </div>
</div>
</div>
@endsection
