<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Рейтинг</title>
  <style>
    *, *::before, *::after {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      background: #000;
      font-family: Arial, Helvetica, sans-serif;
      color: #fff;
      display: flex;
      justify-content: center;
      padding: 40px 20px;
    }

    .wrap {
      width: 100%;
      max-width: 900px;
    }

    /* ── Heading ── */
    .heading {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 14px;
      margin-bottom: 36px;
    }

    .heading svg {
      flex-shrink: 0;
      width: 38px;
      height: 38px;
    }

    .heading__text {
      font-size: 30px;
      font-weight: 700;
      color: #cc0000;
      white-space: nowrap;
    }

    /* ── Grid ── */
    .grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 18px;
    }

    /* ── Card ── */
    .card {
      background: #2c2f3a;
      border-radius: 18px;
      padding: 20px 20px 24px 20px;
      display: flex;
      flex-direction: column;
    }

    /* top: rank + avatar */
    .card__top {
      position: relative;
      display: flex;
      justify-content: center;
      min-height: 158px;
      margin-bottom: 18px;
    }

    .card__rank {
      position: absolute;
      top: 0;
      left: 0;
      font-size: 52px;
      font-weight: 900;
      color: #fff;
      line-height: 1;
    }

    .card__avatar {
      width: 138px;
      height: 138px;
      border-radius: 50%;
      overflow: hidden;
      flex-shrink: 0;
      align-self: flex-end;
    }

    .card__avatar svg {
      display: block;
      width: 100%;
      height: 100%;
    }

    /* name + nick */
    .card__body {
      text-align: center;
      flex: 1;
      margin-bottom: 22px;
    }

    .card__name {
      font-size: 20px;
      font-weight: 600;
      color: #fff;
      line-height: 1.3;
      margin-bottom: 8px;
    }

    .card__nick {
      font-size: 16px;
      font-weight: 500;
      color: #cc0000;
    }

    /* bottom: coin + divider + score */
    .card__footer {
      display: flex;
      align-items: center;
      gap: 14px;
    }

    .coin {
      flex-shrink: 0;
      width: 44px;
      height: 44px;
      border-radius: 50%;
      background: radial-gradient(circle at 36% 30%, #ffe55a 0%, #d49500 52%, #8a6000 100%);
      box-shadow:
        inset 0 2px 4px rgba(255, 240, 130, 0.5),
        0 3px 8px rgba(0,0,0,0.5);
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .coin svg {
      width: 24px;
      height: 24px;
    }

    .vdiv {
      width: 1px;
      height: 44px;
      background: #484b57;
      flex-shrink: 0;
    }

    .card__score {
      font-size: 40px;
      font-weight: 900;
      color: #cc0000;
      line-height: 1;
    }
  </style>
</head>
<body>

<div class="wrap">

  <!-- ── Heading ── -->
  <div class="heading">
    <!-- outlined red star -->
    <svg viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
      <polygon
        points="19,3 23.5,14 35.5,14 26,21.5 29.5,33 19,26.5 8.5,33 12,21.5 2.5,14 14.5,14"
        stroke="#cc0000" stroke-width="2.2" stroke-linejoin="round"
        fill="none"/>
    </svg>
    <span class="heading__text">Рейтинг по итогам всех игр</span>
  </div>

  <!-- ── Cards grid ── -->
  <div class="grid">

    <!-- Card 1 -->
    <div class="card">
      <div class="card__top">
        <span class="card__rank">1</span>
        <div class="card__avatar">
          <svg viewBox="0 0 138 138" xmlns="http://www.w3.org/2000/svg">
            <!-- red dramatic background -->
            <rect width="138" height="138" fill="#1a0000"/>
            <radialGradient id="bg1" cx="50%" cy="45%" r="65%">
              <stop offset="0%" stop-color="#cc0000" stop-opacity="0.9"/>
              <stop offset="70%" stop-color="#660000" stop-opacity="0.7"/>
              <stop offset="100%" stop-color="#0a0000" stop-opacity="1"/>
            </radialGradient>
            <rect width="138" height="138" fill="url(#bg1)"/>
            <!-- coat body -->
            <rect x="24" y="96" width="90" height="50" rx="6" fill="#0c0c0c"/>
            <!-- lapels -->
            <polygon points="46,96 69,115 69,145 30,145 22,110" fill="#141414"/>
            <polygon points="92,96 69,115 69,145 108,145 116,110" fill="#111"/>
            <!-- white shirt front -->
            <rect x="60" y="96" width="18" height="35" fill="#e8e8e0"/>
            <!-- neck -->
            <rect x="58" y="84" width="22" height="18" rx="4" fill="#c09878"/>
            <!-- head -->
            <ellipse cx="69" cy="74" rx="25" ry="27" fill="#c09878"/>
            <!-- jaw shadow -->
            <ellipse cx="69" cy="90" rx="16" ry="8" fill="#a07860" opacity="0.5"/>
            <!-- hair sides (dark, flowing) -->
            <rect x="14" y="60" width="12" height="35" rx="5" fill="#0a0a0a"/>
            <rect x="112" y="60" width="12" height="35" rx="5" fill="#0a0a0a"/>
            <!-- hair back/strands -->
            <ellipse cx="24" cy="75" rx="10" ry="18" fill="#0d0d0d"/>
            <ellipse cx="114" cy="75" rx="10" ry="18" fill="#0d0d0d"/>
            <!-- wide-brim hat -->
            <rect x="20" y="52" width="98" height="10" rx="5" fill="#0d0d0d"/>
            <!-- hat crown -->
            <rect x="38" y="26" width="62" height="30" rx="6" fill="#0d0d0d"/>
            <!-- hat band detail -->
            <rect x="38" y="51" width="62" height="5" fill="#080808"/>
            <!-- hat highlight -->
            <rect x="42" y="28" width="30" height="4" rx="2" fill="#1a1a1a" opacity="0.7"/>
            <!-- eyes (shadowed) -->
            <ellipse cx="58" cy="72" rx="6" ry="5" fill="#0a0a0a"/>
            <ellipse cx="80" cy="72" rx="6" ry="5" fill="#0a0a0a"/>
            <ellipse cx="58" cy="71" rx="4" ry="3.5" fill="#d4b890"/>
            <ellipse cx="80" cy="71" rx="4" ry="3.5" fill="#d4b890"/>
            <circle cx="59" cy="71.5" r="2.5" fill="#2a1a08"/>
            <circle cx="81" cy="71.5" r="2.5" fill="#2a1a08"/>
            <circle cx="59.8" cy="70.8" r="0.9" fill="#fff" opacity="0.6"/>
            <circle cx="81.8" cy="70.8" r="0.9" fill="#fff" opacity="0.6"/>
            <!-- lashes -->
            <line x1="53" y1="67" x2="64" y2="66" stroke="#0d0d0d" stroke-width="1.5"/>
            <line x1="75" y1="67" x2="86" y2="66" stroke="#0d0d0d" stroke-width="1.5"/>
            <!-- nose -->
            <ellipse cx="69" cy="79" rx="4" ry="3" fill="#a87860"/>
            <circle cx="66" cy="81" r="1.8" fill="#9a6e52"/>
            <circle cx="72" cy="81" r="1.8" fill="#9a6e52"/>
            <!-- red lips -->
            <path d="M61 86 Q65 89 69 89 Q73 89 77 86" stroke="#cc2233" stroke-width="2.2" fill="none" stroke-linecap="round"/>
            <path d="M61 86 Q65 84 69 84 Q73 84 77 86" stroke="#cc2233" stroke-width="1.4" fill="none" stroke-linecap="round"/>
            <ellipse cx="69" cy="87" rx="8" ry="2" fill="#bb1122" opacity="0.5"/>
            <!-- cheek blush -->
            <ellipse cx="52" cy="80" rx="7" ry="4" fill="#cc3344" opacity="0.2"/>
            <ellipse cx="86" cy="80" rx="7" ry="4" fill="#cc3344" opacity="0.2"/>
            <!-- vignette -->
            <radialGradient id="vig1" cx="50%" cy="50%" r="50%">
              <stop offset="55%" stop-color="transparent"/>
              <stop offset="100%" stop-color="#000" stop-opacity="0.65"/>
            </radialGradient>
            <rect width="138" height="138" fill="url(#vig1)"/>
          </svg>
        </div>
      </div>
      <div class="card__body">
        <div class="card__name">Жанна<br>Киске</div>
        <div class="card__nick">Лютый</div>
      </div>
      <div class="card__footer">
        <div class="coin">
          <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M7 22H4a1 1 0 01-1-1v-7a1 1 0 011-1h3m0 9v-9m0 9h10a2 2 0 002-2v-5a2 2 0 00-2-2h-3.586a1 1 0 01-.707-.293l-2-2A1 1 0 0013 2h-1a1 1 0 00-1 1v4.586a1 1 0 01-.293.707L9 9.586A1 1 0 007 10.17V13"
              stroke="#5a3300" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </div>
        <div class="vdiv"></div>
        <div class="card__score">3467</div>
      </div>
    </div>

    <!-- Card 2 -->
    <div class="card">
      <div class="card__top">
        <span class="card__rank">2</span>
        <div class="card__avatar">
          <svg viewBox="0 0 138 138" xmlns="http://www.w3.org/2000/svg">
            <rect width="138" height="138" fill="#1a0000"/>
            <radialGradient id="bg2" cx="50%" cy="45%" r="65%">
              <stop offset="0%" stop-color="#cc0000" stop-opacity="0.9"/>
              <stop offset="70%" stop-color="#660000" stop-opacity="0.7"/>
              <stop offset="100%" stop-color="#0a0000" stop-opacity="1"/>
            </radialGradient>
            <rect width="138" height="138" fill="url(#bg2)"/>
            <rect x="24" y="96" width="90" height="50" rx="6" fill="#0c0c0c"/>
            <polygon points="46,96 69,115 69,145 30,145 22,110" fill="#141414"/>
            <polygon points="92,96 69,115 69,145 108,145 116,110" fill="#111"/>
            <rect x="60" y="96" width="18" height="35" fill="#e8e8e0"/>
            <rect x="58" y="84" width="22" height="18" rx="4" fill="#c09878"/>
            <ellipse cx="69" cy="74" rx="25" ry="27" fill="#c09878"/>
            <ellipse cx="69" cy="90" rx="16" ry="8" fill="#a07860" opacity="0.5"/>
            <rect x="14" y="60" width="12" height="35" rx="5" fill="#0a0a0a"/>
            <rect x="112" y="60" width="12" height="35" rx="5" fill="#0a0a0a"/>
            <ellipse cx="24" cy="75" rx="10" ry="18" fill="#0d0d0d"/>
            <ellipse cx="114" cy="75" rx="10" ry="18" fill="#0d0d0d"/>
            <rect x="20" y="52" width="98" height="10" rx="5" fill="#0d0d0d"/>
            <rect x="38" y="26" width="62" height="30" rx="6" fill="#0d0d0d"/>
            <rect x="38" y="51" width="62" height="5" fill="#080808"/>
            <rect x="42" y="28" width="30" height="4" rx="2" fill="#1a1a1a" opacity="0.7"/>
            <ellipse cx="58" cy="72" rx="6" ry="5" fill="#0a0a0a"/>
            <ellipse cx="80" cy="72" rx="6" ry="5" fill="#0a0a0a"/>
            <ellipse cx="58" cy="71" rx="4" ry="3.5" fill="#d4b890"/>
            <ellipse cx="80" cy="71" rx="4" ry="3.5" fill="#d4b890"/>
            <circle cx="59" cy="71.5" r="2.5" fill="#2a1a08"/>
            <circle cx="81" cy="71.5" r="2.5" fill="#2a1a08"/>
            <circle cx="59.8" cy="70.8" r="0.9" fill="#fff" opacity="0.6"/>
            <circle cx="81.8" cy="70.8" r="0.9" fill="#fff" opacity="0.6"/>
            <line x1="53" y1="67" x2="64" y2="66" stroke="#0d0d0d" stroke-width="1.5"/>
            <line x1="75" y1="67" x2="86" y2="66" stroke="#0d0d0d" stroke-width="1.5"/>
            <ellipse cx="69" cy="79" rx="4" ry="3" fill="#a87860"/>
            <circle cx="66" cy="81" r="1.8" fill="#9a6e52"/>
            <circle cx="72" cy="81" r="1.8" fill="#9a6e52"/>
            <path d="M61 86 Q65 89 69 89 Q73 89 77 86" stroke="#cc2233" stroke-width="2.2" fill="none" stroke-linecap="round"/>
            <path d="M61 86 Q65 84 69 84 Q73 84 77 86" stroke="#cc2233" stroke-width="1.4" fill="none" stroke-linecap="round"/>
            <ellipse cx="69" cy="87" rx="8" ry="2" fill="#bb1122" opacity="0.5"/>
            <ellipse cx="52" cy="80" rx="7" ry="4" fill="#cc3344" opacity="0.2"/>
            <ellipse cx="86" cy="80" rx="7" ry="4" fill="#cc3344" opacity="0.2"/>
            <radialGradient id="vig2" cx="50%" cy="50%" r="50%">
              <stop offset="55%" stop-color="transparent"/>
              <stop offset="100%" stop-color="#000" stop-opacity="0.65"/>
            </radialGradient>
            <rect width="138" height="138" fill="url(#vig2)"/>
          </svg>
        </div>
      </div>
      <div class="card__body">
        <div class="card__name">Владимир<br>Владимиров</div>
        <div class="card__nick">Лютый</div>
      </div>
      <div class="card__footer">
        <div class="coin">
          <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M7 22H4a1 1 0 01-1-1v-7a1 1 0 011-1h3m0 9v-9m0 9h10a2 2 0 002-2v-5a2 2 0 00-2-2h-3.586a1 1 0 01-.707-.293l-2-2A1 1 0 0013 2h-1a1 1 0 00-1 1v4.586a1 1 0 01-.293.707L9 9.586A1 1 0 007 10.17V13"
              stroke="#5a3300" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </div>
        <div class="vdiv"></div>
        <div class="card__score">2998</div>
      </div>
    </div>

    <!-- Card 3 -->
    <div class="card">
      <div class="card__top">
        <span class="card__rank">3</span>
        <div class="card__avatar">
          <svg viewBox="0 0 138 138" xmlns="http://www.w3.org/2000/svg">
            <rect width="138" height="138" fill="#1a0000"/>
            <radialGradient id="bg3" cx="50%" cy="45%" r="65%">
              <stop offset="0%" stop-color="#cc0000" stop-opacity="0.9"/>
              <stop offset="70%" stop-color="#660000" stop-opacity="0.7"/>
              <stop offset="100%" stop-color="#0a0000" stop-opacity="1"/>
            </radialGradient>
            <rect width="138" height="138" fill="url(#bg3)"/>
            <rect x="24" y="96" width="90" height="50" rx="6" fill="#0c0c0c"/>
            <polygon points="46,96 69,115 69,145 30,145 22,110" fill="#141414"/>
            <polygon points="92,96 69,115 69,145 108,145 116,110" fill="#111"/>
            <rect x="60" y="96" width="18" height="35" fill="#e8e8e0"/>
            <rect x="58" y="84" width="22" height="18" rx="4" fill="#c09878"/>
            <ellipse cx="69" cy="74" rx="25" ry="27" fill="#c09878"/>
            <ellipse cx="69" cy="90" rx="16" ry="8" fill="#a07860" opacity="0.5"/>
            <rect x="14" y="60" width="12" height="35" rx="5" fill="#0a0a0a"/>
            <rect x="112" y="60" width="12" height="35" rx="5" fill="#0a0a0a"/>
            <ellipse cx="24" cy="75" rx="10" ry="18" fill="#0d0d0d"/>
            <ellipse cx="114" cy="75" rx="10" ry="18" fill="#0d0d0d"/>
            <rect x="20" y="52" width="98" height="10" rx="5" fill="#0d0d0d"/>
            <rect x="38" y="26" width="62" height="30" rx="6" fill="#0d0d0d"/>
            <rect x="38" y="51" width="62" height="5" fill="#080808"/>
            <rect x="42" y="28" width="30" height="4" rx="2" fill="#1a1a1a" opacity="0.7"/>
            <ellipse cx="58" cy="72" rx="6" ry="5" fill="#0a0a0a"/>
            <ellipse cx="80" cy="72" rx="6" ry="5" fill="#0a0a0a"/>
            <ellipse cx="58" cy="71" rx="4" ry="3.5" fill="#d4b890"/>
            <ellipse cx="80" cy="71" rx="4" ry="3.5" fill="#d4b890"/>
            <circle cx="59" cy="71.5" r="2.5" fill="#2a1a08"/>
            <circle cx="81" cy="71.5" r="2.5" fill="#2a1a08"/>
            <circle cx="59.8" cy="70.8" r="0.9" fill="#fff" opacity="0.6"/>
            <circle cx="81.8" cy="70.8" r="0.9" fill="#fff" opacity="0.6"/>
            <line x1="53" y1="67" x2="64" y2="66" stroke="#0d0d0d" stroke-width="1.5"/>
            <line x1="75" y1="67" x2="86" y2="66" stroke="#0d0d0d" stroke-width="1.5"/>
            <ellipse cx="69" cy="79" rx="4" ry="3" fill="#a87860"/>
            <circle cx="66" cy="81" r="1.8" fill="#9a6e52"/>
            <circle cx="72" cy="81" r="1.8" fill="#9a6e52"/>
            <path d="M61 86 Q65 89 69 89 Q73 89 77 86" stroke="#cc2233" stroke-width="2.2" fill="none" stroke-linecap="round"/>
            <path d="M61 86 Q65 84 69 84 Q73 84 77 86" stroke="#cc2233" stroke-width="1.4" fill="none" stroke-linecap="round"/>
            <ellipse cx="69" cy="87" rx="8" ry="2" fill="#bb1122" opacity="0.5"/>
            <ellipse cx="52" cy="80" rx="7" ry="4" fill="#cc3344" opacity="0.2"/>
            <ellipse cx="86" cy="80" rx="7" ry="4" fill="#cc3344" opacity="0.2"/>
            <radialGradient id="vig3" cx="50%" cy="50%" r="50%">
              <stop offset="55%" stop-color="transparent"/>
              <stop offset="100%" stop-color="#000" stop-opacity="0.65"/>
            </radialGradient>
            <rect width="138" height="138" fill="url(#vig3)"/>
          </svg>
        </div>
      </div>
      <div class="card__body">
        <div class="card__name">Рой<br>Джонс</div>
        <div class="card__nick">Лютый</div>
      </div>
      <div class="card__footer">
        <div class="coin">
          <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M7 22H4a1 1 0 01-1-1v-7a1 1 0 011-1h3m0 9v-9m0 9h10a2 2 0 002-2v-5a2 2 0 00-2-2h-3.586a1 1 0 01-.707-.293l-2-2A1 1 0 0013 2h-1a1 1 0 00-1 1v4.586a1 1 0 01-.293.707L9 9.586A1 1 0 007 10.17V13"
              stroke="#5a3300" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </div>
        <div class="vdiv"></div>
        <div class="card__score">2567</div>
      </div>
    </div>

  </div>
</div>

</body>
</html>
