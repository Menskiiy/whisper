@extends('layouts.app')
@section('title','Фото — Whisper')
@section('content')
<div class="page">
  <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:18px">
    <h1 style="font-family:'Syne',sans-serif;font-size:22px;font-weight:800;color:var(--t1)">📸 Все фото</h1>
    <span style="color:var(--t2);font-size:13px">{{ $photos->total() }} фото от открытых профилей</span>
  </div>

  @if($photos->isEmpty())
  <div class="empty"><div class="empty-ico">📷</div><h3>Пока нет фото</h3><p>Публикуйте посты с фотографиями!</p></div>
  @else
  <div class="masonry">
    @foreach($photos as $p)
    <div class="masonry-item" onclick="openImg('{{ asset('storage/posts/'.$p->image) }}')">
      <img src="{{ asset('storage/posts/'.$p->image) }}" loading="lazy" alt="{{ $p->user->login }}">
      <div class="masonry-overlay">
        <a href="/{{ $p->user->login }}" onclick="event.stopPropagation()" style="display:flex;align-items:center;gap:7px;text-decoration:none">
          <img src="{{ $p->user->avatar ? asset('storage/avatars/'.$p->user->avatar) : asset('images/default.png') }}" style="width:26px;height:26px;border-radius:50%;object-fit:cover;border:1.5px solid rgba(255,255,255,.3)">
          <span style="font-size:12px;font-weight:600;color:#fff">{{ $p->user->login }}</span>
        </a>
        <span style="margin-left:auto;color:rgba(255,255,255,.7);font-size:11px;display:flex;align-items:center;gap:3px">
          <svg width="11" height="11" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
          {{ $p->likes_count }}
        </span>
      </div>
    </div>
    @endforeach
  </div>
  @if($photos->hasPages())
  <div style="display:flex;justify-content:center;padding:20px 0">{{ $photos->links() }}</div>
  @endif
  @endif
</div>
@endsection
