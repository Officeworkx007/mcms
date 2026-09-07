<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>HCLSC — Mediation Case Management System</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
  :root{
    --ink:#13152D;
    --ink-soft:#4B4D6B;
    --paper:#F5F6FC;
    --paper-raised:#FFFFFF;
    --indigo:#4C3FE0;
    --indigo-deep:#2E22A6;
    --coral:#FF6B4A;
    --coral-deep:#E8542F;
    --mint:#22D3B2;
    --line:#E2E1F5;
    --radius:14px;
    --shadow:0 20px 45px -20px rgba(19,21,45,0.25);
  }

  *{margin:0;padding:0;box-sizing:border-box;}

  html{scroll-behavior:smooth;}

  body{
    font-family:'Inter',sans-serif;
    background:var(--paper);
    color:var(--ink);
    -webkit-font-smoothing:antialiased;
    overflow-x:hidden;
  }

  h1,h2,h3,.brand-word{font-family:'Sora',sans-serif;}

  a{color:inherit;}

  :focus-visible{
    outline:2.5px solid var(--indigo);
    outline-offset:3px;
    border-radius:4px;
  }

  /* ===== TOP BAR ===== */
  .topbar{
    position:sticky;top:0;z-index:40;
    display:flex;align-items:center;justify-content:space-between;
    padding:20px clamp(20px,5vw,64px);
    background:rgba(245,246,252,0.82);
    backdrop-filter:blur(10px);
    border-bottom:1px solid transparent;
  }
  .topbar.scrolled{border-bottom-color:var(--line);}

  .brand{display:flex;align-items:center;gap:10px;text-decoration:none;}
  .brand-mark{
    width:36px;height:36px;border-radius:10px;
    background:linear-gradient(135deg,var(--indigo),var(--indigo-deep));
    display:flex;align-items:center;justify-content:center;
    flex-shrink:0;
  }
  .brand-mark svg{width:19px;height:19px;}
  .brand-text{display:flex;flex-direction:column;line-height:1.1;}
  .brand-word{font-size:16px;font-weight:700;color:var(--ink);letter-spacing:0.01em;}
  .brand-sub{font-size:10.5px;color:var(--ink-soft);font-weight:500;margin-top:1px;}

  .topnav{display:flex;align-items:center;gap:28px;}
  .topnav-link{
    font-size:14px;font-weight:500;color:var(--ink-soft);
    text-decoration:none;transition:color 0.15s;
  }
  .topnav-link:hover{color:var(--ink);}

  .btn{
    font-family:'Inter',sans-serif;
    font-size:14px;font-weight:600;
    padding:10px 20px;border-radius:9px;border:none;
    cursor:pointer;transition:transform 0.15s,box-shadow 0.15s,background 0.15s;
    display:inline-flex;align-items:center;gap:8px;
  }
  .btn:active{transform:translateY(1px);}
  .btn-primary{
    background:var(--coral);color:#fff;
    box-shadow:0 10px 24px -8px rgba(255,107,74,0.55);
  }
  .btn-primary:hover{background:var(--coral-deep);}
  .btn-ghost{background:transparent;color:var(--ink);}
  .btn-ghost:hover{background:rgba(19,21,45,0.06);}

  /* ===== HERO ===== */
  .hero{
    display:grid;
    grid-template-columns:1.05fr 0.95fr;
    align-items:center;
    gap:40px;
    padding:56px clamp(20px,5vw,64px) 88px;
    max-width:1320px;
    margin:0 auto;
  }

  .hero-copy{max-width:520px;}

  .hero-kicker{
    display:inline-flex;align-items:center;gap:8px;
    font-size:13px;font-weight:600;color:var(--indigo);
    background:rgba(76,63,224,0.09);
    padding:6px 12px;border-radius:100px;
    margin-bottom:22px;
  }
  .hero-kicker-dot{width:6px;height:6px;border-radius:50%;background:var(--indigo);}

  .hero h1{
    font-size:clamp(34px,4.4vw,50px);
    font-weight:700;
    line-height:1.12;
    letter-spacing:-0.015em;
    color:var(--ink);
    margin-bottom:20px;
  }

  .hero p{
    font-size:16.5px;
    line-height:1.65;
    color:var(--ink-soft);
    margin-bottom:32px;
  }

  .hero-actions{display:flex;align-items:center;gap:18px;flex-wrap:wrap;}
  .btn-lg{padding:14px 26px;font-size:15px;border-radius:11px;}
  .hero-note{font-size:13px;color:var(--ink-soft);}

  /* ===== HERO ILLUSTRATION ===== */
  .hero-art{
    position:relative;
    height:460px;
    display:flex;align-items:center;justify-content:center;
  }
  .art-glow{
    position:absolute;inset:0;
    background:
      radial-gradient(320px 320px at 68% 30%, rgba(76,63,224,0.16), transparent 70%),
      radial-gradient(280px 280px at 30% 75%, rgba(34,211,178,0.14), transparent 70%);
    filter:blur(4px);
  }
  .art-svg{position:relative;width:100%;max-width:440px;height:auto;}

  .path-a{
    stroke:var(--indigo);
    stroke-width:2.5;
    fill:none;
    stroke-linecap:round;
    stroke-dasharray:600;
    stroke-dashoffset:600;
    animation:draw 1.6s cubic-bezier(.4,0,.2,1) 0.2s forwards;
  }
  .path-b{
    stroke:var(--coral);
    stroke-width:2.5;
    fill:none;
    stroke-linecap:round;
    stroke-dasharray:600;
    stroke-dashoffset:600;
    animation:draw 1.6s cubic-bezier(.4,0,.2,1) 0.45s forwards;
  }
  @keyframes draw{to{stroke-dashoffset:0;}}

  .resolve-dot{
    opacity:0;
    animation:pop 0.5s cubic-bezier(.34,1.56,.64,1) 1.9s forwards;
  }
  @keyframes pop{
    0%{opacity:0;transform:scale(0.3);}
    100%{opacity:1;transform:scale(1);}
  }

  .art-card{
    position:absolute;
    background:var(--paper-raised);
    border:1px solid var(--line);
    border-radius:12px;
    box-shadow:var(--shadow);
    padding:12px 14px;
    opacity:0;
    animation:cardin 0.6s cubic-bezier(.2,.8,.2,1) forwards;
  }
  @keyframes cardin{
    0%{opacity:0;transform:translateY(10px);}
    100%{opacity:1;transform:translateY(0);}
  }
  .art-card-1{top:8%;right:2%;animation-delay:2.05s;}
  .art-card-2{bottom:10%;left:0%;animation-delay:2.25s;}

  .card-label{font-size:9.5px;font-weight:600;color:var(--ink-soft);letter-spacing:0.02em;margin-bottom:4px;}
  .card-value{font-family:'Sora',sans-serif;font-size:12.5px;font-weight:600;color:var(--ink);white-space:nowrap;}
  .card-mono{font-family:'Inter',monospace;font-size:11px;color:var(--ink-soft);margin-top:2px;}
  .status-pill{
    display:inline-flex;align-items:center;gap:5px;
    font-size:10.5px;font-weight:600;color:#0F9D74;
    background:rgba(34,211,178,0.16);
    padding:3px 9px;border-radius:100px;margin-top:6px;
  }
  .status-pill::before{content:'';width:5px;height:5px;border-radius:50%;background:#0F9D74;}

  /* ===== HOW IT WORKS ===== */
  .flow{
    max-width:1320px;margin:0 auto;
    padding:20px clamp(20px,5vw,64px) 100px;
  }
  .flow-head{margin-bottom:44px;max-width:560px;}
  .flow-head h2{
    font-size:clamp(24px,3vw,30px);
    font-weight:700;letter-spacing:-0.01em;
    margin-bottom:10px;
  }
  .flow-head p{font-size:15px;color:var(--ink-soft);line-height:1.6;}

  .flow-steps{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:0;
    position:relative;
  }
  .flow-steps::before{
    content:'';
    position:absolute;top:22px;left:calc(100%/6);right:calc(100%/6);
    height:1.5px;
    background:repeating-linear-gradient(to right, var(--line) 0 8px, transparent 8px 14px);
  }
  .flow-step{position:relative;padding-right:32px;}
  .flow-num{
    width:44px;height:44px;border-radius:12px;
    background:var(--paper-raised);
    border:1.5px solid var(--line);
    display:flex;align-items:center;justify-content:center;
    font-family:'Sora',sans-serif;font-size:15px;font-weight:700;color:var(--indigo);
    margin-bottom:18px;position:relative;z-index:1;
  }
  .flow-step:nth-child(3) .flow-num{color:var(--coral);}
  .flow-step h3{font-size:16.5px;font-weight:700;margin-bottom:8px;letter-spacing:-0.005em;}
  .flow-step p{font-size:13.5px;color:var(--ink-soft);line-height:1.6;}

  /* ===== FOOTER ===== */
  .site-footer{
    border-top:1px solid var(--line);
    padding:24px clamp(20px,5vw,64px);
    display:flex;align-items:center;justify-content:space-between;
    flex-wrap:wrap;gap:12px;
  }
  .footer-left{display:flex;align-items:center;gap:10px;font-size:13px;color:var(--ink-soft);}
  .footer-right{font-size:12.5px;color:var(--ink-soft);}

  /* ===== MODAL ===== */
  .modal-overlay{
    position:fixed;inset:0;z-index:100;
    background:rgba(19,21,45,0.55);
    backdrop-filter:blur(6px);
    display:flex;align-items:center;justify-content:center;
    padding:20px;
    opacity:0;pointer-events:none;
    transition:opacity 0.22s ease;
  }
  .modal-overlay.open{opacity:1;pointer-events:auto;}

  .modal{
    width:100%;max-width:404px;
    background:var(--paper-raised);
    border-radius:20px;
    box-shadow:0 30px 70px -20px rgba(19,21,45,0.45);
    padding:32px 30px 28px;
    transform:scale(0.94) translateY(8px);
    opacity:0;
    transition:transform 0.28s cubic-bezier(.2,.9,.25,1), opacity 0.22s ease;
    position:relative;
  }
  .modal-overlay.open .modal{transform:scale(1) translateY(0);opacity:1;}

  .modal-close{
    position:absolute;top:16px;right:16px;
    width:30px;height:30px;border-radius:8px;border:none;
    background:transparent;color:var(--ink-soft);
    display:flex;align-items:center;justify-content:center;
    cursor:pointer;transition:background 0.15s,color 0.15s;
  }
  .modal-close:hover{background:rgba(19,21,45,0.06);color:var(--ink);}

  .modal-mark{
    width:44px;height:44px;border-radius:12px;
    background:linear-gradient(135deg,var(--indigo),var(--indigo-deep));
    display:flex;align-items:center;justify-content:center;
    margin-bottom:18px;
  }
  .modal-mark svg{width:22px;height:22px;}

  .modal h2{font-size:21px;font-weight:700;letter-spacing:-0.01em;margin-bottom:6px;}
  .modal-sub{font-size:13.5px;color:var(--ink-soft);margin-bottom:24px;line-height:1.5;}

  .field{margin-bottom:16px;}
  .field label{
    display:block;font-size:12px;font-weight:600;color:var(--ink);
    margin-bottom:7px;
  }
  .field-input-wrap{position:relative;}
  .field input{
    width:100%;padding:12px 14px;
    font-family:'Inter',sans-serif;font-size:14px;color:var(--ink);
    background:var(--paper);
    border:1.5px solid var(--line);border-radius:10px;
    outline:none;transition:border-color 0.15s,box-shadow 0.15s;
  }
  .field input::placeholder{color:#A8ABC4;}
  .field input:focus{
    border-color:var(--indigo);
    box-shadow:0 0 0 4px rgba(76,63,224,0.12);
  }
  .field-toggle{
    position:absolute;right:6px;top:50%;transform:translateY(-50%);
    width:32px;height:32px;border:none;background:transparent;
    color:var(--ink-soft);cursor:pointer;border-radius:7px;
    display:flex;align-items:center;justify-content:center;
    transition:background 0.15s;
  }
  .field-toggle:hover{background:rgba(19,21,45,0.06);}

  .field-row{display:flex;align-items:center;justify-content:space-between;margin-bottom:22px;}
  .remember{display:flex;align-items:center;gap:8px;font-size:13px;color:var(--ink-soft);}
  .remember input{accent-color:var(--indigo);width:15px;height:15px;cursor:pointer;}
  .forgot-link{font-size:13px;font-weight:600;color:var(--indigo);text-decoration:none;}
  .forgot-link:hover{text-decoration:underline;}

  .modal .btn-primary{
    width:100%;justify-content:center;padding:13px;
    font-size:14.5px;border-radius:10px;
  }

  .modal-foot{
    margin-top:22px;padding-top:18px;
    border-top:1px solid var(--line);
    font-size:12.5px;color:var(--ink-soft);
    text-align:center;line-height:1.5;
  }

  /* ===== RESPONSIVE ===== */
  @media (max-width:880px){
    .topnav-link{display:none;}
    .hero{grid-template-columns:1fr;padding-top:36px;}
    .hero-art{height:320px;order:-1;margin-bottom:12px;}
    .flow-steps{grid-template-columns:1fr;gap:28px;}
    .flow-steps::before{display:none;}
    .flow-step{padding-right:0;}
  }

  @media (prefers-reduced-motion:reduce){
    *{animation-duration:0.01ms !important;animation-iteration-count:1 !important;transition-duration:0.01ms !important;}
  }
</style>
</head>
<body>

  <!-- TOP BAR -->
  <header class="topbar" id="topbar">
    <a href="#" class="brand">
      <span class="brand-mark">
        <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M12 3v18M5 8l-3 6a4 4 0 008 0l-3-6M19 8l-3 6a4 4 0 008 0l-3-6M5 8h14"/>
        </svg>
      </span>
      <span class="brand-text">
        <span class="brand-word">HCLSC</span>
        <span class="brand-sub">Mediation Case Management System</span>
      </span>
    </a>
    <nav class="topnav">
      <a href="#how-it-works" class="topnav-link">How it works</a>
      <button class="btn btn-primary" onclick="openModal()">Sign in</button>
    </nav>
  </header>

  <!-- HERO -->
  <section class="hero">
    <div class="hero-copy">
      <span class="hero-kicker"><span class="hero-kicker-dot"></span>High Court Legal Services Committee</span>
      <h1>Every dispute has a path to resolution.</h1>
      <p>HCLSC's mediation case management system keeps referrals, sittings, and outcomes organised — from the day a case is referred to the day it's settled.</p>
      <div class="hero-actions">
        <button class="btn btn-primary btn-lg" onclick="openModal()">Sign in to continue</button>
        <span class="hero-note">For authorised staff only</span>
      </div>
    </div>

    <div class="hero-art" aria-hidden="true">
      <div class="art-glow"></div>
      <svg class="art-svg" viewBox="0 0 440 400" fill="none">
        <path class="path-a" d="M20 60 C 140 60, 160 200, 220 200" />
        <path class="path-b" d="M420 340 C 300 340, 280 200, 220 200" />
        <circle class="resolve-dot" cx="220" cy="200" r="7" fill="#22D3B2"/>
        <circle class="resolve-dot" cx="220" cy="200" r="16" stroke="#22D3B2" stroke-width="1.5" opacity="0.35"/>
      </svg>

      <div class="art-card art-card-1">
        <div class="card-label">Case Reference</div>
        <div class="card-mono">HC/MED/2026/0417</div>
        <span class="status-pill">Resolved</span>
      </div>

      <div class="art-card art-card-2">
        <div class="card-label">Assigned Mediator</div>
        <div class="card-value">Adv. R. Meetei</div>
        <div class="card-mono">Next sitting · 14 Sep</div>
      </div>
    </div>
  </section>

  <!-- HOW IT WORKS -->
  <section class="flow" id="how-it-works">
    <div class="flow-head">
      <h2>From referral to resolution, in one place.</h2>
      <p>Every case moves through the same three stages — the system tracks each one so nothing sits without a record.</p>
    </div>
    <div class="flow-steps">
      <div class="flow-step">
        <div class="flow-num">01</div>
        <h3>Case referred</h3>
        <p>A case reaches the mediation centre and is logged with its parties, category, and the mediator assigned to it.</p>
      </div>
      <div class="flow-step">
        <div class="flow-num">02</div>
        <h3>Mediation conducted</h3>
        <p>Sitting dates are recorded as they happen, so the full history of a case is visible at any point.</p>
      </div>
      <div class="flow-step">
        <div class="flow-num">03</div>
        <h3>Outcome recorded</h3>
        <p>The case is marked settled or unsettled, and rolls up automatically into category and phase-wise reports.</p>
      </div>
    </div>
  </section>

  <!-- FOOTER -->
  <footer class="site-footer">
    <div class="footer-left">
      <span class="brand-mark" style="width:24px;height:24px;border-radius:6px;">
        <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" style="width:12px;height:12px;">
          <path d="M12 3v18M5 8l-3 6a4 4 0 008 0l-3-6M19 8l-3 6a4 4 0 008 0l-3-6M5 8h14"/>
        </svg>
      </span>
      HCLSC Mediation Case Management System
    </div>
    <div class="footer-right">For authorised court personnel only</div>
  </footer>

  <!-- LOGIN MODAL -->
  <div class="modal-overlay" id="loginOverlay">
    <div class="modal" role="dialog" aria-modal="true" aria-labelledby="loginTitle">
      <button class="modal-close" onclick="closeModal()" aria-label="Close">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round"><path d="M6 18L18 6M6 6l12 12"/></svg>
      </button>

      <div class="modal-mark">
        <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M12 3v18M5 8l-3 6a4 4 0 008 0l-3-6M19 8l-3 6a4 4 0 008 0l-3-6M5 8h14"/>
        </svg>
      </div>

      <h2 id="loginTitle">Sign in to HCLSC</h2>
      <p class="modal-sub">Enter your registry credentials to access the mediation case management system.</p>

      <form onsubmit="return false;">
        <div class="field">
          <label for="username">Username or email</label>
          <input id="username" type="text" placeholder="you@hclsc.gov.in" autocomplete="username">
        </div>

        <div class="field">
          <label for="password">Password</label>
          <div class="field-input-wrap">
            <input id="password" type="password" placeholder="Enter your password" autocomplete="current-password">
            <button type="button" class="field-toggle" onclick="togglePassword()" aria-label="Show password">
              <svg id="eyeIcon" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"/><circle cx="12" cy="12" r="3"/>
              </svg>
            </button>
          </div>
        </div>

        <div class="field-row">
          <label class="remember"><input type="checkbox"> Keep me signed in</label>
          <a href="#" class="forgot-link">Forgot password?</a>
        </div>

        <button type="submit" class="btn btn-primary">Sign in</button>
      </form>

      <div class="modal-foot">
        Access is provisioned by your court administrator.<br>Contact the registry office if you need an account.
      </div>
    </div>
  </div>

<script>
  // Top bar shadow on scroll
  const topbar = document.getElementById('topbar');
  window.addEventListener('scroll', () => {
    topbar.classList.toggle('scrolled', window.scrollY > 8);
  });

  // Modal open/close
  const overlay = document.getElementById('loginOverlay');

  function openModal(){
    overlay.classList.add('open');
    document.body.style.overflow = 'hidden';
    setTimeout(() => document.getElementById('username').focus(), 200);
  }
  function closeModal(){
    overlay.classList.remove('open');
    document.body.style.overflow = '';
  }
  overlay.addEventListener('click', (e) => {
    if (e.target === overlay) closeModal();
  });
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && overlay.classList.contains('open')) closeModal();
  });

  // Password visibility toggle
  function togglePassword(){
    const input = document.getElementById('password');
    const eye = document.getElementById('eyeIcon');
    const isHidden = input.type === 'password';
    input.type = isHidden ? 'text' : 'password';
    eye.innerHTML = isHidden
      ? '<path d="M17.94 17.94A10.94 10.94 0 0112 20c-7 0-11-8-11-8a21.8 21.8 0 015.06-6.06M9.9 4.24A10.94 10.94 0 0112 4c7 0 11 8 11 8a21.75 21.75 0 01-2.16 3.19M14.12 14.12a3 3 0 11-4.24-4.24"/><path d="M1 1l22 22"/>'
      : '<path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"/><circle cx="12" cy="12" r="3"/>';
  }
</script>

</body>
</html>

