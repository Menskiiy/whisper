@extends('layouts.app')
@section('title','Участники — {{ $community->name }}')
@section('content')
<div class="page" style="max-width:680px">
<div class="card">
  <div class="card-head"><div class="icon-badge">👥</div>Участники: {{ $community->name }}</div>
  @foreach($members as $m)
  <div style="display:flex;align-items:center;gap:12px;padding:13px 18px;border-bottom:1px solid var(--b1)">
    <a href="/{{ $m->user->login }}">
      <img src="{{ $m->user->avatar ? asset('storage/avatars/'.$m->user->avatar) : asset('images/default.png') }}" style="width:40px;height:40px;border-radius:50%;object-fit:cover;border:1.5px solid var(--b1)">
    </a>
    <div style="flex:1">
      <a href="/{{ $m->user->login }}" style="font-weight:700;font-size:13.5px;color:var(--t1);text-decoration:none">{{ $m->user->name ?: $m->user->login }}</a>
      <div style="font-size:12px;color:var(--t3)">{{ $m->user->login }}</div>
    </div>
    @if($m->role !== 'owner')
    <form method="POST" action="{{ route('communities.members.role', [$community, $m]) }}" style="display:flex;gap:8px;align-items:center">
      @csrf @method('PUT')
      <select name="role" onchange="this.form.submit()" style="background:var(--s2);border:1px solid var(--b1);border-radius:8px;color:var(--t1);font-family:inherit;font-size:12px;padding:5px 8px">
        <option value="admin" {{ $m->role=='admin'?'selected':'' }}>Админ</option>
        <option value="mod"   {{ $m->role=='mod'  ?'selected':'' }}>Модератор</option>
        <option value="member"{{ $m->role=='member'?'selected':'' }}>Участник</option>
      </select>
    </form>
    <form method="POST" action="{{ route('communities.members.remove', [$community, $m]) }}" onsubmit="return confirm('Удалить участника?')">
      @csrf @method('DELETE')
      <button type="submit" class="btn btn-danger btn-xs">Удалить</button>
    </form>
    @else
    <span class="tag" style="color:gold;border-color:gold;background:rgba(255,215,0,.1)">Owner</span>
    @endif
  </div>
  @endforeach
  <div style="padding:12px 18px"><a href="{{ route('communities.show', $community->slug) }}" class="btn btn-ghost btn-sm">← Назад</a></div>
</div>
</div>
@endsection
