@extends('layouts.club')
@include('include.club.header')
@section('content')
    <style>
        .bb{
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .main {}
        .button{

            display: inline-block;
            padding: 10px 20px;
            background-color: #cc0000;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }
        .green{
            background-color: green !important;
            margin-left: 10px;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 70vh;
        }

        /* О клубе */
        .about-section-main {
            width: 90%;
            margin-bottom: 24px;
            border-radius: 12px;
            position: relative;
            background: linear-gradient(135deg, #13171d 0%, #1f2937 100%);
            padding: 24px;
            overflow: hidden;
            background-image: url('/new/a-club.svg');
            background-size: cover;
            background-position: center;
            margin: 0 auto;

        }

        .about-section-main::before,
        .about-section-main::after,
        .corner-tr,
        .corner-bl {
            content: "";
            position: absolute;
            width: 22px;
            height: 22px;
            background-color: #cc0000;
            border-radius: 0;
        }

        .corner-tr {
            top: 0;
            right: 0;
            width: 22px;
            height: 22px;
            background-color: #cc0000;
            border-bottom-left-radius: 52px;
        }

        /* Bottom-left: quarter circle curves toward top-right */
        .corner-bl {
            bottom: 0;
            left: 0;
            width: 22px;
            height: 22px;
            background-color: #cc0000;
            border-top-right-radius: 52px;
        }

        /* Top-left: quarter circle curves toward bottom-right */
        .about-section-main::before {
            top: 0;
            left: 0;
            border-bottom-right-radius: 52px;
        }

        /* Bottom-right: quarter circle curves toward top-left */
        .about-section-main::after {
            bottom: 0;
            right: 0;
            border-top-left-radius: 52px;
        }


        .about-section {
            margin-bottom: 24px;
            background: linear-gradient(135deg, #13171d 0%, #1f2937 100%);
            padding: 16px;
            border-radius: 12px;
            border: 1px solid #4b5563;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }



        .about-title {
            font-size: 31px;
            font-weight: 700;

            line-height: 1.2;
            color: var(--primary-color);

        }

        .about-title-white {
            font-size: 30px;
            font-weight: 700;
            line-height: 1.2;
            color: white;
            text-align: center;
            width: 100% !important;
        }

        .title-h3 {
            text-align: center;
            color: white;
            width: 100% !important;
            padding: 5px 0 15px 0;
        }

        .about-text-main {
            color: white;
            line-height: 1.2;
            margin-bottom: 12px;
            font-weight: 300;
            width: 93%;
            height: 240px;
            overflow-y: auto;
            font-size: 13px;
            text-align: center;

            /* Включаем автоматический перенос слов (чтобы не было огромных дыр между словами) */
            -webkit-hyphens: auto;
            -ms-hyphens: auto;
            hyphens: auto;

        }

        .about-text-main h2,
        h1,
        h3 {
            color: var(--primary-color);
            font-weight: 700;
            font-size: 40px;
            line-height: 1.4;
            margin-bottom: 8px;
            text-align: center;
            margin: 1px auto;
            /* width: 80%; */

        }
    </style>
    <div class="main">
        <div class="about-section-main" bis_skin_checked="1">
            <span class="corner-tr"></span>
            <span class="corner-bl"></span>

            <div class="about-text-main" bis_skin_checked="1">
                <h2 style="">Спасибо!</h2>
                <p style="font-size: 20px; font-weight: 600;">Спасибо. Ваш чек отправлен.<br><br> Хотите загрузить чек снова (еще один)?
                    <br><br>Ожидайте подтверждения покупки админом</p>
            </div>
            <div class="bb">
            <a href="/account" class="button">Назад</a>
                    <a href="/account?open=modal" class="button green">Новый чек</a>
        </div>
        </div>
    </div>
    @include('include.club.bottom-nav')
@endsection
