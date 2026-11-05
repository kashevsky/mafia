<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Клубная игра</title>
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
      max-width: 660px;
    }

    /* ═══ BANNER ═══ */
    .banner {
      position: relative;
      height: 82px;
      display: flex;
      align-items: center;
      margin-bottom: 30px;
    }

    /* pill background — dark left side, red right side */
    .banner__pill {
      position: absolute;
      top: 0;
      left: 52px;   /* starts behind the circle center */
      right: 0;
      bottom: 0;
      border-radius: 41px;
      background: linear-gradient(
        to right,
        #180000 0%,
        #180000 16%,
        #d10000 30%,
        #d10000 100%
      );
    }

    /* large circle — protrudes above & below banner */
    .banner__avatar {
      position: relative;
      z-index: 2;
      flex-shrink: 0;
      width: 132px;
      height: 132px;
      border-radius: 50%;
      overflow: hidden;
      background: radial-gradient(circle at 42% 38%, #1a3060 0%, #0d1a40 40%, #040810 100%);
    }

    /* car SVG inside banner circle */
    .banner__avatar svg {
      width: 100%;
      height: 100%;
    }

    .banner__text {
      position: relative;
      z-index: 2;
      padding: 0 28px 0 22px;
    }

    .banner__sub {
      font-size: 15px;
      font-weight: 400;
      color: #ffbbbb;
      margin-bottom: 6px;
    }

    .banner__title {
      font-size: 29px;
      font-weight: 900;
      color: #fff;
      text-transform: uppercase;
      letter-spacing: 0.3px;
      white-space: nowrap;
    }

    /* ═══ CITY SUBTITLE ═══ */
    .city {
      text-align: center;
      font-size: 23px;
      font-weight: 700;
      color: #fff;
      margin-bottom: 22px;
    }

    /* ═══ PLAYERS CONTAINER ═══ */
    .players {
      border: 1.5px solid #aaaaaa;
      border-radius: 20px;
      background: #272727;
      padding: 10px;
      display: flex;
      flex-direction: column;
      gap: 8px;
    }

    /* ═══ PLAYER ROW ═══ */
    .row {
      display: flex;
      align-items: center;
      background: #1d1d1d;
      border-radius: 14px;
      height: 84px;
      overflow: hidden;
    }

    /* circle avatar */
    .row__avatar {
      flex-shrink: 0;
      width: 72px;
      height: 72px;
      border-radius: 50%;
      margin: 0 14px;
      overflow: hidden;
      background: #1a0000;
    }

    .row__avatar svg {
      width: 100%;
      height: 100%;
    }

    /* name + nick block */
    .row__info {
      flex: 1 1 0;
      min-width: 0;
      padding-right: 20px;
    }

    .row__name {
      font-size: 18px;
      font-weight: 600;
      color: #fff;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .row__nick {
      font-size: 14px;
      font-weight: 500;
      color: #cc0000;
      margin-top: 4px;
    }

    /* vertical dividers */
    .divider {
      width: 1px;
      height: 46px;
      background: #3e3e3e;
      flex-shrink: 0;
    }

    /* role */
    .row__role {
      font-size: 16px;
      color: #ccc;
      padding: 0 26px;
      white-space: nowrap;
      flex-shrink: 0;
    }

    /* coin */
    .row__coin {
      padding: 0 18px;
      flex-shrink: 0;
      display: flex;
      align-items: center;
    }

    .coin {
      width: 44px;
      height: 44px;
      border-radius: 50%;
      background: radial-gradient(
        circle at 36% 30%,
        #ffe458 0%,
        #d49500 50%,
        #906000 100%
      );
      box-shadow:
        inset 0 2px 4px rgba(255, 240, 140, 0.55),
        0 3px 8px rgba(0, 0, 0, 0.55);
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .coin svg {
      width: 24px;
      height: 24px;
    }

    /* score */
    .row__score {
      padding: 0 22px 0 18px;
      font-size: 36px;
      font-weight: 900;
      color: #cc0000;
      flex-shrink: 0;
    }
  </style>
</head>
<body>

<div class="wrap">

  <!-- ── Banner ── -->
  <div class="banner">
    <div class="banner__pill"></div>

    <!-- racing car circle -->
    <div class="banner__avatar">
      <svg viewBox="0 0 132 132" xmlns="http://www.w3.org/2000/svg">
        <!-- night city background -->
        <rect width="132" height="132" fill="#080c18"/>
        <!-- city glow on ground -->
        <ellipse cx="66" cy="115" rx="70" ry="24" fill="#1a0404" opacity="0.9"/>
        <!-- distant buildings -->
        <rect x="0"  y="40" width="12" height="55" fill="#0d1428"/>
        <rect x="14" y="52" width="9"  height="43" fill="#0d1428"/>
        <rect x="25" y="35" width="14" height="60" fill="#101830"/>
        <rect x="105" y="45" width="10" height="50" fill="#0d1428"/>
        <rect x="118" y="38" width="14" height="57" fill="#101830"/>
        <!-- building windows (tiny dots) -->
        <rect x="2"  y="44" width="2" height="2" fill="#3366cc" opacity="0.6"/>
        <rect x="6"  y="48" width="2" height="2" fill="#3366cc" opacity="0.5"/>
        <rect x="26" y="39" width="2" height="2" fill="#4488ee" opacity="0.6"/>
        <rect x="30" y="45" width="2" height="2" fill="#3366cc" opacity="0.5"/>
        <rect x="106" y="49" width="2" height="2" fill="#3366cc" opacity="0.5"/>
        <rect x="110" y="43" width="2" height="2" fill="#4488ee" opacity="0.4"/>
        <!-- road surface -->
        <rect x="0" y="96" width="132" height="36" fill="#0e0e14"/>
        <!-- road markings -->
        <rect x="58" y="100" width="5"  height="12" fill="#1a1a2a"/>
        <rect x="69" y="104" width="5"  height="8"  fill="#1a1a2a"/>
        <!-- motion blur streaks (speed effect) -->
        <rect x="0"  y="88" width="50" height="2" fill="#cc2200" opacity="0.7" rx="1"/>
        <rect x="0"  y="93" width="38" height="1" fill="#cc4400" opacity="0.5" rx="1"/>
        <rect x="82" y="88" width="50" height="2" fill="#cc2200" opacity="0.7" rx="1"/>
        <rect x="94" y="93" width="38" height="1" fill="#cc4400" opacity="0.5" rx="1"/>
        <!-- car body -->
        <rect x="22" y="74" width="88" height="20" rx="6" fill="#1a2e80"/>
        <!-- car roof -->
        <rect x="36" y="62" width="60" height="18" rx="5" fill="#1e34a0"/>
        <!-- windshield front -->
        <rect x="78" y="63" width="16" height="14" rx="2" fill="#3a78cc" opacity="0.8"/>
        <!-- windshield rear -->
        <rect x="38" y="63" width="16" height="14" rx="2" fill="#2a5899" opacity="0.7"/>
        <!-- car hood / front slope -->
        <polygon points="110,74 130,80 130,88 110,88" fill="#1a2e80"/>
        <!-- red police lights on roof -->
        <rect x="52" y="60" width="10" height="5" rx="2" fill="#ff2200" opacity="0.9"/>
        <rect x="70" y="60" width="10" height="5" rx="2" fill="#0044ff" opacity="0.9"/>
        <!-- headlights glow -->
        <ellipse cx="124" cy="82" rx="8" ry="5" fill="#ff4400" opacity="0.6"/>
        <rect x="120" y="78" width="8" height="4" rx="1" fill="#ffcc44"/>
        <rect x="120" y="83" width="8" height="3" rx="1" fill="#ff3300"/>
        <!-- wheels -->
        <circle cx="42"  cy="94" r="10" fill="#111"/>
        <circle cx="42"  cy="94" r="6"  fill="#1e1e1e"/>
        <circle cx="42"  cy="94" r="3"  fill="#333"/>
        <circle cx="90"  cy="94" r="10" fill="#111"/>
        <circle cx="90"  cy="94" r="6"  fill="#1e1e1e"/>
        <circle cx="90"  cy="94" r="3"  fill="#333"/>
        <!-- red accent stripe -->
        <rect x="22" y="78" width="88" height="3" rx="1" fill="#cc1100" opacity="0.85"/>
      </svg>
    </div>

    <div class="banner__text">
      <div class="banner__sub">Клубная игра 01.08.2026</div>
      <div class="banner__title">МИРНЫЕ&nbsp;–&nbsp;ПОБЕДИТЕЛИ</div>
    </div>
  </div>

  <!-- ── City line ── -->
  <div class="city">Мирный город: 8</div>

  <!-- ── Players ── -->
  <div class="players">

    <!-- Row 1 -->
    <div class="row">
      <div class="row__avatar">
        <!-- Detective man, dramatic red lighting -->
        <svg viewBox="0 0 72 72" xmlns="http://www.w3.org/2000/svg">
          <rect width="72" height="72" fill="#100202"/>
          <!-- red glow background -->
          <radialGradient id="gm" cx="50%" cy="80%" r="65%">
            <stop offset="0%" stop-color="#880000" stop-opacity="0.8"/>
            <stop offset="100%" stop-color="#100202" stop-opacity="0"/>
          </radialGradient>
          <rect width="72" height="72" fill="url(#gm)"/>
          <!-- coat body -->
          <rect x="14" y="48" width="44" height="32" rx="4" fill="#0e0e0e"/>
          <!-- suit lapels -->
          <polygon points="28,48 36,60 36,72 22,72 14,56" fill="#1a1a1a"/>
          <polygon points="44,48 36,60 36,72 50,72 58,56" fill="#161616"/>
          <!-- shirt & tie -->
          <rect x="32" y="48" width="8" height="20" fill="#e8e0d0"/>
          <polygon points="36,50 38.5,60 36,65 33.5,60" fill="#880000"/>
          <!-- neck -->
          <rect x="30" y="38" width="12" height="14" rx="3" fill="#c08060"/>
          <!-- head -->
          <ellipse cx="36" cy="32" rx="14" ry="15" fill="#b87050"/>
          <!-- jaw shadow -->
          <ellipse cx="36" cy="40" rx="9" ry="5" fill="#9a5a3a" opacity="0.6"/>
          <!-- hat brim -->
          <rect x="14" y="21" width="44" height="6" rx="3" fill="#111"/>
          <!-- hat crown -->
          <rect x="20" y="7" width="32" height="17" rx="4" fill="#111"/>
          <!-- hat shadow detail -->
          <rect x="20" y="19" width="32" height="4" fill="#0a0a0a"/>
          <!-- eyes (shadowed under brim) -->
          <ellipse cx="29" cy="30" rx="3.5" ry="2.5" fill="#0a0a0a"/>
          <ellipse cx="43" cy="30" rx="3.5" ry="2.5" fill="#0a0a0a"/>
          <!-- eye whites -->
          <ellipse cx="29" cy="29.5" rx="2" ry="1.5" fill="#ddd"/>
          <ellipse cx="43" cy="29.5" rx="2" ry="1.5" fill="#ddd"/>
          <!-- pupils -->
          <circle cx="29.5" cy="30" r="1.2" fill="#111"/>
          <circle cx="43.5" cy="30" r="1.2" fill="#111"/>
          <!-- nose -->
          <ellipse cx="36" cy="35" rx="2.5" ry="1.5" fill="#9a5040"/>
          <!-- mouth -->
          <path d="M31 39 Q36 41.5 41 39" stroke="#7a3828" stroke-width="1.5" fill="none" stroke-linecap="round"/>
          <!-- red side light on face -->
          <ellipse cx="16" cy="32" rx="10" ry="14" fill="#cc2200" opacity="0.18"/>
        </svg>
      </div>

      <div class="row__info">
        <div class="row__name">Александр Иванов</div>
        <div class="row__nick">Лютый</div>
      </div>

      <div class="divider"></div>

      <div class="row__role">Шериф</div>

      <div class="divider"></div>

      <div class="row__coin">
        <div class="coin">
          <!-- thumbs-up emblem on coin -->
          <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M7 22H4a1 1 0 01-1-1v-7a1 1 0 011-1h3m0 9v-9m0 9h10a2 2 0 002-2v-5a2 2 0 00-2-2h-3.586a1 1 0 01-.707-.293l-2-2A1 1 0 0013 2h-1a1 1 0 00-1 1v4.586a1 1 0 01-.293.707L9 9.586A1 1 0 007 10.17V13"
              stroke="#5a3300" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </div>
      </div>

      <div class="divider"></div>

      <div class="row__score">40</div>
    </div>

    <!-- Row 2 -->
    <div class="row">
      <div class="row__avatar">
        <!-- Detective woman, dramatic lighting -->
        <svg viewBox="0 0 72 72" xmlns="http://www.w3.org/2000/svg">
          <rect width="72" height="72" fill="#100202"/>
          <!-- red glow -->
          <radialGradient id="gf" cx="50%" cy="80%" r="65%">
            <stop offset="0%" stop-color="#880000" stop-opacity="0.8"/>
            <stop offset="100%" stop-color="#100202" stop-opacity="0"/>
          </radialGradient>
          <rect width="72" height="72" fill="url(#gf)"/>
          <!-- coat body -->
          <rect x="14" y="48" width="44" height="32" rx="4" fill="#0e0e0e"/>
          <!-- white shirt collar/front -->
          <polygon points="28,48 36,56 36,72 18,72 14,60" fill="#1a1a1a"/>
          <polygon points="44,48 36,56 36,72 54,72 58,60" fill="#161616"/>
          <rect x="30" y="48" width="12" height="18" fill="#e8e8e0"/>
          <!-- neck -->
          <rect x="30" y="38" width="12" height="13" rx="3" fill="#c09070"/>
          <!-- head -->
          <ellipse cx="36" cy="31" rx="13" ry="14" fill="#c09070"/>
          <!-- hair sides falling down -->
          <rect x="10" y="26" width="7"  height="18" rx="3" fill="#0a0a0a"/>
          <rect x="55" y="26" width="7"  height="18" rx="3" fill="#0a0a0a"/>
          <!-- wide-brim hat -->
          <rect x="8"  y="19" width="56" height="6" rx="3" fill="#111"/>
          <!-- hat crown -->
          <rect x="20" y="6"  width="32" height="16" rx="4" fill="#111"/>
          <!-- hat band red -->
          <rect x="20" y="18" width="32" height="3" fill="#880000"/>
          <!-- jaw softening -->
          <ellipse cx="36" cy="40" rx="8" ry="4" fill="#a07860" opacity="0.5"/>
          <!-- eyes -->
          <ellipse cx="29" cy="30" rx="3.5" ry="2.5" fill="#0a0a0a"/>
          <ellipse cx="43" cy="30" rx="3.5" ry="2.5" fill="#0a0a0a"/>
          <ellipse cx="29" cy="29.5" rx="2.2" ry="1.6" fill="#ddd"/>
          <ellipse cx="43" cy="29.5" rx="2.2" ry="1.6" fill="#ddd"/>
          <circle cx="29.5" cy="30" r="1.2" fill="#111"/>
          <circle cx="43.5" cy="30" r="1.2" fill="#111"/>
          <!-- lashes hint -->
          <line x1="27" y1="27.5" x2="32" y2="27" stroke="#111" stroke-width="1.2"/>
          <line x1="41" y1="27.5" x2="46" y2="27" stroke="#111" stroke-width="1.2"/>
          <!-- nose -->
          <ellipse cx="36" cy="34" rx="2" ry="1.5" fill="#a07060"/>
          <!-- red lips -->
          <path d="M31 38.5 Q33.5 40.5 36 40.5 Q38.5 40.5 41 38.5" stroke="#cc2233" stroke-width="1.8" fill="none" stroke-linecap="round"/>
          <path d="M31 38.5 Q33.5 37 36 37 Q38.5 37 41 38.5" stroke="#cc2233" stroke-width="1" fill="none" stroke-linecap="round"/>
          <!-- red side glow -->
          <ellipse cx="14" cy="32" rx="10" ry="14" fill="#cc2200" opacity="0.16"/>
        </svg>
      </div>

      <div class="row__info">
        <div class="row__name">Имя Длиннаяфамилия</div>
        <div class="row__nick">Лаки</div>
      </div>

      <div class="divider"></div>

      <div class="row__role">Детектив</div>

      <div class="divider"></div>

      <div class="row__coin">
        <div class="coin">
          <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M7 22H4a1 1 0 01-1-1v-7a1 1 0 011-1h3m0 9v-9m0 9h10a2 2 0 002-2v-5a2 2 0 00-2-2h-3.586a1 1 0 01-.707-.293l-2-2A1 1 0 0013 2h-1a1 1 0 00-1 1v4.586a1 1 0 01-.293.707L9 9.586A1 1 0 007 10.17V13"
              stroke="#5a3300" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </div>
      </div>

      <div class="divider"></div>

      <div class="row__score">39</div>
    </div>

  </div>
</div>

</body>
</html>
