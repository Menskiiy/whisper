<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Войти — Whisper</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@800&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    <style>
        :root{--bg:#090b12;--surface:#111420;--surface2:#161b2e;--border:rgba(255,255,255,0.07);--text:#e8eaf6;--muted:#7b80a0;--accent:#7c5af5;--pink:#ff6b9d}
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'Plus Jakarta Sans',sans-serif;background:var(--bg);color:var(--text);min-height:100vh;overflow-x:hidden;
          display:flex;align-items:center;justify-content:center;
          background-image:radial-gradient(ellipse 70% 60% at 50% 0%,rgba(124,90,245,0.15),transparent);
          padding:20px}

        /* Виспи-тян */
        .wispy-chan{position:fixed;pointer-events:none;z-index:999;
          transition:transform 0.6s cubic-bezier(0.68,-0.55,0.265,1.55)}
        .wispy-chan img{width:75%;height:75%;object-fit:contain;
          filter:drop-shadow(0 8px 24px rgba(255,107,157,0.4))}
        .wispy-wave{animation:wave 3s ease-in-out infinite}
        @keyframes wave{0%,100%{transform:translateY(0) rotate(-5deg)}50%{transform:translateY(-15px) rotate(5deg)}}

        /* Диалоговое окно */
        .speech-bubble{position:fixed;background:linear-gradient(135deg,rgba(124,90,245,0.95),rgba(255,107,157,0.95));
          border-radius:20px;padding:16px 20px;max-width:320px;color:#fff;font-size:14.5px;line-height:1.6;
          box-shadow:0 12px 40px rgba(0,0,0,0.6);z-index:1000;opacity:0;transform:scale(0.8);
          transition:all 0.4s cubic-bezier(0.68,-0.55,0.265,1.55)}
        .speech-bubble.show{opacity:1;transform:scale(1)}
        .speech-bubble::after{content:'';position:absolute;width:0;height:0;border:12px solid transparent}

        /* Контейнер */
        .container{width:100%;max-width:440px;position:relative;z-index:1}
        .card{background:var(--surface);border:1px solid var(--border);border-radius:24px;padding:44px 40px;
          box-shadow:0 30px 80px rgba(0,0,0,0.5);opacity:0;transform:translateY(20px);
          transition:all 0.5s ease}
        .card.show{opacity:1;transform:translateY(0)}

        .logo{text-align:center;font-family:'Syne',sans-serif;font-size:36px;font-weight:800;
          background:linear-gradient(135deg,#a78bfa,var(--pink));-webkit-background-clip:text;
          -webkit-text-fill-color:transparent;margin-bottom:32px}

        .welcome{text-align:center;color:var(--muted);font-size:14px;margin-bottom:28px;
          opacity:0;animation:fadeIn 0.6s ease 0.3s forwards}
        @keyframes fadeIn{to{opacity:1}}

        .fg{margin-bottom:16px;opacity:0;animation:fadeIn 0.5s ease forwards}
        .fg:nth-child(1){animation-delay:0.4s}
        .fg:nth-child(2){animation-delay:0.5s}
        
        .fg input{width:100%;background:var(--surface2);border:1px solid var(--border);
          border-radius:12px;padding:13px 16px;color:var(--text);font-family:inherit;
          font-size:14.5px;outline:none;transition:all .2s}
        .fg input::placeholder{color:var(--muted)}
        .fg input:focus{border-color:var(--accent);box-shadow:0 0 0 3px rgba(124,90,245,0.1)}
        
        .err{color:var(--pink);font-size:12.5px;margin-top:5px}
        
        .btn{width:100%;background:linear-gradient(135deg,var(--accent),var(--pink));color:#fff;
          border:none;border-radius:40px;padding:14px;font-family:inherit;font-weight:700;
          font-size:15px;cursor:pointer;transition:all .2s;
          box-shadow:0 6px 24px rgba(124,90,245,0.4);margin-top:8px;opacity:0;
          animation:fadeIn 0.5s ease 0.6s forwards}
        .btn:hover{transform:translateY(-2px);box-shadow:0 8px 32px rgba(255,107,157,0.5)}
        
        .row{display:flex;align-items:center;justify-content:space-between;margin-bottom:18px;
          opacity:0;animation:fadeIn 0.5s ease 0.55s forwards}
        .chk{display:flex;align-items:center;gap:8px;color:var(--muted);font-size:13.5px;cursor:pointer}
        .chk input{width:16px;height:16px;accent-color:var(--pink)}
        
        .foot{text-align:center;margin-top:24px;font-size:14px;color:var(--muted);
          opacity:0;animation:fadeIn 0.5s ease 0.7s forwards}
        .foot a{color:var(--accent);text-decoration:none;font-weight:600}
        .foot a:hover{color:var(--pink)}

        .alert-err{background:rgba(255,107,157,.12);border:1px solid rgba(255,107,157,.3);
          border-radius:12px;padding:14px 16px;color:var(--pink);font-size:13px;margin-bottom:20px}

        /* Блики */
        .sparkle{position:fixed;width:4px;height:4px;background:var(--pink);border-radius:50%;
          pointer-events:none;opacity:0;animation:sparkle 2s ease-in-out infinite}
        @keyframes sparkle{0%,100%{opacity:0;transform:scale(0)}
          50%{opacity:1;transform:scale(1.5)}}
        
        .sparkle:nth-child(2){animation-delay:0.3s;left:15%;top:20%}
        .sparkle:nth-child(3){animation-delay:0.6s;right:20%;top:30%}
        .sparkle:nth-child(4){animation-delay:0.9s;left:25%;bottom:25%}
        .sparkle:nth-child(5){animation-delay:1.2s;right:15%;bottom:20%}
    </style>
</head>
<body>

<!-- Виспи-тян -->
<div class="wispy-chan wispy-bounce" id="wispy">
  <img src="{{ asset('images/Bot.gif') }}" alt="Виспи-тян" id="wispy-img">
</div>

<!-- Диалог -->
<div class="speech-bubble left" id="speech"></div>

<!-- Блики -->
<div class="sparkle"></div>
<div class="sparkle"></div>
<div class="sparkle"></div>
<div class="sparkle"></div>
<div class="sparkle"></div>

<div class="container">
    <div class="card" id="card">
        <div class="logo">Whisper</div>
        <div class="welcome" id="welcome-text">Вы вернулись, Мастер!~</div>

        @if($errors->any())
            <div class="alert-err">
                Неверный логин или пароль. Попробуйте ещё раз, Мастер~
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="fg">
                <input type="text" name="login" value="{{ old('login') }}" placeholder="Логин или email" required autofocus id="login-input">
            </div>
            <div class="fg">
                <input type="password" name="password" placeholder="Пароль" required id="password-input">
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
            Нет аккаунта?
            <a href="{{ route('register') }}">Зарегистрироваться</a>
        </div>
    </div>
</div>

<script>
const welcomeMessages = [
  "Вы вернулись, Мастер!~ Как же я соскучилась! Давайте я помогу вам войти в ваш аккаунт~",
  "С возвращением, Мастер!~ В ваше отсутствие здесь появилось много интересного!",
  "Ах, Мастер!~ Вы наконец-то вернулись! Скорее входите, вас ждут новые приключения!~"
];

const inputMessages = [
  "Вводите смелее, Мастер! Я помню вас~",
  "Точно-точно, это вы! Узнаю вашу энергию~",
  "Ещё чуть-чуть, и мы встретимся снова, Мастер!~"
];

let messageIndex = 0;

// Инициализация
window.onload = () => {
  // Выбираем случайное приветствие
  const randomWelcome = welcomeMessages[Math.floor(Math.random() * welcomeMessages.length)];
  
  setTimeout(() => {
    document.getElementById('card').classList.add('show');
    showDialogue(randomWelcome);
    positionWispy();
  }, 300);
};

function showDialogue(text) {
  const bubble = document.getElementById('speech');
  
  bubble.classList.remove('show');
  
  setTimeout(() => {
    bubble.textContent = text;
    bubble.style.left = '230px';
    bubble.style.top = '50%';
    bubble.style.transform = 'translateY(-50%)';
    
    setTimeout(() => bubble.classList.add('show'), 50);
  }, 300);
}

function positionWispy() {
  const wispy = document.getElementById('wispy');
  wispy.style.left = '40px';
  wispy.style.top = '50%';
  wispy.style.transform = 'translateY(-50%)';
}

// Реакции на ввод
document.addEventListener('DOMContentLoaded', () => {
  const loginInput = document.getElementById('login-input');
  const passwordInput = document.getElementById('password-input');
  
  let lastInteraction = 0;
  
  const handleInput = () => {
    const now = Date.now();
    if (now - lastInteraction > 3000) {
      const message = inputMessages[messageIndex % inputMessages.length];
      showDialogue(message);
      messageIndex++;
      
      // Виспи-тян подпрыгивает
      const wispy = document.getElementById('wispy');
      wispy.style.animation = 'none';
      setTimeout(() => wispy.style.animation = '', 10);
    }
    lastInteraction = now;
  };
  
  loginInput.addEventListener('input', handleInput);
  passwordInput.addEventListener('input', handleInput);
  
  // При фокусе на полях
  loginInput.addEventListener('focus', () => {
    if (loginInput.value === '') {
      showDialogue("Ваш логин или email, Мастер~ Я буду ждать!");
    }
  });
  
  passwordInput.addEventListener('focus', () => {
    showDialogue("Секретное заклинание, помните?~ Только тихо-тихо~");
  });
});

// Анимация при отправке формы
document.querySelector('form').addEventListener('submit', (e) => {
  showDialogue("Сейчас проверю... Минуточку, Мастер!~");
  
  const wispy = document.getElementById('wispy');
  wispy.style.animation = 'none';
  wispy.style.transform = 'translateY(-50%) rotate(360deg)';
  
  setTimeout(() => {
    wispy.style.transition = 'transform 0.6s ease';
  }, 100);
});
</script>

</body>
</html>
