<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>McAlpine Polska – Syfony, Odpływy, Armatura sanitarna</title>
  <style>
    /* ===========================
       RESET & BASE
    =========================== */
    *, *::before, *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    html {
      font-size: 16px;
      scroll-behavior: smooth;
    }

    body {
      font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
      color: #222;
      background: #fff;
      line-height: 1.6;
    }

    a {
      text-decoration: none;
      color: inherit;
    }

    ul {
      list-style: none;
    }

    img {
      display: block;
      max-width: 100%;
    }

    button {
      cursor: pointer;
      border: none;
      background: none;
      font-family: inherit;
    }

    /* ===========================
       TOP BAR
    =========================== */
    .top-bar {
      background: #1a1a1a;
      color: #ccc;
      font-size: 13px;
      padding: 6px 0;
    }

    .top-bar-inner {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .top-bar-contact {
      display: flex;
      gap: 24px;
      align-items: center;
    }

    .top-bar-contact span {
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .top-bar-contact .icon {
      width: 14px;
      height: 14px;
      fill: #e84c3d;
      display: inline-block;
    }

    .top-bar-social {
      display: flex;
      gap: 12px;
      align-items: center;
    }

    .top-bar-social a {
      color: #aaa;
      font-size: 14px;
      transition: color 0.2s;
    }

    .top-bar-social a:hover {
      color: #fff;
    }

    /* ===========================
       HEADER / NAV
    =========================== */
    .site-header {
      position: sticky;
      top: 0;
      z-index: 1000;
      background: #fff;
      box-shadow: 0 2px 8px rgba(0,0,0,0.12);
    }

    .header-inner {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      height: 72px;
    }

    /* Logo */
    .logo {
      display: flex;
      align-items: center;
      gap: 12px;
      font-size: 22px;
      font-weight: 800;
      letter-spacing: -0.5px;
      color: #1a1a1a;
    }

    .logo-mark {
      width: 48px;
      height: 48px;
      background: #e84c3d;
      border-radius: 4px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .logo-mark svg {
      width: 28px;
      height: 28px;
      fill: #fff;
    }

    .logo-text {
      line-height: 1.1;
    }

    .logo-text span {
      display: block;
      font-size: 10px;
      font-weight: 400;
      color: #777;
      letter-spacing: 2px;
      text-transform: uppercase;
    }

    /* Main Nav */
    .main-nav {
      display: flex;
      align-items: center;
      height: 100%;
    }

    .main-nav > ul {
      display: flex;
      height: 100%;
    }

    .main-nav > ul > li {
      position: relative;
      height: 100%;
      display: flex;
      align-items: center;
    }

    .main-nav > ul > li > a {
      padding: 0 16px;
      font-size: 14px;
      font-weight: 600;
      color: #222;
      height: 100%;
      display: flex;
      align-items: center;
      border-bottom: 3px solid transparent;
      transition: color 0.2s, border-color 0.2s;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .main-nav > ul > li > a:hover,
    .main-nav > ul > li:hover > a {
      color: #e84c3d;
      border-bottom-color: #e84c3d;
    }

    /* Dropdown */
    .dropdown {
      position: absolute;
      top: 100%;
      left: 0;
      background: #fff;
      min-width: 220px;
      box-shadow: 0 6px 24px rgba(0,0,0,0.15);
      border-top: 3px solid #e84c3d;
      opacity: 0;
      visibility: hidden;
      transform: translateY(-6px);
      transition: opacity 0.2s, transform 0.2s, visibility 0.2s;
      z-index: 999;
    }

    .main-nav > ul > li:hover .dropdown {
      opacity: 1;
      visibility: visible;
      transform: translateY(0);
    }

    .dropdown li a {
      display: block;
      padding: 10px 18px;
      font-size: 13px;
      color: #333;
      border-bottom: 1px solid #f0f0f0;
      transition: background 0.15s, color 0.15s;
    }

    .dropdown li:last-child a {
      border-bottom: none;
    }

    .dropdown li a:hover {
      background: #f9f9f9;
      color: #e84c3d;
      padding-left: 24px;
    }

    /* Mega dropdown */
    .mega-dropdown {
      left: 0;
      min-width: 640px;
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 0;
    }

    .mega-col {
      padding: 20px 18px;
      border-right: 1px solid #f0f0f0;
    }

    .mega-col:last-child {
      border-right: none;
    }

    .mega-col-title {
      font-size: 11px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 1px;
      color: #e84c3d;
      margin-bottom: 10px;
      padding-bottom: 6px;
      border-bottom: 1px solid #eee;
    }

    .mega-col ul li a {
      display: block;
      padding: 6px 0;
      font-size: 13px;
      color: #333;
      border-bottom: none;
      transition: color 0.15s, padding-left 0.15s;
    }

    .mega-col ul li a:hover {
      color: #e84c3d;
      background: none;
      padding-left: 6px;
    }

    /* Search */
    .nav-search {
      margin-left: 16px;
      display: flex;
      align-items: center;
    }

    .nav-search button {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      background: #f0f0f0;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: background 0.2s;
    }

    .nav-search button:hover {
      background: #e84c3d;
    }

    .nav-search button:hover svg {
      stroke: #fff;
    }

    .nav-search svg {
      width: 16px;
      height: 16px;
      stroke: #555;
      fill: none;
      stroke-width: 2;
    }

    /* ===========================
       HERO SLIDER
    =========================== */
    .hero-slider {
      position: relative;
      width: 100%;
      height: 580px;
      overflow: hidden;
    }

    .slide {
      position: absolute;
      inset: 0;
      display: flex;
      align-items: center;
      opacity: 0;
      transition: opacity 0.8s ease;
    }

    .slide.active {
      opacity: 1;
      z-index: 2;
    }

    .slide-bg {
      position: absolute;
      inset: 0;
      background-size: cover;
      background-position: center;
    }

    .slide-1 .slide-bg {
      background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
    }

    .slide-2 .slide-bg {
      background: linear-gradient(135deg, #2c2c2c 0%, #1a1a1a 50%, #0a0a0a 100%);
    }

    .slide-3 .slide-bg {
      background: linear-gradient(135deg, #1e3a2f 0%, #0d2818 50%, #051a0e 100%);
    }

    .slide-4 .slide-bg {
      background: linear-gradient(135deg, #2a1a0e 0%, #1a0e08 50%, #0f0804 100%);
    }

    .slide-overlay {
      position: absolute;
      inset: 0;
      background: rgba(0,0,0,0.45);
    }

    .slide-content {
      position: relative;
      z-index: 3;
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 20px;
      width: 100%;
    }

    .slide-eyebrow {
      font-size: 12px;
      font-weight: 700;
      letter-spacing: 3px;
      text-transform: uppercase;
      color: #e84c3d;
      margin-bottom: 14px;
    }

    .slide-title {
      font-size: 52px;
      font-weight: 800;
      color: #fff;
      line-height: 1.1;
      max-width: 600px;
      margin-bottom: 20px;
    }

    .slide-desc {
      font-size: 17px;
      color: rgba(255,255,255,0.8);
      max-width: 500px;
      margin-bottom: 32px;
      line-height: 1.6;
    }

    .slide-cta {
      display: inline-block;
      background: #e84c3d;
      color: #fff;
      font-size: 14px;
      font-weight: 700;
      letter-spacing: 1px;
      text-transform: uppercase;
      padding: 14px 32px;
      border-radius: 2px;
      transition: background 0.2s, transform 0.2s;
    }

    .slide-cta:hover {
      background: #c0392b;
      transform: translateY(-2px);
    }

    /* Slider Controls */
    .slider-arrow {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      z-index: 10;
      width: 48px;
      height: 48px;
      background: rgba(255,255,255,0.15);
      border: 2px solid rgba(255,255,255,0.3);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: background 0.2s;
    }

    .slider-arrow:hover {
      background: #e84c3d;
      border-color: #e84c3d;
    }

    .slider-arrow svg {
      width: 18px;
      height: 18px;
      stroke: #fff;
      fill: none;
      stroke-width: 2.5;
    }

    .slider-prev {
      left: 24px;
    }

    .slider-next {
      right: 24px;
    }

    .slider-dots {
      position: absolute;
      bottom: 24px;
      left: 50%;
      transform: translateX(-50%);
      z-index: 10;
      display: flex;
      gap: 8px;
    }

    .dot {
      width: 10px;
      height: 10px;
      border-radius: 50%;
      background: rgba(255,255,255,0.4);
      cursor: pointer;
      transition: background 0.2s, transform 0.2s;
    }

    .dot.active {
      background: #e84c3d;
      transform: scale(1.3);
    }

    /* Hero product image placeholder */
    .slide-product {
      position: absolute;
      right: 80px;
      top: 50%;
      transform: translateY(-50%);
      width: 320px;
      height: 360px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .product-placeholder {
      width: 220px;
      height: 280px;
      border-radius: 8px;
      background: rgba(255,255,255,0.08);
      border: 1px solid rgba(255,255,255,0.15);
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 12px;
    }

    .product-placeholder svg {
      width: 64px;
      height: 64px;
      stroke: rgba(255,255,255,0.3);
      fill: none;
      stroke-width: 1;
    }

    .product-placeholder span {
      font-size: 12px;
      color: rgba(255,255,255,0.3);
      letter-spacing: 1px;
      text-transform: uppercase;
    }

    /* ===========================
       CATEGORY GRID
    =========================== */
    .category-section {
      background: #f5f5f5;
      padding: 60px 0;
    }

    .section-header {
      max-width: 1200px;
      margin: 0 auto 36px;
      padding: 0 20px;
    }

    .section-title {
      font-size: 30px;
      font-weight: 800;
      color: #1a1a1a;
      margin-bottom: 8px;
    }

    .section-subtitle {
      font-size: 15px;
      color: #777;
    }

    .section-title-line {
      display: flex;
      align-items: center;
      gap: 14px;
    }

    .title-accent {
      width: 48px;
      height: 4px;
      background: #e84c3d;
      border-radius: 2px;
    }

    .category-grid {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 20px;
      display: grid;
      grid-template-columns: repeat(6, 1fr);
      gap: 16px;
    }

    .cat-tile {
      background: #fff;
      border-radius: 4px;
      overflow: hidden;
      box-shadow: 0 2px 8px rgba(0,0,0,0.06);
      transition: box-shadow 0.2s, transform 0.2s;
      cursor: pointer;
    }

    .cat-tile:hover {
      box-shadow: 0 8px 24px rgba(0,0,0,0.12);
      transform: translateY(-3px);
    }

    .cat-img {
      height: 120px;
      background: #eee;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
      position: relative;
    }

    .cat-img svg {
      width: 56px;
      height: 56px;
      stroke: #bbb;
      fill: none;
      stroke-width: 1.2;
    }

    .cat-img-1  { background: #e8f4fd; }
    .cat-img-2  { background: #fef0e8; }
    .cat-img-3  { background: #e8fdf0; }
    .cat-img-4  { background: #f8e8fd; }
    .cat-img-5  { background: #fdfde8; }
    .cat-img-6  { background: #e8eafd; }
    .cat-img-7  { background: #fde8e8; }
    .cat-img-8  { background: #e8fdf8; }
    .cat-img-9  { background: #f0fde8; }
    .cat-img-10 { background: #fde8f5; }
    .cat-img-11 { background: #fdf5e8; }
    .cat-img-12 { background: #e8f0fd; }

    .cat-label {
      padding: 12px 10px;
      text-align: center;
      font-size: 12px;
      font-weight: 600;
      color: #333;
      line-height: 1.3;
      text-transform: uppercase;
      letter-spacing: 0.3px;
    }

    /* ===========================
       CERTIFICATIONS
    =========================== */
    .cert-section {
      background: #fff;
      padding: 56px 0;
      border-top: 1px solid #eee;
      border-bottom: 1px solid #eee;
    }

    .cert-inner {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 20px;
    }

    .cert-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 32px;
      align-items: center;
    }

    .cert-badge {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 16px;
      padding: 32px 24px;
      border: 1px solid #e8e8e8;
      border-radius: 6px;
      text-align: center;
      transition: border-color 0.2s, box-shadow 0.2s;
    }

    .cert-badge:hover {
      border-color: #e84c3d;
      box-shadow: 0 4px 16px rgba(232,76,61,0.08);
    }

    .cert-icon {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      background: #f5f5f5;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .cert-icon svg {
      width: 40px;
      height: 40px;
      stroke: #e84c3d;
      fill: none;
      stroke-width: 1.5;
    }

    .cert-name {
      font-size: 15px;
      font-weight: 700;
      color: #1a1a1a;
    }

    .cert-desc {
      font-size: 13px;
      color: #777;
    }

    .cert-download {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      font-size: 12px;
      font-weight: 600;
      color: #e84c3d;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      border-bottom: 1px solid #e84c3d;
      padding-bottom: 1px;
      transition: color 0.2s;
    }

    .cert-download:hover {
      color: #c0392b;
    }

    .cert-download svg {
      width: 14px;
      height: 14px;
      stroke: currentColor;
      fill: none;
      stroke-width: 2;
    }

    /* ===========================
       ABOUT PILLARS
    =========================== */
    .about-section {
      background: #1a1a1a;
      padding: 80px 0;
    }

    .about-inner {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 20px;
    }

    .about-top {
      text-align: center;
      margin-bottom: 56px;
    }

    .about-tag {
      display: inline-block;
      font-size: 11px;
      font-weight: 700;
      letter-spacing: 3px;
      text-transform: uppercase;
      color: #e84c3d;
      margin-bottom: 16px;
    }

    .about-title {
      font-size: 36px;
      font-weight: 800;
      color: #fff;
      margin-bottom: 16px;
    }

    .about-sub {
      font-size: 16px;
      color: #888;
      max-width: 560px;
      margin: 0 auto;
    }

    .about-highlight {
      color: #e84c3d;
      font-weight: 700;
    }

    .pillars-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 32px;
    }

    .pillar {
      background: #242424;
      border-radius: 6px;
      padding: 40px 32px;
      text-align: center;
      border: 1px solid #333;
      transition: border-color 0.2s, transform 0.2s;
    }

    .pillar:hover {
      border-color: #e84c3d;
      transform: translateY(-4px);
    }

    .pillar-icon {
      width: 72px;
      height: 72px;
      border-radius: 50%;
      background: rgba(232,76,61,0.12);
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 24px;
    }

    .pillar-icon svg {
      width: 36px;
      height: 36px;
      stroke: #e84c3d;
      fill: none;
      stroke-width: 1.5;
    }

    .pillar-title {
      font-size: 20px;
      font-weight: 800;
      color: #fff;
      margin-bottom: 12px;
    }

    .pillar-text {
      font-size: 14px;
      color: #888;
      line-height: 1.7;
    }

    /* ===========================
       STATS BAR
    =========================== */
    .stats-bar {
      background: #e84c3d;
      padding: 36px 0;
    }

    .stats-inner {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 20px;
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 0;
    }

    .stat-item {
      text-align: center;
      padding: 0 20px;
      border-right: 1px solid rgba(255,255,255,0.25);
    }

    .stat-item:last-child {
      border-right: none;
    }

    .stat-number {
      font-size: 40px;
      font-weight: 900;
      color: #fff;
      line-height: 1;
      margin-bottom: 6px;
    }

    .stat-label {
      font-size: 13px;
      color: rgba(255,255,255,0.75);
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    /* ===========================
       TEXT / SEO SECTION
    =========================== */
    .text-section {
      background: #fff;
      padding: 72px 0;
    }

    .text-inner {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 20px;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 64px;
      align-items: start;
    }

    .text-block h2 {
      font-size: 28px;
      font-weight: 800;
      color: #1a1a1a;
      margin-bottom: 20px;
      line-height: 1.2;
    }

    .text-block h2 em {
      color: #e84c3d;
      font-style: normal;
    }

    .text-block p {
      font-size: 15px;
      color: #555;
      line-height: 1.8;
      margin-bottom: 16px;
    }

    .text-block p:last-child {
      margin-bottom: 0;
    }

    .text-block ul {
      list-style: none;
      margin-top: 16px;
    }

    .text-block ul li {
      font-size: 14px;
      color: #555;
      padding: 6px 0 6px 20px;
      position: relative;
      border-bottom: 1px solid #f0f0f0;
    }

    .text-block ul li::before {
      content: '';
      position: absolute;
      left: 0;
      top: 50%;
      transform: translateY(-50%);
      width: 6px;
      height: 6px;
      border-radius: 50%;
      background: #e84c3d;
    }

    .text-block-link {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      margin-top: 24px;
      font-size: 13px;
      font-weight: 700;
      color: #e84c3d;
      text-transform: uppercase;
      letter-spacing: 1px;
      transition: gap 0.2s;
    }

    .text-block-link:hover {
      gap: 12px;
    }

    .text-block-link svg {
      width: 16px;
      height: 16px;
      stroke: currentColor;
      fill: none;
      stroke-width: 2.5;
    }

    /* ===========================
       NEWSLETTER
    =========================== */
    .newsletter-section {
      background: #f5f5f5;
      padding: 60px 0;
    }

    .newsletter-inner {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 40px;
    }

    .newsletter-text h3 {
      font-size: 24px;
      font-weight: 800;
      color: #1a1a1a;
      margin-bottom: 8px;
    }

    .newsletter-text p {
      font-size: 14px;
      color: #777;
    }

    .newsletter-form {
      display: flex;
      gap: 0;
      flex: 0 0 auto;
    }

    .newsletter-form input {
      padding: 14px 20px;
      font-size: 14px;
      border: 2px solid #ddd;
      border-right: none;
      border-radius: 2px 0 0 2px;
      width: 300px;
      outline: none;
      transition: border-color 0.2s;
      font-family: inherit;
    }

    .newsletter-form input:focus {
      border-color: #e84c3d;
    }

    .newsletter-form button {
      padding: 14px 28px;
      background: #e84c3d;
      color: #fff;
      font-size: 13px;
      font-weight: 700;
      letter-spacing: 1px;
      text-transform: uppercase;
      border-radius: 0 2px 2px 0;
      transition: background 0.2s;
      font-family: inherit;
    }

    .newsletter-form button:hover {
      background: #c0392b;
    }

    /* ===========================
       FOOTER
    =========================== */
    .site-footer {
      background: #111;
      color: #999;
      padding: 64px 0 0;
    }

    .footer-inner {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 20px 48px;
      display: grid;
      grid-template-columns: 2fr 1fr 1fr 1fr;
      gap: 48px;
    }

    .footer-brand .logo {
      color: #fff;
      margin-bottom: 20px;
    }

    .footer-brand p {
      font-size: 14px;
      line-height: 1.7;
      margin-bottom: 24px;
    }

    .footer-social {
      display: flex;
      gap: 12px;
    }

    .social-btn {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      background: #222;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: background 0.2s;
    }

    .social-btn:hover {
      background: #e84c3d;
    }

    .social-btn svg {
      width: 16px;
      height: 16px;
      fill: #aaa;
      transition: fill 0.2s;
    }

    .social-btn:hover svg {
      fill: #fff;
    }

    .footer-col h4 {
      font-size: 13px;
      font-weight: 700;
      color: #fff;
      text-transform: uppercase;
      letter-spacing: 1.5px;
      margin-bottom: 20px;
      padding-bottom: 12px;
      border-bottom: 1px solid #222;
    }

    .footer-col ul li {
      margin-bottom: 8px;
    }

    .footer-col ul li a {
      font-size: 14px;
      color: #888;
      transition: color 0.2s;
    }

    .footer-col ul li a:hover {
      color: #e84c3d;
    }

    .footer-contact-item {
      display: flex;
      align-items: flex-start;
      gap: 10px;
      margin-bottom: 12px;
      font-size: 14px;
      color: #888;
    }

    .footer-contact-item svg {
      width: 16px;
      height: 16px;
      stroke: #e84c3d;
      fill: none;
      stroke-width: 2;
      flex-shrink: 0;
      margin-top: 2px;
    }

    .footer-bottom {
      border-top: 1px solid #1e1e1e;
      padding: 20px;
      max-width: 1200px;
      margin: 0 auto;
      display: flex;
      align-items: center;
      justify-content: space-between;
      font-size: 13px;
    }

    .footer-bottom-bar {
      background: #0d0d0d;
    }

    .footer-bottom-links {
      display: flex;
      gap: 20px;
    }

    .footer-bottom-links a {
      color: #666;
      transition: color 0.2s;
    }

    .footer-bottom-links a:hover {
      color: #e84c3d;
    }

    /* ===========================
       HOLIDAY NOTICE MODAL
    =========================== */
    .modal-overlay {
      position: fixed;
      inset: 0;
      background: rgba(0,0,0,0.6);
      z-index: 9999;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    .modal-overlay.hidden {
      display: none;
    }

    .modal-box {
      background: #fff;
      border-radius: 6px;
      max-width: 480px;
      width: 100%;
      padding: 40px 36px;
      position: relative;
      box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    }

    .modal-close {
      position: absolute;
      top: 16px;
      right: 16px;
      width: 32px;
      height: 32px;
      background: #f0f0f0;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: background 0.2s;
    }

    .modal-close:hover {
      background: #e84c3d;
    }

    .modal-close:hover svg {
      stroke: #fff;
    }

    .modal-close svg {
      width: 16px;
      height: 16px;
      stroke: #555;
      fill: none;
      stroke-width: 2.5;
    }

    .modal-icon {
      width: 64px;
      height: 64px;
      border-radius: 50%;
      background: #fef2f0;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 20px;
    }

    .modal-icon svg {
      width: 32px;
      height: 32px;
      stroke: #e84c3d;
      fill: none;
      stroke-width: 1.8;
    }

    .modal-title {
      font-size: 20px;
      font-weight: 800;
      color: #1a1a1a;
      margin-bottom: 12px;
    }

    .modal-text {
      font-size: 14px;
      color: #555;
      line-height: 1.7;
      margin-bottom: 8px;
    }

    .modal-dates {
      background: #f8f8f8;
      border-left: 3px solid #e84c3d;
      padding: 12px 16px;
      margin: 16px 0 24px;
      font-size: 14px;
      color: #333;
      font-weight: 600;
    }

    .modal-btn {
      width: 100%;
      padding: 14px;
      background: #e84c3d;
      color: #fff;
      font-size: 14px;
      font-weight: 700;
      letter-spacing: 1px;
      text-transform: uppercase;
      border-radius: 2px;
      cursor: pointer;
      font-family: inherit;
      border: none;
      transition: background 0.2s;
    }

    .modal-btn:hover {
      background: #c0392b;
    }

    /* ===========================
       PRODUCTS FEATURE STRIP
    =========================== */
    .feature-strip {
      background: #fff;
      border-top: 1px solid #eee;
      border-bottom: 1px solid #eee;
      padding: 32px 0;
    }

    .feature-strip-inner {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 20px;
      display: flex;
      gap: 0;
    }

    .feature-item {
      flex: 1;
      display: flex;
      align-items: center;
      gap: 14px;
      padding: 0 24px;
      border-right: 1px solid #eee;
    }

    .feature-item:first-child {
      padding-left: 0;
    }

    .feature-item:last-child {
      border-right: none;
    }

    .feature-item svg {
      width: 32px;
      height: 32px;
      stroke: #e84c3d;
      fill: none;
      stroke-width: 1.5;
      flex-shrink: 0;
    }

    .feature-item-text strong {
      display: block;
      font-size: 14px;
      font-weight: 700;
      color: #1a1a1a;
    }

    .feature-item-text span {
      font-size: 12px;
      color: #888;
    }

    /* ===========================
       SCROLLBAR
    =========================== */
    ::-webkit-scrollbar {
      width: 8px;
    }

    ::-webkit-scrollbar-track {
      background: #f0f0f0;
    }

    ::-webkit-scrollbar-thumb {
      background: #ccc;
      border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
      background: #e84c3d;
    }

    /* ===========================
       RESPONSIVE (basic)
    =========================== */
    @media (max-width: 1024px) {
      .category-grid {
        grid-template-columns: repeat(4, 1fr);
      }

      .footer-inner {
        grid-template-columns: 1fr 1fr;
        gap: 32px;
      }

      .stats-inner {
        grid-template-columns: repeat(2, 1fr);
        gap: 24px;
      }

      .stat-item {
        border-right: none;
        border-bottom: 1px solid rgba(255,255,255,0.2);
        padding-bottom: 24px;
      }

      .stat-item:nth-child(odd) {
        border-right: 1px solid rgba(255,255,255,0.2);
      }
    }

    @media (max-width: 768px) {
      .top-bar-contact {
        flex-direction: column;
        align-items: flex-start;
        gap: 4px;
      }

      .main-nav {
        display: none;
      }

      .hero-slider {
        height: 420px;
      }

      .slide-title {
        font-size: 32px;
      }

      .slide-product {
        display: none;
      }

      .category-grid {
        grid-template-columns: repeat(3, 1fr);
      }

      .cert-grid {
        grid-template-columns: 1fr;
      }

      .pillars-grid {
        grid-template-columns: 1fr;
      }

      .text-inner {
        grid-template-columns: 1fr;
        gap: 40px;
      }

      .newsletter-inner {
        flex-direction: column;
        text-align: center;
      }

      .newsletter-form {
        flex-direction: column;
        width: 100%;
      }

      .newsletter-form input {
        width: 100%;
        border-right: 2px solid #ddd;
        border-bottom: none;
        border-radius: 2px 2px 0 0;
      }

      .newsletter-form button {
        border-radius: 0 0 2px 2px;
      }

      .footer-inner {
        grid-template-columns: 1fr;
      }

      .feature-strip-inner {
        flex-direction: column;
        gap: 20px;
      }

      .feature-item {
        border-right: none;
        border-bottom: 1px solid #eee;
        padding: 0 0 20px;
      }

      .feature-item:last-child {
        border-bottom: none;
      }
    }
  </style>
</head>
<body>

  <!-- ========================
       HOLIDAY MODAL
  ========================= -->
  <div class="modal-overlay" id="holidayModal">
    <div class="modal-box">
      <button class="modal-close" onclick="closeModal()">
        <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
      <div class="modal-icon">
        <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
      </div>
      <div class="modal-title">Informacja o przerwach w pracy</div>
      <p class="modal-text">Uprzejmie informujemy, że nasze biuro będzie nieczynne w następujących terminach:</p>
      <div class="modal-dates">
        📅 05.06.2026<br>
        📅 11–12.06.2026
      </div>
      <p class="modal-text">Zamówienia złożone w tych dniach zostaną zrealizowane w kolejnym dniu roboczym. Przepraszamy za niedogodności.</p>
      <button class="modal-btn" onclick="closeModal()">ZAMKNIJ</button>
    </div>
  </div>

  <!-- ========================
       TOP BAR
  ========================= -->
  <div class="top-bar">
    <div class="top-bar-inner">
      <div class="top-bar-contact">
        <span>
          <svg class="icon" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
          ul. Białostocka 7, Karpin, 05-252 Dąbrówka
        </span>
        <span>
          <svg class="icon" viewBox="0 0 24 24"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
          +48 29 741 01 91
        </span>
      </div>
      <div class="top-bar-social">
        <a href="#" title="YouTube">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-2.75 12.64 12.64 0 0 0-7.64 0 4.83 4.83 0 0 1-3.77 2.75A12.8 12.8 0 0 0 2 12a12.8 12.8 0 0 0 2.41 5.31 4.83 4.83 0 0 1 3.77 2.75 12.64 12.64 0 0 0 7.64 0 4.83 4.83 0 0 1 3.77-2.75A12.8 12.8 0 0 0 22 12a12.8 12.8 0 0 0-2.41-5.31zM10 15.5v-7l6 3.5z"/></svg>
        </a>
        <a href="#" title="Facebook">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
        </a>
        <a href="#" title="Instagram">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor"/></svg>
        </a>
      </div>
    </div>
  </div>

  <!-- ========================
       HEADER
  ========================= -->
  <header class="site-header">
    <div class="header-inner">
      <!-- Logo -->
      <a href="#" class="logo">
        <div class="logo-mark">
          <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        </div>
        <div class="logo-text">
          McAlpine
          <span>Polska</span>
        </div>
      </a>

      <!-- Nav -->
      <nav class="main-nav">
        <ul>
          <li>
            <a href="#">Produkty ▾</a>
            <div class="dropdown mega-dropdown">
              <div class="mega-col">
                <div class="mega-col-title">Syfony umywalkowe</div>
                <ul>
                  <li><a href="#">Chrom</a></li>
                  <li><a href="#">Rurowe</a></li>
                  <li><a href="#">Niskie</a></li>
                  <li><a href="#">Podtynkowe</a></li>
                  <li><a href="#">Butelkowe</a></li>
                  <li><a href="#">Spusty</a></li>
                  <li><a href="#">Akcesoria</a></li>
                </ul>
              </div>
              <div class="mega-col">
                <div class="mega-col-title">Syfony zlewozmywakowe</div>
                <ul>
                  <li><a href="#">Podwójne</a></li>
                  <li><a href="#">Rurowe</a></li>
                  <li><a href="#">Butelkowe</a></li>
                  <li><a href="#">Spusty</a></li>
                  <li><a href="#">Akcesoria</a></li>
                </ul>
                <div class="mega-col-title" style="margin-top:16px;">Syfony brodzikowe</div>
                <ul>
                  <li><a href="#">Górnoczyszczące</a></li>
                  <li><a href="#">Niskie</a></li>
                  <li><a href="#">Akcesoria</a></li>
                </ul>
              </div>
              <div class="mega-col">
                <div class="mega-col-title">Syfony wannowe</div>
                <ul>
                  <li><a href="#">Automatyczne</a></li>
                  <li><a href="#">Zewnętrzne</a></li>
                  <li><a href="#">Akcesoria</a></li>
                </ul>
                <div class="mega-col-title" style="margin-top:16px;">Inne</div>
                <ul>
                  <li><a href="#">Odpływy podłogowe</a></li>
                  <li><a href="#">Zawory napowietrzające</a></li>
                  <li><a href="#">Ochrona BHP</a></li>
                </ul>
              </div>
            </div>
          </li>
          <li><a href="#">O McAlpine</a></li>
          <li><a href="#">Poradniki</a></li>
          <li><a href="#">Nowości</a></li>
          <li><a href="#">Kontakt</a></li>
        </ul>
        <div class="nav-search">
          <button title="Szukaj">
            <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          </button>
        </div>
      </nav>
    </div>
  </header>

  <!-- ========================
       HERO SLIDER
  ========================= -->
  <section class="hero-slider" id="heroSlider">
    <!-- Slide 1 -->
    <div class="slide slide-1 active">
      <div class="slide-bg"></div>
      <div class="slide-overlay"></div>
      <div class="slide-content">
        <div class="slide-eyebrow">Nowość w ofercie</div>
        <h1 class="slide-title">Rękawice Super Strength</h1>
        <p class="slide-desc">Profesjonalna ochrona BHP dla instalatorów. Wytrzymałość i komfort w trudnych warunkach pracy.</p>
        <a href="#" class="slide-cta">Zobacz produkt</a>
      </div>
      <div class="slide-product">
        <div class="product-placeholder">
          <svg viewBox="0 0 24 24"><path d="M18 11V6a2 2 0 0 0-2-2 2 2 0 0 0-2 2v0M14 10V4a2 2 0 0 0-2-2 2 2 0 0 0-2 2v2M10 10.5V6a2 2 0 0 0-2-2 2 2 0 0 0-2 2v8M6 14c0 0-1.5 1-1.5 3 0 2.5 2.5 5 7.5 5s7.5-2.5 7.5-5c0-2-1.5-3-1.5-3V11"/></svg>
          <span>Rękawice BHP</span>
        </div>
      </div>
    </div>

    <!-- Slide 2 -->
    <div class="slide slide-2">
      <div class="slide-bg"></div>
      <div class="slide-overlay"></div>
      <div class="slide-content">
        <div class="slide-eyebrow">Bestseller</div>
        <h1 class="slide-title">Odpływ prysznicowy MP2730</h1>
        <p class="slide-desc">Syfon brodzikowy nowej generacji. Prosty montaż, maksymalny przepływ wody i łatwe czyszczenie.</p>
        <a href="#" class="slide-cta">Sprawdź szczegóły</a>
      </div>
      <div class="slide-product">
        <div class="product-placeholder">
          <svg viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          <span>MP2730L3HST</span>
        </div>
      </div>
    </div>

    <!-- Slide 3 -->
    <div class="slide slide-3">
      <div class="slide-bg"></div>
      <div class="slide-overlay"></div>
      <div class="slide-content">
        <div class="slide-eyebrow">Nowa kolekcja</div>
        <h1 class="slide-title">Przyłącze pralki WMVT-1.00</h1>
        <p class="slide-desc">Innowacyjne rozwiązanie do podłączenia pralki. Trwałość i niezawodność w każdych warunkach.</p>
        <a href="#" class="slide-cta">Dowiedz się więcej</a>
      </div>
      <div class="slide-product">
        <div class="product-placeholder">
          <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="3"/></svg>
          <span>WMVT-1.00</span>
        </div>
      </div>
    </div>

    <!-- Slide 4 -->
    <div class="slide slide-4">
      <div class="slide-bg"></div>
      <div class="slide-overlay"></div>
      <div class="slide-content">
        <div class="slide-eyebrow">Innowacja</div>
        <h1 class="slide-title">Perfect Grind SLIM</h1>
        <p class="slide-desc">Zmywarki do odpadów organicznych nowej generacji. Cicha praca, kompaktowe wymiary.</p>
        <a href="#" class="slide-cta">Zobacz ofertę</a>
      </div>
      <div class="slide-product">
        <div class="product-placeholder">
          <svg viewBox="0 0 24 24"><path d="M22 12H2M12 2v20"/></svg>
          <span>Perfect Grind SLIM</span>
        </div>
      </div>
    </div>

    <!-- Arrows -->
    <button class="slider-arrow slider-prev" onclick="changeSlide(-1)" title="Poprzedni">
      <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
    </button>
    <button class="slider-arrow slider-next" onclick="changeSlide(1)" title="Następny">
      <svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
    </button>

    <!-- Dots -->
    <div class="slider-dots">
      <div class="dot active" onclick="goToSlide(0)"></div>
      <div class="dot" onclick="goToSlide(1)"></div>
      <div class="dot" onclick="goToSlide(2)"></div>
      <div class="dot" onclick="goToSlide(3)"></div>
    </div>
  </section>

  <!-- ========================
       FEATURE STRIP
  ========================= -->
  <div class="feature-strip">
    <div class="feature-strip-inner">
      <div class="feature-item">
        <svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        <div class="feature-item-text">
          <strong>Ponad 100 lat doświadczenia</strong>
          <span>Producent z Glasgow, Wielka Brytania</span>
        </div>
      </div>
      <div class="feature-item">
        <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
        <div class="feature-item-text">
          <strong>Certyfikowane produkty</strong>
          <span>Spełniają europejskie normy jakości</span>
        </div>
      </div>
      <div class="feature-item">
        <svg viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
        <div class="feature-item-text">
          <strong>Szybka dostawa do Polski</strong>
          <span>Magazyn: Karpin, 05-252 Dąbrówka</span>
        </div>
      </div>
      <div class="feature-item">
        <svg viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.15 13 19.79 19.79 0 0 1 1.08 4.38 2 2 0 0 1 3.05 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
        <div class="feature-item-text">
          <strong>Wsparcie techniczne</strong>
          <span>+48 29 741 01 91</span>
        </div>
      </div>
    </div>
  </div>

  <!-- ========================
       CATEGORY GRID
  ========================= -->
  <section class="category-section">
    <div class="section-header">
      <div class="section-title-line">
        <div class="title-accent"></div>
        <h2 class="section-title">Kategorie produktów</h2>
      </div>
      <p class="section-subtitle">Szeroki wybór armatury sanitarnej i instalacyjnej</p>
    </div>

    <div class="category-grid">
      <a href="#" class="cat-tile">
        <div class="cat-img cat-img-1">
          <svg viewBox="0 0 24 24"><path d="M5 3v18M19 3v18M5 12h14M3 3h18M3 21h18"/></svg>
        </div>
        <div class="cat-label">Syfony umywalkowe</div>
      </a>
      <a href="#" class="cat-tile">
        <div class="cat-img cat-img-2">
          <svg viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
        </div>
        <div class="cat-label">Syfony zlewozmywakowe</div>
      </a>
      <a href="#" class="cat-tile">
        <div class="cat-img cat-img-3">
          <svg viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="15" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg>
        </div>
        <div class="cat-label">Syfony wannowe</div>
      </a>
      <a href="#" class="cat-tile">
        <div class="cat-img cat-img-4">
          <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M8 12h8M12 8v8"/></svg>
        </div>
        <div class="cat-label">Syfony brodzikowe</div>
      </a>
      <a href="#" class="cat-tile">
        <div class="cat-img cat-img-5">
          <svg viewBox="0 0 24 24"><path d="M6 3v7a6 6 0 0 0 6 6 6 6 0 0 0 6-6V3"/><line x1="4" y1="3" x2="20" y2="3"/></svg>
        </div>
        <div class="cat-label">Przyłącza kanalizacyjne</div>
      </a>
      <a href="#" class="cat-tile">
        <div class="cat-img cat-img-6">
          <svg viewBox="0 0 24 24"><path d="M14.5 10c-.83 0-1.5-.67-1.5-1.5v-5c0-.83.67-1.5 1.5-1.5s1.5.67 1.5 1.5v5c0 .83-.67 1.5-1.5 1.5z"/><path d="M20.5 10H19V8.5c0-.83.67-1.5 1.5-1.5s1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/><path d="M9.5 14c.83 0 1.5.67 1.5 1.5v5c0 .83-.67 1.5-1.5 1.5S8 21.33 8 20.5v-5c0-.83.67-1.5 1.5-1.5z"/><path d="M3.5 14H5v1.5c0 .83-.67 1.5-1.5 1.5S2 16.33 2 15.5 2.67 14 3.5 14z"/><path d="M14 14.5c0-.83.67-1.5 1.5-1.5h5c.83 0 1.5.67 1.5 1.5s-.67 1.5-1.5 1.5h-5c-.83 0-1.5-.67-1.5-1.5z"/><path d="M15.5 19H14v1.5c0 .83.67 1.5 1.5 1.5s1.5-.67 1.5-1.5-.67-1.5-1.5-1.5z"/><path d="M10 9.5C10 8.67 9.33 8 8.5 8h-5C2.67 8 2 8.67 2 9.5S2.67 11 3.5 11h5c.83 0 1.5-.67 1.5-1.5z"/><path d="M8.5 5H10V3.5C10 2.67 9.33 2 8.5 2S7 2.67 7 3.5 7.67 5 8.5 5z"/></svg>
        </div>
        <div class="cat-label">Przyłącza montażowe</div>
      </a>
      <a href="#" class="cat-tile">
        <div class="cat-img cat-img-7">
          <svg viewBox="0 0 24 24"><path d="M12 22V12M12 12C12 12 8 10 8 6a4 4 0 0 1 8 0c0 4-4 6-4 6z"/><path d="M2 22h20"/></svg>
        </div>
        <div class="cat-label">Odpływy pralkowe</div>
      </a>
      <a href="#" class="cat-tile">
        <div class="cat-img cat-img-8">
          <svg viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
        </div>
        <div class="cat-label">Dozowniki płynów</div>
      </a>
      <a href="#" class="cat-tile">
        <div class="cat-img cat-img-9">
          <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        </div>
        <div class="cat-label">Odpływy bidetowe</div>
      </a>
      <a href="#" class="cat-tile">
        <div class="cat-img cat-img-10">
          <svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M3 15h18M9 3v18M15 3v18"/></svg>
        </div>
        <div class="cat-label">Odpływy podłogowe</div>
      </a>
      <a href="#" class="cat-tile">
        <div class="cat-img cat-img-11">
          <svg viewBox="0 0 24 24"><path d="M12 2v6M12 22v-6M4.93 4.93l4.24 4.24M14.83 14.83l4.24 4.24M2 12h6M22 12h-6M4.93 19.07l4.24-4.24M14.83 9.17l4.24-4.24"/></svg>
        </div>
        <div class="cat-label">Złączki montażowe</div>
      </a>
      <a href="#" class="cat-tile">
        <div class="cat-img cat-img-12">
          <svg viewBox="0 0 24 24"><path d="M18 8h1a4 4 0 0 1 0 8h-1M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8zM6 1v3M10 1v3M14 1v3"/></svg>
        </div>
        <div class="cat-label">Zawory napowietrzające</div>
      </a>
    </div>
  </section>

  <!-- ========================
       CERTIFICATIONS
  ========================= -->
  <section class="cert-section">
    <div class="cert-inner">
      <div class="section-header" style="margin-bottom:36px; padding:0;">
        <div class="section-title-line">
          <div class="title-accent"></div>
          <h2 class="section-title">Certyfikaty i atesty</h2>
        </div>
        <p class="section-subtitle">Nasze produkty spełniają najwyższe standardy jakości</p>
      </div>
      <div class="cert-grid">
        <div class="cert-badge">
          <div class="cert-icon">
            <svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg>
          </div>
          <div class="cert-name">Atest Higieniczny PZH</div>
          <div class="cert-desc">Certyfikat Państwowego Zakładu Higieny potwierdzający bezpieczeństwo materiałów</div>
          <a href="#" class="cert-download">
            <svg viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
            Pobierz PDF (PL)
          </a>
        </div>
        <div class="cert-badge">
          <div class="cert-icon">
            <svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/></svg>
          </div>
          <div class="cert-name">Certyfikat CE</div>
          <div class="cert-desc">Znak CE potwierdzający zgodność z dyrektywami Unii Europejskiej</div>
          <a href="#" class="cert-download">
            <svg viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
            Pobierz PDF (EN)
          </a>
        </div>
        <div class="cert-badge">
          <div class="cert-icon">
            <svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M9 9h6M9 12h6M9 15h4"/></svg>
          </div>
          <div class="cert-name">Katalog produktów 2024</div>
          <div class="cert-desc">Pełna dokumentacja techniczna i katalog produktów McAlpine na rok 2024</div>
          <a href="#" class="cert-download">
            <svg viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
            Pobierz katalog
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- ========================
       ABOUT / PILLARS
  ========================= -->
  <section class="about-section">
    <div class="about-inner">
      <div class="about-top">
        <div class="about-tag">O marce McAlpine</div>
        <h2 class="about-title">Tradycja, jakość i innowacja</h2>
        <p class="about-sub">Firma McAlpine istnieje od <span class="about-highlight">ponad 100 lat</span>. Nasze produkty są projektowane i wytwarzane w Glasgow, w 7 wyspecjalizowanych zakładach produkcyjnych.</p>
      </div>
      <div class="pillars-grid">
        <div class="pillar">
          <div class="pillar-icon">
            <svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
          </div>
          <div class="pillar-title">Jakość</div>
          <p class="pillar-text">Każdy produkt McAlpine jest wytwarzany z najwyższej jakości materiałów — polipropylenu, ABS, polietylenu. Rygorystyczna kontrola jakości na każdym etapie produkcji.</p>
        </div>
        <div class="pillar">
          <div class="pillar-icon">
            <svg viewBox="0 0 24 24"><path d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 1 1 7.072 0l-.548.547A3.374 3.374 0 0 0 14 18.469V19a2 2 0 1 1-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
          </div>
          <div class="pillar-title">Innowacje</div>
          <p class="pillar-text">Nieustanny rozwój technologiczny pozwala nam produkować ponad 58 kilometrów rur dziennie. Stale inwestujemy w badania i rozwój nowych rozwiązań instalacyjnych.</p>
        </div>
        <div class="pillar">
          <div class="pillar-icon">
            <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2M9 7a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
          </div>
          <div class="pillar-title">Zrozumienie</div>
          <p class="pillar-text">Rozumiemy potrzeby instalatorów i użytkowników końcowych. Nasze produkty są projektowane tak, by montaż był szybki i bezproblemowy, a eksploatacja niezawodna.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- ========================
       STATS BAR
  ========================= -->
  <div class="stats-bar">
    <div class="stats-inner">
      <div class="stat-item">
        <div class="stat-number">100+</div>
        <div class="stat-label">Lat doświadczenia</div>
      </div>
      <div class="stat-item">
        <div class="stat-number">7</div>
        <div class="stat-label">Zakładów produkcyjnych</div>
      </div>
      <div class="stat-item">
        <div class="stat-number">58 km</div>
        <div class="stat-label">Rur produkowanych dziennie</div>
      </div>
      <div class="stat-item">
        <div class="stat-number">1000+</div>
        <div class="stat-label">Produktów w ofercie</div>
      </div>
    </div>
  </div>

  <!-- ========================
       TEXT / SEO SECTION
  ========================= -->
  <section class="text-section">
    <div class="text-inner">
      <div class="text-block">
        <h2>Dlaczego warto wybrać <em>McAlpine</em>?</h2>
        <p>McAlpine to brytyjska marka z ponad stuletnią tradycją produkcji armatury sanitarnej i instalacyjnej. Firma powstała w Glasgow i do dziś prowadzi tam swoją główną działalność produkcyjną, rozłożoną na 7 wyspecjalizowanych zakładach.</p>
        <p>Nasze produkty — syfony, odpływy, przyłącza — są wytwarzane z najwyższej klasy materiałów: polipropylenu, ABS oraz polietylenu. Każdy element przechodzi rygorystyczną kontrolę jakości zgodnie z normami europejskimi i brytyjskimi.</p>
        <ul>
          <li>Produkcja w standardach BS i EN</li>
          <li>Atest Higieniczny PZH dla rynku polskiego</li>
          <li>Dostępność techniczna i wsparcie serwisowe</li>
          <li>Szeroki asortyment dla profesjonalistów</li>
        </ul>
        <a href="#" class="text-block-link">
          Dowiedz się więcej o firmie
          <svg viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </a>
      </div>
      <div class="text-block">
        <h2>Syfony i odpływy dla <em>każdego zastosowania</em></h2>
        <p>Oferujemy pełny asortyment syfonów do umywalek, zlewozmywaków, wanien i brodzików. Każdy model jest dostępny w kilku wariantach — chromowanym, plastikowym, butelkowym lub rurowym — aby sprostać różnym wymaganiom projektowym i instalacyjnym.</p>
        <p>Nasze odpływy podłogowe i prysznicowe wyróżniają się niskim profilem i łatwością montażu. Model MP2730L3HST-NT to jeden z bestsellerów na rynku polskim, ceniony za minimalistyczny design i wysoką wydajność przepływu.</p>
        <p>Dzięki rozbudowanej sieci dystrybutorów w całej Polsce, produkty McAlpine są dostępne szybko i w konkurencyjnych cenach.</p>
        <a href="#" class="text-block-link">
          Przeglądaj produkty
          <svg viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </a>
      </div>
    </div>
  </section>

  <!-- ========================
       NEWSLETTER
  ========================= -->
  <section class="newsletter-section">
    <div class="newsletter-inner">
      <div class="newsletter-text">
        <h3>Bądź na bieżąco z nowościami</h3>
        <p>Zapisz się do newslettera i otrzymuj informacje o nowych produktach, promocjach i poradnikach instalacyjnych.</p>
      </div>
      <form class="newsletter-form" onsubmit="return false;">
        <input type="email" placeholder="Wpisz swój adres e-mail" />
        <button type="submit">Zapisz się</button>
      </form>
    </div>
  </section>

  <!-- ========================
       FOOTER
  ========================= -->
  <footer class="site-footer">
    <div class="footer-inner">
      <!-- Brand -->
      <div class="footer-brand">
        <a href="#" class="logo">
          <div class="logo-mark">
            <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
          </div>
          <div class="logo-text">
            McAlpine
            <span style="color:#555;">Polska</span>
          </div>
        </a>
        <p>McAlpine Polska — autoryzowany dystrybutor brytyjskiej marki McAlpine na rynku polskim. Wysokiej jakości armatura sanitarna i instalacyjna z ponad 100-letnią tradycją.</p>
        <div class="footer-social">
          <a href="#" class="social-btn" title="Facebook">
            <svg viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
          </a>
          <a href="#" class="social-btn" title="Instagram">
            <svg viewBox="0 0 24 24" fill="none" stroke="#aaa" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="#aaa"/></svg>
          </a>
          <a href="#" class="social-btn" title="YouTube">
            <svg viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-2.75 12.64 12.64 0 0 0-7.64 0 4.83 4.83 0 0 1-3.77 2.75A12.8 12.8 0 0 0 2 12a12.8 12.8 0 0 0 2.41 5.31 4.83 4.83 0 0 1 3.77 2.75 12.64 12.64 0 0 0 7.64 0 4.83 4.83 0 0 1 3.77-2.75A12.8 12.8 0 0 0 22 12a12.8 12.8 0 0 0-2.41-5.31zM10 15.5v-7l6 3.5z"/></svg>
          </a>
        </div>
      </div>

      <!-- O nas -->
      <div class="footer-col">
        <h4>O nas</h4>
        <ul>
          <li><a href="#">Historia firmy</a></li>
          <li><a href="#">Rozwiązania instalacyjne</a></li>
          <li><a href="#">Certyfikaty</a></li>
          <li><a href="#">Kariera</a></li>
          <li><a href="#">Kontakt</a></li>
        </ul>
      </div>

      <!-- Pobierz -->
      <div class="footer-col">
        <h4>Pobierz</h4>
        <ul>
          <li><a href="#">Katalog 2024 (PDF)</a></li>
          <li><a href="#">Atesty i certyfikaty</a></li>
          <li><a href="#">Instrukcje montażu</a></li>
          <li><a href="#">Pliki CAD / DXF</a></li>
          <li><a href="#">Karty techniczne</a></li>
        </ul>
      </div>

      <!-- Kontakt -->
      <div class="footer-col">
        <h4>Kontakt</h4>
        <div class="footer-contact-item">
          <svg viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
          <span>ul. Białostocka 7, Karpin<br>05-252 Dąbrówka</span>
        </div>
        <div class="footer-contact-item">
          <svg viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.15 13 19.79 19.79 0 0 1 1.08 4.38 2 2 0 0 1 3.05 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
          <span>+48 29 741 01 91</span>
        </div>
        <div class="footer-contact-item">
          <svg viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
          <span>biuro@mcalpine.pl</span>
        </div>
      </div>
    </div>

    <!-- Bottom bar -->
    <div class="footer-bottom-bar">
      <div class="footer-bottom">
        <span>© 2026 McAlpine Polska. Wszelkie prawa zastrzeżone.</span>
        <div class="footer-bottom-links">
          <a href="#">Polityka prywatności</a>
          <a href="#">Pliki cookies</a>
          <a href="#">Ochrona danych</a>
        </div>
      </div>
    </div>
  </footer>

  <!-- ========================
       JAVASCRIPT
  ========================= -->
  <script>
    // ---- Slider ----
    var currentSlide = 0;
    var slides = document.querySelectorAll('.slide');
    var dots = document.querySelectorAll('.dot');
    var autoplayTimer;

    function showSlide(n) {
      slides[currentSlide].classList.remove('active');
      dots[currentSlide].classList.remove('active');
      currentSlide = (n + slides.length) % slides.length;
      slides[currentSlide].classList.add('active');
      dots[currentSlide].classList.add('active');
    }

    function changeSlide(dir) {
      showSlide(currentSlide + dir);
      resetAutoplay();
    }

    function goToSlide(n) {
      showSlide(n);
      resetAutoplay();
    }

    function resetAutoplay() {
      clearInterval(autoplayTimer);
      autoplayTimer = setInterval(function() {
        showSlide(currentSlide + 1);
      }, 5000);
    }

    autoplayTimer = setInterval(function() {
      showSlide(currentSlide + 1);
    }, 5000);

    // ---- Modal ----
    function closeModal() {
      document.getElementById('holidayModal').classList.add('hidden');
    }

    // Close modal on overlay click
    document.getElementById('holidayModal').addEventListener('click', function(e) {
      if (e.target === this) closeModal();
    });

    // Close modal on Escape
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') closeModal();
    });
  </script>

</body>
</html>
