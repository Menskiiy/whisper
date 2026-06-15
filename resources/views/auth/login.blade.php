<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Войти — Whisper</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
<style>
:root{--bg:#06070f;--s1:#0c0d1c;--s2:#111322;--b1:rgba(255,255,255,.065);--acc:#7c5af5;--pink:#ff5f87;--t1:#eef0fc;--t2:#9095b5;--t3:#565c7a}
*{box-sizing:border-box;margin:0;padding:0}
body{
  font-family:'Plus Jakarta Sans',sans-serif;background:var(--bg);color:var(--t1);
  min-height:100vh;overflow-x:hidden;display:flex;align-items:center;justify-content:center;padding:20px;
  background-image:
    radial-gradient(ellipse 70% 60% at 50% -5%,rgba(124,90,245,.14),transparent),
    radial-gradient(ellipse 40% 40% at 80% 70%,rgba(255,95,135,.06),transparent);
}

/* Floating orbs */
.orb{position:fixed;border-radius:50%;pointer-events:none;filter:blur(80px);animation:orb-float 8s ease-in-out infinite}
.orb-1{width:400px;height:400px;background:rgba(124,90,245,.07);top:-100px;left:-100px;animation-delay:0s}
.orb-2{width:300px;height:300px;background:rgba(255,95,135,.05);bottom:-50px;right:-50px;animation-delay:3s}
@keyframes orb-float{0%,100%{transform:translate(0,0)}50%{transform:translate(20px,15px)}}

/* Виспи-тян */
.wispy-chan{
  position:fixed;pointer-events:none;z-index:10;
  left:48px;top:50%;transform:translateY(-50%);
  width:160px;height:195px;
  filter:drop-shadow(0 8px 28px rgba(124,90,245,.45));
  animation:wc-float 4s ease-in-out infinite;
}
.wispy-chan img{width:100%;height:100%;object-fit:contain}
@keyframes wc-float{0%,100%{transform:translateY(-50%)}50%{transform:translateY(calc(-50% - 14px))}}

/* Speech bubble */
.speech-bubble{
  position:fixed;z-index:11;
  left:230px;top:50%;transform:translateY(-50%);
  background:linear-gradient(135deg,rgba(15,16,36,.97),rgba(12,13,26,.99));
  border:1px solid rgba(124,90,245,.3);border-radius:18px;
  padding:16px 20px;max-width:300px;
  font-size:14.5px;line-height:1.65;color:var(--t1);
  box-shadow:0 16px 50px rgba(0,0,0,.65),0 0 0 1px rgba(124,90,245,.1) inset;
  opacity:0;transform:translateY(-50%) scale(.92);
  transition:all .4s cubic-bezier(.34,1.5,.64,1);
}
.speech-bubble.show{opacity:1;transform:translateY(-50%) scale(1)}
.speech-bubble::before{
  content:'';position:absolute;left:-10px;top:50%;transform:translateY(-50%);
  border:6px solid transparent;border-right-color:rgba(124,90,245,.3);
}

/* Container */
.container{width:100%;max-width:440px;position:relative;z-index:5}
.card{
  background:linear-gradient(160deg,rgba(12,13,28,.97) 0%,rgba(10,11,22,.99) 100%);
  border:1px solid var(--b1);border-radius:26px;padding:46px 40px;
  box-shadow:0 32px 90px rgba(0,0,0,.75),0 0 0 1px rgba(124,90,245,.05) inset;
  opacity:0;transform:translateY(22px);transition:all .5s cubic-bezier(.34,1.2,.64,1);
}
.card.show{opacity:1;transform:translateY(0)}

.logo{
  text-align:center;font-family:'Syne',sans-serif;font-size:40px;font-weight:800;
  background:linear-gradient(130deg,#c4a8ff 0%,#ff8fae 100%);
  -webkit-background-clip:text;-webkit-text-fill-color:transparent;
  margin-bottom:6px;letter-spacing:-1px;
}
.subtitle{
  text-align:center;color:var(--t2);font-size:14px;margin-bottom:32px;
  opacity:0;animation:fadeIn .6s ease .3s forwards;
}
@keyframes fadeIn{to{opacity:1}}

.fg{margin-bottom:16px;opacity:0;animation:fadeIn .5s ease forwards}
.fg:nth-child(1){animation-delay:.35s}
.fg:nth-child(2){animation-delay:.45s}
.fg label{display:block;font-size:11px;font-weight:700;color:var(--t3);text-transform:uppercase;letter-spacing:.08em;margin-bottom:6px}
.fg input{
  width:100%;background:rgba(255,255,255,.04);border:1.5px solid var(--b1);
  border-radius:13px;padding:13px 16px;color:var(--t1);
  font-family:inherit;font-size:14.5px;outline:none;transition:all .22s;
}
.fg input::placeholder{color:var(--t3)}
.fg input:focus{border-color:rgba(124,90,245,.55);background:rgba(124,90,245,.03);box-shadow:0 0 0 4px rgba(124,90,245,.08)}

.err{color:#ff8baa;font-size:12.5px;margin-top:5px;display:flex;align-items:center;gap:5px}

.btn{
  width:100%;
  background:linear-gradient(135deg,var(--acc) 0%,#9d6bf0 50%,var(--pink) 100%);
  background-size:200% auto;
  color:#fff;border:none;border-radius:40px;padding:15px;
  font-family:inherit;font-weight:700;font-size:15px;cursor:pointer;
  transition:all .3s;box-shadow:0 6px 26px rgba(124,90,245,.45);
  margin-top:8px;opacity:0;animation:fadeIn .5s ease .6s forwards;
}
.btn:hover{background-position:right center;transform:translateY(-2px);box-shadow:0 8px 36px rgba(255,95,135,.45)}
.btn:active{transform:translateY(0)}

.row{
  display:flex;align-items:center;justify-content:space-between;
  margin-bottom:18px;opacity:0;animation:fadeIn .5s ease .55s forwards;
}
.chk{display:flex;align-items:center;gap:8px;color:var(--t2);font-size:13.5px;cursor:pointer}
.chk input{width:16px;height:16px;accent-color:var(--acc);border-radius:4px}

.foot{
  text-align:center;margin-top:26px;font-size:14px;color:var(--t2);
  opacity:0;animation:fadeIn .5s ease .7s forwards;
}
.foot a{color:var(--acc);text-decoration:none;font-weight:600;transition:color .2s}
.foot a:hover{color:var(--pink)}

.alert-err{
  background:rgba(255,95,135,.08);border:1px solid rgba(255,95,135,.25);
  border-radius:12px;padding:13px 16px;color:#ff8baa;font-size:13px;margin-bottom:20px;
  display:flex;align-items:center;gap:8px;
}

/* Sparkles */
.sparkle{position:fixed;width:5px;height:5px;border-radius:50%;pointer-events:none;opacity:0;animation:sparkle 3s ease-in-out infinite}
@keyframes sparkle{0%,100%{opacity:0;transform:scale(0) rotate(0deg)}50%{opacity:.8;transform:scale(1.5) rotate(180deg)}}
.sp1{background:var(--acc);left:12%;top:22%;animation-delay:.0s}
.sp2{background:var(--pink);right:14%;top:18%;animation-delay:.6s}
.sp3{background:#36cfb5;left:18%;bottom:28%;animation-delay:1.2s}
.sp4{background:var(--acc);right:18%;bottom:22%;animation-delay:1.8s}
.sp5{background:var(--pink);left:50%;top:12%;animation-delay:.9s}

@media(max-width:768px){
  .wispy-chan,.speech-bubble{display:none}
  .card{padding:32px 26px;border-radius:22px}
  .logo{font-size:34px}
}
</style>
</head>
<body>

<div class="orb orb-1"></div>
<div class="orb orb-2"></div>
<div class="sparkle sp1"></div>
<div class="sparkle sp2"></div>
<div class="sparkle sp3"></div>
<div class="sparkle sp4"></div>
<div class="sparkle sp5"></div>

<div class="wispy-chan" id="wispy">
  <img src="{{ asset('images/Bot.gif') }}" alt="Виспи-тян">
</div>
<div class="speech-bubble" id="speech"></div>

<div class="container">
  <div class="card" id="card">
    <div class="logo">Whisper</div>
    <p class="subtitle" id="welcome-text">С возвращением! 🌸</p>

    @if($errors->any())
    <div class="alert-err">
      <svg width="15" height="15" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
      Неверный логин или пароль. Попробуйте ещё раз~
    </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
      @csrf
      <div class="fg">
        <label>Логин или Email</label>
        <input type="text" name="login" value="{{ old('login') }}" placeholder="Введите логин или email" required autofocus id="login-input">
      </div>
      <div class="fg">
        <label>Пароль</label>
        <input type="password" name="password" placeholder="Ваш секретный пароль" required id="password-input">
      </div>
      <div class="row">
        <label class="chk">
          <input type="checkbox" name="remember">
          Запомнить меня
        </label>
      </div>
      <button type="submit" class="btn">Войти ✨</button>
    </form>

    <div class="foot">
      Нет аккаунта? <a href="{{ route('register') }}">Зарегистрироваться</a>
    </div>
  </div>
</div>

<script>
const welcomeMessages = [
  "Вы вернулись, Мастер! 🌸✨\n\nКак же я соскучилась! Давайте быстрее войдём в ваш аккаунт~",
  "С возвращением, Мастер! 💜\n\nВ ваше отсутствие здесь появилось много интересного!",
  "Ах, наконец-то! 🎉\n\nПостараюсь помочь вам войти как можно быстрее~",
];
const inputMessages = [
  "Вводите смелее, Мастер~ Я узнаю вас! 💜",
  "Точно-точно, это вы! 🌸",
  "Ещё чуть-чуть, и мы встретимся снова!~",
];

let msgIdx = 0;
function showDialogue(text) {
  const b = document.getElementById('speech');
  b.classList.remove('show');
  setTimeout(() => { b.innerHTML = text.replace(/\n/g,'<br>'); b.classList.add('show'); }, 280);
}

window.onload = () => {
  setTimeout(() => { document.getElementById('card').classList.add('show'); }, 200);
  setTimeout(() => { showDialogue(welcomeMessages[Math.floor(Math.random()*welcomeMessages.length)]); }, 600);
};

let lastInput = 0;
function handleInput() {
  const now = Date.now();
  if (now - lastInput > 3200) { showDialogue(inputMessages[msgIdx++ % inputMessages.length]); }
  lastInput = now;
}
document.getElementById('login-input').addEventListener('input', handleInput);
document.getElementById('password-input').addEventListener('input', handleInput);
document.getElementById('login-input').addEventListener('focus', () => showDialogue("Ваш логин или email, Мастер~ Я жду!"));
document.getElementById('password-input').addEventListener('focus', () => showDialogue("Секретное заклинание... только тихо-тихо~ 🤫"));
document.querySelector('form').addEventListener('submit', () => showDialogue("Сейчас проверю! Минуточку, Мастер!~"));
</script>
</body>
</html>
