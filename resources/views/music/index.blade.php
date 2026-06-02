@extends('layouts.app')
@section('title','Музыка — Whisper')
@section('content')
<div class="page">
<div class="two-col">
  <div class="main-col">

    <!-- Featured / trending -->
    @if($featured->isNotEmpty())
    <div class="card">
      <div class="card-head"><div class="icon-badge">🔥</div>Популярное</div>
      <div style="padding:12px 14px">
        @foreach($featured as $t)
        <div class="track-row" data-tid="{{ $t->id }}" onclick="playTrack({{ $t->id }},'{{ addslashes($t->title) }}','{{ addslashes($t->artist) }}','{{ $t->cover ? asset('storage/tracks/'.$t->cover) : '' }}')">
          @if($t->cover)<img src="{{ asset('storage/tracks/'.$t->cover) }}" class="track-cover">@else<div class="track-cover" style="display:flex;align-items:center;justify-content:center;font-size:20px">🎵</div>@endif
          <div class="track-info">
            <div class="track-title">{{ $t->title }}</div>
            <div class="track-artist">{{ $t->artist ?? $t->user->login }} @if($t->genre)· <span class="tag" style="font-size:10px;padding:1px 6px">{{ $t->genre }}</span>@endif</div>
          </div>
          <div style="display:flex;align-items:center;gap:12px;flex-shrink:0">
            <span style="font-size:11.5px;color:var(--t3)">{{ number_format($t->plays_count) }} прослушиваний</span>
            <button onclick="event.stopPropagation();likeTrack(this,{{ $t->id }})" class="act like {{ $t->isLikedBy(auth()->user()) ? 'liked':'' }}">
              <svg width="12" height="12" fill="{{ $t->isLikedBy(auth()->user()) ? 'currentColor':'none' }}" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
              <span class="like-c">{{ $t->likes_count }}</span>
            </button>
            <span class="track-dur">{{ $t->getDurationFormatted() }}</span>
          </div>
        </div>
        @endforeach
      </div>
    </div>
    @endif

    <!-- All tracks -->
    <div class="card">
      <div class="card-head"><div class="icon-badge">🎵</div>Все треки</div>
      @forelse($tracks as $t)
      <div class="track-row" data-tid="{{ $t->id }}" onclick="playTrack({{ $t->id }},'{{ addslashes($t->title) }}','{{ addslashes($t->artist) }}','{{ $t->cover ? asset('storage/tracks/'.$t->cover) : '' }}')">
        @if($t->cover)<img src="{{ asset('storage/tracks/'.$t->cover) }}" class="track-cover">@else<div class="track-cover" style="display:flex;align-items:center;justify-content:center;font-size:18px">🎵</div>@endif
        <div class="track-info">
          <div class="track-title">{{ $t->title }}</div>
          <div class="track-artist">
            <a href="/{{ $t->user->login }}" onclick="event.stopPropagation()" style="text-decoration:none;color:inherit">{{ $t->artist ?? $t->user->login }}</a>
            @if($t->album)· {{ $t->album }}@endif
          </div>
        </div>
        <div style="display:flex;align-items:center;gap:10px;flex-shrink:0">
          <button onclick="event.stopPropagation();likeTrack(this,{{ $t->id }})" class="act like {{ $t->isLikedBy(auth()->user()) ? 'liked':'' }}">
            <svg width="12" height="12" fill="{{ $t->isLikedBy(auth()->user()) ? 'currentColor':'none' }}" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
            <span class="like-c">{{ $t->likes_count }}</span>
          </button>
          <span class="track-dur">{{ $t->getDurationFormatted() }}</span>
          @if($t->user_id == auth()->id())
          <form method="POST" action="{{ route('music.destroy', $t) }}" onsubmit="return confirm('Удалить трек?')" onclick="event.stopPropagation()">@csrf @method('DELETE')<button type="submit" class="act" style="color:var(--acc2);font-size:13px">✕</button></form>
          @endif
        </div>
      </div>
      @empty
      <div class="empty"><div class="empty-ico">🎵</div><h3>Нет треков</h3><p>Загрузите первый трек!</p></div>
      @endforelse
      @if($tracks->hasPages())<div style="padding:12px 18px">{{ $tracks->links() }}</div>@endif
    </div>

  </div>

  <!-- SIDEBAR -->
  <div class="side-col">
    <!-- Upload -->
    <div class="card">
      <div class="card-head"><div class="icon-badge">⬆</div>Загрузить трек</div>
      <div style="padding:16px">
        <form method="POST" action="{{ route('music.store') }}" enctype="multipart/form-data">
          @csrf
          <div class="fg"><label>Название *</label><input name="title" placeholder="Название трека" required></div>
          <div class="fg"><label>Исполнитель</label><input name="artist" placeholder="Имя или группа"></div>
          <div style="display:flex;gap:10px">
            <div class="fg" style="flex:1"><label>Альбом</label><input name="album" placeholder="Альбом"></div>
            <div class="fg" style="flex:1"><label>Жанр</label><input name="genre" placeholder="Поп, Рок…"></div>
          </div>
          <div class="fg"><label>Аудио файл * (MP3/OGG/WAV/FLAC)</label>
            <label class="attach-btn" style="width:100%;justify-content:center;padding:12px;border:1.5px dashed var(--b2);border-radius:10px" id="audio-lbl">
              🎵 Выбрать файл
              <input type="file" name="file" id="audio-file-input" accept=".mp3,.ogg,.wav,.flac,.aac,.m4a" required onchange="onAudioFileChange(this)">
            </label>
          </div>
          <input type="hidden" name="duration" id="audio-duration-input" value="0">
          <div class="fg"><label>Обложка</label>
            <label class="attach-btn" style="width:100%;justify-content:center;padding:10px;border:1.5px dashed var(--b2);border-radius:10px">🖼 Обложка<input type="file" name="cover" accept="image/*" onchange="previewImg(this,'cprev')"></label>
            <div id="cprev" style="margin-top:6px;text-align:center"></div>
          </div>
          <div class="chk-row"><input type="checkbox" name="is_public" value="1" checked><span>Публичный трек</span></div>
          <button type="submit" class="btn btn-p" style="width:100%;justify-content:center">Загрузить</button>
        </form>
      </div>
    </div>

    <!-- Genres -->
    @if($genres->isNotEmpty())
    <div class="card">
      <div class="card-head">Жанры</div>
      <div style="padding:12px 14px;display:flex;flex-wrap:wrap;gap:7px">
        @foreach($genres as $g)@if($g)<a href="{{ route('music.index') }}?genre={{ urlencode($g) }}" class="tag">{{ $g }}</a>@endif
        @endforeach
      </div>
    </div>
    @endif

    <!-- My tracks -->
    @if($mine->isNotEmpty())
    <div class="card">
      <div class="card-head">Мои треки</div>
      @foreach($mine->take(5) as $t)
      <div class="track-row" data-tid="{{ $t->id }}" onclick="playTrack({{ $t->id }},'{{ addslashes($t->title) }}','{{ addslashes($t->artist) }}','{{ $t->cover ? asset('storage/tracks/'.$t->cover) : '' }}')">
        @if($t->cover)<img src="{{ asset('storage/tracks/'.$t->cover) }}" class="track-cover" style="width:34px;height:34px">@else<div class="track-cover" style="width:34px;height:34px;font-size:14px;display:flex;align-items:center;justify-content:center">🎵</div>@endif
        <div class="track-info"><div class="track-title" style="font-size:12.5px">{{ $t->title }}</div></div>
        <span class="track-dur">{{ $t->getDurationFormatted() }}</span>
      </div>
      @endforeach
    </div>
    @endif
  </div>
