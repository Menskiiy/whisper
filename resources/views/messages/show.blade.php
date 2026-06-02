@extends('layouts.app')
@section('title','Сообщения — Whisper')
@section('content')
<div class="page" style="max-width:760px">
<div class="card" style="margin-bottom:0">
  <!-- Header -->
  <div class="chat-h">
    <a href="{{ route('messages.inbox') }}" style="color:var(--t2);display:flex;align-items:center;margin-right:2px;transition:color .18s"
       onmouseover="this.style.color='var(--acc)'" onmouseout="this.style.color='var(--t2)'">
      <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 18l-6-6 6-6"/></svg>
    </a>
    <a href="/{{ $user->login }}" style="display:flex;align-items:center">
      <img src="{{ $user->avatar ? asset('storage/avatars/'.$user->avatar) : asset('images/default.png') }}"
           style="width:38px;height:38px;border-radius:50%;object-fit:cover;border:2px solid rgba(124,90,245,0.35)">
    </a>
    <div style="flex:1">
      <div style="font-weight:700;font-size:14.5px;color:var(--t1)">{{ $user->name ?: $user->login }}</div>
      <div style="font-size:12px;color:var(--t3)">{{ $user->login }}</div>
    </div>
    <a href="/{{ $user->login }}" class="btn btn-ghost btn-sm">Профиль</a>
  </div>

  <!-- Messages -->
  <div class="msgs-area" id="msgs">
    @forelse($messages as $msg)
    <div class="{{ $msg->from_user_id == auth()->id() ? 'bw-me' : 'bw-them' }}">
      <div class="{{ $msg->from_user_id == auth()->id() ? 'bubble bubble-me' : 'bubble bubble-them' }}">
        @if($msg->image)
          <div style="margin-bottom:{{ $msg->body ? '9px' : '0' }}">
            @if($msg->media_type === 'video')
              <video controls style="max-width:100%;max-height:280px;border-radius:9px;display:block">
                <source src="{{ asset('storage/messages/'.$msg->image) }}">
              </video>
            @else
              <a href="{{ asset('storage/messages/'.$msg->image) }}" target="_blank">
                <img src="{{ asset('storage/messages/'.$msg->image) }}"
                     style="max-width:100%;max-height:280px;border-radius:9px;display:block">
              </a>
            @endif
          </div>
        @endif
        @if($msg->body)<div style="word-break:break-word;white-space:pre-wrap">{{ $msg->body }}</div>@endif
        <div class="bubble-time">
          {{ $msg->created_at->format('H:i') }}
          @if(!$msg->created_at->isToday()) · {{ $msg->created_at->format('d.m') }} @endif
        </div>
        @if($msg->from_user_id == auth()->id())
        <div style="margin-top:6px;display:flex;gap:10px;font-size:11.5px">
          <a href="#" onclick="event.preventDefault();toggleEdit('{{ $msg->id }}')"
             style="color:rgba(255,255,255,0.6);text-decoration:none"
             onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.6)'">✏ изм.</a>
          <form method="POST" action="{{ route('messages.destroy', $msg) }}" style="display:inline" onsubmit="return confirm('Удалить?')">
            @csrf @method('DELETE')
            <button type="submit" style="background:none;border:none;color:rgba(255,255,255,0.6);cursor:pointer;font-size:11.5px;font-family:inherit;padding:0"
                    onmouseover="this.style.color='#ff5f87'" onmouseout="this.style.color='rgba(255,255,255,0.6)'">✕ удал.</button>
          </form>
        </div>
        <div id="edit-{{ $msg->id }}" style="display:none;margin-top:8px">
          <form method="POST" action="{{ route('messages.update', $msg) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <textarea name="body" style="width:100%;background:rgba(255,255,255,0.12);border:1px solid rgba(255,255,255,0.2);border-radius:8px;padding:7px 10px;color:#fff;font-family:inherit;font-size:13px;resize:none;outline:none">{{ $msg->body }}</textarea>
            <div style="display:flex;gap:7px;margin-top:7px">
              <button type="submit" style="background:rgba(255,255,255,0.2);color:#fff;border:none;border-radius:20px;padding:5px 12px;cursor:pointer;font-family:inherit;font-size:12px">Сохранить</button>
              <button type="button" onclick="toggleEdit('{{ $msg->id }}')" style="background:rgba(0,0,0,0.2);color:rgba(255,255,255,0.6);border:none;border-radius:20px;padding:5px 12px;cursor:pointer;font-family:inherit;font-size:12px">Отмена</button>
            </div>
          </form>
        </div>
        @endif
      </div>
    </div>
    @empty
    <div class="empty" style="padding:44px 20px"><div class="empty-ico">✨</div><h3>Начни разговор</h3><p>Отправь первое сообщение!</p></div>
    @endforelse
  </div>

  <!-- Input -->
  <div class="chat-input">
    <form method="POST" action="{{ route('messages.store', $user) }}" enctype="multipart/form-data" id="msgform">
      @csrf
      <div id="mprev" style="margin-bottom:8px"></div>
      <div class="chat-input-row">
        <label style="cursor:pointer;color:var(--t3);display:flex;align-items:center;transition:color .18s;padding:4px"
               onmouseover="this.style.color='var(--acc)'" onmouseout="this.style.color='var(--t3)'">
          <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="3"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
          <input type="file" name="media" id="mmedia" accept="image/*,video/*,.gif" style="display:none" onchange="prevMsg(this)">
        </label>
        <textarea name="body" id="msgtext" class="chat-ta" rows="1" placeholder="Написать сообщение... (Ctrl+Enter)"
                  oninput="autoH(this)"></textarea>
        <button type="submit" class="btn btn-p" style="height:44px;padding:0 18px;flex-shrink:0">
          <svg width="15" height="15" fill="currentColor" viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
        </button>
      </div>
    </form>
  </div>
</div>
</div>

<script>
document.getElementById('msgs').scrollTop = 999999;
function autoH(ta){ta.style.height='auto';ta.style.height=Math.min(ta.scrollHeight,120)+'px';}
function toggleEdit(id){const e=document.getElementById('edit-'+id);e.style.display=e.style.display==='none'?'block':'none';}
function prevMsg(input){
  const a=document.getElementById('mprev'); a.innerHTML='';
  if(!input.files[0])return;
  const f=input.files[0],r=new FileReader();
  r.onload=e=>{
    const isV=f.type.startsWith('video/');
    a.innerHTML=`<div style="position:relative;display:inline-block">
      ${isV?`<video src="${e.target.result}" controls style="max-width:180px;border-radius:9px;border:1px solid rgba(124,90,245,0.3)"></video>`
           :`<img src="${e.target.result}" style="max-height:120px;border-radius:9px;border:1px solid rgba(124,90,245,0.3)">`}
      <button type="button" onclick="clearM()" style="position:absolute;top:-7px;right:-7px;background:var(--acc2);color:#fff;border:none;border-radius:50%;width:22px;height:22px;cursor:pointer;font-size:15px;line-height:1;display:flex;align-items:center;justify-content:center">×</button>
    </div>`;
  };r.readAsDataURL(f);
}
function clearM(){document.getElementById('mmedia').value='';document.getElementById('mprev').innerHTML='';}
document.getElementById('msgtext').addEventListener('keydown',function(e){if(e.ctrlKey&&e.key==='Enter')document.getElementById('msgform').submit();});
</script>
@endsection
