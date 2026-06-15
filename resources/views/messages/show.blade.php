@extends('layouts.app')
@section('title','Сообщения — Whisper')
@section('content')
<div class="page" style="max-width:800px;padding-top:16px;padding-bottom:16px">
<div class="card" style="margin-bottom:0;display:flex;flex-direction:column;height:calc(100vh - 110px);min-height:500px">

  <!-- Chat header -->
  <div class="chat-h" style="flex-shrink:0">
    <a href="{{ route('messages.inbox') }}" style="color:var(--t2);display:flex;align-items:center;margin-right:4px;transition:color .18s;padding:4px;border-radius:8px"
       onmouseover="this.style.background='rgba(124,90,245,.1)';this.style.color='var(--acc)'"
       onmouseout="this.style.background='transparent';this.style.color='var(--t2)'">
      <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 18l-6-6 6-6"/></svg>
    </a>
    <div style="position:relative">
      <img src="{{ $user->avatar ? asset('storage/avatars/'.$user->avatar) : asset('images/default.png') }}"
           style="width:42px;height:42px;border-radius:50%;object-fit:cover;border:2.5px solid rgba(124,90,245,.4);box-shadow:0 0 0 3px rgba(124,90,245,.08)">
    </div>
    <div style="flex:1;min-width:0">
      <a href="/{{ $user->login }}" style="font-family:'Syne',sans-serif;font-weight:700;font-size:15px;color:var(--t1);text-decoration:none;display:block">
        {{ $user->name ?: $user->login }}
      </a>
      <div style="font-size:12px;color:var(--t3);margin-top:1px;display:flex;align-items:center;gap:5px">
        <span style="display:inline-block;width:6px;height:6px;background:var(--acc3);border-radius:50%;box-shadow:0 0 5px var(--acc3)"></span>
        {{ $user->login }}
      </div>
    </div>
    <a href="/{{ $user->login }}" class="btn btn-ghost btn-sm" style="border:1px solid var(--b1)!important">
      <svg width="13" height="13" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/></svg>
      Профиль
    </a>
  </div>

  <!-- Messages area -->
  <div class="msgs-area" id="msgs" style="flex:1">
    @forelse($messages as $msg)
    @php $isMe = $msg->from_user_id == auth()->id(); @endphp
    <div class="{{ $isMe ? 'bw-me' : 'bw-them' }}">
      @if(!$isMe)
        <img src="{{ $user->avatar ? asset('storage/avatars/'.$user->avatar) : asset('images/default.png') }}" class="bw-av">
      @endif
      <div class="{{ $isMe ? 'bubble bubble-me' : 'bubble bubble-them' }}">
        @if($msg->image)
          <div style="margin-bottom:{{ $msg->body ? '8px' : '0' }}">
            @if($msg->media_type === 'video')
              <video controls style="max-width:100%;max-height:260px;border-radius:10px;display:block">
                <source src="{{ asset('storage/messages/'.$msg->image) }}">
              </video>
            @else
              <a href="{{ asset('storage/messages/'.$msg->image) }}" target="_blank">
                <img src="{{ asset('storage/messages/'.$msg->image) }}"
                     style="max-width:100%;max-height:260px;border-radius:10px;display:block;cursor:zoom-in"
                     onclick="openImg(this.src);event.preventDefault()">
              </a>
            @endif
          </div>
        @endif
        @if($msg->body)
          <div style="word-break:break-word;white-space:pre-wrap">{{ $msg->body }}</div>
        @endif
        <div class="bubble-time" style="text-align:{{ $isMe ? 'right' : 'left' }}">
          {{ $msg->created_at->format('H:i') }}
          @if(!$msg->created_at->isToday()) · {{ $msg->created_at->format('d.m') }} @endif
          @if($isMe) · Вы @endif
        </div>
        @if($isMe)
        <div style="margin-top:5px;display:flex;gap:10px;justify-content:flex-end">
          <button onclick="toggleEdit('{{ $msg->id }}')"
             style="background:rgba(255,255,255,.12);border:none;color:rgba(255,255,255,.75);font-size:11px;font-family:inherit;padding:2px 8px;border-radius:6px;cursor:pointer;transition:all .18s"
             onmouseover="this.style.background='rgba(255,255,255,.22)'"
             onmouseout="this.style.background='rgba(255,255,255,.12)'">✏ изм.</button>
          <form method="POST" action="{{ route('messages.destroy', $msg) }}" style="display:inline" onsubmit="return confirm('Удалить сообщение?')">
            @csrf @method('DELETE')
            <button type="submit"
                style="background:rgba(255,95,135,.15);border:none;color:rgba(255,150,170,.9);font-size:11px;font-family:inherit;padding:2px 8px;border-radius:6px;cursor:pointer;transition:all .18s"
                onmouseover="this.style.background='rgba(255,95,135,.3)'"
                onmouseout="this.style.background='rgba(255,95,135,.15)'">✕ удал.</button>
          </form>
        </div>
        <div id="edit-{{ $msg->id }}" style="display:none;margin-top:8px">
          <form method="POST" action="{{ route('messages.update', $msg) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <textarea name="body" style="width:100%;background:rgba(255,255,255,.14);border:1px solid rgba(255,255,255,.25);border-radius:10px;padding:8px 11px;color:#fff;font-family:inherit;font-size:13.5px;resize:none;outline:none;line-height:1.5">{{ $msg->body }}</textarea>
            <div style="display:flex;gap:7px;margin-top:7px;justify-content:flex-end">
              <button type="button" onclick="toggleEdit('{{ $msg->id }}')" style="background:rgba(0,0,0,.2);color:rgba(255,255,255,.6);border:none;border-radius:20px;padding:5px 13px;cursor:pointer;font-family:inherit;font-size:12px">Отмена</button>
              <button type="submit" style="background:rgba(255,255,255,.22);color:#fff;border:none;border-radius:20px;padding:5px 13px;cursor:pointer;font-family:inherit;font-size:12px;font-weight:600">Сохранить</button>
            </div>
          </form>
        </div>
        @endif
      </div>
    </div>
    @empty
    <div class="empty" style="padding:50px 20px">
      <div class="empty-ico">✨</div>
      <h3>Начни разговор</h3>
      <p>Отправь первое сообщение<br>пользователю {{ $user->name ?: $user->login }}</p>
    </div>
    @endforelse
  </div>

  <!-- Input area -->
  <div class="chat-input" style="flex-shrink:0">
    <form method="POST" action="{{ route('messages.store', $user) }}" enctype="multipart/form-data" id="msgform">
      @csrf
      <div id="mprev" style="margin-bottom:8px"></div>
      <div class="chat-input-row">
        <label style="cursor:pointer;color:var(--t3);display:flex;align-items:center;justify-content:center;
               width:44px;height:44px;border-radius:12px;background:rgba(255,255,255,.04);
               border:1.5px solid var(--b1);flex-shrink:0;transition:all .2s"
               onmouseover="this.style.borderColor='rgba(124,90,245,.5)';this.style.color='var(--acc)'"
               onmouseout="this.style.borderColor='var(--b1)';this.style.color='var(--t3)'">
          <svg width="19" height="19" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
            <rect x="3" y="3" width="18" height="18" rx="3"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/>
          </svg>
          <input type="file" name="media" id="mmedia" accept="image/*,video/*,.gif" style="display:none" onchange="prevMsg(this)">
        </label>
        <textarea name="body" id="msgtext" class="chat-ta" rows="1"
                  placeholder="Написать сообщение... (Ctrl+Enter для отправки)"
                  oninput="autoH(this)"></textarea>
        <button type="submit" class="chat-send" title="Отправить (Ctrl+Enter)">
          <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
            <path d="M22 2L11 13"/><path d="M22 2L15 22l-4-9-9-4 20-7z"/>
          </svg>
        </button>
      </div>
    </form>
  </div>

