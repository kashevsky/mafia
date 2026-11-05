<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GameRoleMail extends Mailable
{
    use Queueable, SerializesModels;

    // Публичные свойства автоматически попадут в Blade-шаблон
    public $role;
    public $photoUrl;
    public $rulesUrl;
    public $chatUrl;

    /**
     * Создание нового экземпляра письма.
     */
    public function __construct($account)
    {
        $photoMap = [
            'Шериф'      => 'she.png',
            'Адвокат'     => 'adv.png',
            'Бомба'       => 'bom.png',
            'Каратель'    => 'fer.png',
            'Детектив'    => 'det.png',
            'Доктор'      => 'doc.png',
            'Стрелочник'  => 'str.png',
            'Судья'       => 'sud.png',
            'Мафия'       => 'maf.png',
            'Мирный'      => 'mir.png',
            'Журналист'   => 'jur.png',
            'Дон'         => 'don.png',
            'Приспешник'  => 'pri.png',
            'Оборотень'   => 'obo.png',
            'Фредди'      => 'mst.png',
        ];

        // Корректируем названия ролей
        $roleName = $account->role;
        if ($roleName == 'Фредди') { $roleName = 'Мститель'; }
        if ($roleName == 'Каратель') { $roleName = 'Ферзь'; }
        $this->role = $roleName;

        // Формируем ссылки
        $photoName = $photoMap[$account->role] ?? 'mir.png';
        $this->photoUrl = rtrim(env('APP_URL'), '/') . '/rols-b/' . $photoName . '?v=' . time();
        $this->rulesUrl = rtrim(env('APP_URL'), '/') . '?tab=roles';
        $this->chatUrl = 'https://t.me';
    }

    /**
     * Сборка письма для старых версий Laravel.
     */
    public function build()
    {
        return $this->subject('Ваша роль в игре: ' . $this->role)
                    ->view('emails.game_role');
    }
}
