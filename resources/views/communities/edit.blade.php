@extends('layouts.app')
@section('title','Настройки — {{ $community->name }}')
@section('content')
<div class="page" style="max-width:640px">
<div class="card">
  <div class="card-head"><div class="icon-badge">⚙</div>Настройки сообщества: {{ $community->name }}</div>
  <div style="padding:24px">
    @if($errors->any())<div class="alert-err">@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>@endif
    <form method="POST" action="{{ route('communities.update', $community) }}" enctype="multipart/form-data">
      @csrf @method('PUT')
      <div class="fg"><label>Название</label><input name="name" value="{{ old('name',$community->name) }}" required></div>
      <div class="fg"><label>Описание</label><textarea name="description" rows="3">{{ old('description',$community->description) }}</textarea></div>
      <div class="fg"><label>Правила сообщества</label><textarea name="rules" rows="6" placeholder="Пишите правила по одному...">{{ old('rules',$community->rules) }}</textarea></div>
      <div style="display:flex;gap:14px">
        <div class="fg" style="flex:1"><label>Категория</label>
          <select name="category"><option value="">— Без категории —</option>
            @foreach(['Технологии','Игры','Музыка','Кино','Спорт','Наука','Искусство','Путешествия','Еда','Политика','Образование','Другое'] as $cat)
              <option {{ old('category',$community->category)==$cat?'selected':'' }}>{{ $cat }}</option>
            @endforeach
          </select>
        </div>
        <div class="fg" style="flex:1"><label>Приватность</label>
          <select name="privacy">
            <option value="public" {{ $community->privacy=='public'?'selected':'' }}>🌐 Публичное</option>
            <option value="private" {{ $community->privacy=='private'?'selected':'' }}>🔒 Закрытое</option>
          </select>
        </div>
      </div>
      <div style="display:flex;gap:14px">
        <div class="fg" style="flex:1"><label>Новый аватар</label>
          @if($community->avatar)<img src="{{ asset('storage/communities/'.$community->avatar) }}" style="width:60px;height:60px;border-radius:10px;object-fit:cover;margin-bottom:8px;display:block">@endif
          <label class="attach-btn" style="width:100%;justify-content:center;padding:10px;border:1.5px dashed var(--b2);border-radius:10px">📷 Выбрать<input type="file" name="avatar" accept="image/*" onchange="previewImg(this,'avp')"></label>
          <div id="avp" style="margin-top:6px"></div>
        </div>
        <div class="fg" style="flex:1"><label>Новый баннер</label>
          <label class="attach-btn" style="width:100%;justify-content:center;padding:10px;border:1.5px dashed var(--b2);border-radius:10px">🖼 Выбрать<input type="file" name="banner" accept="image/*" onchange="previewImg(this,'bnp')"></label>
          <div id="bnp" style="margin-top:6px"></div>
        </div>
      </div>
      <div class="fg"><label>Акцентный цвет</label>
        <input type="color" name="accent_color" value="{{ $community->accent_color ?? '#7c5af5' }}" style="width:48px;height:36px;border-radius:8px;cursor:pointer;padding:2px;border:1px solid var(--b1);background:transparent">
      </div>
      <button type="submit" class="btn btn-p" style="width:100%;justify-content:center">Сохранить</button>
    </form>
    <div style="margin-top:14px"><a href="{{ route('communities.show', $community->slug) }}" class="btn btn-ghost btn-sm">← К сообществу</a></div>
  </div>
</div>
</div>
@endsection
