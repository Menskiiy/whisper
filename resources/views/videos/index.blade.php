@extends('layouts.app')
@section('title','Видео — Whisper')
@section('content')
<div class="page">
<div class="two-col">
  <div class="main-col">

    @if($trending->isNotEmpty())
    <div style="margin-bottom:18px">
      <div style="font-family:'Syne',sans-serif;font-size:15px;font-weight:700;color:var(--t1);margin-bottom:12px;display:flex;align-items:center;gap:8px">🔥 В тренде</div>
      <div class="video-grid" style="grid-template-columns:repeat(auto-fill,minmax(200px,1fr))">
        @foreach($trending as $v)
        <a href="{{ route('videos.show', $v) }}" class="v-card">
          <div style="position:relative">
            <video src="{{ asset('storage/videos/'.$v->file) }}" class="v-thumb" preload="metadata"></video>
            <span class="v-dur">{{ $v->getDurationFormatted() }}</span>
          </div>
          <div class="v-info">
            <div class="v-title">{{ $v->title }}</div>
            <div class="v-meta">{{ $v->user->login }} · {{ number_format($v->views_count) }} просм.</div>
          </div>
        </a>
        @endforeach
      </div>
    </div>
    @endif

    <div>
      <div style="font-family:'Syne',sans-serif;font-size:15px;font-weight:700;color:var(--t1);margin-bottom:12px">Все видео</div>
      <div class="video-grid">
        @forelse($videos as $v)
        <a href="{{ route('videos.show', $v) }}" class="v-card">
          <div style="position:relative">
            @if($v->thumbnail)
              <img src="{{ asset('storage/videos/'.$v->thumbnail) }}" class="v-thumb">
            @else
              <video src="{{ asset('storage/videos/'.$v->file) }}" class="v-thumb" preload="metadata"></video>
            @endif
            <span class="v-dur">{{ $v->getDurationFormatted() }}</span>
          </div>
          <div class="v-info">
            <div class="v-title">{{ $v->title }}</div>
            <div class="v-meta">
              {{ $v->user->login }} · {{ number_format($v->views_count) }} просм.
              @if($v->category)
                · <span>{{ $v->category }}</span>
              @endif
            </div>
          </div>
        </a>
        @empty
        <div class="empty" style="grid-column:1/-1">
          <div class="empty-ico">🎬</div>
          <h3>Нет видео</h3>
          <p>Загрузите первое!</p>
        </div>
        @endforelse
      </div>
      @if($videos->hasPages())
        <div style="padding:14px 0">{{ $videos->links() }}</div>
      @endif
    </div>
  </div>

  <div class="side-col">
    <!-- Upload -->
    <div class="card">
      <div class="card-head"><div class="icon-badge">🎬</div>Загрузить видео</div>
      <div style="padding:16px">
        <form method="POST" action="{{ route('videos.store') }}" enctype="multipart/form-data">
          @csrf
          <div class="fg"><label>Название *</label><input name="title" placeholder="Название видео" required></div>
          <div class="fg"><label>Описание</label><textarea name="description" rows="2" placeholder="Описание…"></textarea></div>
          <div class="fg">
            <label>Категория</label>
            <select name="category">
              <option value="">— Без категории —</option>
              @foreach(['Кино','Дорамы','Сериалы','Аниме','Блоги','Игры','Музыка','Спорт','Образование','Другое'] as $cat)
                <option>{{ $cat }}</option>
              @endforeach
            </select>
          </div>
          <div class="fg">
            <label>Видео файл * (MP4/WebM/MOV)</label>
            <label class="attach-btn" style="width:100%;justify-content:center;padding:12px;border:1.5px dashed var(--b2);border-radius:10px">
              🎬 Выбрать
              <input type="file" name="file" accept=".mp4,.mov,.avi,.webm,.mkv" required
                onchange="this.parentElement.querySelector('b') ? this.parentElement.querySelector('b').textContent = this.files[0].name : this.parentElement.insertAdjacentHTML('beforeend','<b style=\'font-size:11px;color:var(--acc);margin-left:6px\'>' + this.files[0].name + '</b>')">
            </label>
          </div>
          <div class="fg">
            <label>Обложка (превью)</label>
            <label class="attach-btn" style="width:100%;justify-content:center;padding:10px;border:1.5px dashed var(--b2);border-radius:10px">
              🖼 Обложка
              <input type="file" name="thumbnail" accept="image/*" onchange="previewImg(this,'vprev')">
            </label>
            <div id="vprev" style="margin-top:6px;text-align:center"></div>
          </div>
          <div class="chk-row"><input type="checkbox" name="is_public" value="1" checked><span>Публичное видео</span></div>
          <button type="submit" class="btn btn-p" style="width:100%;justify-content:center">Загрузить</button>
        </form>
      </div>
    </div>

    @if($categories->isNotEmpty())
    <div class="card">
      <div class="card-head">Категории</div>
      <div style="padding:12px 14px;display:flex;flex-wrap:wrap;gap:7px">
        @foreach($categories as $cat)
          @if($cat)
            <a href="{{ route('videos.index') }}?category={{ urlencode($cat) }}" class="tag">{{ $cat }}</a>
          @endif
        @endforeach
      </div>
    </div>
    @endif

  </div>
</div>
</div>
@endsection
