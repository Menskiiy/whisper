@extends('layouts.app')
@section('title','Настройки профиля')
@section('content')
<div class="page" style="max-width:680px">
<div class="card">
  <div class="card-head"><div class="icon-badge">⚙</div>Настройки профиля</div>

  <!-- Tabs -->
  <div style="display:flex;border-bottom:1px solid var(--b1);padding:0 20px;gap:2px">
    @foreach([['main','Основное','👤'],['appearance','Внешний вид','🎨'],['social','Соц. сети','🔗'],['privacy','Приватность','🔒']] as [$tab,$label,$ico])
    <button onclick="showTab('{{ $tab }}')" id="tab-btn-{{ $tab }}"
      style="background:none;border:none;color:var(--t2);font-family:inherit;font-size:13px;padding:13px 14px;cursor:pointer;border-bottom:2px solid transparent;transition:all .18s;display:flex;align-items:center;gap:5px">
      {{ $ico }} {{ $label }}
    </button>
    @endforeach
  </div>

  <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" style="padding:24px">
    @csrf
    @if($errors->any())<div class="alert-err" style="margin-bottom:18px">@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>@endif

    <!-- MAIN TAB -->
    <div id="tab-main">
      <div style="display:flex;gap:20px;margin-bottom:22px">
        <div style="text-align:center">
          <div style="position:relative;display:inline-block">
            <img src="{{ auth()->user()->avatar ? asset('storage/avatars/'.auth()->user()->avatar) : asset('images/default.png') }}"
                 id="av-prev" style="width:90px;height:90px;border-radius:50%;object-fit:cover;border:3px solid var(--bg);box-shadow:0 0 0 2.5px var(--acc)">
            <label style="position:absolute;bottom:-4px;right:-4px;width:28px;height:28px;background:var(--acc);border-radius:50%;display:flex;align-items:center;justify-content:center;cursor:pointer;border:2px solid var(--bg)">
              <svg width="13" height="13" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/><circle cx="12" cy="13" r="4"/></svg>
              <input type="file" id="av-input" accept="image/*" style="display:none" onchange="previewAv(this)">
            </label>
          </div>
          <div style="font-size:11px;color:var(--t3);margin-top:6px">Аватар</div>
        </div>
        <div style="flex:1">
          <div class="fg"><label>Имя</label><input name="name" value="{{ old('name', auth()->user()->name) }}" placeholder="Ваше имя" required></div>
          <div class="fg"><label>Статус</label><input name="status" value="{{ old('status', auth()->user()->status) }}" placeholder="Кратко о себе…" maxlength="100"></div>
        </div>
      </div>
      <div class="fg"><label>О себе</label><textarea name="bio" rows="3" placeholder="Расскажите о себе…" maxlength="300">{{ old('bio', auth()->user()->bio) }}</textarea></div>
      <div style="display:flex;gap:14px">
        <div class="fg" style="flex:1"><label>День рождения</label><input type="date" name="birthday" value="{{ auth()->user()->birthday?->format('Y-m-d') }}" style="color-scheme:dark"></div>
        <div class="fg" style="flex:1"><label>Город</label><input name="location" value="{{ old('location', auth()->user()->location) }}" placeholder="Москва"></div>
      </div>
    </div>

    <!-- APPEARANCE TAB -->
    <div id="tab-appearance" style="display:none">
      <div class="fg"><label>Баннер профиля</label>
        @if(auth()->user()->banner)
          <div style="margin-bottom:10px"><img src="{{ asset('storage/banners/'.auth()->user()->banner) }}" style="width:100%;height:100px;object-fit:cover;border-radius:10px;border:1px solid var(--b1)"></div>
        @endif
        <label class="attach-btn" style="width:100%;justify-content:center;padding:14px;border:1.5px dashed var(--b2);border-radius:11px">
          🖼 Загрузить баннер (рекомендуется 1200×300)
          <input type="file" name="banner" accept="image/*" onchange="previewBanner(this)">
        </label>
        <div id="bn-prev" style="margin-top:8px"></div>
      </div>
      <div class="fg">
        <label>Акцентный цвет профиля</label>
        <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;margin-top:6px">
          <input type="color" name="accent_color" id="acc-input" value="{{ auth()->user()->accent_color ?? '#7c5af5' }}"
                 style="width:48px;height:40px;border-radius:9px;cursor:pointer;padding:2px;border:1px solid var(--b1);background:transparent">
          <div style="display:flex;gap:8px;flex-wrap:wrap">
            @foreach(['#7c5af5','#ff5f87','#36cfb5','#f59e0b','#3b82f6','#10b981','#ef4444','#ec4899'] as $clr)
            <div onclick="document.getElementById('acc-input').value='{{ $clr }}'" style="width:28px;height:28px;border-radius:50%;background:{{ $clr }};cursor:pointer;border:2px solid transparent;transition:border-color .15s"
                 onmouseover="this.style.borderColor='#fff'" onmouseout="this.style.borderColor='transparent'"></div>
            @endforeach
          </div>
        </div>
      </div>
      <div style="background:var(--s2);border-radius:12px;padding:14px;margin-top:4px">
        <div style="font-size:12px;color:var(--t3);margin-bottom:8px">Предпросмотр</div>
        <div style="height:50px;border-radius:8px;background:linear-gradient(135deg," id="preview-banner" style="background:linear-gradient(135deg, var(--acc), var(--acc2))"></div>
      </div>
    </div>

    <!-- SOCIAL TAB -->
    <div id="tab-social" style="display:none">
      <div class="fg"><label>Сайт</label>
        <div style="position:relative">
          <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--t3)">🌐</span>
          <input name="website" value="{{ old('website', auth()->user()->website) }}" placeholder="https://example.com" style="padding-left:34px">
        </div>
      </div>
      <div class="fg"><label>ВКонтакте</label>
        <div style="position:relative">
          <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--t3)">vk.com/</span>
          <input name="vk" value="{{ old('vk', auth()->user()->vk) }}" placeholder="username" style="padding-left:64px">
        </div>
      </div>
      <div class="fg"><label>Telegram</label>
        <div style="position:relative">
          <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--t3)">@</span>
          <input name="telegram" value="{{ old('telegram', auth()->user()->telegram) }}" placeholder="username" style="padding-left:28px">
        </div>
      </div>
      <div class="fg"><label>Instagram</label>
        <div style="position:relative">
          <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--t3)">@</span>
          <input name="instagram" value="{{ old('instagram', auth()->user()->instagram) }}" placeholder="username" style="padding-left:28px">
        </div>
      </div>
    </div>

    <!-- PRIVACY TAB -->
    <div id="tab-privacy" style="display:none">
      <div style="background:var(--s2);border:1px solid var(--b1);border-radius:12px;padding:16px 18px;margin-bottom:16px">
        <div style="display:flex;align-items:center;justify-content:space-between">
          <div>
            <div style="font-weight:600;font-size:14px;color:var(--t1)">🔒 Закрытый профиль</div>
            <div style="font-size:12.5px;color:var(--t2);margin-top:4px">Только подписчики смогут видеть ваши посты и информацию</div>
          </div>
          <label style="position:relative;display:inline-block;width:44px;height:24px;flex-shrink:0">
            <input type="checkbox" name="is_private" value="1" {{ auth()->user()->is_private ? 'checked' : '' }} style="opacity:0;width:0;height:0" id="priv-toggle">
            <span onclick="document.getElementById('priv-toggle').click()" style="position:absolute;cursor:pointer;inset:0;background:{{ auth()->user()->is_private ? 'var(--acc)':'var(--s3)' }};border-radius:24px;transition:background .2s;display:flex;align-items:center;padding:2px" id="priv-thumb">
              <span style="width:20px;height:20px;border-radius:50%;background:#fff;transition:transform .2s;transform:translateX({{ auth()->user()->is_private ? '20px':'0' }})"></span>
            </span>
          </label>
        </div>
      </div>
      <div style="background:rgba(255,95,135,.06);border:1px solid rgba(255,95,135,.2);border-radius:12px;padding:14px 16px">
        <div style="font-weight:600;font-size:13.5px;color:var(--acc2);margin-bottom:8px">⚠ Опасная зона</div>
        <div style="font-size:12.5px;color:var(--t2);margin-bottom:12px">Это действие нельзя отменить. Все данные будут удалены.</div>
        <a href="#" onclick="alert('Для удаления аккаунта свяжитесь с администрацией')" class="btn btn-danger btn-sm">Удалить аккаунт</a>
      </div>
    </div>

    <!-- Hidden file input for avatar -->
    <input type="file" name="avatar" id="av-file" style="display:none">

    <div style="margin-top:22px;display:flex;gap:10px">
      <button type="submit" class="btn btn-p" style="flex:1;justify-content:center">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/></svg>
        Сохранить изменения
      </button>
      <a href="{{ route('profile') }}" class="btn btn-ghost">Отмена</a>
    </div>
  </form>
