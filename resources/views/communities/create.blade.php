@extends('layouts.app')
@section('title','Создать сообщество')
@section('content')
<div class="page" style="max-width:620px">
<div class="card">
  <div class="card-head"><div class="icon-badge">🌐</div>Создать сообщество</div>
  <div style="padding:24px">
    @if($errors->any())<div class="alert-err">@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>@endif
    <form method="POST" action="{{ route('communities.store') }}" enctype="multipart/form-data">
      @csrf
      <div class="fg"><label>Название *</label><input name="name" value="{{ old('name') }}" placeholder="Название сообщества" required></div>
      <div class="fg"><label>Slug (URL) *</label><input name="slug" value="{{ old('slug') }}" placeholder="moe-soobshchestvo" pattern="[a-z0-9\-]+" required>
        <div style="font-size:11px;color:var(--t3);margin-top:3px">/communities/<b id="slug-p">...</b></div>
      </div>
      <div class="fg"><label>Описание</label><textarea name="description" rows="3" placeholder="О чём это сообщество?">{{ old('description') }}</textarea></div>
      <div style="display:flex;gap:14px">
        <div class="fg" style="flex:1"><label>Категория</label>
          <select name="category"><option value="">— Без категории —</option>
            @foreach(['Технологии','Игры','Музыка','Кино','Спорт','Наука','Искусство','Путешествия','Еда','Политика','Образование','Другое'] as $cat)
              <option {{ old('category')==$cat?'selected':'' }}>{{ $cat }}</option>
            @endforeach
          </select>
        </div>
        <div class="fg" style="flex:1"><label>Приватность</label>
          <select name="privacy"><option value="public">🌐 Публичное</option><option value="private">🔒 Закрытое</option></select>
        </div>
      </div>
      <div style="display:flex;gap:14px">
        <div class="fg" style="flex:1"><label>Аватар</label>
          <label class="attach-btn" style="width:100%;justify-content:center;padding:11px;border:1.5px dashed var(--b2);border-radius:10px">📷 Выбрать<input type="file" name="avatar" accept="image/*" onchange="previewImg(this,'avp')"></label>
          <div id="avp" style="margin-top:6px;text-align:center"></div>
        </div>
        <div class="fg" style="flex:1"><label>Баннер</label>
          <label class="attach-btn" style="width:100%;justify-content:center;padding:11px;border:1.5px dashed var(--b2);border-radius:10px">🖼 Выбрать<input type="file" name="banner" accept="image/*" onchange="previewImg(this,'bnp')"></label>
          <div id="bnp" style="margin-top:6px;text-align:center"></div>
        </div>
      </div>
      <div class="fg"><label>Акцентный цвет</label>
        <div style="display:flex;align-items:center;gap:10px">
          <input type="color" name="accent_color" value="#7c5af5" style="width:48px;height:36px;border-radius:8px;cursor:pointer;padding:2px;border:1px solid var(--b1);background:transparent">
          <span style="font-size:12px;color:var(--t2)">Главный цвет сообщества</span>
        </div>
      </div>
      <button type="submit" class="btn btn-p" style="width:100%;justify-content:center;margin-top:8px">Создать сообщество</button>
    </form>
  </div>
</div>
</div>
<script>
document.querySelector('[name=name]').addEventListener('input',function(){
  document.getElementById('slug-p').textContent=this.value.toLowerCase().replace(/[^a-z0-9]+/g,'-').replace(/^-|-$/g,'')||'...';
});
</script>
@endsection
