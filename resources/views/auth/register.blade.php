<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Регистрация — Whisper</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
<style>
:root{--bg:#06070f;--b1:rgba(255,255,255,.065);--acc:#7c5af5;--pink:#ff5f87;--t1:#eef0fc;--t2:#9095b5;--t3:#565c7a}
*{box-sizing:border-box;margin:0;padding:0}
body{
  font-family:'Plus Jakarta Sans',sans-serif;background:var(--bg);color:var(--t1);
  min-height:100vh;display:flex;align-items:flex-start;justify-content:center;
  padding:200px 16px;
  background-image:
    radial-gradient(ellipse 70% 50% at 50% -5%,rgba(124,90,245,.13),transparent),
    radial-gradient(ellipse 40% 35% at 85% 75%,rgba(255,95,135,.06),transparent);
}
.orb{position:fixed;border-radius:50%;pointer-events:none;filter:blur(70px)}
.orb-1{width:350px;height:350px;background:rgba(124,90,245,.06);top:-80px;left:-80px}
.orb-2{width:280px;height:280px;background:rgba(255,95,135,.05);bottom:-40px;right:-40px}

/* Виспи-тян */
.wispy-chan{
  position:fixed;pointer-events:none;z-index:10;
  left:36px;top:50%;transform:translateY(-50%);
  width:140px;height:170px;
  filter:drop-shadow(0 8px 24px rgba(124,90,245,.4));
  animation:wc-float 4s ease-in-out infinite;
}
.wispy-chan img{width:100%;height:100%;object-fit:contain}
@keyframes wc-float{0%,100%{transform:translateY(-50%)}50%{transform:translateY(calc(-50% - 12px))}}

.speech-bubble{
  position:fixed;z-index:11;left:198px;top:50%;
  transform:translateY(-50%) scale(.92);
  background:linear-gradient(135deg,rgba(12,13,28,.97),rgba(10,11,22,.99));
  border:1px solid rgba(124,90,245,.28);border-radius:18px;
  padding:15px 18px;max-width:280px;
  font-size:13.5px;line-height:1.65;color:var(--t1);
  box-shadow:0 14px 44px rgba(0,0,0,.6);
  opacity:0;transition:all .4s cubic-bezier(.34,1.5,.64,1);
}
.speech-bubble.show{opacity:1;transform:translateY(-50%) scale(1)}
.speech-bubble::before{
  content:'';position:absolute;left:-10px;top:50%;transform:translateY(-50%);
  border:6px solid transparent;border-right-color:rgba(124,90,245,.28);
}

/* Card */
.container{width:100%;max-width:460px;position:relative;z-index:5}
.card{
  background:linear-gradient(160deg,rgba(12,13,28,.97),rgba(10,11,22,.99));
  border:1px solid var(--b1);border-radius:26px;padding:40px 38px;
  box-shadow:0 30px 80px rgba(0,0,0,.7),0 0 0 1px rgba(124,90,245,.05) inset;
}
.logo{
  text-align:center;font-family:'Syne',sans-serif;font-size:38px;font-weight:800;
  background:linear-gradient(130deg,#c4a8ff,#ff8fae);
  -webkit-background-clip:text;-webkit-text-fill-color:transparent;
  margin-bottom:5px;letter-spacing:-1px;
}
.subtitle{text-align:center;color:var(--t2);font-size:13.5px;margin-bottom:28px}

/* Fields */
.fg{margin-bottom:14px}
.fg label{display:block;font-size:11px;font-weight:700;color:var(--t3);text-transform:uppercase;letter-spacing:.08em;margin-bottom:5px}
.fg input{
  width:100%;background:rgba(255,255,255,.04);border:1.5px solid var(--b1);
  border-radius:12px;padding:12px 15px;color:var(--t1);
  font-family:inherit;font-size:14px;outline:none;transition:all .22s;
}
.fg input::placeholder{color:var(--t3)}
.fg input:focus{border-color:rgba(124,90,245,.55);background:rgba(124,90,245,.03);box-shadow:0 0 0 3px rgba(124,90,245,.08)}
.err{color:#ff8baa;font-size:12px;margin-top:4px;display:flex;align-items:center;gap:4px}

/* Gender picker */
.gender-btns{display:flex;gap:8px}
.gender-btn{
  flex:1;background:rgba(255,255,255,.04);border:1.5px solid var(--b1);
  border-radius:12px;padding:11px 8px;color:var(--t2);
  font-family:inherit;font-size:13px;font-weight:500;
  cursor:pointer;transition:all .2s;text-align:center;
}
.gender-btn:hover{border-color:rgba(124,90,245,.4);color:var(--t1)}
.gender-btn.sel{background:rgba(124,90,245,.14);border-color:var(--acc);color:#c4a8ff;font-weight:600}

/* Terms checkbox */
.chk-row{
  display:flex;align-items:flex-start;gap:9px;
  margin-bottom:14px;font-size:13px;color:var(--t2);
  padding:12px 14px;
  background:rgba(124,90,245,.05);border:1px solid rgba(124,90,245,.12);
  border-radius:12px;
}
.chk-row input[type=checkbox]{
  width:17px;height:17px;accent-color:var(--acc);
  flex-shrink:0;margin-top:1px;cursor:pointer;
}
.chk-row a{color:var(--acc);text-decoration:none;font-weight:500}
.chk-row a:hover{color:var(--pink)}

/* Button */
.btn-submit{
  width:100%;
  background:linear-gradient(135deg,var(--acc) 0%,#9d6bf0 50%,var(--pink) 100%);
  background-size:200% auto;
  color:#fff;border:none;border-radius:40px;padding:14px;
  font-family:inherit;font-weight:700;font-size:15px;cursor:pointer;
  transition:all .3s;box-shadow:0 6px 24px rgba(124,90,245,.4);margin-top:6px;
}
.btn-submit:hover{background-position:right center;transform:translateY(-2px);box-shadow:0 8px 34px rgba(255,95,135,.42)}
.btn-submit:active{transform:translateY(0)}

.foot{text-align:center;margin-top:22px;font-size:14px;color:var(--t2)}
.foot a{color:var(--acc);text-decoration:none;font-weight:600}
.foot a:hover{color:var(--pink)}

.alert-err{
  background:rgba(255,95,135,.08);border:1px solid rgba(255,95,135,.25);
  border-radius:12px;padding:12px 15px;color:#ff8baa;font-size:13px;margin-bottom:18px;
  display:flex;align-items:flex-start;gap:8px;line-height:1.55;
}

/* Sparkles */
.sparkle{position:fixed;border-radius:50%;pointer-events:none;opacity:0;animation:sparkle 3.5s ease-in-out infinite}
@keyframes sparkle{0%,100%{opacity:0;transform:scale(0)}50%{opacity:.65;transform:scale(1.5)}}
.sp1{width:5px;height:5px;background:var(--acc);left:10%;top:18%;animation-delay:0s}
.sp2{width:4px;height:4px;background:var(--pink);right:12%;top:24%;animation-delay:.8s}
.sp3{width:5px;height:5px;background:#36cfb5;left:20%;bottom:20%;animation-delay:1.6s}

@media(max-width:768px){
  .wispy-chan,.speech-bubble{display:none}
  .card{padding:28px 22px;border-radius:20px}
  .logo{font-size:32px}
}
</style>
</head>
<body>

<div class="orb orb-1"></div>
<div class="orb orb-2"></div>
<div class="sparkle sp1"></div>
<div class="sparkle sp2"></div>
<div class="sparkle sp3"></div>

<div class="wispy-chan"><img src="{{ asset('images/Bot.gif') }}" alt="Виспи-тян"></div>
<div class="speech-bubble" id="speech"></div>

<div class="container">
  <div class="card">
    <div class="logo">Whisper</div>
    <p class="subtitle">Создайте свой аккаунт в Виспере ✨</p>

    @if($errors->any())
    <div class="alert-err">
      <svg width="15" height="15" fill="currentColor" viewBox="0 0 24 24" style="flex-shrink:0;margin-top:1px">
        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
      </svg>
      <div>{{ $errors->first() }}</div>
    </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
      @csrf

      {{-- Логин --}}
      <div class="fg">
        <label>Логин</label>
        <input type="text" name="login" value="{{ old('login') }}"
               placeholder="@логин (латиница, без пробелов)" required autocomplete="username">
        @error('login')<div class="err"><span>⚠</span>{{ $message }}</div>@enderror
      </div>

      {{-- Email --}}
      <div class="fg">
        <label>Email</label>
        <input type="email" name="email" value="{{ old('email') }}"
               placeholder="your@email.com" required autocomplete="email">
        @error('email')<div class="err"><span>⚠</span>{{ $message }}</div>@enderror
      </div>

      {{-- Пароль --}}
      <div class="fg">
        <label>Пароль</label>
        <input type="password" name="password"
               placeholder="Минимум 8 символов" required autocomplete="new-password">
        @error('password')<div class="err"><span>⚠</span>{{ $message }}</div>@enderror
      </div>

      {{-- Подтверждение --}}
      <div class="fg">
        <label>Подтверждение пароля</label>
        <input type="password" name="password_confirmation"
               placeholder="Повторите пароль" required autocomplete="new-password">
      </div>

      {{-- Пол — контроллер требует male/female/other --}}
      <div class="fg">
        <label>Пол</label>
        <div class="gender-btns" id="gender-btns">
          <button type="button" class="gender-btn {{ old('gender','other')==='other' ? 'sel':'' }}"
                  onclick="setGender(this,'other')">👤 Не указан</button>
          <button type="button" class="gender-btn {{ old('gender')==='male' ? 'sel':'' }}"
                  onclick="setGender(this,'male')">♂ Мужской</button>
          <button type="button" class="gender-btn {{ old('gender')==='female' ? 'sel':'' }}"
                  onclick="setGender(this,'female')">♀ Женский</button>
        </div>
        {{-- Скрытое поле — контроллер: required|in:male,female,other --}}
        <input type="hidden" name="gender" id="gender-val" value="{{ old('gender','other') }}">
        @error('gender')<div class="err"><span>⚠</span>{{ $message }}</div>@enderror
      </div>

      {{-- Правила — контроллер: terms required|accepted --}}
      <label class="chk-row">
        <input type="checkbox" name="terms" id="terms" value="1" {{ old('terms') ? 'checked' : '' }}>
        <span>Я принимаю <a href="#" onclick="return false">правила</a> и <a href="#" onclick="return false">политику конфиденциальности</a></span>
      </label>
      @error('terms')<div class="err" style="margin-top:-8px;margin-bottom:10px"><span>⚠</span>{{ $message }}</div>@enderror

      <button type="submit" class="btn-submit">Создать аккаунт 🚀</button>
    </form>

    <div class="foot">Уже есть аккаунт? <a href="{{ route('login') }}">Войти</a></div>
  </div>
</div>

<script>
function setGender(btn, val) {
  document.querySelectorAll('.gender-btn').forEach(b => b.classList.remove('sel'));
  btn.classList.add('sel');
  document.getElementById('gender-val').value = val;
}

const msgs = [
  'Добро пожаловать в Виспер! 🌸\n\nЗаполните форму — и мы вместе создадим ваш аккаунт!',
  'Отлично, что вы здесь! 💜\n\nЯ помогу разобраться, если что-то непонятно~',
  'Ура, новый участник! 🎉\n\nЭто займёт всего минуту, обещаю~',
];
function showBubble(text) {
  const b = document.getElementById('speech');
  b.classList.remove('show');
  setTimeout(() => { b.innerHTML = text.replace(/\n/g,'<br>'); b.classList.add('show'); }, 260);
}
setTimeout(() => showBubble(msgs[Math.floor(Math.random() * msgs.length)]), 500);

document.getElementById('terms').addEventListener('change', function() {
  showBubble(this.checked
    ? 'Отлично! Всё по правилам 💜 Осталось только нажать кнопку!'
    : 'Нужно принять правила, иначе не смогу вас зарегистрировать... 🙈');
});
</script>
</body>
</html>
