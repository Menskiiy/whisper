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
:root{--bg:#07080f;--s1:#0f1120;--s2:#141828;--b1:rgba(255,255,255,0.06);--acc:#7c5af5;--pink:#ff6b9d;--t1:#eceef8;--t2:#9ba0bf;--t3:#5a607a}
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Plus Jakarta Sans',sans-serif;background:var(--bg);color:var(--t1);min-height:100vh;overflow-x:hidden;
  background-image:radial-gradient(ellipse 70% 60% at 50% 0%,rgba(124,90,245,.14),transparent);
  display:flex;align-items:center;justify-content:center;padding:20px}

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
.container{width:100%;max-width:480px;position:relative;z-index:1}
.card{background:var(--s1);border:1px solid var(--b1);border-radius:22px;padding:40px 38px;
  box-shadow:0 28px 70px rgba(0,0,0,.6);opacity:0;transform:translateY(20px);
  transition:all 0.5s ease}
.card.show{opacity:1;transform:translateY(0)}

.logo{text-align:center;font-family:'Syne',sans-serif;font-size:34px;font-weight:800;
  background:linear-gradient(130deg,#a78bfa,var(--pink));-webkit-background-clip:text;
  -webkit-text-fill-color:transparent;margin-bottom:32px}

/* Этапы формы */
.step{display:none;opacity:0;transform:translateY(10px);transition:all 0.4s ease}
.step.active{display:block;animation:fadeInUp 0.5s ease forwards}
@keyframes fadeInUp{to{opacity:1;transform:translateY(0)}}

.fg{margin-bottom:18px}
.fg label{display:block;font-size:13px;font-weight:600;color:var(--t2);margin-bottom:8px}
.fg input,.fg select{width:100%;background:var(--s2);border:1px solid var(--b1);
  border-radius:12px;padding:13px 16px;color:var(--t1);font-family:inherit;font-size:14px;
  outline:none;transition:all .2s}
.fg input::placeholder{color:var(--t3)}
.fg input:focus,.fg select:focus{border-color:var(--acc);box-shadow:0 0 0 3px rgba(124,90,245,0.1)}
.fg select{-webkit-appearance:none;cursor:pointer;
  background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%239ba0bf' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
  background-repeat:no-repeat;background-position:right 14px center}
.fg select option{background:var(--s2);color:var(--t1)}

.gender-btns{display:flex;gap:10px}
.gender-btn{flex:1;background:var(--s2);border:2px solid var(--b1);border-radius:12px;
  padding:12px;text-align:center;cursor:pointer;transition:all .2s;font-size:13.5px;color:var(--t2)}
.gender-btn:hover{border-color:var(--acc);color:var(--t1)}
.gender-btn.selected{border-color:var(--pink);background:rgba(255,107,157,0.1);color:var(--pink)}

.chk-row{display:flex;align-items:flex-start;gap:10px;margin-bottom:20px;font-size:13px;color:var(--t2)}
.chk-row input[type=checkbox]{width:18px;height:18px;accent-color:var(--pink);flex-shrink:0;margin-top:2px}
.chk-row a{color:var(--acc);text-decoration:none}

.btn{width:100%;background:linear-gradient(135deg,var(--acc),var(--pink));color:#fff;
  border:none;border-radius:40px;padding:14px;font-family:inherit;font-weight:700;
  font-size:15px;cursor:pointer;transition:all .2s;
  box-shadow:0 6px 24px rgba(124,90,245,.4);margin-top:8px}
.btn:hover{transform:translateY(-2px);box-shadow:0 8px 32px rgba(255,107,157,.5)}
.btn:disabled{opacity:0.5;cursor:not-allowed;transform:none}

.btn-outline{background:transparent;border:2px solid var(--b1);color:var(--t2);box-shadow:none}
.btn-outline:hover{border-color:var(--acc);color:var(--t1);box-shadow:none}

.err{color:var(--pink);font-size:12px;margin-top:6px}
.alert-err{background:rgba(255,107,157,.12);border:1px solid rgba(255,107,157,.3);
  border-radius:12px;padding:14px 16px;color:var(--pink);font-size:13px;margin-bottom:20px}

.foot{text-align:center;margin-top:24px;font-size:13.5px;color:var(--t2)}
.foot a{color:var(--acc);text-decoration:none;font-weight:600}
.foot a:hover{color:var(--pink)}

.progress{display:flex;gap:8px;justify-content:center;margin-bottom:28px}
.progress-dot{width:8px;height:8px;border-radius:50%;background:var(--b1);transition:all .3s}
.progress-dot.active{background:var(--pink);width:24px;border-radius:4px}

/* Скрытая форма для отправки */
#real-form{display:none}
</style>
</head>
<body>

<!-- Виспи-тян -->
<div class="wispy-chan wispy-bounce" id="wispy">
  <img src="{{ asset('images/Bot.gif') }}" alt="Виспи-тян" id="wispy-img">
</div>

<!-- Диалог -->
<div class="speech-bubble" id="speech"></div>

<div class="container">
  <div class="card" id="card">
    <div class="logo">Whisper</div>
    
    @if($errors->any())
    <div class="alert-err">
      @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
    </div>
    @endif

    <div class="progress" id="progress">
      <div class="progress-dot active"></div>
      <div class="progress-dot"></div>
      <div class="progress-dot"></div>
      <div class="progress-dot"></div>
      <div class="progress-dot"></div>
    </div>

    <!-- Этап 0: Приветствие -->
    <div class="step active" id="step-0">
      <button type="button" class="btn" onclick="nextStep()">Начать регистрацию</button>
    </div>

    <!-- Этап 1: Логин -->
    <div class="step" id="step-1">
      <div class="fg">
        <label>Как вас называть, Мастер?~</label>
        <input type="text" id="login" placeholder="Введите логин" autofocus>
        <div class="err" id="err-login"></div>
      </div>
      <button type="button" class="btn" onclick="nextStep()">Далее</button>
    </div>

    <!-- Этап 2: Пол -->
    <div class="step" id="step-2">
      <div class="fg">
        <label>А теперь... немного о вас~</label>
        <div class="gender-btns">
          <div class="gender-btn" onclick="selectGender('male')">
            <div style="font-size:24px;margin-bottom:4px">👨</div>
            Мужской
          </div>
          <div class="gender-btn" onclick="selectGender('female')">
            <div style="font-size:24px;margin-bottom:4px">👩</div>
            Женский
          </div>
          <div class="gender-btn" onclick="selectGender('other')">
            <div style="font-size:24px;margin-bottom:4px">✨</div>
            Другой
          </div>
        </div>
        <input type="hidden" id="gender" value="male">
      </div>
      <button type="button" class="btn" onclick="nextStep()">Далее</button>
    </div>

    <!-- Этап 3: Email -->
    <div class="step" id="step-3">
      <div class="fg">
        <label>Куда вам присылать магию?~</label>
        <input type="email" id="email" placeholder="your@email.com">
        <div class="err" id="err-email"></div>
      </div>
      <button type="button" class="btn" onclick="nextStep()">Далее</button>
    </div>

    <!-- Этап 4: Пароль -->
    <div class="step" id="step-4">
      <div class="fg">
        <label>Придумайте секретное заклинание~</label>
        <input type="password" id="password" placeholder="Минимум 8 символов">
        <div class="err" id="err-password"></div>
      </div>
      <div class="fg">
        <label>Повторите его, чтобы не забыть!</label>
        <input type="password" id="password_confirmation" placeholder="Ещё раз">
        <div class="err" id="err-confirmation"></div>
      </div>
      <button type="button" class="btn" onclick="nextStep()">Далее</button>
    </div>

    <!-- Этап 5: Соглашение -->
    <div class="step" id="step-5">
      <div class="chk-row">
        <input type="checkbox" id="terms" value="1">
        <label for="terms">Я принимаю <a href="#">правила сервиса</a> и обещаю быть хорошим Мастером~</label>
      </div>
      <div class="err" id="err-terms"></div>
      <button type="button" class="btn" onclick="submitForm()">Создать аккаунт! ✨</button>
      <button type="button" class="btn btn-outline" onclick="prevStep()" style="margin-top:10px">Назад</button>
    </div>

    <div class="foot">
      Уже есть аккаунт? <a href="{{ route('login') }}">Войти</a>
    </div>
  </div>
</div>

<!-- Реальная форма для отправки -->
<form method="POST" action="{{ route('register') }}" id="real-form">
  @csrf
  <input type="hidden" name="login" id="real-login">
  <input type="hidden" name="gender" id="real-gender">
  <input type="hidden" name="email" id="real-email">
  <input type="hidden" name="password" id="real-password">
  <input type="hidden" name="password_confirmation" id="real-password-confirmation">
  <input type="hidden" name="terms" id="real-terms">
</form>

<script>
let currentStep = 0;
const totalSteps = 5;

const dialogues = [
  { text: "Приветствую вас, Мастер!~ Я Виспи-тян, ваш проводник в мир Whisper! Здесь собираются удивительные люди, делятся историями и находят друзей. Готовы присоединиться к нам?~", position: 'left' },
  { text: "Буду рада обращаться к вам так~", position: 'right' },
  { text: "Хм-м~~ Понятно! Это поможет мне лучше понять вас, Мастер~", position: 'left' },
  { text: "На эту почту я буду присылать важные новости и магические уведомления!~", position: 'right' },
  { text: "Держите свое заклинание в секрете, Мастер~ Никому его не показывайте!", position: 'left' },
  { text: "Почти готово! Осталось только согласиться с правилами, и мы начнём ваше приключение в Whisper!~", position: 'right' }
];

// Инициализация
window.onload = () => {
  setTimeout(() => {
    document.getElementById('card').classList.add('show');
    showDialogue(0);
    positionWispy('left', false);
  }, 300);
};

function showDialogue(step) {
  const dialogue = dialogues[step];
  const bubble = document.getElementById('speech');
  const wispy = document.getElementById('wispy');
  
  // Скрываем старый диалог
  bubble.classList.remove('show');
  
  setTimeout(() => {
    // 1. Сразу удаляем старые стили позиции
    bubble.style.removeProperty('left');
    bubble.style.removeProperty('right');
    
    // 2. Обновляем текст и класс
    bubble.textContent = dialogue.text;
    bubble.className = 'speech-bubble ' + dialogue.position;
    
    // 3. Устанавливаем позицию только на десктопе
    //    На мобильных responsive.css ставит пузырь в угол через !important
    if (window.innerWidth > 768) {
      if (dialogue.position === 'left') {
        bubble.style.left = '230px';
        bubble.style.top = '50%';
        bubble.style.transform = 'translateY(-50%)';
      } else {
        bubble.style.right = '230px';
        bubble.style.top = '50%';
        bubble.style.transform = 'translateY(-50%)';
      }
    }
    
    // 4. Показываем
    setTimeout(() => bubble.classList.add('show'), 50);
  }, 300);
}

function positionWispy(side, flip = false) {
  // На мобильных — CSS сам ставит Виспи в угол, ничего не делаем
  if (window.innerWidth <= 768) return;
  
  const wispy = document.getElementById('wispy');
  const img = document.getElementById('wispy-img');
  
  if (side === 'left') {
    wispy.style.left = '40px';
    wispy.style.right = 'auto';
    wispy.style.top = '50%';
    wispy.style.transform = 'translateY(-50%)' + (flip ? ' scaleX(-1)' : '');
  } else {
    wispy.style.right = '40px';
    wispy.style.left = 'auto';
    wispy.style.top = '50%';
    wispy.style.transform = 'translateY(-50%)' + (flip ? ' scaleX(-1)' : '');
  }
}

function nextStep() {
  // Валидация текущего шага
  if (!validateStep(currentStep)) return;
  
  currentStep++;
  if (currentStep > totalSteps) return;
  
  // Скрываем текущий шаг
  document.querySelectorAll('.step').forEach(s => s.classList.remove('active'));
  
  // Показываем следующий
  setTimeout(() => {
    document.getElementById('step-' + currentStep).classList.add('active');
    showDialogue(currentStep);
    
    // Двигаем Виспи-тян
    if (currentStep % 2 === 0) {
      positionWispy('left', false);
    } else {
      positionWispy('right', true);
    }
    
    // Обновляем прогресс
    updateProgress();
  }, 200);
}

function prevStep() {
  if (currentStep === 0) return;
  currentStep--;
  
  document.querySelectorAll('.step').forEach(s => s.classList.remove('active'));
  document.getElementById('step-' + currentStep).classList.add('active');
  
  if (currentStep % 2 === 0) {
    positionWispy('left', false);
  } else {
    positionWispy('right', true);
  }
  
  updateProgress();
}

function updateProgress() {
  const dots = document.querySelectorAll('.progress-dot');
  dots.forEach((dot, i) => {
    if (i < currentStep) {
      dot.classList.add('active');
    } else {
      dot.classList.remove('active');
    }
  });
}

function selectGender(gender) {
  document.querySelectorAll('.gender-btn').forEach(btn => btn.classList.remove('selected'));
  event.target.closest('.gender-btn').classList.add('selected');
  document.getElementById('gender').value = gender;
}

function validateStep(step) {
  clearErrors();
  
  if (step === 1) {
    const login = document.getElementById('login').value.trim();
    if (!login || login.length < 3) {
      showError('err-login', 'Логин должен быть не менее 3 символов');
      return false;
    }
  }
  
  if (step === 3) {
    const email = document.getElementById('email').value.trim();
    if (!email || !email.includes('@')) {
      showError('err-email', 'Введите корректный email');
      return false;
    }
  }
  
  if (step === 4) {
    const password = document.getElementById('password').value;
    const confirmation = document.getElementById('password_confirmation').value;
    
    if (password.length < 8) {
      showError('err-password', 'Пароль должен быть не менее 8 символов');
      return false;
    }
    
    if (password !== confirmation) {
      showError('err-confirmation', 'Пароли не совпадают');
      return false;
    }
  }
  
  return true;
}

function showError(id, text) {
  document.getElementById(id).textContent = text;
}

function clearErrors() {
  document.querySelectorAll('.err').forEach(err => err.textContent = '');
}

function submitForm() {
  if (!document.getElementById('terms').checked) {
    showError('err-terms', 'Необходимо принять правила');
    return;
  }
  
  // Копируем данные в реальную форму
  document.getElementById('real-login').value = document.getElementById('login').value;
  document.getElementById('real-gender').value = document.getElementById('gender').value;
  document.getElementById('real-email').value = document.getElementById('email').value;
  document.getElementById('real-password').value = document.getElementById('password').value;
  document.getElementById('real-password-confirmation').value = document.getElementById('password_confirmation').value;
  document.getElementById('real-terms').value = document.getElementById('terms').checked ? '1' : '';
  
  // Отправляем форму
  document.getElementById('real-form').submit();
}

// Анимация при вводе
document.addEventListener('DOMContentLoaded', () => {
  const inputs = document.querySelectorAll('input[type="text"], input[type="email"], input[type="password"]');
  inputs.forEach(input => {
    input.addEventListener('focus', () => {
      // Виспи-тян подпрыгивает
      const wispy = document.getElementById('wispy');
      wispy.style.animation = 'none';
      setTimeout(() => wispy.style.animation = '', 10);
    });
  });
});
</script>

</body>
</html>