</div>
</div>

<script>
document.getElementById('msgs').scrollTop = 999999;
function toggleEdit(id){
  const e=document.getElementById('edit-'+id);
  e.style.display=e.style.display==='none'?'block':'none';
}
function prevMsg(input){
  const a=document.getElementById('mprev');a.innerHTML='';
  if(!input.files[0])return;
  const f=input.files[0],r=new FileReader();
  r.onload=e=>{
    const isV=f.type.startsWith('video/');
    a.innerHTML=`<div style="display:inline-flex;align-items:center;gap:10px;padding:8px 12px;background:rgba(124,90,245,.08);border:1px solid rgba(124,90,245,.2);border-radius:10px">
      ${isV
        ?`<video src="${e.target.result}" controls style="max-height:80px;max-width:160px;border-radius:7px"></video>`
        :`<img src="${e.target.result}" style="max-height:80px;border-radius:7px">`}
      <button type="button" onclick="clearM()" style="background:var(--acc2);color:#fff;border:none;border-radius:50%;width:22px;height:22px;cursor:pointer;font-size:15px;line-height:1;flex-shrink:0">×</button>
    </div>`;
  };
  r.readAsDataURL(f);
}
function clearM(){document.getElementById('mmedia').value='';document.getElementById('mprev').innerHTML='';}
document.getElementById('msgtext').addEventListener('keydown',function(e){
  if(e.ctrlKey&&e.key==='Enter')document.getElementById('msgform').submit();
});
</script>
@endsection
