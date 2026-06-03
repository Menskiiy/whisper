<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title','Whisper')</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
<style>
:root{
  --bg:#07080f;--s1:#0f1120;--s2:#141828;--s3:#1b2038;
  --b1:rgba(255,255,255,0.06);--b2:rgba(255,255,255,0.1);
  --acc:#7c5af5;--acc2:#ff5f87;--acc3:#36cfb5;
  --t1:#eceef8;--t2:#9ba0bf;--t3:#5a607a;
  --glow:rgba(124,90,245,.18);
}
*{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}
body{font-family:'Plus Jakarta Sans',sans-serif;background:var(--bg);color:var(--t1);min-height:100vh;
  background-image:radial-gradient(ellipse 90% 50% at 50% -10%,rgba(124,90,245,.08),transparent)}

/* NAV */
.nav{position:sticky;top:0;z-index:500;height:56px;
  background:rgba(7,8,15,.92);backdrop-filter:blur(24px) saturate(180%);
  border-bottom:1px solid var(--b1);display:flex;align-items:center;padding:0 20px;gap:12px}
.nav-logo{font-family:'Syne',sans-serif;font-weight:800;font-size:20px;
  background:linear-gradient(130deg,#a78bfa,#ff5f87);-webkit-background-clip:text;
  -webkit-text-fill-color:transparent;text-decoration:none;flex-shrink:0}
.nav-links{display:flex;gap:1px;flex:1;justify-content:center;flex-wrap:nowrap}
.nav-a{color:var(--t2);text-decoration:none;font-size:12.5px;font-weight:500;
  padding:6px 10px;border-radius:9px;transition:all .18s;
  display:flex;align-items:center;gap:5px;position:relative;white-space:nowrap}
.nav-a:hover{color:var(--t1);background:rgba(255,255,255,0.05)}
.nav-a.on{color:var(--acc);background:rgba(124,90,245,.1)}
.nav-badge{position:absolute;top:2px;right:2px;min-width:16px;height:16px;
  background:var(--acc2);border-radius:8px;font-size:10px;font-weight:700;color:#fff;
  display:flex;align-items:center;justify-content:center;padding:0 3px;border:2px solid var(--bg)}
.nav-right{display:flex;align-items:center;gap:8px;flex-shrink:0}
.nav-srch{display:flex;align-items:center;gap:6px;background:var(--s2);border:1px solid var(--b1);
  border-radius:9px;padding:6px 11px;color:var(--t2);font-size:12.5px;cursor:pointer;
  text-decoration:none;transition:all .18s}
.nav-srch:hover{border-color:var(--acc);color:var(--t1)}
.user-pill{display:flex;align-items:center;gap:8px;background:var(--s1);border:1px solid var(--b1);
  border-radius:40px;padding:3px 10px 3px 3px;text-decoration:none;transition:border-color .18s}
.user-pill:hover{border-color:var(--acc)}
.user-pill img{width:28px;height:28px;border-radius:50%;object-fit:cover;border:2px solid var(--acc)}
.user-pill-n{font-size:12px;font-weight:600;color:var(--t1)}
.nav-logout{background:none;border:none;color:var(--t3);cursor:pointer;padding:4px;display:flex;align-items:center;transition:color .18s}
.nav-logout:hover{color:var(--acc2)}

/* BOTTOM NAVIGATION (скрыта по умолчанию, показывается только на мобильных через responsive.css) */
.bottom-nav{position:fixed;bottom:0;left:0;right:0;background:rgba(10,11,22,.97);
  backdrop-filter:blur(24px) saturate(200%);border-top:1px solid var(--b1);
  display:none;align-items:center;justify-content:space-around;
  padding:6px 4px max(10px,env(safe-area-inset-bottom));
  z-index:999;box-shadow:0 -4px 24px rgba(0,0,0,.4)}
.bottom-nav-item{display:flex;flex-direction:column;align-items:center;gap:3px;
  color:var(--t3);text-decoration:none;padding:5px 10px;border-radius:12px;
  transition:all .22s cubic-bezier(.34,1.56,.64,1);position:relative;flex:1;max-width:78px;min-width:52px}
.bottom-nav-item svg{width:23px;height:23px;transition:all .22s}
.bottom-nav-item img{transition:all .22s}
.bottom-nav-item span{font-size:10px;font-weight:500;transition:all .22s;letter-spacing:.01em}
.bottom-nav-item:active{transform:scale(0.9)}
.bottom-nav-item.active{color:var(--acc)}
.bottom-nav-item.active svg,.bottom-nav-item.active img{transform:scale(1.08)}
/* Активный индикатор - точка под иконкой */
.bottom-nav-item.active::before{content:'';position:absolute;bottom:2px;left:50%;transform:translateX(-50%);
  width:4px;height:4px;border-radius:50%;background:var(--acc);
  box-shadow:0 0 8px rgba(124,90,245,.8)}
.bottom-nav-badge{position:absolute;top:3px;right:6px;min-width:16px;height:16px;
  background:var(--acc2);border-radius:8px;font-size:9px;font-weight:700;color:#fff;
  display:flex;align-items:center;justify-content:center;padding:0 4px;
  border:2px solid rgba(10,11,22,.97);animation:pulse 2s ease-in-out infinite}
@keyframes pulse{0%,100%{transform:scale(1)}50%{transform:scale(1.15)}}

/* LAYOUT */
.page{max-width:1080px;margin:0 auto;padding:24px 16px}
.two-col{display:flex;gap:22px;align-items:flex-start}
.main-col{flex:1;min-width:0}
.side-col{width:266px;flex-shrink:0}

/* CARD */
.card{background:var(--s1);border:1px solid var(--b1);border-radius:16px;overflow:hidden;margin-bottom:18px}
.card-head{padding:15px 20px 12px;border-bottom:1px solid var(--b1);
  font-family:'Syne',sans-serif;font-weight:700;font-size:14px;color:var(--t1);
  display:flex;align-items:center;gap:9px}
.icon-badge{width:32px;height:32px;border-radius:9px;background:rgba(124,90,245,.12);
  border:1px solid rgba(124,90,245,.25);display:flex;align-items:center;justify-content:center;color:var(--acc)}

/* BUTTONS */
.btn{display:inline-flex;align-items:center;gap:6px;font-family:inherit;font-weight:600;
  font-size:13px;padding:8px 18px;border-radius:40px;border:none;cursor:pointer;
  transition:all .18s;text-decoration:none;white-space:nowrap}
.btn-p{background:linear-gradient(135deg,var(--acc),#9d6bf0);color:#fff!important;box-shadow:0 4px 16px rgba(124,90,245,.28)}
.btn-p:hover{transform:translateY(-1px);box-shadow:0 6px 22px rgba(124,90,245,.44)}
.btn-o{background:transparent;border:1.5px solid var(--acc)!important;color:var(--acc)!important}
.btn-o:hover{background:rgba(124,90,245,.08)}
.btn-ghost{background:transparent;border:none!important;color:var(--t2)}
.btn-ghost:hover{color:var(--t1);background:rgba(255,255,255,.04)}
.btn-danger{background:transparent;border:1.5px solid var(--acc2)!important;color:var(--acc2)!important}
.btn-danger:hover{background:rgba(255,95,135,.08)}
.btn-sm{padding:6px 13px;font-size:12px}
.btn-xs{padding:4px 10px;font-size:11.5px}

/* COMPOSER */
.composer{padding:15px 18px;border-bottom:1px solid var(--b1)}
.composer-row{display:flex;gap:11px}
.composer-av{width:38px;height:38px;border-radius:50%;object-fit:cover;border:2px solid rgba(124,90,245,.3);flex-shrink:0}
.composer-body{flex:1}
.composer textarea{width:100%;background:var(--s2);border:1px solid var(--b1);border-radius:11px;
  padding:10px 13px;color:var(--t1);font-family:inherit;font-size:14px;resize:none;outline:none;
  transition:border-color .18s;line-height:1.55}
.composer textarea:focus{border-color:var(--acc)}
.composer textarea::placeholder{color:var(--t3)}
.composer-foot{display:flex;align-items:center;justify-content:space-between;margin-top:9px;gap:8px}
.attach-btn{display:flex;align-items:center;gap:5px;color:var(--t2);font-size:12px;cursor:pointer;
  padding:5px 9px;border-radius:8px;transition:all .18s;border:none;background:none;font-family:inherit}
.attach-btn:hover{color:var(--acc);background:rgba(124,90,245,.08)}
.attach-btn input{display:none}
.char-c{font-size:11.5px;color:var(--t3);margin-right:auto}
.char-c.warn{color:var(--acc2)}

/* POST */
.post{padding:15px 18px;border-bottom:1px solid var(--b1);transition:background .15s}
.post:hover{background:rgba(255,255,255,.012)}
.post-row{display:flex;gap:11px}
.post-av{width:40px;height:40px;border-radius:50%;object-fit:cover;border:2px solid var(--b2);flex-shrink:0;transition:border-color .18s}
.post-av:hover{border-color:var(--acc)}
.post-content{flex:1;min-width:0}
.post-meta{display:flex;align-items:center;gap:5px;flex-wrap:wrap;margin-bottom:5px}
.post-name{font-weight:700;font-size:13.5px;color:var(--t1);text-decoration:none}
.post-name:hover{color:var(--acc)}
.post-login{color:var(--t3);font-size:12px;text-decoration:none}
.post-login:hover{color:var(--acc)}
.post-dot{color:var(--t3);font-size:10px}
.post-time{color:var(--t3);font-size:11.5px}
.post-body{font-size:14px;line-height:1.65;color:var(--t1);margin-bottom:9px;word-break:break-word}
.post-img{max-width:100%;border-radius:11px;border:1px solid var(--b1);display:block;margin-top:9px;cursor:zoom-in}
.post-actions{display:flex;align-items:center;gap:3px;margin-top:9px}
.act{display:flex;align-items:center;gap:4px;color:var(--t3);font-size:12px;font-weight:500;
  padding:5px 9px;border-radius:7px;border:none;background:none;cursor:pointer;transition:all .18s;font-family:inherit;text-decoration:none}
.act:hover{background:rgba(255,255,255,.04)}
.act.like:hover,.act.liked{color:#ff5f87}
.act.like:hover{background:rgba(255,95,135,.08)}
.act.comment:hover{color:var(--acc);background:rgba(124,90,245,.08)}
.act-sep{flex:1}

/* COMMENTS */
.cmts{margin-top:11px;padding:11px 13px;background:rgba(0,0,0,.2);border-radius:10px;border:1px solid var(--b1)}
.cmt-fr{display:flex;gap:8px;margin-bottom:10px}
.cmt-av{width:26px;height:26px;border-radius:50%;object-fit:cover;flex-shrink:0;border:1.5px solid var(--b2)}
.cmt-ta{flex:1;background:var(--s2);border:1px solid var(--b1);border-radius:9px;padding:7px 10px;
  color:var(--t1);font-family:inherit;font-size:12.5px;resize:none;outline:none;transition:border-color .18s}
.cmt-ta:focus{border-color:var(--acc)}
.cmt-ta::placeholder{color:var(--t3)}
.cmt-item{display:flex;gap:8px;padding:7px 0;border-top:1px solid rgba(255,255,255,.04)}
.cmt-bubble{flex:1;background:var(--s2);border-radius:9px;padding:8px 11px;min-width:0}
.cmt-name{font-weight:600;font-size:12px;color:var(--t1);text-decoration:none}
.cmt-name:hover{color:var(--acc)}
.cmt-text{font-size:12.5px;color:#bcc0d8;line-height:1.5;margin-top:3px;word-break:break-word}

/* PROFILE */
.profile-banner{height:140px;position:relative;overflow:hidden}
.pbn-inner{width:100%;height:100%;position:relative;background:linear-gradient(135deg,#160a40,#2b0a52 50%,#081040)}
.pbn-inner img{width:100%;height:100%;object-fit:cover;opacity:.7}
.pbn-inner::after{content:'';position:absolute;inset:0;background:repeating-linear-gradient(45deg,rgba(124,90,245,.04) 0,rgba(124,90,245,.04) 1px,transparent 1px,transparent 40px)}
.p-info-row{padding:12px 20px 16px;display:flex;justify-content:space-between;align-items:flex-end}
.p-av-wrap{margin-top:-48px}
.p-av{width:88px;height:88px;border-radius:50%;object-fit:cover;border:3px solid var(--bg);box-shadow:0 0 0 2.5px var(--acc)}
.p-name{font-family:'Syne',sans-serif;font-size:20px;font-weight:800;color:var(--t1)}
.p-login{color:var(--t2);font-size:13px;margin-top:2px}
.p-status{display:inline-flex;align-items:center;gap:5px;margin-top:6px;
  background:rgba(124,90,245,.1);border:1px solid rgba(124,90,245,.2);
  border-radius:7px;padding:3px 10px;font-size:12px;color:#b39dfc}
.p-bio{font-size:13.5px;color:#b0b5d5;margin-top:8px;line-height:1.6}
.p-meta{display:flex;gap:12px;margin-top:8px;flex-wrap:wrap}
.p-meta-i{display:flex;align-items:center;gap:5px;color:var(--t2);font-size:12px}
.p-meta-i a{color:inherit;text-decoration:none}
.p-meta-i a:hover{color:var(--acc)}
.pstats{display:flex;border-top:1px solid var(--b1);border-bottom:1px solid var(--b1)}
.pstat{flex:1;text-align:center;padding:12px 8px;border-right:1px solid var(--b1)}
.pstat:last-child{border-right:none}
.pstat-n{font-family:'Syne',sans-serif;font-size:18px;font-weight:700;color:var(--acc)}
.pstat-l{font-size:10.5px;color:var(--t3);margin-top:1px;letter-spacing:.04em;text-transform:uppercase}

/* SIDEBAR */
.who-item{display:flex;align-items:center;justify-content:space-between;padding:9px 0;border-bottom:1px solid rgba(255,255,255,.04)}
.who-item:last-child{border-bottom:none}
.who-user{display:flex;align-items:center;gap:9px;text-decoration:none;flex:1;min-width:0}
.who-av{width:34px;height:34px;border-radius:50%;object-fit:cover;border:1.5px solid var(--b1);flex-shrink:0}
.who-name{font-weight:600;font-size:12.5px;color:var(--t1);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.who-login{font-size:11px;color:var(--t3)}

/* MESSAGES */
.inbox-item{display:flex;align-items:center;gap:12px;padding:13px 20px;border-bottom:1px solid var(--b1);text-decoration:none;color:inherit;transition:background .15s}
.inbox-item:hover{background:rgba(124,90,245,.04)}
.inbox-av{width:42px;height:42px;border-radius:50%;object-fit:cover;border:1.5px solid var(--b1);flex-shrink:0}

/* CHAT */
.chat-h{padding:13px 18px;border-bottom:1px solid var(--b1);display:flex;align-items:center;gap:10px;background:linear-gradient(90deg,rgba(124,90,245,.06),transparent)}
.msgs-area{max-height:520px;overflow-y:auto;padding:16px 18px;display:flex;flex-direction:column;gap:11px}
.msgs-area::-webkit-scrollbar{width:4px}
.msgs-area::-webkit-scrollbar-thumb{background:var(--b2);border-radius:3px}
.bw-me{display:flex;justify-content:flex-end}
.bw-them{display:flex;justify-content:flex-start}
.bubble{max-width:72%;padding:10px 14px;border-radius:15px;font-size:13.5px;line-height:1.6;word-break:break-word}
.bubble-me{background:linear-gradient(135deg,var(--acc),#9d6bf0);color:#fff;border-bottom-right-radius:3px}
.bubble-them{background:var(--s2);border:1px solid var(--b1);color:var(--t1);border-bottom-left-radius:3px}
.bubble-time{font-size:10px;opacity:.5;margin-top:3px}
.chat-input{padding:13px 18px;border-top:1px solid var(--b1)}
.chat-input-row{display:flex;gap:8px;align-items:flex-end}
.chat-ta{flex:1;background:var(--s2);border:1px solid var(--b1);border-radius:11px;
  padding:10px 13px;color:var(--t1);font-family:inherit;font-size:13.5px;resize:none;
  outline:none;transition:border-color .18s;min-height:42px}
.chat-ta:focus{border-color:var(--acc)}

/* SEARCH */
.srch-box{width:100%;background:var(--s2);border:1.5px solid var(--b1);border-radius:11px;
  padding:12px 18px 12px 42px;color:var(--t1);font-family:inherit;font-size:14.5px;outline:none;transition:border-color .18s}
.srch-box:focus{border-color:var(--acc)}
.srch-wrap{position:relative}
.srch-ico{position:absolute;left:13px;top:50%;transform:translateY(-50%);color:var(--t3);pointer-events:none}

/* AUTH */
.auth-wrap{min-height:100vh;display:flex;align-items:center;justify-content:center;padding:40px 16px;
  background:var(--bg);background-image:radial-gradient(ellipse 70% 60% at 50% 0%,rgba(124,90,245,.14),transparent)}
.auth-card{background:var(--s1);border:1px solid var(--b1);border-radius:22px;
  padding:42px 38px;width:100%;max-width:420px;box-shadow:0 28px 70px rgba(0,0,0,.6)}
.auth-logo{text-align:center;font-family:'Syne',sans-serif;font-size:34px;font-weight:800;
  background:linear-gradient(130deg,#a78bfa,#ff5f87);-webkit-background-clip:text;-webkit-text-fill-color:transparent;margin-bottom:5px}
.auth-sub{text-align:center;color:var(--t2);font-size:13.5px;margin-bottom:28px}
.fg{margin-bottom:14px}
.fg label{display:block;font-size:11px;font-weight:600;color:var(--t3);text-transform:uppercase;letter-spacing:.07em;margin-bottom:5px}
.fg input,.fg select,.fg textarea{width:100%;background:var(--s2);border:1px solid var(--b1);border-radius:10px;
  padding:11px 14px;color:var(--t1);font-family:inherit;font-size:13.5px;outline:none;transition:border-color .18s}
.fg input::placeholder,.fg textarea::placeholder{color:var(--t3)}
.fg input:focus,.fg select:focus,.fg textarea:focus{border-color:var(--acc)}
.fg select option{background:var(--s2);color:var(--t1)}
.form-err{color:#ff7fa0;font-size:12px;margin-top:4px}
.chk-row{display:flex;align-items:flex-start;gap:9px;margin-bottom:14px;font-size:13px;color:var(--t2)}
.chk-row input{width:14px;height:14px;accent-color:var(--acc);flex-shrink:0;margin-top:2px}
.chk-row a{color:var(--acc);text-decoration:none}

/* COMMUNITIES */
.c-banner{height:130px;position:relative;overflow:hidden}
.c-banner-in{width:100%;height:100%;background:linear-gradient(135deg,#160a40,#0a1040);position:relative}
.c-banner-in img{width:100%;height:100%;object-fit:cover;opacity:.65}
.c-banner-in::after{content:'';position:absolute;inset:0;background:rgba(0,0,0,.2)}
.c-card{background:var(--s1);border:1px solid var(--b1);border-radius:14px;overflow:hidden;transition:border-color .18s,transform .18s}
.c-card:hover{border-color:rgba(124,90,245,.35);transform:translateY(-2px)}
.comm-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(230px,1fr));gap:14px}

/* MUSIC PLAYER */
.player{position:fixed;bottom:0;left:0;right:0;z-index:800;
  background:rgba(7,8,15,.96);backdrop-filter:blur(20px);
  border-top:1px solid var(--b1);padding:10px 20px;
  display:none;align-items:center;gap:16px}
.player.active{display:flex}
.player-cover{width:44px;height:44px;border-radius:8px;object-fit:cover;border:1px solid var(--b1);flex-shrink:0;background:var(--s2)}
.player-info{min-width:0;flex:1}
.player-title{font-size:13.5px;font-weight:600;color:var(--t1);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.player-artist{font-size:11.5px;color:var(--t3)}
.player-controls{display:flex;align-items:center;gap:10px;flex:2;justify-content:center}
.p-btn{background:none;border:none;color:var(--t2);cursor:pointer;transition:color .18s;padding:4px;display:flex;align-items:center}
.p-btn:hover{color:var(--t1)}
.p-btn.play-btn{width:38px;height:38px;border-radius:50%;background:var(--acc);color:#fff;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 14px rgba(124,90,245,.35)}
.p-btn.play-btn:hover{transform:scale(1.08)}
.player-progress{flex:3;display:flex;align-items:center;gap:8px}
.progress-bar{flex:1;height:4px;background:var(--b2);border-radius:3px;cursor:pointer;position:relative}
.progress-fill{height:100%;border-radius:3px;background:linear-gradient(90deg,var(--acc),#a78bfa);transition:width .2s}
.p-time{font-size:11px;color:var(--t3);width:36px;text-align:center}
.p-vol{width:80px;accent-color:var(--acc)}
.track-row{display:flex;align-items:center;gap:12px;padding:10px 16px;border-bottom:1px solid var(--b1);transition:background .15s;cursor:pointer}
.track-row:hover{background:rgba(124,90,245,.05)}
.track-row.playing{background:rgba(124,90,245,.08)}
.track-cover{width:44px;height:44px;border-radius:8px;object-fit:cover;border:1px solid var(--b1);flex-shrink:0;background:var(--s2)}
.track-info{flex:1;min-width:0}
.track-title{font-size:13.5px;font-weight:600;color:var(--t1);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.track-artist{font-size:12px;color:var(--t3)}
.track-dur{font-size:12px;color:var(--t3);margin-left:auto;flex-shrink:0}

/* VIDEOS */
.video-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:16px}
.v-card{background:var(--s1);border:1px solid var(--b1);border-radius:13px;overflow:hidden;transition:border-color .18s,transform .18s;text-decoration:none;display:block}
.v-card:hover{border-color:rgba(124,90,245,.3);transform:translateY(-2px)}
.v-thumb{width:100%;aspect-ratio:16/9;object-fit:cover;background:var(--s2);display:block}
.v-info{padding:11px 14px}
.v-title{font-size:13.5px;font-weight:600;color:var(--t1);line-height:1.4;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical}
.v-meta{font-size:11.5px;color:var(--t3);margin-top:5px}
.v-dur{position:absolute;bottom:7px;right:8px;background:rgba(0,0,0,.75);color:#fff;font-size:11px;font-weight:600;padding:2px 6px;border-radius:5px}

/* PHOTOS MASONRY */
.masonry{columns:4;column-gap:10px}
.masonry-item{break-inside:avoid;margin-bottom:10px;position:relative;overflow:hidden;border-radius:10px;cursor:pointer}
.masonry-item img{width:100%;display:block;border:1px solid var(--b1);border-radius:10px;transition:transform .22s}
.masonry-item:hover img{transform:scale(1.03)}
.masonry-overlay{position:absolute;inset:0;background:linear-gradient(to top,rgba(0,0,0,.6),transparent);opacity:0;transition:opacity .22s;border-radius:10px;display:flex;align-items:flex-end;padding:10px}
.masonry-item:hover .masonry-overlay{opacity:1}

/* NOTIFICATIONS */
.notif-item{display:flex;align-items:flex-start;gap:12px;padding:13px 18px;border-bottom:1px solid var(--b1);transition:background .15s}
.notif-item:hover{background:rgba(255,255,255,.012)}
.notif-item.unread{background:rgba(124,90,245,.04)}
.notif-icon{width:34px;height:34px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.notif-icon.like{background:rgba(255,95,135,.15);color:#ff5f87}
.notif-icon.follow{background:rgba(54,207,181,.15);color:#36cfb5}
.notif-icon.comment{background:rgba(124,90,245,.15);color:var(--acc)}

/* MISC */
.empty{text-align:center;padding:52px 20px}
.empty-ico{font-size:42px;opacity:.3;margin-bottom:13px}
.empty h3{font-family:'Syne',sans-serif;font-size:16px;color:var(--t1);margin-bottom:5px}
.empty p{color:var(--t2);font-size:13px}
.alert-ok{background:rgba(54,207,181,.1);border:1px solid rgba(54,207,181,.25);border-radius:9px;padding:10px 14px;color:#5de8cf;font-size:13px;margin-bottom:13px;display:flex;align-items:center;gap:7px}
.alert-err{background:rgba(255,95,135,.1);border:1px solid rgba(255,95,135,.25);border-radius:9px;padding:10px 14px;color:#ff8baa;font-size:13px;margin-bottom:13px}
.tag{display:inline-flex;align-items:center;gap:4px;background:rgba(124,90,245,.1);border:1px solid rgba(124,90,245,.2);border-radius:7px;padding:3px 9px;font-size:11.5px;color:#b39dfc}
.divider{height:1px;background:var(--b1);margin:14px 0}
a{color:inherit}

/* LIGHTBOX */
#lb{display:none;position:fixed;inset:0;background:rgba(0,0,0,.92);z-index:1000;align-items:center;justify-content:center;cursor:zoom-out}
#lb.open{display:flex}
#lb-img{max-width:92vw;max-height:90vh;border-radius:11px}

@keyframes fadeUp{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:none}}
.fade-up{animation:fadeUp .3s ease both}

@media(max-width:800px){
  .two-col{flex-direction:column}.side-col{width:100%}
  .nav-links .nav-lbl{display:none}
  .masonry{columns:2}
  .video-grid{grid-template-columns:repeat(auto-fill,minmax(180px,1fr))}
}
/* Мобильный nav: логотип + поиск (иконка) + аватарка + выход */
@media(max-width:640px){
  .nav-logout svg{width:18px;height:18px}
  .nav-logout{padding:6px;border-radius:8px;background:rgba(255,255,255,.04);border:1px solid var(--b1)}
  .nav-logout:hover{background:rgba(255,95,135,.1);border-color:var(--acc2)}
}
</style>
@yield('head')
</head>
<body>

<nav class="nav">
  <a href="{{ route('home') }}" class="nav-logo">W</a>

  <div class="nav-links">
    <a href="{{ route('home') }}" class="nav-a {{ request()->routeIs('home') ? 'on':'' }}">
      <svg width="15" height="15" fill="currentColor" viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
      <span class="nav-lbl">Лента</span>
    </a>
    <a href="{{ route('communities.index') }}" class="nav-a {{ request()->routeIs('communities.*') ? 'on':'' }}">
      <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 010 20M12 2a15.3 15.3 0 000 20"/></svg>
      <span class="nav-lbl">Группы</span>
    </a>
    <a href="{{ route('music.index') }}" class="nav-a {{ request()->routeIs('music.*') ? 'on':'' }}">
      <svg width="15" height="15" fill="currentColor" viewBox="0 0 24 24"><path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/></svg>
      <span class="nav-lbl">Музыка</span>
    </a>
    <a href="{{ route('videos.index') }}" class="nav-a {{ request()->routeIs('videos.*') ? 'on':'' }}">
      <svg width="15" height="15" fill="currentColor" viewBox="0 0 24 24"><path d="M23 7l-7 5 7 5V7z"/><rect x="1" y="5" width="15" height="14" rx="2"/></svg>
      <span class="nav-lbl">Видео</span>
    </a>
    <a href="{{ route('photos.index') }}" class="nav-a {{ request()->routeIs('photos.*') ? 'on':'' }}">
      <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="3"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
      <span class="nav-lbl">Фото</span>
    </a>
    <a href="{{ route('notifications') }}" class="nav-a {{ request()->routeIs('notifications') ? 'on':'' }}">
      <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 01-3.46 0"/></svg>
      <span class="nav-lbl">Уведомления</span>
      @php $nc=auth()->user()->unreadNotificationsCount() @endphp
      @if($nc>0)<span class="nav-badge">{{ $nc>9?'9+':$nc }}</span>@endif
    </a>
    <a href="{{ route('messages.inbox') }}" class="nav-a {{ request()->routeIs('messages.*') ? 'on':'' }}">
      <svg width="15" height="15" fill="currentColor" viewBox="0 0 24 24"><path d="M20 2H4a2 2 0 00-2 2v18l4-4h14a2 2 0 002-2V4a2 2 0 00-2-2z"/></svg>
      <span class="nav-lbl">Сообщения</span>
      @php $mc=auth()->user()->unreadMessagesCount() @endphp
      @if($mc>0)<span class="nav-badge">{{ $mc>9?'9+':$mc }}</span>@endif
    </a>
    <a href="{{ route('profile') }}" class="nav-a {{ request()->routeIs('profile') ? 'on':'' }}">
      <svg width="15" height="15" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/></svg>
      <span class="nav-lbl">Профиль</span>
    </a>
  </div>

  <div class="nav-right">
    <a href="{{ route('search') }}" class="nav-srch">
      <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
      Поиск
    </a>
    <a href="{{ route('profile') }}" class="user-pill">
      <img src="{{ auth()->user()->avatar ? asset('storage/avatars/'.auth()->user()->avatar) : asset('images/default.png') }}" alt="">
      <span class="user-pill-n">{{ auth()->user()->login }}</span>
    </a>
    <form method="POST" action="{{ route('logout') }}" style="margin:0">
      @csrf
      <button type="submit" class="nav-logout" title="Выйти">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
      </button>
    </form>
  </div>
</nav>

<div class="fade-up">
@if(session('success'))
<div style="max-width:1080px;margin:14px auto;padding:0 16px">
  <div class="alert-ok"><svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ session('success') }}</div>
</div>
@endif
@yield('content')
</div>

<!-- Persistent Music Player -->
<div class="player" id="gplayer">
  <img src="" id="gp-cover" class="player-cover" onerror="this.style.display='none'">
  <div class="player-info">
    <div class="player-title" id="gp-title">—</div>
    <div class="player-artist" id="gp-artist"></div>
  </div>
  <div class="player-controls">
    <button class="p-btn" onclick="prevTrack()" title="Назад"><svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M6 6h2v12H6zm3.5 6l8.5 6V6z"/></svg></button>
    <button class="p-btn play-btn" id="gp-playbtn" onclick="togglePlay()">
      <svg id="gp-ico-play" width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><polygon points="5,3 19,12 5,21"/></svg>
      <svg id="gp-ico-pause" width="16" height="16" fill="currentColor" viewBox="0 0 24 24" style="display:none"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg>
    </button>
    <button class="p-btn" onclick="nextTrack()" title="Вперёд"><svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M6 18l8.5-6L6 6v12zm2-6zM16 6h2v12h-2z"/></svg></button>
  </div>
  <div class="player-progress">
    <span class="p-time" id="gp-cur">0:00</span>
    <div class="progress-bar" id="gp-bar" onclick="seekTo(event)"><div class="progress-fill" id="gp-fill" style="width:0%"></div></div>
    <span class="p-time" id="gp-dur">0:00</span>
  </div>
  <input type="range" class="p-vol" id="gp-vol" min="0" max="1" step=".05" value=".7" oninput="setVol(this.value)" title="Громкость">
  <button class="p-btn nav-logout" onclick="closePlayer()" title="Закрыть"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
</div>

<!-- Lightbox -->
<div id="lb" onclick="closeLb()"><img id="lb-img" src=""></div>

<script>
// ── CSRF helper ──
const csrf = document.querySelector('meta[name=csrf-token]')?.content || '';
function post(url, data={}) {
  return fetch(url, {method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrf,'Accept':'application/json'}, body:JSON.stringify(data)});
}

// ── AJAX LIKE (posts) ──
function likePost(btn, postId) {
  const countEl = btn.querySelector('.like-c');
  const ico = btn.querySelector('svg');
  post(`/posts/${postId}/like`).then(r=>r.json()).then(d=>{
    countEl.textContent = d.likes_count;
    btn.classList.toggle('liked', d.liked);
  });
}

// ── AJAX FOLLOW ──
function followUser(btn, userId) {
  post(`/follow/${userId}`).then(r=>r.json()).then(d=>{
    btn.textContent = d.following ? 'Отписаться' : 'Подписаться';
    btn.classList.toggle('btn-o', d.following);
    btn.classList.toggle('btn-p', !d.following);
    const fc = document.querySelector(`.followers-count[data-uid="${userId}"]`);
    if (fc) fc.textContent = d.followers;
  });
}

// ── AJAX COMMENTS ──
function toggleComments(id) {
  const el = document.getElementById('cmts-'+id);
  if (!el) return;
  el.style.display = el.style.display==='none' ? 'block' : 'none';
}

function submitComment(e, postId) {
  e.preventDefault();
  const form = e.target;
  const ta   = form.querySelector('textarea');
  if (!ta.value.trim()) return;
  const fd = new FormData(form);
  fetch(`/posts/${postId}/comments`, {method:'POST', headers:{'X-CSRF-TOKEN':csrf,'Accept':'application/json'}, body:fd})
    .then(r=>r.json()).then(d=>{
      const list = document.getElementById('cmt-list-'+postId);
      const html = `<div class="cmt-item" id="cmt-${d.id}">
        <img src="${d.avatar}" class="cmt-av">
        <div class="cmt-bubble">
          <div style="display:flex;align-items:center;justify-content:space-between;gap:8px">
            <div><a href="/${d.login}" class="cmt-name">${d.name}</a><span style="color:var(--t3);font-size:11px;margin-left:6px">${d.time}</span></div>
            <button onclick="deleteCmt(this,${d.id})" style="background:none;border:none;color:var(--t3);cursor:pointer;font-size:14px;line-height:1">×</button>
          </div>
          <div class="cmt-text">${d.body}</div>
          ${d.image?`<img src="${d.image}" style="max-width:160px;border-radius:7px;margin-top:6px">` : ''}
        </div>
      </div>`;
      list.insertAdjacentHTML('beforeend', html);
      ta.value = '';
      const cc = document.querySelector(`.cmt-count[data-pid="${postId}"]`);
      if (cc) cc.textContent = d.count;
    });
}

function deleteCmt(btn, cmtId) {
  if (!confirm('Удалить?')) return;
  fetch(`/comments/${cmtId}`, {method:'DELETE', headers:{'X-CSRF-TOKEN':csrf,'Accept':'application/json'}})
    .then(r=>r.json()).then(()=>{ document.getElementById('cmt-'+cmtId)?.remove(); });
}

// ── LIGHTBOX ──
function openImg(src){document.getElementById('lb-img').src=src;document.getElementById('lb').classList.add('open');}
function closeLb(){document.getElementById('lb').classList.remove('open');}
document.addEventListener('keydown',e=>{if(e.key==='Escape'){ closeLb(); closePlayer(); }});

// ── MUSIC PLAYER ──
let audio = new Audio();
let playlist = [];
let currentIdx = -1;
audio.volume = 0.7;

const GP_KEY = 'whisper_gp';

function savePlayerState() {
  if (!audio.src) return;
  try {
    sessionStorage.setItem(GP_KEY, JSON.stringify({
      url:    audio.src,
      title:  document.getElementById('gp-title').textContent,
      artist: document.getElementById('gp-artist').textContent,
      cover:  document.getElementById('gp-cover').src,
      pos:    audio.currentTime,
      vol:    audio.volume,
      tid:    window._gpTrackId || null
    }));
  } catch(e){}
}

audio.addEventListener('timeupdate', ()=>{
  const d = audio.duration||0, c = audio.currentTime;
  document.getElementById('gp-fill').style.width = (d?c/d*100:0)+'%';
  document.getElementById('gp-cur').textContent  = fmt(c);
  document.getElementById('gp-dur').textContent  = fmt(d);
  savePlayerState();
});
audio.addEventListener('ended',  ()=>nextTrack());
audio.addEventListener('play',   ()=>{ document.getElementById('gp-ico-play').style.display='none';  document.getElementById('gp-ico-pause').style.display=''; savePlayerState(); });
audio.addEventListener('pause',  ()=>{ document.getElementById('gp-ico-play').style.display='';       document.getElementById('gp-ico-pause').style.display='none'; savePlayerState(); });

function fmt(s){ s=Math.floor(s||0); return Math.floor(s/60)+':'+(s%60<10?'0':'')+s%60; }

function showPlayer(title, artist, cover) {
  document.getElementById('gp-title').textContent  = title;
  document.getElementById('gp-artist').textContent = artist||'';
  const img = document.getElementById('gp-cover');
  img.src = cover||''; img.style.display = cover?'':'none';
  document.getElementById('gplayer').classList.add('active');
  document.body.style.paddingBottom = '70px';
}

function playTrack(trackId, title, artist, cover) {
  post(`/music/${trackId}/play`).then(r=>r.json()).then(d=>{
    window._gpTrackId = trackId;
    audio.src = d.url;
    audio.play();
    showPlayer(title, artist, cover);
    document.querySelectorAll('.track-row').forEach(r=>r.classList.remove('playing'));
    document.querySelector(`.track-row[data-tid="${trackId}"]`)?.classList.add('playing');
  });
}

// Restore player state after page navigation
document.addEventListener('DOMContentLoaded', ()=>{
  try {
    const saved = JSON.parse(sessionStorage.getItem(GP_KEY)||'null');
    if (saved && saved.url) {
      window._gpTrackId = saved.tid;
      audio.volume = saved.vol || 0.7;
      document.getElementById('gp-vol').value = audio.volume;
      audio.src  = saved.url;
      audio.currentTime = saved.pos || 0;
      showPlayer(saved.title, saved.artist, saved.cover);
      // Пробуем автоплей; браузер разрешит если была интеракция раньше
      const playPromise = audio.play();
      if (playPromise !== undefined) {
        playPromise.catch(()=>{
          // Автоплей заблокирован — показываем подсказку
          const hint = document.createElement('div');
          hint.id = 'gp-resume-hint';
          hint.style.cssText = 'position:absolute;inset:0;display:flex;align-items:center;justify-content:center;background:rgba(0,0,0,.55);border-radius:6px;cursor:pointer;font-size:12px;color:#fff;gap:6px;z-index:10';
          hint.innerHTML = '<svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><polygon points="5,3 19,12 5,21"/></svg> Нажмите чтобы продолжить';
          hint.onclick = ()=>{ audio.play(); hint.remove(); };
          const pbtn = document.getElementById('gp-playbtn');
          pbtn.style.position='relative';
          pbtn.appendChild(hint);
        });
      }
      if (saved.tid) {
        document.querySelector(`.track-row[data-tid="${saved.tid}"]`)?.classList.add('playing');
      }
    }
  } catch(e){}
});

function togglePlay(){ audio.paused ? audio.play() : audio.pause(); }
function nextTrack(){ if(playlist.length && currentIdx<playlist.length-1){ currentIdx++; const t=playlist[currentIdx]; playTrack(t.id,t.title,t.artist,t.cover); } }
function prevTrack(){ if(playlist.length && currentIdx>0){ currentIdx--; const t=playlist[currentIdx]; playTrack(t.id,t.title,t.artist,t.cover); } }
function seekTo(e){ const bar=document.getElementById('gp-bar'); const r=bar.getBoundingClientRect(); audio.currentTime=(e.clientX-r.left)/r.width*(audio.duration||0); }
function setVol(v){ audio.volume=v; savePlayerState(); }
function closePlayer(){ audio.pause(); audio.src=''; window._gpTrackId=null; sessionStorage.removeItem(GP_KEY); document.getElementById('gplayer').classList.remove('active'); document.body.style.paddingBottom=''; }

// Misc helpers
function previewImg(input, previewId) {
  const area = document.getElementById(previewId);
  if (!area) return;
  area.innerHTML='';
  if (!input.files[0]) return;
  const r = new FileReader();
  r.onload = e => { area.innerHTML=`<div style="position:relative;display:inline-block"><img src="${e.target.result}" style="max-height:150px;border-radius:9px;border:1px solid rgba(255,255,255,.08)"><button type="button" onclick="document.getElementById('${previewId}').innerHTML='';this.closest('div').previousSibling&&(this.closest('div').previousSibling.value='')" style="position:absolute;top:-7px;right:-7px;background:var(--acc2);color:#fff;border:none;border-radius:50%;width:21px;height:21px;cursor:pointer;font-size:14px;line-height:1">×</button></div>`; };
  r.readAsDataURL(input.files[0]);
}
function autoH(ta){ ta.style.height='auto'; ta.style.height=Math.min(ta.scrollHeight,120)+'px'; }
function previewVid(input, previewId) {
  const area = document.getElementById(previewId);
  if (!area) return;
  area.innerHTML='';
  if (!input.files[0]) return;
  const url = URL.createObjectURL(input.files[0]);
  area.innerHTML=`<div style="position:relative;display:inline-block"><video src="${url}" style="max-height:150px;max-width:100%;border-radius:9px;border:1px solid rgba(255,255,255,.08)" controls></video><button type="button" onclick="document.getElementById('${previewId}').innerHTML='';input.value=''" style="position:absolute;top:-7px;right:-7px;background:var(--acc2);color:#fff;border:none;border-radius:50%;width:21px;height:21px;cursor:pointer;font-size:14px;line-height:1">×</button></div>`;
}
</script>
@yield('scripts')

<!-- Нижняя навигация для мобильных -->
<nav class="bottom-nav">
  <a href="{{ route('home') }}" class="bottom-nav-item {{ request()->routeIs('home') ? 'active':'' }}">
    <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
    <span>Лента</span>
  </a>
  <a href="{{ route('communities.index') }}" class="bottom-nav-item {{ request()->routeIs('communities.*') ? 'active':'' }}">
    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 010 20M12 2a15.3 15.3 0 000 20"/></svg>
    <span>Группы</span>
  </a>
  <a href="{{ route('messages.inbox') }}" class="bottom-nav-item {{ request()->routeIs('messages.*') ? 'active':'' }}" style="position:relative">
    <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path d="M20 2H4a2 2 0 00-2 2v18l4-4h14a2 2 0 002-2V4a2 2 0 00-2-2z"/></svg>
    <span>Чат</span>
    @php $mc=auth()->user()->unreadMessagesCount() @endphp
    @if($mc>0)<span class="bottom-nav-badge">{{ $mc>9?'9+':$mc }}</span>@endif
  </a>
  <a href="{{ route('notifications') }}" class="bottom-nav-item {{ request()->routeIs('notifications') ? 'active':'' }}" style="position:relative">
    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 01-3.46 0"/></svg>
    <span>Уведомления</span>
    @php $nc=auth()->user()->unreadNotificationsCount() @endphp
    @if($nc>0)<span class="bottom-nav-badge">{{ $nc>9?'9+':$nc }}</span>@endif
  </a>
  <a href="{{ route('profile') }}" class="bottom-nav-item {{ request()->routeIs('profile') ? 'active':'' }}">
    <img src="{{ auth()->user()->avatar ? asset('storage/avatars/'.auth()->user()->avatar) : asset('images/default.png') }}" alt="" style="width:26px;height:26px;border-radius:50%;object-fit:cover;border:2px solid currentColor;transition:all .2s">
    <span>Профиль</span>
  </a>
</nav>

</body>
</html>
