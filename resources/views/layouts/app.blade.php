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
/* ═══════════════════════════════════════════════════════════
   WHISPER — Design System 2.0 · Aurora Dark
   ═══════════════════════════════════════════════════════════ */
:root{
  --bg:#06070f;--s1:#0c0d1c;--s2:#11132280;--s3:#161928;
  --b1:rgba(255,255,255,.055);--b2:rgba(255,255,255,.1);--b3:rgba(255,255,255,.18);
  --acc:#7c5af5;--acc2:#ff5f87;--acc3:#36cfb5;--acc4:#f0a050;
  --t1:#eef0fc;--t2:#9095b5;--t3:#565c7a;
  --glow:rgba(124,90,245,.2);--glow2:rgba(255,95,135,.15);
  --r-sm:10px;--r-md:16px;--r-lg:22px;--r-xl:28px;
}
*{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}

body{
  font-family:'Plus Jakarta Sans',sans-serif;
  background:var(--bg);color:var(--t1);
  min-height:100vh;overflow-x:hidden;
  background-image:
    radial-gradient(ellipse 60% 40% at 15% 5%,rgba(124,90,245,.07),transparent),
    radial-gradient(ellipse 40% 30% at 85% 80%,rgba(255,95,135,.05),transparent),
    radial-gradient(ellipse 50% 35% at 70% 10%,rgba(54,207,181,.04),transparent);
}

/* Aurora grain overlay */
body::after{
  content:'';position:fixed;inset:0;pointer-events:none;z-index:0;
  opacity:.018;
  background-image:url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
  background-size:200px 200px;
}

