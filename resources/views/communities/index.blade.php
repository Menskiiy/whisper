@extends('layouts.app')
@section('title','Группы — Whisper')
@section('content')
<div class="page">
<div class="two-col">

  <div class="main-col">
    <!-- Page header -->
    <div style="margin-bottom:16px;padding:20px 22px;
      background:linear-gradient(160deg,rgba(12,13,28,.95),rgba(10,11,22,.98));
      border:1px solid var(--b1);border-radius:var(--r-lg);
      display:flex;align-items:center;justify-content:space-between;gap:14px">
      <div style="display:flex;align-items:center;gap:13px">
        <div class="icon-badge" style="width:44px;height:44px;border-radius:13px;flex-shrink:0">
          <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 010 20"/>
          </svg>
        </div>
        <div>
          <div style="font-family:'Syne',sans-serif;font-weight:800;font-size:19px;color:var(--t1)">Сообщества</div>
          <div style="font-size:12.5px;color:var(--t3);margin-top:2px">Найдите единомышленников</div>
        </div>
      </div>
      <a href="{{ route('communities.create') }}" class="btn btn-p btn-sm">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Создать
      </a>
    </div>

    <div class="card">
      <div style="padding:18px">
        <div class="comm-grid">
          @forelse($communities as $c)
          <a href="{{ route('communities.show', $c->slug) }}" style="text-decoration:none">
            <div class="c-card">
              <div class="c-banner">
                <div class="c-banner-in">
                  @if($c->banner)<img src="{{ asset('storage/communities/'.$c->banner) }}" alt="">@endif
                </div>
              </div>
              <div style="padding:12px 14px 16px;margin-top:-26px;position:relative">
                @if($c->avatar)
                  <img src="{{ asset('storage/communities/'.$c->avatar) }}"
                       style="width:50px;height:50px;border-radius:12px;object-fit:cover;
                              border:3px solid var(--s1);box-shadow:0 0 0 2px rgba(124,90,245,.4);
                              display:block;margin-bottom:10px">
                @else
                  <div style="width:50px;height:50px;border-radius:12px;
                    background:linear-gradient(135deg,rgba(124,90,245,.2),rgba(255,95,135,.1));
                    border:3px solid var(--s1);display:flex;align-items:center;
                    justify-content:center;font-size:22px;margin-bottom:10px">🌐</div>
                @endif
                <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:14px;color:var(--t1);line-height:1.3">{{ $c->name }}</div>
                @if($c->description)
                  <div style="font-size:12px;color:var(--t2);margin-top:5px;line-height:1.5;
                    overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical">
                    {{ $c->description }}
                  </div>
                @endif
                <div style="display:flex;align-items:center;justify-content:space-between;margin-top:10px">
                  <span style="font-size:11.5px;color:var(--t3);display:flex;align-items:center;gap:4px">
                    <svg width="11" height="11" fill="currentColor" viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                    {{ $c->members_count }}
                  </span>
                  @if($c->category)<span class="tag" style="font-size:10.5px;padding:2px 8px">{{ $c->category }}</span>@endif
                </div>
              </div>
            </div>
          </a>
          @empty
          <div class="empty" style="grid-column:1/-1;padding:52px 20px">
            <div class="empty-ico">🌐</div>
            <h3>Нет сообществ</h3>
            <p>Создайте первое сообщество!</p>
            <a href="{{ route('communities.create') }}" class="btn btn-p btn-sm" style="margin-top:16px">Создать сообщество</a>
          </div>
          @endforelse
        </div>
      </div>
    </div>
  </div>

  <div class="side-col">
    @if($mine->isNotEmpty())
    <div class="card">
      <div class="card-head">
        <div class="icon-badge"><svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg></div>
        Мои группы
      </div>
      <div style="padding:8px 16px 14px">
        @foreach($mine as $c)
        <div class="who-item">
          <a href="{{ route('communities.show', $c->slug) }}" class="who-user">
            @if($c->avatar)
              <img src="{{ asset('storage/communities/'.$c->avatar) }}" class="who-av" style="border-radius:9px">
            @else
              <div class="who-av" style="border-radius:9px;background:rgba(124,90,245,.15);display:flex;align-items:center;justify-content:center;font-size:16px">🌐</div>
            @endif
            <div style="min-width:0">
              <div class="who-name">{{ $c->name }}</div>
              <div class="who-login">{{ $c->pivot->role }}</div>
            </div>
          </a>
        </div>
        @endforeach
      </div>
    </div>
    @endif

    <div class="card">
      <div style="padding:22px;text-align:center">
        <div style="width:52px;height:52px;border-radius:16px;background:linear-gradient(135deg,rgba(124,90,245,.2),rgba(255,95,135,.1));display:flex;align-items:center;justify-content:center;font-size:26px;margin:0 auto 14px">🌐</div>
        <div style="font-family:'Syne',sans-serif;font-size:15px;font-weight:700;margin-bottom:7px">Создать сообщество</div>
        <div style="font-size:12.5px;color:var(--t2);margin-bottom:16px;line-height:1.6">Объединитесь с людьми по интересам и создайте своё пространство</div>
        <a href="{{ route('communities.create') }}" class="btn btn-p btn-sm" style="width:100%;justify-content:center">
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          Создать
        </a>
      </div>
    </div>
  </div>
</div>
</div>
@endsection