</div>
</div>
<script>
function onAudioFileChange(input) {
  if (!input.files[0]) return;
  const file = input.files[0];
  // Показать имя файла
  const lbl = document.getElementById('audio-lbl');
  const existing = lbl.querySelector('b');
  if (existing) existing.textContent = file.name;
  else lbl.insertAdjacentHTML('beforeend', `<b style='font-size:11px;color:var(--acc);margin-left:6px'>${file.name}</b>`);
  // Определить длительность через Audio API
  const url = URL.createObjectURL(file);
  const tmp = new Audio();
  tmp.src = url;
  tmp.addEventListener('loadedmetadata', () => {
    const secs = Math.round(tmp.duration) || 0;
    document.getElementById('audio-duration-input').value = secs;
    URL.revokeObjectURL(url);
  });
  tmp.addEventListener('error', () => URL.revokeObjectURL(url));
}

function likeTrack(btn, id) {
  const c = btn.querySelector('.like-c');
  post('/music/'+id+'/like').then(r=>r.json()).then(d=>{
    c.textContent = d.likes_count;
    btn.classList.toggle('liked', d.liked);
    btn.querySelector('svg').setAttribute('fill', d.liked ? 'currentColor' : 'none');
  });
}
// Build playlist from page
document.addEventListener('DOMContentLoaded', ()=>{
  playlist = Array.from(document.querySelectorAll('.track-row[data-tid]')).map(r=>{
    return {id:r.dataset.tid, title:r.querySelector('.track-title')?.textContent||'', artist:r.querySelector('.track-artist')?.textContent||'', cover:''};
  });
});
</script>
@endsection