/* ── NAV ─────────────────────────────────────────────────── */
.nav{
  position:sticky;top:0;z-index:500;height:58px;
  background:rgba(6,7,15,.88);backdrop-filter:blur(28px) saturate(200%);
  border-bottom:1px solid var(--b1);
  display:flex;align-items:center;padding:0 20px;gap:10px;
}
.nav::after{
  content:'';position:absolute;inset:0;bottom:auto;height:1px;
  background:linear-gradient(90deg,transparent,rgba(124,90,245,.3),rgba(255,95,135,.2),transparent);
  pointer-events:none;
}
.nav-logo{
  font-family:'Syne',sans-serif;font-weight:800;font-size:22px;
  background:linear-gradient(130deg,#c4a8ff 0%,#ff8fae 100%);
  -webkit-background-clip:text;-webkit-text-fill-color:transparent;
  text-decoration:none;flex-shrink:0;letter-spacing:-.5px;
}
.nav-links{display:flex;gap:1px;flex:1;justify-content:center;flex-wrap:nowrap}
.nav-a{
  color:var(--t3);text-decoration:none;font-size:12.5px;font-weight:500;
  padding:6px 11px;border-radius:var(--r-sm);transition:all .2s;
  display:flex;align-items:center;gap:5px;position:relative;white-space:nowrap;
}
.nav-a:hover{color:var(--t1);background:var(--b1)}
.nav-a.on{color:var(--acc);background:rgba(124,90,245,.1)}
.nav-a.on svg{filter:drop-shadow(0 0 4px rgba(124,90,245,.6))}
.nav-badge{
  position:absolute;top:2px;right:2px;min-width:16px;height:16px;
  background:var(--acc2);border-radius:8px;font-size:9.5px;font-weight:700;color:#fff;
  display:flex;align-items:center;justify-content:center;padding:0 3px;
  border:2px solid var(--bg);animation:badge-pulse 2.5s ease-in-out infinite;
}
@keyframes badge-pulse{0%,100%{box-shadow:0 0 0 0 rgba(255,95,135,.5)}60%{box-shadow:0 0 0 5px rgba(255,95,135,0)}}
.nav-right{display:flex;align-items:center;gap:8px;flex-shrink:0}
.nav-srch{
  display:flex;align-items:center;gap:6px;
  background:rgba(255,255,255,.04);border:1px solid var(--b1);
  border-radius:var(--r-sm);padding:6px 12px;color:var(--t3);
  font-size:12.5px;cursor:pointer;text-decoration:none;transition:all .2s;
}
.nav-srch:hover{border-color:rgba(124,90,245,.4);color:var(--t1);background:rgba(124,90,245,.06)}
.user-pill{
  display:flex;align-items:center;gap:8px;
  background:var(--s1);border:1px solid var(--b1);
  border-radius:40px;padding:3px 10px 3px 3px;
  text-decoration:none;transition:all .2s;
}
.user-pill:hover{border-color:rgba(124,90,245,.5);box-shadow:0 0 0 3px rgba(124,90,245,.07)}
.user-pill img{width:28px;height:28px;border-radius:50%;object-fit:cover;border:2px solid rgba(124,90,245,.5)}
.user-pill-n{font-size:12px;font-weight:600;color:var(--t1)}
.nav-logout{
  background:none;border:none;color:var(--t3);cursor:pointer;
  padding:6px;border-radius:var(--r-sm);display:flex;align-items:center;
  transition:all .2s;
}
.nav-logout:hover{color:var(--acc2);background:rgba(255,95,135,.1)}

/* BOTTOM NAV (shown on mobile via responsive.css) */
.bottom-nav{
  position:fixed;bottom:0;left:0;right:0;
  background:rgba(6,7,15,.97);backdrop-filter:blur(28px) saturate(200%);
  border-top:1px solid var(--b1);
  display:none;align-items:center;justify-content:space-around;
  padding:6px 4px max(10px,env(safe-area-inset-bottom));
  z-index:999;
}
.bottom-nav::before{
  content:'';position:absolute;top:0;left:0;right:0;height:1px;
  background:linear-gradient(90deg,transparent,rgba(124,90,245,.3),transparent);
}
.bottom-nav-item{
  display:flex;flex-direction:column;align-items:center;gap:3px;
  color:var(--t3);text-decoration:none;padding:5px 10px;border-radius:14px;
  transition:all .25s cubic-bezier(.34,1.56,.64,1);position:relative;
  flex:1;max-width:78px;min-width:52px;
}
.bottom-nav-item svg,.bottom-nav-item img{transition:all .25s}
.bottom-nav-item span{font-size:10px;font-weight:500;letter-spacing:.01em}
.bottom-nav-item:active{transform:scale(.88)}
.bottom-nav-item.active{color:var(--acc)}
.bottom-nav-item.active svg,.bottom-nav-item.active img{transform:scale(1.1);filter:drop-shadow(0 0 6px rgba(124,90,245,.7))}
.bottom-nav-item.active::after{
  content:'';position:absolute;bottom:1px;left:50%;transform:translateX(-50%);
  width:22px;height:3px;border-radius:3px;
  background:linear-gradient(90deg,var(--acc),var(--acc2));
  box-shadow:0 0 10px rgba(124,90,245,.8);
}
.bottom-nav-badge{
  position:absolute;top:3px;right:6px;min-width:16px;height:16px;
  background:var(--acc2);border-radius:8px;font-size:9px;font-weight:700;color:#fff;
  display:flex;align-items:center;justify-content:center;padding:0 4px;
  border:2px solid rgba(6,7,15,.97);animation:badge-pulse 2s ease-in-out infinite;
}

/* ── LAYOUT ──────────────────────────────────────────────── */
.page{max-width:1100px;margin:0 auto;padding:22px 16px;position:relative;z-index:1}
.two-col{display:flex;gap:22px;align-items:flex-start}
.main-col{flex:1;min-width:0}
.side-col{width:272px;flex-shrink:0;position:sticky;top:76px}

/* ── CARDS ───────────────────────────────────────────────── */
.card{
  background:linear-gradient(160deg,rgba(12,13,28,.95) 0%,rgba(10,11,22,.98) 100%);
  border:1px solid var(--b1);border-radius:var(--r-lg);
  overflow:hidden;margin-bottom:16px;position:relative;
  transition:border-color .22s;
}
.card::before{
  content:'';position:absolute;inset:0;border-radius:inherit;pointer-events:none;
  background:linear-gradient(135deg,rgba(124,90,245,.04) 0%,transparent 60%,rgba(255,95,135,.02) 100%);
}
.card-head{
  padding:16px 20px 14px;border-bottom:1px solid var(--b1);
  font-family:'Syne',sans-serif;font-weight:700;font-size:14px;color:var(--t1);
  display:flex;align-items:center;gap:10px;
  background:linear-gradient(90deg,rgba(124,90,245,.06) 0%,transparent 60%);
}
.icon-badge{
  width:32px;height:32px;border-radius:9px;
  background:linear-gradient(135deg,rgba(124,90,245,.18),rgba(124,90,245,.08));
  border:1px solid rgba(124,90,245,.25);
  display:flex;align-items:center;justify-content:center;color:var(--acc);
  flex-shrink:0;
}

/* ── SECTION LABEL ───────────────────────────────────────── */
.sec-label{
  display:flex;align-items:center;gap:10px;
  padding:11px 18px 10px;
  font-size:11px;font-weight:700;color:var(--t3);
  text-transform:uppercase;letter-spacing:.08em;
  border-bottom:1px solid var(--b1);
}
.sec-label-line{flex:1;height:1px;background:var(--b1)}

/* ── BUTTONS ─────────────────────────────────────────────── */
.btn{
  display:inline-flex;align-items:center;gap:6px;
  font-family:inherit;font-weight:600;font-size:13px;
  padding:8px 18px;border-radius:40px;border:none;cursor:pointer;
  transition:all .2s;text-decoration:none;white-space:nowrap;
}
.btn-p{
  background:linear-gradient(135deg,var(--acc),#9d6bf0);
  color:#fff!important;
  box-shadow:0 4px 18px rgba(124,90,245,.3);
}
.btn-p:hover{transform:translateY(-1px);box-shadow:0 6px 24px rgba(124,90,245,.5)}
.btn-p:active{transform:translateY(0)}
.btn-o{background:transparent;border:1.5px solid rgba(124,90,245,.5)!important;color:var(--acc)!important}
.btn-o:hover{background:rgba(124,90,245,.08);border-color:var(--acc)!important}
.btn-ghost{background:transparent;border:none!important;color:var(--t2)}
.btn-ghost:hover{color:var(--t1);background:rgba(255,255,255,.05)}
.btn-danger{background:transparent;border:1.5px solid rgba(255,95,135,.4)!important;color:var(--acc2)!important}
.btn-danger:hover{background:rgba(255,95,135,.08)}
.btn-teal{background:linear-gradient(135deg,rgba(54,207,181,.9),rgba(36,180,156,.9));color:#fff!important}
.btn-teal:hover{box-shadow:0 4px 16px rgba(54,207,181,.35)}
.btn-sm{padding:6px 14px;font-size:12px}
.btn-xs{padding:4px 10px;font-size:11.5px}

/* ── COMPOSER ────────────────────────────────────────────── */
.composer{padding:16px 18px;border-bottom:1px solid var(--b1)}
.composer-row{display:flex;gap:12px}
.composer-av{
  width:40px;height:40px;border-radius:50%;object-fit:cover;flex-shrink:0;
  border:2px solid rgba(124,90,245,.4);
  box-shadow:0 0 0 3px rgba(124,90,245,.08);
}
.composer-body{flex:1}
.composer textarea{
  width:100%;background:rgba(255,255,255,.04);border:1.5px solid var(--b1);
  border-radius:14px;padding:11px 14px;color:var(--t1);
  font-family:inherit;font-size:14px;resize:none;outline:none;
  transition:all .2s;line-height:1.6;
}
.composer textarea:focus{border-color:rgba(124,90,245,.5);background:rgba(124,90,245,.03);box-shadow:0 0 0 3px rgba(124,90,245,.07)}
.composer textarea::placeholder{color:var(--t3)}
.composer-foot{display:flex;align-items:center;justify-content:space-between;margin-top:10px;gap:8px}
.attach-btn{
  display:flex;align-items:center;gap:5px;color:var(--t3);font-size:12px;
  cursor:pointer;padding:5px 9px;border-radius:8px;transition:all .2s;
  border:none;background:none;font-family:inherit;
}
.attach-btn:hover{color:var(--acc);background:rgba(124,90,245,.08)}
.attach-btn input{display:none}
.char-c{font-size:11.5px;color:var(--t3);margin-right:auto}
.char-c.warn{color:var(--acc2)}

/* ── POSTS ───────────────────────────────────────────────── */
.post{
  padding:16px 18px;border-bottom:1px solid var(--b1);
  transition:background .18s;
  position:relative;
}
.post::before{
  content:'';position:absolute;left:0;top:0;bottom:0;width:2px;
  background:linear-gradient(180deg,transparent,rgba(124,90,245,.4),transparent);
  opacity:0;transition:opacity .2s;
}
.post:hover{background:rgba(124,90,245,.025)}
.post:hover::before{opacity:1}
.post-row{display:flex;gap:12px}
.post-av{
  width:42px;height:42px;border-radius:50%;object-fit:cover;flex-shrink:0;
  border:2px solid var(--b2);transition:all .2s;
}
.post-av:hover{border-color:var(--acc);box-shadow:0 0 0 3px rgba(124,90,245,.15)}
.post-content{flex:1;min-width:0}
.post-meta{display:flex;align-items:center;gap:5px;flex-wrap:wrap;margin-bottom:6px}
.post-name{font-weight:700;font-size:14px;color:var(--t1);text-decoration:none;letter-spacing:-.01em}
.post-name:hover{color:var(--acc)}
.post-login{color:var(--t3);font-size:12px;text-decoration:none}
.post-login:hover{color:var(--acc)}
.post-dot{color:var(--t3);font-size:10px}
.post-time{color:var(--t3);font-size:11.5px}
.post-body{font-size:14.5px;line-height:1.7;color:var(--t1);margin-bottom:10px;word-break:break-word}
.post-img{
  max-width:100%;border-radius:14px;border:1px solid var(--b1);
  display:block;margin-top:10px;cursor:zoom-in;transition:transform .22s;
}
.post-img:hover{transform:scale(1.01)}
.post-actions{display:flex;align-items:center;gap:2px;margin-top:10px}
.act{
  display:flex;align-items:center;gap:5px;color:var(--t3);
  font-size:12.5px;font-weight:500;
  padding:6px 10px;border-radius:8px;border:none;
  background:none;cursor:pointer;transition:all .2s;
  font-family:inherit;text-decoration:none;
}
.act:hover{background:rgba(255,255,255,.05)}
.act.like:hover,.act.liked{color:#ff5f87}
.act.like:hover{background:rgba(255,95,135,.08)}
.act.liked svg{filter:drop-shadow(0 0 4px rgba(255,95,135,.6))}
.act.comment:hover{color:var(--acc);background:rgba(124,90,245,.08)}
.act-sep{flex:1}

/* ── COMMENTS ────────────────────────────────────────────── */
.cmts{margin-top:12px;padding:12px 14px;background:rgba(0,0,0,.25);border-radius:12px;border:1px solid var(--b1)}
.cmt-fr{display:flex;gap:9px;margin-bottom:11px}
.cmt-av{width:26px;height:26px;border-radius:50%;object-fit:cover;flex-shrink:0;border:1.5px solid var(--b2)}
.cmt-ta{
  flex:1;background:rgba(255,255,255,.04);border:1.5px solid var(--b1);
  border-radius:10px;padding:7px 11px;color:var(--t1);
  font-family:inherit;font-size:13px;resize:none;outline:none;transition:all .2s;
}
.cmt-ta:focus{border-color:rgba(124,90,245,.45)}
.cmt-ta::placeholder{color:var(--t3)}
.cmt-item{display:flex;gap:9px;padding:8px 0;border-top:1px solid rgba(255,255,255,.04)}
.cmt-bubble{
  flex:1;background:rgba(255,255,255,.03);
  border:1px solid var(--b1);border-radius:10px;
  padding:8px 12px;min-width:0;
}
.cmt-name{font-weight:600;font-size:12.5px;color:var(--t1);text-decoration:none}
.cmt-name:hover{color:var(--acc)}
.cmt-text{font-size:13px;color:#bbc0d8;line-height:1.55;margin-top:3px;word-break:break-word}

/* ── PROFILE ─────────────────────────────────────────────── */
.profile-banner{height:160px;position:relative;overflow:hidden}
.pbn-inner{
  width:100%;height:100%;position:relative;
  background:linear-gradient(135deg,#130a40 0%,#270b55 40%,#081245 100%);
}
.pbn-inner img{width:100%;height:100%;object-fit:cover;opacity:.75}
.pbn-inner::after{
  content:'';position:absolute;inset:0;
  background:
    radial-gradient(ellipse 60% 80% at 20% 50%,rgba(124,90,245,.2),transparent),
    linear-gradient(to bottom,transparent 40%,rgba(6,7,15,.8));
}
.pbn-inner::before{
  content:'';position:absolute;inset:0;
  background:repeating-linear-gradient(45deg,rgba(124,90,245,.03) 0,rgba(124,90,245,.03) 1px,transparent 1px,transparent 50px);
  z-index:1;
}
.p-info-row{padding:0 20px 18px;display:flex;justify-content:space-between;align-items:flex-end}
.p-av-wrap{margin-top:-52px;flex-shrink:0;position:relative;z-index:2}
.p-av{
  width:96px;height:96px;border-radius:50%;object-fit:cover;
  border:3.5px solid var(--bg);
  box-shadow:0 0 0 2.5px var(--acc),0 8px 30px rgba(0,0,0,.6);
}
.p-name{font-family:'Syne',sans-serif;font-size:21px;font-weight:800;color:var(--t1);letter-spacing:-.02em}
.p-login{color:var(--t2);font-size:13.5px;margin-top:3px}
.p-status{
  display:inline-flex;align-items:center;gap:6px;margin-top:8px;
  background:rgba(124,90,245,.1);border:1px solid rgba(124,90,245,.22);
  border-radius:8px;padding:4px 11px;font-size:12px;color:#c4a8ff;
}
.p-status::before{content:'✦';color:var(--acc);font-size:10px}
.p-bio{font-size:14px;color:#b5b9d5;margin-top:10px;line-height:1.65}
.p-meta{display:flex;gap:14px;margin-top:10px;flex-wrap:wrap}
.p-meta-i{display:flex;align-items:center;gap:5px;color:var(--t2);font-size:12.5px}
.p-meta-i a{color:inherit;text-decoration:none}
.p-meta-i a:hover{color:var(--acc)}
.pstats{display:flex;border-top:1px solid var(--b1);border-bottom:1px solid var(--b1)}
.pstat{flex:1;text-align:center;padding:14px 8px;border-right:1px solid var(--b1);cursor:default;transition:background .18s}
.pstat:last-child{border-right:none}
.pstat:hover{background:rgba(124,90,245,.04)}
.pstat-n{
  font-family:'Syne',sans-serif;font-size:20px;font-weight:800;
  background:linear-gradient(135deg,var(--acc),#b89cfc);
  -webkit-background-clip:text;-webkit-text-fill-color:transparent;
}
.pstat-l{font-size:10px;color:var(--t3);margin-top:2px;letter-spacing:.05em;text-transform:uppercase}

/* ── SIDEBAR ─────────────────────────────────────────────── */
.who-item{
  display:flex;align-items:center;justify-content:space-between;
  padding:9px 0;border-bottom:1px solid rgba(255,255,255,.035);
}
.who-item:last-child{border-bottom:none}
.who-user{display:flex;align-items:center;gap:10px;text-decoration:none;flex:1;min-width:0}
.who-av{
  width:36px;height:36px;border-radius:50%;object-fit:cover;
  border:1.5px solid var(--b1);flex-shrink:0;transition:border-color .18s;
}
.who-user:hover .who-av{border-color:rgba(124,90,245,.5)}
.who-name{font-weight:600;font-size:13px;color:var(--t1);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.who-login{font-size:11px;color:var(--t3);margin-top:1px}

/* Quick-links strip */
.quick-strip{
  display:flex;gap:6px;overflow-x:auto;padding:14px 18px;
  scrollbar-width:none;border-bottom:1px solid var(--b1);
  background:linear-gradient(90deg,rgba(124,90,245,.04),transparent);
}
.quick-strip::-webkit-scrollbar{display:none}
.qs-item{
  display:flex;flex-direction:column;align-items:center;gap:5px;
  flex-shrink:0;cursor:pointer;padding:8px 14px;border-radius:14px;
  transition:all .2s;text-decoration:none;
  background:rgba(255,255,255,.03);border:1px solid var(--b1);
}
.qs-item:hover{background:rgba(124,90,245,.08);border-color:rgba(124,90,245,.3)}
.qs-item-ico{
  width:36px;height:36px;border-radius:11px;
  display:flex;align-items:center;justify-content:center;font-size:18px;
}
.qs-item-lbl{font-size:11px;font-weight:500;color:var(--t2);white-space:nowrap}

/* Activity badge */
.activity-dot{
  width:8px;height:8px;border-radius:50%;background:var(--acc3);
  box-shadow:0 0 6px var(--acc3);flex-shrink:0;
}

/* ── MESSAGES ────────────────────────────────────────────── */
.inbox-item{
  display:flex;align-items:center;gap:12px;padding:14px 20px;
  border-bottom:1px solid var(--b1);text-decoration:none;color:inherit;
  transition:all .18s;position:relative;
}
.inbox-item:hover{background:rgba(124,90,245,.04)}
.inbox-item.unread{background:rgba(124,90,245,.025)}
.inbox-item.unread::before{
  content:'';position:absolute;left:0;top:0;bottom:0;width:3px;
  background:linear-gradient(180deg,var(--acc),var(--acc2));border-radius:0 2px 2px 0;
}
.inbox-av-wrap{position:relative;flex-shrink:0}
.inbox-av{width:46px;height:46px;border-radius:50%;object-fit:cover;border:2px solid var(--b2);display:block}
.inbox-online{
  position:absolute;bottom:1px;right:1px;width:12px;height:12px;
  background:var(--acc3);border-radius:50%;border:2.5px solid var(--s1);
  box-shadow:0 0 6px var(--acc3);
}
.inbox-name{font-weight:700;font-size:14px;color:var(--t1)}
.inbox-preview{font-size:12.5px;color:var(--t3);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin-top:2px}
.inbox-time{font-size:11px;color:var(--t3);flex-shrink:0;align-self:flex-start;margin-top:2px}
.inbox-badge{
  min-width:20px;height:20px;background:var(--acc2);border-radius:10px;
  font-size:10px;font-weight:700;color:#fff;
  display:flex;align-items:center;justify-content:center;padding:0 5px;flex-shrink:0;
}

/* ── CHAT ────────────────────────────────────────────────── */
.chat-h{
  padding:14px 18px;border-bottom:1px solid var(--b1);
  display:flex;align-items:center;gap:11px;
  background:linear-gradient(90deg,rgba(124,90,245,.07),transparent);
  position:relative;
}
.chat-h::after{
  content:'';position:absolute;bottom:0;left:0;right:0;height:1px;
  background:linear-gradient(90deg,rgba(124,90,245,.3),transparent);
}
.msgs-area{
  max-height:calc(100vh - 280px);min-height:320px;overflow-y:auto;
  padding:18px 18px 12px;display:flex;flex-direction:column;gap:10px;
}
.msgs-area::-webkit-scrollbar{width:4px}
.msgs-area::-webkit-scrollbar-track{background:transparent}
.msgs-area::-webkit-scrollbar-thumb{background:rgba(124,90,245,.3);border-radius:3px}
.bw-me{display:flex;justify-content:flex-end}
.bw-them{display:flex;justify-content:flex-start;align-items:flex-end;gap:8px}
.bw-av{width:28px;height:28px;border-radius:50%;object-fit:cover;flex-shrink:0;border:1.5px solid var(--b2)}
.bubble{
  max-width:72%;padding:10px 14px;font-size:14px;
  line-height:1.65;word-break:break-word;border-radius:18px;
}
.bubble-me{
  background:linear-gradient(135deg,var(--acc) 0%,#9d6bf0 100%);
  color:#fff;border-bottom-right-radius:5px;
  box-shadow:0 4px 16px rgba(124,90,245,.3);
}
.bubble-them{
  background:rgba(255,255,255,.06);border:1px solid var(--b2);
  color:var(--t1);border-bottom-left-radius:5px;
}
.bubble-time{font-size:10px;opacity:.55;margin-top:4px;letter-spacing:.03em}
.chat-input{padding:14px 18px;border-top:1px solid var(--b1)}
.chat-input-row{display:flex;gap:10px;align-items:flex-end}
.chat-ta{
  flex:1;background:rgba(255,255,255,.04);border:1.5px solid var(--b1);
  border-radius:14px;padding:10px 14px;color:var(--t1);
  font-family:inherit;font-size:14px;resize:none;
  outline:none;transition:all .2s;min-height:44px;
}
.chat-ta:focus{border-color:rgba(124,90,245,.5);box-shadow:0 0 0 3px rgba(124,90,245,.06)}
.chat-send{
  width:44px;height:44px;border-radius:14px;
  background:linear-gradient(135deg,var(--acc),#9d6bf0);
  border:none;color:#fff;cursor:pointer;
  display:flex;align-items:center;justify-content:center;
  flex-shrink:0;transition:all .2s;
  box-shadow:0 4px 16px rgba(124,90,245,.35);
}
.chat-send:hover{transform:scale(1.06);box-shadow:0 6px 22px rgba(124,90,245,.5)}

/* Day divider in chat */
.chat-day{
  display:flex;align-items:center;gap:10px;
  font-size:11px;color:var(--t3);padding:4px 0;
}
.chat-day::before,.chat-day::after{content:'';flex:1;height:1px;background:var(--b1)}

/* ── SEARCH ──────────────────────────────────────────────── */
.srch-box{
  width:100%;background:rgba(255,255,255,.04);border:1.5px solid var(--b1);
  border-radius:14px;padding:12px 18px 12px 44px;color:var(--t1);
  font-family:inherit;font-size:14.5px;outline:none;transition:all .2s;
}
.srch-box:focus{border-color:rgba(124,90,245,.5);background:rgba(124,90,245,.03)}
.srch-wrap{position:relative}
.srch-ico{position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--t3);pointer-events:none}

/* ── AUTH ────────────────────────────────────────────────── */
.auth-wrap{
  min-height:100vh;display:flex;align-items:center;justify-content:center;
  padding:40px 16px;
  background:var(--bg);
  background-image:radial-gradient(ellipse 80% 60% at 50% -10%,rgba(124,90,245,.12),transparent);
}
.auth-card{
  background:linear-gradient(160deg,rgba(12,13,28,.97),rgba(10,11,22,.99));
  border:1px solid var(--b1);border-radius:var(--r-xl);
  padding:42px 38px;width:100%;max-width:420px;
  box-shadow:0 30px 80px rgba(0,0,0,.7),0 0 0 1px rgba(124,90,245,.05) inset;
}
.auth-logo{
  text-align:center;font-family:'Syne',sans-serif;font-size:36px;font-weight:800;
  background:linear-gradient(130deg,#c4a8ff,#ff8fae);
  -webkit-background-clip:text;-webkit-text-fill-color:transparent;margin-bottom:6px;
}
.auth-sub{text-align:center;color:var(--t2);font-size:13.5px;margin-bottom:30px}
.fg{margin-bottom:15px}
.fg label{display:block;font-size:11px;font-weight:700;color:var(--t3);text-transform:uppercase;letter-spacing:.08em;margin-bottom:6px}
.fg input,.fg select,.fg textarea{
  width:100%;background:rgba(255,255,255,.04);border:1.5px solid var(--b1);
  border-radius:12px;padding:12px 15px;color:var(--t1);
  font-family:inherit;font-size:14px;outline:none;transition:all .2s;
}
.fg input::placeholder,.fg textarea::placeholder{color:var(--t3)}
.fg input:focus,.fg select:focus,.fg textarea:focus{
  border-color:rgba(124,90,245,.55);background:rgba(124,90,245,.03);
  box-shadow:0 0 0 3px rgba(124,90,245,.08);
}
.fg select option{background:#0c0d1c;color:var(--t1)}
.form-err{color:#ff7fa0;font-size:12px;margin-top:5px}
.chk-row{display:flex;align-items:flex-start;gap:9px;margin-bottom:15px;font-size:13px;color:var(--t2)}
.chk-row input{width:15px;height:15px;accent-color:var(--acc);flex-shrink:0;margin-top:2px}
.chk-row a{color:var(--acc);text-decoration:none}

/* ── COMMUNITIES ─────────────────────────────────────────── */
.c-banner{height:130px;position:relative;overflow:hidden}
.c-banner-in{
  width:100%;height:100%;
  background:linear-gradient(135deg,#160a40,#0a1040);position:relative;
}
.c-banner-in img{width:100%;height:100%;object-fit:cover;opacity:.65}
.c-banner-in::after{content:'';position:absolute;inset:0;background:rgba(0,0,0,.2)}
.c-card{
  background:linear-gradient(160deg,rgba(12,13,28,.95),rgba(10,11,22,.98));
  border:1px solid var(--b1);border-radius:16px;overflow:hidden;
  transition:all .22s;
}
.c-card:hover{border-color:rgba(124,90,245,.35);transform:translateY(-3px);box-shadow:0 12px 40px rgba(0,0,0,.4)}
.comm-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(230px,1fr));gap:14px}

/* ── MUSIC PLAYER ────────────────────────────────────────── */
.player{
  position:fixed;bottom:0;left:0;right:0;z-index:800;
  background:rgba(6,7,15,.96);backdrop-filter:blur(24px) saturate(180%);
  border-top:1px solid var(--b1);padding:10px 20px;
  display:none;align-items:center;gap:16px;
}
.player::before{
  content:'';position:absolute;top:0;left:0;right:0;height:1px;
  background:linear-gradient(90deg,transparent,rgba(124,90,245,.4),transparent);
}
.player.active{display:flex}
.player-cover{
  width:46px;height:46px;border-radius:10px;object-fit:cover;
  border:1px solid var(--b1);flex-shrink:0;background:var(--s2);
}
.player-info{min-width:0;flex:1}
.player-title{font-size:13.5px;font-weight:600;color:var(--t1);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.player-artist{font-size:11.5px;color:var(--t3);margin-top:2px}
.player-controls{display:flex;align-items:center;gap:10px;flex:2;justify-content:center}
.p-btn{background:none;border:none;color:var(--t2);cursor:pointer;transition:all .18s;padding:4px;display:flex;align-items:center}
.p-btn:hover{color:var(--t1)}
.p-btn.play-btn{
  width:40px;height:40px;border-radius:50%;
  background:linear-gradient(135deg,var(--acc),#9d6bf0);
  color:#fff;display:flex;align-items:center;justify-content:center;
  box-shadow:0 4px 16px rgba(124,90,245,.4);
}
.p-btn.play-btn:hover{transform:scale(1.1);box-shadow:0 6px 22px rgba(124,90,245,.6)}
.player-progress{flex:3;display:flex;align-items:center;gap:8px}
.progress-bar{
  flex:1;height:4px;background:rgba(255,255,255,.1);
  border-radius:3px;cursor:pointer;position:relative;
}
.progress-fill{height:100%;border-radius:3px;background:linear-gradient(90deg,var(--acc),#a78bfa);transition:width .2s}
.p-time{font-size:11px;color:var(--t3);width:36px;text-align:center}
.p-vol{width:80px;accent-color:var(--acc)}
.track-row{
  display:flex;align-items:center;gap:12px;padding:11px 16px;
  border-bottom:1px solid var(--b1);transition:all .18s;cursor:pointer;
}
.track-row:hover{background:rgba(124,90,245,.05)}
.track-row.playing{background:rgba(124,90,245,.08)}
.track-cover{width:46px;height:46px;border-radius:10px;object-fit:cover;border:1px solid var(--b1);flex-shrink:0;background:var(--s2)}
.track-info{flex:1;min-width:0}
.track-title{font-size:14px;font-weight:600;color:var(--t1);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.track-artist{font-size:12px;color:var(--t3);margin-top:2px}
.track-dur{font-size:12px;color:var(--t3);margin-left:auto;flex-shrink:0}

/* ── VIDEOS ──────────────────────────────────────────────── */
.video-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:16px}
.v-card{
  background:linear-gradient(160deg,rgba(12,13,28,.95),rgba(10,11,22,.98));
  border:1px solid var(--b1);border-radius:16px;overflow:hidden;
  transition:all .22s;text-decoration:none;display:block;
}
.v-card:hover{border-color:rgba(124,90,245,.3);transform:translateY(-3px);box-shadow:0 12px 40px rgba(0,0,0,.4)}
.v-thumb{width:100%;aspect-ratio:16/9;object-fit:cover;background:var(--s2);display:block}
.v-info{padding:11px 14px}
.v-title{font-size:13.5px;font-weight:600;color:var(--t1);line-height:1.4;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical}
.v-meta{font-size:11.5px;color:var(--t3);margin-top:5px}
.v-dur{position:absolute;bottom:7px;right:8px;background:rgba(0,0,0,.75);color:#fff;font-size:11px;font-weight:600;padding:2px 7px;border-radius:5px}

/* ── PHOTOS MASONRY ──────────────────────────────────────── */
.masonry{columns:4;column-gap:10px}
.masonry-item{break-inside:avoid;margin-bottom:10px;position:relative;overflow:hidden;border-radius:12px;cursor:pointer}
.masonry-item img{width:100%;display:block;border:1px solid var(--b1);border-radius:12px;transition:transform .25s}
.masonry-item:hover img{transform:scale(1.04)}
.masonry-overlay{position:absolute;inset:0;background:linear-gradient(to top,rgba(0,0,0,.65),transparent);opacity:0;transition:opacity .25s;border-radius:12px;display:flex;align-items:flex-end;padding:10px}
.masonry-item:hover .masonry-overlay{opacity:1}

/* ── NOTIFICATIONS ───────────────────────────────────────── */
.notif-item{
  display:flex;align-items:center;gap:12px;padding:14px 18px;
  border-bottom:1px solid var(--b1);transition:background .15s;
}
.notif-item:hover{background:rgba(255,255,255,.012)}
.notif-item.unread{background:rgba(124,90,245,.04);border-left:3px solid var(--acc)}
.notif-icon{width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.notif-icon.like{background:rgba(255,95,135,.15);color:#ff5f87}
.notif-icon.follow{background:rgba(54,207,181,.15);color:#36cfb5}
.notif-icon.comment{background:rgba(124,90,245,.15);color:var(--acc)}

/* ── MISC ────────────────────────────────────────────────── */
.empty{text-align:center;padding:56px 20px}
.empty-ico{font-size:44px;opacity:.35;margin-bottom:14px}
.empty h3{font-family:'Syne',sans-serif;font-size:17px;color:var(--t1);margin-bottom:6px}
.empty p{color:var(--t2);font-size:13.5px}
.alert-ok{
  background:rgba(54,207,181,.08);border:1px solid rgba(54,207,181,.22);
  border-radius:10px;padding:11px 15px;color:#5de8cf;font-size:13px;
  margin-bottom:14px;display:flex;align-items:center;gap:8px;
}
.alert-err{
  background:rgba(255,95,135,.08);border:1px solid rgba(255,95,135,.22);
  border-radius:10px;padding:11px 15px;color:#ff8baa;font-size:13px;margin-bottom:14px;
}
.tag{
  display:inline-flex;align-items:center;gap:4px;
  background:rgba(124,90,245,.1);border:1px solid rgba(124,90,245,.2);
  border-radius:7px;padding:3px 10px;font-size:11.5px;color:#c4a8ff;
}
.divider{height:1px;background:var(--b1);margin:14px 0}
a{color:inherit}

/* ── GRADIENT TEXT ───────────────────────────────────────── */
.grad-text{background:linear-gradient(130deg,#c4a8ff,#ff8fae);-webkit-background-clip:text;-webkit-text-fill-color:transparent}

/* ── LIGHTBOX ────────────────────────────────────────────── */
#lb{display:none;position:fixed;inset:0;background:rgba(0,0,0,.94);z-index:1000;align-items:center;justify-content:center;cursor:zoom-out;backdrop-filter:blur(4px)}
#lb.open{display:flex}
#lb-img{max-width:92vw;max-height:90vh;border-radius:14px;box-shadow:0 20px 80px rgba(0,0,0,.8)}

/* ── ANIMATIONS ──────────────────────────────────────────── */
@keyframes fadeUp{from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:none}}
.fade-up{animation:fadeUp .35s ease both}
@keyframes shimmer{0%{background-position:-200px 0}100%{background-position:200px 0}}

/* ── RESPONSIVE ──────────────────────────────────────────── */
@media(max-width:900px){
  .two-col{flex-direction:column}
  .side-col{width:100%;position:static}
  .nav-links .nav-lbl{display:none}
  .masonry{columns:2}
  .video-grid{grid-template-columns:repeat(auto-fill,minmax(180px,1fr))}
}
@media(max-width:640px){
  .nav-logout{padding:6px;border-radius:9px;background:rgba(255,255,255,.04);border:1px solid var(--b1)}
  .page{padding:12px 0}
  .card{border-radius:0;margin-bottom:0;border-left:none;border-right:none}
  .card:first-child{border-top:1px solid var(--b1)}
}
</style>
@yield('head')
</head>
<body>

<nav class="nav">
  <a href="{{ route('home') }}" class="nav-logo">Whisper</a>

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
      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
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
<div style="max-width:1100px;margin:16px auto;padding:0 16px">
  <div class="alert-ok">
    <svg width="15" height="15" fill="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    {{ session('success') }}
  </div>
</div>
@endif
@yield('content')
</div>

<!-- Music Player -->
<div class="player" id="gplayer">
  <img src="" id="gp-cover" class="player-cover" onerror="this.style.display='none'">
  <div class="player-info">
    <div class="player-title" id="gp-title">—</div>
    <div class="player-artist" id="gp-artist"></div>
  </div>
  <div class="player-controls">
    <button class="p-btn" onclick="prevTrack()"><svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M6 6h2v12H6zm3.5 6l8.5 6V6z"/></svg></button>
    <button class="p-btn play-btn" id="gp-playbtn" onclick="togglePlay()">
      <svg id="gp-ico-play" width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><polygon points="5,3 19,12 5,21"/></svg>
      <svg id="gp-ico-pause" width="16" height="16" fill="currentColor" viewBox="0 0 24 24" style="display:none"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg>
    </button>
    <button class="p-btn" onclick="nextTrack()"><svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M6 18l8.5-6L6 6v12zm2-6zM16 6h2v12h-2z"/></svg></button>
  </div>
  <div class="player-progress">
    <span class="p-time" id="gp-cur">0:00</span>
    <div class="progress-bar" id="gp-bar" onclick="seekTo(event)"><div class="progress-fill" id="gp-fill" style="width:0%"></div></div>
    <span class="p-time" id="gp-dur">0:00</span>
  </div>
  <input type="range" class="p-vol" id="gp-vol" min="0" max="1" step=".05" value=".7" oninput="setVol(this.value)">
  <button class="p-btn nav-logout" onclick="closePlayer()"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
</div>

<!-- Lightbox -->
<div id="lb" onclick="closeLb()"><img id="lb-img" src=""></div>

<script>
const csrf = document.querySelector('meta[name=csrf-token]')?.content || '';
function post(url, data={}) {
  return fetch(url, {method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrf,'Accept':'application/json'}, body:JSON.stringify(data)});
}
function likePost(btn, postId) {
  const countEl = btn.querySelector('.like-c');
  post(`/posts/${postId}/like`).then(r=>r.json()).then(d=>{
    countEl.textContent = d.likes_count;
    btn.classList.toggle('liked', d.liked);
  });
}
function followUser(btn, userId) {
  post(`/follow/${userId}`).then(r=>r.json()).then(d=>{
    btn.textContent = d.following ? 'Отписаться' : 'Подписаться';
    btn.classList.toggle('btn-o', d.following);
    btn.classList.toggle('btn-p', !d.following);
    const fc = document.querySelector(`.followers-count[data-uid="${userId}"]`);
    if (fc) fc.textContent = d.followers;
  });
}
function toggleComments(id) {
  const el = document.getElementById('cmts-'+id);
  if (!el) return;
  el.style.display = el.style.display==='none' ? 'block' : 'none';
}
function submitComment(e, postId) {
  e.preventDefault();
  const form = e.target;
  const ta = form.querySelector('textarea');
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
            <button onclick="deleteCmt(this,${d.id})" style="background:none;border:none;color:var(--t3);cursor:pointer;font-size:14px;line-height:1;transition:color .15s" onmouseover="this.style.color='var(--acc2)'" onmouseout="this.style.color='var(--t3)'">×</button>
          </div>
          <div class="cmt-text">${d.body}</div>
          ${d.image?`<img src="${d.image}" style="max-width:160px;border-radius:9px;margin-top:6px">` : ''}
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
function openImg(src){document.getElementById('lb-img').src=src;document.getElementById('lb').classList.add('open');}
function closeLb(){document.getElementById('lb').classList.remove('open');}
document.addEventListener('keydown',e=>{if(e.key==='Escape'){closeLb();closePlayer();}});

// Music
let audio = new Audio();
let playlist = [], currentIdx = -1;
audio.volume = 0.7;
const GP_KEY = 'whisper_gp';
function savePlayerState() {
  if (!audio.src) return;
  try { sessionStorage.setItem(GP_KEY, JSON.stringify({url:audio.src,title:document.getElementById('gp-title').textContent,artist:document.getElementById('gp-artist').textContent,cover:document.getElementById('gp-cover').src,pos:audio.currentTime,vol:audio.volume,tid:window._gpTrackId||null})); } catch(e){}
}
audio.addEventListener('timeupdate',()=>{
  const d=audio.duration||0,c=audio.currentTime;
  document.getElementById('gp-fill').style.width=(d?c/d*100:0)+'%';
  document.getElementById('gp-cur').textContent=fmt(c);
  document.getElementById('gp-dur').textContent=fmt(d);
  savePlayerState();
});
audio.addEventListener('ended',()=>nextTrack());
audio.addEventListener('play',()=>{document.getElementById('gp-ico-play').style.display='none';document.getElementById('gp-ico-pause').style.display='';savePlayerState();});
audio.addEventListener('pause',()=>{document.getElementById('gp-ico-play').style.display='';document.getElementById('gp-ico-pause').style.display='none';savePlayerState();});
function fmt(s){s=Math.floor(s||0);return Math.floor(s/60)+':'+(s%60<10?'0':'')+s%60;}
function showPlayer(title,artist,cover){
  document.getElementById('gp-title').textContent=title;
  document.getElementById('gp-artist').textContent=artist||'';
  const img=document.getElementById('gp-cover');
  img.src=cover||'';img.style.display=cover?'':'none';
  document.getElementById('gplayer').classList.add('active');
  document.body.style.paddingBottom='72px';
}
function playTrack(trackId,title,artist,cover){
  post(`/music/${trackId}/play`).then(r=>r.json()).then(d=>{
    window._gpTrackId=trackId;audio.src=d.url;audio.play();
    showPlayer(title,artist,cover);
    document.querySelectorAll('.track-row').forEach(r=>r.classList.remove('playing'));
    document.querySelector(`.track-row[data-tid="${trackId}"]`)?.classList.add('playing');
  });
}
document.addEventListener('DOMContentLoaded',()=>{
  try {
    const saved=JSON.parse(sessionStorage.getItem(GP_KEY)||'null');
    if(saved&&saved.url){
      window._gpTrackId=saved.tid;audio.volume=saved.vol||0.7;
      document.getElementById('gp-vol').value=audio.volume;
      audio.src=saved.url;audio.currentTime=saved.pos||0;
      showPlayer(saved.title,saved.artist,saved.cover);
      audio.play().catch(()=>{});
      if(saved.tid)document.querySelector(`.track-row[data-tid="${saved.tid}"]`)?.classList.add('playing');
    }
  } catch(e){}
});
function togglePlay(){audio.paused?audio.play():audio.pause();}
function nextTrack(){if(playlist.length&&currentIdx<playlist.length-1){currentIdx++;const t=playlist[currentIdx];playTrack(t.id,t.title,t.artist,t.cover);}}
function prevTrack(){if(playlist.length&&currentIdx>0){currentIdx--;const t=playlist[currentIdx];playTrack(t.id,t.title,t.artist,t.cover);}}
function seekTo(e){const bar=document.getElementById('gp-bar');const r=bar.getBoundingClientRect();audio.currentTime=(e.clientX-r.left)/r.width*(audio.duration||0);}
function setVol(v){audio.volume=v;savePlayerState();}
function closePlayer(){audio.pause();audio.src='';window._gpTrackId=null;sessionStorage.removeItem(GP_KEY);document.getElementById('gplayer').classList.remove('active');document.body.style.paddingBottom='';}

function previewImg(input,previewId){
  const area=document.getElementById(previewId);if(!area)return;area.innerHTML='';
  if(!input.files[0])return;
  const r=new FileReader();
  r.onload=e=>{area.innerHTML=`<div style="position:relative;display:inline-block;margin-top:8px"><img src="${e.target.result}" style="max-height:140px;border-radius:10px;border:1px solid rgba(255,255,255,.08)"><button type="button" onclick="document.getElementById('${previewId}').innerHTML=''" style="position:absolute;top:-7px;right:-7px;background:var(--acc2);color:#fff;border:none;border-radius:50%;width:22px;height:22px;cursor:pointer;font-size:14px;line-height:1">×</button></div>`;};
  r.readAsDataURL(input.files[0]);
}
function autoH(ta){ta.style.height='auto';ta.style.height=Math.min(ta.scrollHeight,120)+'px';}
function previewVid(input,previewId){
  const area=document.getElementById(previewId);if(!area)return;area.innerHTML='';
  if(!input.files[0])return;
  const url=URL.createObjectURL(input.files[0]);
  area.innerHTML=`<div style="position:relative;display:inline-block;margin-top:8px"><video src="${url}" style="max-height:140px;max-width:100%;border-radius:10px;border:1px solid rgba(255,255,255,.08)" controls></video><button type="button" onclick="document.getElementById('${previewId}').innerHTML=''" style="position:absolute;top:-7px;right:-7px;background:var(--acc2);color:#fff;border:none;border-radius:50%;width:22px;height:22px;cursor:pointer;font-size:14px;line-height:1">×</button></div>`;
}
</script>
@yield('scripts')

<!-- Bottom Nav (mobile) -->
<nav class="bottom-nav">
  <a href="{{ route('home') }}" class="bottom-nav-item {{ request()->routeIs('home') ? 'active':'' }}">
    <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
    <span>Лента</span>
  </a>
  <a href="{{ route('communities.index') }}" class="bottom-nav-item {{ request()->routeIs('communities.*') ? 'active':'' }}">
    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 010 20"/></svg>
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
    <span>События</span>
    @php $nc=auth()->user()->unreadNotificationsCount() @endphp
    @if($nc>0)<span class="bottom-nav-badge">{{ $nc>9?'9+':$nc }}</span>@endif
  </a>
  <a href="{{ route('profile') }}" class="bottom-nav-item {{ request()->routeIs('profile') ? 'active':'' }}">
    <img src="{{ auth()->user()->avatar ? asset('storage/avatars/'.auth()->user()->avatar) : asset('images/default.png') }}" alt="" style="width:26px;height:26px;border-radius:50%;object-fit:cover;border:2px solid currentColor">
    <span>Профиль</span>
  </a>
</nav>

{{-- ══════════════════════════════════════
     ВИСПИ-ТЯН · AI ASSISTANT WIDGET
     ══════════════════════════════════════ --}}
<style>
.wispy-fab{
  position:fixed;bottom:26px;right:26px;width:58px;height:58px;border-radius:50%;
  background:linear-gradient(145deg,#7c5af5,#ff5f87);border:none;cursor:pointer;
  z-index:9600;box-shadow:0 4px 24px rgba(124,90,245,.6);
  display:flex;align-items:center;justify-content:center;
  transition:transform .3s cubic-bezier(.34,1.56,.64,1),box-shadow .25s;
  padding:0;overflow:hidden;animation:wispy-bob 4s ease-in-out infinite;
}
.wispy-fab:hover{transform:scale(1.13);box-shadow:0 0 0 10px rgba(124,90,245,.1),0 8px 32px rgba(124,90,245,.7)}
.wispy-fab.active{animation:none;transform:scale(.9)}
@keyframes wispy-bob{0%,100%{transform:translateY(0)}50%{transform:translateY(-8px)}}
.wispy-dot{position:absolute;top:5px;right:5px;width:13px;height:13px;background:var(--acc2);border-radius:50%;border:2.5px solid var(--bg);display:none;animation:wispy-ping 1.6s ease-in-out infinite}
.wispy-dot.show{display:block}
@keyframes wispy-ping{0%,100%{transform:scale(1);opacity:1}50%{transform:scale(1.3);opacity:.7}}

.wispy-panel{
  position:fixed;bottom:96px;right:26px;width:376px;max-height:590px;
  background:linear-gradient(160deg,#0d0e1e 0%,#0f1022 100%);
  border:1px solid rgba(124,90,245,.22);border-radius:24px;
  box-shadow:0 28px 90px rgba(0,0,0,.8),0 0 0 1px rgba(124,90,245,.05) inset;
  display:flex;flex-direction:column;z-index:9590;overflow:hidden;
  transform:translateY(28px) scale(.94);opacity:0;pointer-events:none;
  transition:transform .4s cubic-bezier(.34,1.4,.64,1),opacity .3s ease;
}
.wispy-panel.open{transform:translateY(0) scale(1);opacity:1;pointer-events:all}

.wispy-hd{
  display:flex;align-items:center;gap:11px;padding:14px 16px 13px;
  flex-shrink:0;position:relative;overflow:hidden;
  background:linear-gradient(135deg,rgba(124,90,245,.14) 0%,rgba(255,95,135,.08) 100%);
  border-bottom:1px solid rgba(124,90,245,.15);
}
.wispy-hd::before{content:'';position:absolute;top:-60px;right:-30px;width:140px;height:140px;background:radial-gradient(circle,rgba(124,90,245,.2),transparent 70%);pointer-events:none}
.wispy-hd-av{width:48px;height:54px;flex-shrink:0;filter:drop-shadow(0 4px 12px rgba(124,90,245,.6))}
.wispy-hd-name{font-family:'Syne',sans-serif;font-weight:800;font-size:15px;line-height:1.2;background:linear-gradient(120deg,#c4a8ff,#ff8fae);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.wispy-hd-status{font-size:11px;color:var(--t3);margin-top:2px;display:flex;align-items:center;gap:5px}
.wispy-hd-status::before{content:'';display:inline-block;width:7px;height:7px;background:#36cfb5;border-radius:50%;box-shadow:0 0 7px #36cfb5;animation:ws-pulse 2s ease-in-out infinite}
@keyframes ws-pulse{0%,100%{opacity:1}50%{opacity:.4}}
.wispy-ibtn{background:none;border:none;color:var(--t3);cursor:pointer;padding:5px;border-radius:9px;display:flex;align-items:center;transition:all .17s}
.wispy-ibtn:hover{color:var(--t1);background:rgba(255,255,255,.07)}
.wispy-ibtn.d:hover{color:var(--acc2)}

.wispy-msgs{flex:1;overflow-y:auto;padding:14px 14px 8px;display:flex;flex-direction:column;gap:10px;scroll-behavior:smooth}
.wispy-msgs::-webkit-scrollbar{width:4px}
.wispy-msgs::-webkit-scrollbar-thumb{background:rgba(124,90,245,.3);border-radius:4px}
.wispy-msg{display:flex;gap:8px;align-items:flex-end;opacity:0;transform:translateY(14px);transition:all .35s cubic-bezier(.34,1.3,.64,1)}
.wispy-msg.in{opacity:1;transform:translateY(0)}
.wispy-msg-u{flex-direction:row-reverse}
.wispy-mav{width:28px;height:28px;border-radius:50%;overflow:hidden;flex-shrink:0;border:1.5px solid rgba(124,90,245,.35);background:#0f1022}
.wispy-bub{max-width:82%;padding:10px 14px;border-radius:17px;font-size:13.5px;line-height:1.65;word-break:break-word}
.wispy-msg-ai .wispy-bub{background:rgba(124,90,245,.1);border:1px solid rgba(124,90,245,.2);border-bottom-left-radius:4px;color:var(--t1)}
.wispy-msg-u .wispy-bub{background:linear-gradient(135deg,rgba(124,90,245,.26),rgba(255,95,135,.16));border:1px solid rgba(124,90,245,.3);border-bottom-right-radius:4px;color:var(--t1)}
.wispy-bub em{color:#c4a8ff;font-style:normal}
.wispy-bub strong{color:#e2d8ff}

.wispy-typing{display:none;align-items:flex-end;gap:8px;padding:0 14px 8px}
.wispy-typing.on{display:flex}
.wispy-dots{background:rgba(124,90,245,.1);border:1px solid rgba(124,90,245,.2);border-radius:14px;padding:10px 14px;display:flex;gap:5px;align-items:center}
.wispy-dots span{display:inline-block;width:6px;height:6px;background:#a78bfa;border-radius:50%;animation:ws-dot 1.2s ease-in-out infinite}
.wispy-dots span:nth-child(2){animation-delay:.2s}
.wispy-dots span:nth-child(3){animation-delay:.4s}
@keyframes ws-dot{0%,80%,100%{transform:scale(.8);opacity:.5}40%{transform:scale(1.2);opacity:1}}

.wispy-quick{padding:6px 14px 10px;flex-shrink:0}
.wispy-qlbl{font-size:10.5px;color:var(--t3);margin-bottom:7px;text-transform:uppercase;letter-spacing:.07em}
.wispy-chips{display:flex;flex-wrap:wrap;gap:6px}
.wispy-chip{background:rgba(124,90,245,.08);border:1px solid rgba(124,90,245,.2);border-radius:40px;padding:5px 12px;font-size:12px;color:#c4a8ff;cursor:pointer;transition:all .17s;font-family:inherit;white-space:nowrap}
.wispy-chip:hover{background:rgba(124,90,245,.2);border-color:rgba(124,90,245,.45);color:var(--t1);transform:translateY(-1px)}

.wispy-inp-row{display:flex;align-items:flex-end;gap:8px;padding:10px 14px;border-top:1px solid rgba(124,90,245,.12);background:rgba(0,0,0,.2);flex-shrink:0}
.wispy-ta{flex:1;background:rgba(255,255,255,.04);border:1.5px solid rgba(124,90,245,.2);border-radius:13px;padding:9px 13px;color:var(--t1);font-family:inherit;font-size:13.5px;resize:none;outline:none;max-height:100px;overflow-y:auto;line-height:1.5;transition:border-color .18s}
.wispy-ta:focus{border-color:rgba(124,90,245,.55)}
.wispy-ta::placeholder{color:var(--t3)}
.wispy-send{width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#7c5af5,#ff5f87);border:none;color:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:all .22s cubic-bezier(.34,1.56,.64,1);box-shadow:0 3px 14px rgba(124,90,245,.5)}
.wispy-send:hover{transform:scale(1.12)}
.wispy-send:disabled{opacity:.4;cursor:not-allowed;transform:none}
.wispy-hint{text-align:center;font-size:10.5px;color:var(--t3);padding:0 14px 8px;opacity:.6}

.wispy-overlay{position:fixed;inset:0;background:rgba(0,0,0,.65);backdrop-filter:blur(6px);z-index:9700;display:flex;align-items:center;justify-content:center;opacity:0;pointer-events:none;transition:opacity .25s}
.wispy-overlay.open{opacity:1;pointer-events:all}
.wispy-modal{background:linear-gradient(160deg,#0f1022,#0d0e1e);border:1px solid rgba(124,90,245,.25);border-radius:22px;width:400px;max-width:calc(100vw - 32px);box-shadow:0 30px 90px rgba(0,0,0,.75);transform:translateY(22px) scale(.96);transition:transform .34s cubic-bezier(.34,1.4,.64,1);overflow:hidden}
.wispy-overlay.open .wispy-modal{transform:translateY(0) scale(1)}
.wispy-mhd{padding:18px 20px 14px;border-bottom:1px solid rgba(124,90,245,.14);display:flex;align-items:center;justify-content:space-between;background:linear-gradient(135deg,rgba(124,90,245,.12),rgba(255,95,135,.06))}
.wispy-mtitle{font-family:'Syne',sans-serif;font-weight:700;font-size:15px;color:var(--t1)}
.wispy-mbody{padding:18px 20px}
.wispy-mlbl{font-size:11.5px;font-weight:600;color:var(--t2);margin-bottom:9px;text-transform:uppercase;letter-spacing:.06em}
.wispy-pbtns{display:flex;flex-wrap:wrap;gap:6px;margin-bottom:16px}
.wispy-pbtn{background:rgba(124,90,245,.07);border:1.5px solid rgba(124,90,245,.18);border-radius:40px;padding:6px 13px;font-size:12.5px;color:var(--t2);cursor:pointer;transition:all .17s;font-family:inherit}
.wispy-pbtn:hover{border-color:var(--acc);color:var(--t1)}
.wispy-pbtn.on{background:rgba(124,90,245,.18);border-color:var(--acc);color:#c4a8ff;font-weight:600}
.wispy-pta{width:100%;background:rgba(255,255,255,.04);border:1.5px solid rgba(124,90,245,.2);border-radius:12px;padding:10px 13px;color:var(--t1);font-family:inherit;font-size:13px;resize:none;outline:none;transition:border-color .18s;line-height:1.6;box-sizing:border-box}
.wispy-pta:focus{border-color:rgba(124,90,245,.55)}
.wispy-pta::placeholder{color:var(--t3)}
.wispy-pcc{font-size:11px;color:var(--t3);text-align:right;margin-top:5px}
.wispy-mnote{margin-top:12px;font-size:11.5px;color:var(--t3);background:rgba(124,90,245,.07);border-radius:10px;padding:9px 12px;line-height:1.55}
.wispy-mft{padding:14px 20px;border-top:1px solid rgba(124,90,245,.1);display:flex;justify-content:flex-end;gap:9px}

@media(max-width:640px){
  .wispy-fab{bottom:82px;right:16px;width:52px;height:52px}
  .wispy-panel{bottom:150px;right:8px;left:8px;width:auto;max-height:60vh}
}
</style>

<svg style="display:none" xmlns="http://www.w3.org/2000/svg">
<defs><symbol id="wc" viewBox="0 0 100 115">
  <path d="M22 115 Q32 97 42 93 L50 100 L58 93 Q68 97 78 115Z" fill="#1a1045"/>
  <circle cx="35" cy="100" r="5.5" fill="#7c5af5"/>
  <text x="35.2" y="102.5" text-anchor="middle" font-size="6" font-weight="900" fill="white" font-family="sans-serif">W</text>
  <rect x="43" y="80" width="14" height="13" rx="4" fill="#f9c5a8"/>
  <path d="M33 92 Q42 85 43 80 L50 86 L57 80 Q58 85 67 92Z" fill="#2d1a60"/>
  <path d="M43 80 L50 86 L57 80" fill="none" stroke="#7c5af5" stroke-width="1.5"/>
  <ellipse cx="50" cy="46" rx="37" ry="42" fill="#3d1275"/>
  <path d="M13 60 C0 75 -3 100 4 112 C13 108 18 88 18 70Z" fill="#3d1275"/>
  <path d="M87 60 C100 75 103 100 96 112 C87 108 82 88 82 70Z" fill="#3d1275"/>
  <ellipse cx="50" cy="50" rx="27" ry="31" fill="#f9c5a8"/>
  <path d="M23 42 Q27 15 50 12 Q73 15 77 42 Q67 26 50 24 Q33 26 23 42Z" fill="#5020a0"/>
  <path d="M23 42 L18 63 L27 66 L29 46Z" fill="#5020a0"/>
  <path d="M77 42 L82 63 L73 66 L71 46Z" fill="#5020a0"/>
  <ellipse cx="37" cy="48" rx="9.5" ry="10.5" fill="white"/>
  <ellipse cx="63" cy="48" rx="9.5" ry="10.5" fill="white"/>
  <ellipse cx="37" cy="49.5" rx="7" ry="8" fill="#8055f0"/>
  <ellipse cx="63" cy="49.5" rx="7" ry="8" fill="#8055f0"/>
  <ellipse cx="37" cy="50.5" rx="4.5" ry="5.5" fill="#5235c0"/>
  <ellipse cx="63" cy="50.5" rx="4.5" ry="5.5" fill="#5235c0"/>
  <ellipse cx="37" cy="51.5" rx="2.8" ry="3.2" fill="#0c0028"/>
  <ellipse cx="63" cy="51.5" rx="2.8" ry="3.2" fill="#0c0028"/>
  <ellipse cx="39.5" cy="47" rx="2.2" ry="2.5" fill="white"/>
  <ellipse cx="65.5" cy="47" rx="2.2" ry="2.5" fill="white"/>
  <path d="M27.5 41 Q37 38 46.5 41" fill="none" stroke="#0c0028" stroke-width="1.4" stroke-linecap="round"/>
  <path d="M53.5 41 Q63 38 72.5 41" fill="none" stroke="#0c0028" stroke-width="1.4" stroke-linecap="round"/>
  <rect x="27" y="41" width="20" height="14" rx="7" fill="none" stroke="#ff6b9d" stroke-width="2"/>
  <rect x="53" y="41" width="20" height="14" rx="7" fill="none" stroke="#ff6b9d" stroke-width="2"/>
  <line x1="47" y1="48" x2="53" y2="48" stroke="#ff6b9d" stroke-width="2"/>
  <path d="M47 63.5 Q50 66 53 63.5" fill="none" stroke="#d4906a" stroke-width="1.2" stroke-linecap="round"/>
  <path d="M41 71 Q50 79 59 71" fill="none" stroke="#ff8fae" stroke-width="2.2" stroke-linecap="round"/>
  <ellipse cx="28" cy="63" rx="8.5" ry="4.5" fill="#ffb3c8" opacity=".5"/>
  <ellipse cx="72" cy="63" rx="8.5" ry="4.5" fill="#ffb3c8" opacity=".5"/>
</symbol></defs>
</svg>

<button class="wispy-fab" id="wfab" onclick="WP.toggle()" title="Виспи-тян 💜">
  <svg width="34" height="40" viewBox="0 0 100 115"><use href="#wc"/></svg>
  <span class="wispy-dot" id="wdot"></span>
</button>

<div class="wispy-panel" id="wpanel">
  <div class="wispy-hd">
    <svg class="wispy-hd-av" viewBox="0 0 100 115"><use href="#wc"/></svg>
    <div style="flex:1;min-width:0">
      <div class="wispy-hd-name">Виспи-тян 💜</div>
      <div class="wispy-hd-status">Онлайн · Администратор Виспера</div>
    </div>
    <button class="wispy-ibtn" onclick="WP.openSettings()" title="Настройка">
      <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/></svg>
    </button>
    <button class="wispy-ibtn d" onclick="WP.clear()" title="Очистить">
      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6M10 11v6M14 11v6M9 6V4h6v2"/></svg>
    </button>
    <button class="wispy-ibtn" onclick="WP.close()" title="Свернуть">
      <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
  </div>
  <div class="wispy-msgs" id="wmsgs"></div>
  <div class="wispy-typing" id="wtyping">
    <div class="wispy-mav"><svg viewBox="0 0 100 115" width="28" height="28"><use href="#wc"/></svg></div>
    <div class="wispy-dots"><span></span><span></span><span></span></div>
  </div>
  <div class="wispy-quick" id="wquick">
    <div class="wispy-qlbl">С чего начнём? 🌸</div>
    <div class="wispy-chips">
      <button class="wispy-chip" onclick="WP.quick('Покажи мою статистику')">📊 Статистика</button>
      <button class="wispy-chip" onclick="WP.quick('Как создать группу?')">👥 Создать группу</button>
      <button class="wispy-chip" onclick="WP.quick('Что умеет Виспер?')">💡 Возможности</button>
      <button class="wispy-chip" onclick="WP.quick('Как оформить профиль красиво?')">✨ Профиль</button>
      <button class="wispy-chip" onclick="WP.quick('Как работают личные сообщения?')">💬 Сообщения</button>
      <button class="wispy-chip" onclick="WP.quick('Расскажи о правилах Виспера')">📋 Правила</button>
    </div>
  </div>
  <div class="wispy-inp-row">
    <textarea class="wispy-ta" id="wta" placeholder="Спроси Виспи-тян... 💬" rows="1" maxlength="500"></textarea>
    <button class="wispy-send" id="wsend">
      <svg width="15" height="15" fill="currentColor" viewBox="0 0 24 24"><path d="M22 2L11 13"/><path d="M22 2L15 22l-4-9-9-4 20-7z"/></svg>
    </button>
  </div>
  <div class="wispy-hint">Enter — отправить · Shift+Enter — новая строка</div>
</div>

<div class="wispy-overlay" id="woverlay" onclick="WP.oc(event)">
  <div class="wispy-modal" onclick="event.stopPropagation()">
    <div class="wispy-mhd">
      <div class="wispy-mtitle">🎀 Настройка личности Виспи-тян</div>
      <button class="wispy-ibtn" onclick="WP.closeSettings()"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
    </div>
    <div class="wispy-mbody">
      <div class="wispy-mlbl">Готовые стили</div>
      <div class="wispy-pbtns" id="wpbtns">
        <button class="wispy-pbtn" data-v="" onclick="WP.sp(this)">🌸 По умолчанию</button>
        <button class="wispy-pbtn" data-v="Будь цундере: ворчи, что помогаешь только по обязанности, но всё равно очень старайся помочь." onclick="WP.sp(this)">😤 Цундере</button>
        <button class="wispy-pbtn" data-v="Говори официально и строго, используй слова 'соблаговолите', 'разумеется'. Но иногда роняй деловой тон." onclick="WP.sp(this)">📚 Официальная</button>
        <button class="wispy-pbtn" data-v="Говори как крутая подруга-геймер. Используй термины из игр, иногда обращайся 'бро'." onclick="WP.sp(this)">🎮 Геймер</button>
        <button class="wispy-pbtn" data-v="Говори немного таинственно и поэтично. Называй пользователя 'путник' или 'светлая душа'." onclick="WP.sp(this)">🌙 Таинственная</button>
        <button class="wispy-pbtn" data-v="Говори очень энергично! Обращайся 'сэмпай'! Вставляй японские слова: сугой, кавай, нани." onclick="WP.sp(this)">🌟 Аниме-сэмпай</button>
      </div>
      <div class="wispy-mlbl">Или опиши свой стиль</div>
      <textarea class="wispy-pta" id="wpta" rows="3" maxlength="600" placeholder="Например: «Говори как мудрый сенсей...»"></textarea>
      <div class="wispy-pcc"><span id="wpcc">0</span>/600</div>
      <div class="wispy-mnote">✨ Изменения стиля вступят в силу с следующего сообщения.</div>
    </div>
    <div class="wispy-mft">
      <button class="btn btn-ghost btn-sm" onclick="WP.closeSettings()">Отмена</button>
      <button class="btn btn-p btn-sm" onclick="WP.save()">Сохранить ✨</button>
    </div>
  </div>
</div>

<script>
const WP = (() => {
  const UN = @json(auth()->user()->name);
  let hist=[], pers='', busy=false;
  const $i=id=>document.getElementById(id);
  const wf=(url,body)=>fetch(url,{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrf,'Accept':'application/json'},body:JSON.stringify(body)});
  function _welcome(){
    const h=new Date().getHours();
    const g=h<12?'Доброе утро':h<17?'Добрый день':'Добрый вечер';
    _add('assistant',`${g}, ${UN}! 🌸✨\n\n*лихорадочно поправляет значок и чуть не роняет папку с документами*\n\nЯ — Виспи-тян, твой личный ассистент в Виспере! П-постараюсь помочь с любым вопросом о сайте... Хотя иногда немного путаюсь в бумагах 🙈\n\nС чего начнём? 💜`,true);
  }
  function _add(role,text,skipHist=false){if(!skipHist)hist.push({role,content:text});_render(role,text);_scroll();}
  function _render(role,text){
    const wrap=document.createElement('div');
    wrap.className=`wispy-msg wispy-msg-${role==='assistant'?'ai':'u'}`;
    if(role==='assistant'){const av=document.createElement('div');av.className='wispy-mav';av.innerHTML='<svg viewBox="0 0 100 115" width="28" height="28"><use href="#wc"/></svg>';wrap.appendChild(av);}
    const bub=document.createElement('div');bub.className='wispy-bub';bub.innerHTML=_fmt(text);wrap.appendChild(bub);
    $i('wmsgs').appendChild(wrap);requestAnimationFrame(()=>setTimeout(()=>wrap.classList.add('in'),10));
  }
  function _fmt(t){return t.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/\*\*([^*\n]+)\*\*/g,'<strong>$1</strong>').replace(/\*([^*\n]+)\*/g,'<em>$1</em>').replace(/\n/g,'<br>');}
  function _showTyping(){const e=$i('wtyping');e.classList.add('on');e.style.display='flex';_scroll();}
  function _hideTyping(){const e=$i('wtyping');e.classList.remove('on');e.style.display='none';}
  function _hideQuick(){const q=$i('wquick');if(q)q.style.display='none';}
  function _scroll(){const e=$i('wmsgs');setTimeout(()=>{e.scrollTop=e.scrollHeight;},80);}
  function _syncPbtns(){document.querySelectorAll('.wispy-pbtn').forEach(b=>{b.classList.toggle('on',(b.dataset.v||'')===($i('wpta')?.value??pers));});}
  return {
    init(){
      fetch('/wispy/personality',{headers:{'Accept':'application/json','X-CSRF-TOKEN':csrf}}).then(r=>r.json()).then(d=>{pers=d.personality||'';_syncPbtns();}).catch(()=>{});
      const ta=$i('wta');
      ta.addEventListener('input',()=>{ta.style.height='auto';ta.style.height=Math.min(ta.scrollHeight,100)+'px';});
      ta.addEventListener('keydown',e=>{if(e.key==='Enter'&&!e.shiftKey){e.preventDefault();WP.send();}});
      $i('wsend').addEventListener('click',()=>WP.send());
      $i('wpta').addEventListener('input',()=>{$i('wpcc').textContent=$i('wpta').value.length;});
      if(!sessionStorage.getItem('wpy_seen'))setTimeout(()=>$i('wdot').classList.add('show'),3000);
    },
    toggle(){$i('wpanel').classList.contains('open')?this.close():this.open();},
    open(){$i('wpanel').classList.add('open');$i('wfab').classList.add('active');$i('wdot').classList.remove('show');sessionStorage.setItem('wpy_seen','1');if(hist.length===0)setTimeout(()=>_welcome(),420);setTimeout(()=>$i('wta').focus(),500);},
    close(){$i('wpanel').classList.remove('open');$i('wfab').classList.remove('active');},
    async send(){
      if(busy)return;
      const ta=$i('wta');const text=ta.value.trim();if(!text)return;
      ta.value='';ta.style.height='auto';_hideQuick();_add('user',text);_showTyping();
      busy=true;$i('wsend').disabled=true;
      try{const r=await wf('/wispy/chat',{messages:hist,personality:pers});const d=await r.json();_hideTyping();_add('assistant',d.reply||d.error||'Ой, что-то пошло не так... 😳');}
      catch(e){_hideTyping();_add('assistant','О-ой! Кажется, я уронила серверный кабель... 🙈 Попробуй чуть позже!');}
      finally{busy=false;$i('wsend').disabled=false;$i('wta').focus();}
    },
    quick(text){$i('wta').value=text;this.send();},
    clear(){hist=[];$i('wmsgs').innerHTML='';$i('wquick').style.display='';setTimeout(()=>_welcome(),150);},
    openSettings(){$i('wpta').value=pers;$i('wpcc').textContent=pers.length;_syncPbtns();$i('woverlay').classList.add('open');},
    closeSettings(){$i('woverlay').classList.remove('open');},
    oc(e){if(e.target===$i('woverlay'))this.closeSettings();},
    sp(btn){$i('wpta').value=btn.dataset.v||'';$i('wpcc').textContent=$i('wpta').value.length;_syncPbtns();},
    async save(){pers=$i('wpta').value;try{await wf('/wispy/personality',{personality:pers});}catch(e){}this.closeSettings();const replies=['*поправляет очки* Поняла! Буду именно такой! 💜✨','*торопливо записывает* Записала! Постараюсь! 😊','*прижимает листочек к груди* Ого! Обязательно попробую! 🌸'];setTimeout(()=>_add('assistant',replies[Math.floor(Math.random()*replies.length)]),300);},
  };
})();
document.addEventListener('DOMContentLoaded',()=>WP.init());
</script>

</body>
</html>
