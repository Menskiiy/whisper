@extends('layouts.app')
@section('title','{{ $video->title }} — Whisper')
@section('content')
<div class="page">
<div class="two-col">
  <div class="main-col">
    <div class="card">
      <video controls autoplay style="width:100%;aspect-ratio:16/9;background:#000;display:block">
        <source src="{{ asset('storage/videos/'.$video->file) }}" type="video/mp4">
      </video>
      <div style="padding:16px 20px">
        <h1 style="font-family:'Syne',sans-serif;font-size:20px;font-weight:800;color:var(--t1)">{{ $video->title }}</h1>
        <div style="display:flex;align-items:center;justify-content:space-between;margin-top:8px;flex-wrap:wrap;gap:10px">
          <div style="display:flex;align-items:center;gap:12px">
            <a href="/{{ $video->user->login }}" style="display:flex;align-items:center;gap:9px;text-decoration:none">
              <img src="{{ $video->user->avatar ? asset('storage/avatars/'.$video->user->avatar) : asset('images/default.png') }}" style="width:36px;height:36px;border-radius:50%;object-fit:cover">
              <span style="font-weight:600;font-size:14px;color:var(--t1)">{{ $video->user->name ?: $video->user->login }}</span>
            </a>
            <span style="color:var(--t3);font-size:13px">{{ number_format($video->views_count) }} просмотров · {{ $video->created_at->diffForHumans() }}</span>
          </div>
          <div style="display:flex;gap:9px">
            <button id="like-btn" onclick="likeVideo(this,{{ $video->id }})"
              class="btn {{ $liked?'btn-danger':'btn-ghost' }} btn-sm">
              @if($liked)❤️@else🤍@endif <span id="vc-likes">{{ $video->likes_count }}</span>
            </button>
            @if($video->user_id == auth()->id())
            <form method="POST" action="{{ route('videos.destroy', $video) }}" onsubmit="return confirm('Удалить видео?')">@csrf @method('DELETE')<button type="submit" class="btn btn-danger btn-sm">Удалить</button></form>
            @endif
          </div>
        </div>
        @if($video->category)<div class="tag" style="margin-top:10px">{{ $video->category }}</div>@endif
        @if($video->description)<div style="margin-top:12px;font-size:14px;color:var(--t2);line-height:1.65;white-space:pre-line">{{ $video->description }}</div>@endif
      </div>
    </div>
  </div>
  <div class="side-col">
    @if($related->isNotEmpty())
    <div style="font-family:'Syne',sans-serif;font-size:14px;font-weight:700;color:var(--t1);margin-bottom:12px">Похожие видео</div>
    @foreach($related as $v)
    <a href="{{ route('videos.show', $v) }}" class="v-card" style="margin-bottom:11px">
      <div style="position:relative">
        @if($v->thumbnail)<img src="{{ asset('storage/videos/'.$v->thumbnail) }}" class="v-thumb">
        @else<video src="{{ asset('storage/videos/'.$v->file) }}" class="v-thumb" preload="metadata"></video>@endif
        <span class="v-dur">{{ $v->getDurationFormatted() }}</span>
      </div>
      <div class="v-info"><div class="v-title">{{ $v->title }}</div><div class="v-meta">{{ $v->user->login }} · {{ number_format($v->views_count) }} просм.</div></div>
    </a>
    @endforeach
    @endif
  </div>
</div>
</div>
<script>
function likeVideo(btn, id) {
  post('/videos/'+id+'/like').then(r=>r.json()).then(d=>{
    document.getElementById('vc-likes').textContent = d.likes_count;
    btn.innerHTML = (d.liked?'❤️':'🤍')+' <span id="vc-likes">'+d.likes_count+'</span>';
    btn.classList.toggle('btn-danger', d.liked);
    btn.classList.toggle('btn-ghost', !d.liked);
  });
}
</script>
@endsection
