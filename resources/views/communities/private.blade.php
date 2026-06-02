@extends('layouts.app')
@section('title','{{ $community->name }} — Whisper')
@section('content')
<div class="page" style="max-width:500px">
<div class="card">
  <div style="height:100px;background:linear-gradient(135deg,{{ $community->accent_color ?? '#7c5af5' }}44,{{ $community->accent_color ?? '#7c5af5' }}11)"></div>
  <div style="padding:16px 22px 24px;text-align:center">
    @if($community->avatar)
      <img src="{{ asset('storage/communities/'.$community->avatar) }}" style="width:72px;height:72px;border-radius:14px;object-fit:cover;border:3px solid var(--bg);margin-top:-44px;display:block;margin-left:auto;margin-right:auto;margin-bottom:14px">
    @else
      <div style="width:72px;height:72px;border-radius:14px;background:rgba(124,90,245,.15);display:flex;align-items:center;justify-content:center;font-size:28px;margin:-44px auto 14px">🔒</div>
    @endif
    <h1 style="font-family:'Syne',sans-serif;font-size:20px;font-weight:800;color:var(--t1)">{{ $community->name }}</h1>
    <p style="color:var(--t2);font-size:13.5px;margin:8px 0 20px">Это закрытое сообщество. Войдите и вступите, чтобы просматривать его.</p>
    @auth
      <form method="POST" action="{{ route('communities.join', $community) }}">
        @csrf
        <button type="submit" class="btn btn-p" style="width:100%;justify-content:center">Запросить вступление</button>
      </form>
    @else
      <a href="{{ route('login') }}" class="btn btn-p" style="width:100%;justify-content:center;display:flex">Войти</a>
    @endauth
  </div>
</div>
</div>
@endsection