</div>
</div>

<script>
function showTab(name) {
  ['main','appearance','social','privacy'].forEach(t=>{
    document.getElementById('tab-'+t).style.display = t===name?'block':'none';
    const btn = document.getElementById('tab-btn-'+t);
    btn.style.color = t===name ? 'var(--acc)' : 'var(--t2)';
    btn.style.borderBottomColor = t===name ? 'var(--acc)' : 'transparent';
  });
}
showTab('main');

function previewAv(input) {
  if (!input.files[0]) return;
  const r = new FileReader();
  r.onload = e => document.getElementById('av-prev').src = e.target.result;
  r.readAsDataURL(input.files[0]);
  const dt = new DataTransfer(); dt.items.add(input.files[0]);
  document.getElementById('av-file').files = dt.files;
}
function previewBanner(input) {
  if (!input.files[0]) return;
  const r = new FileReader();
  r.onload = e => document.getElementById('bn-prev').innerHTML = `<img src="${e.target.result}" style="width:100%;height:90px;object-fit:cover;border-radius:10px">`;
  r.readAsDataURL(input.files[0]);
}
document.getElementById('priv-toggle')?.addEventListener('change', function() {
  const thumb = document.getElementById('priv-thumb');
  thumb.style.background = this.checked ? 'var(--acc)' : 'var(--s3)';
  thumb.querySelector('span').style.transform = this.checked ? 'translateX(20px)' : 'translateX(0)';
});
</script>
@endsection
