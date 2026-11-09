<?php

namespace App\Console\Commands;

use App\Models\Account;
use App\Models\GameResult;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AddRating extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add-rating';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $arr = Account::get()->pluck('rating', 'id');
        $arr = [

            1 => 94,
            63 => 42,
            64 => 70,
            66 => 5,
            72 => 0,
            73 => 0,
            74 => 0,
            75 => 103,
            76 => 0,
            77 => 0,
            78 => 416,
            79 => 0,
            81 => 39,
            82 => 0,
            83 => 0,
            85 => 0,
            86 => 486,
            87 => 724,
            89 => 0,
            90 => 226,
            91 => 160,
            92 => 0,
            93 => 28,
            94 => 0,
            95 => 0,
            96 => 200,
            97 => 29,
            103 => 0,
            116 => 0,
            117 => 0,
            118 => 387,
            119 => 8,
            120 => 0,
            121 => 173,
            124 => 0,
            125 => 0,
            126 => 0,
            127 => 0,
            128 => 0,
            129 => 0,
            130 => 0,
            131 => 93,
            132 => 0,
            133 => 0,
            134 => 0,
            135 => 0,
            136 => 0,
            138 => 0,
            139 => 0,
            140 => 0,
            141 => 0,
            142 => 0,
            143 => 0,
            144 => 0,
            145 => 0,
            147 => 0,
            148 => 0,
            149 => 0,
            150 => 0,
            151 => 0,
            152 => 54,
            153 => 55,
            154 => 0,
            155 => 0,
            156 => 108,
            157 => 0,
            158 => 11,
            159 => 0,
            160 => 0,
            161 => 0,
            162 => 186,
            163 => 0,
            164 => 0,
            165 => 0,
            166 => 0,
            167 => 0,
            168 => 44,
            169 => 52,
            170 => 118,
            171 => 86,
            172 => 31,
            173 => 34,
            174 => 40,
            175 => 54,
            176 => 32,
            177 => 57,
            178 => 85,
            179 => 40,
            180 => 47,
            181 => 47,
            182 => 40,
            183 => 78,
            184 => 29,
            185 => 0,
            186 => 0,
            187 => 0,
            188 => 0,
            189 => 0,
            190 => 179,
            191 => 0,
            192 => 6,
            193 => 21,
            194 => 121,
            195 => 20,
            196 => 0,
            197 => 0,
            198 => 28,
            199 => 54,
            200 => 29,
            201 => 54,
            202 => 0,
            203 => 0,
            204 => 86,
            205 => 65,
            206 => 158,
            207 => 56,
            208 => 70,
            209 => 156,
            210 => 133,
            211 => 112,
            212 => 107,
            213 => 120,
            214 => 150,
            215 => 98,
            216 => 121,
            217 => 66,
            218 => 90,
            219 => 17,
            220 => 60,
            221 => 0,
            222 => 86,
            223 => 0,
            224 => 0,
            225 => 0,
            226 => 136,
            227 => 116,
            228 => 70,
            229 => 96,
            230 => 86,
            231 => 56,
            232 => 70,
            233 => 0,
            234 => 51,
            235 => 60,
            236 => 73,
            237 => 47,
            238 => 43,
            239 => 43,
            240 => 44,
            241 => 101,
            242 => 58,
            243 => 46,
            244 => 72,
            245 => 12,
            246 => 66,
            247 => 30,
            248 => 45,
            249 => 0,
            250 => 0,
        ];
        foreach ($arr as $id => $rating) {
            if ($rating == 0) {
                continue;
            }
            Account::where('id', $id)->update(['rating' => $rating]);
        }


        $players_rating = GameResult::whereBetween('created_at', [
            Carbon::parse('2026-11-05')->startOfDay(),
            Carbon::parse('2026-11-07')->endOfDay()
        ])
            ->select('account_id')
            ->selectRaw('SUM(total) as total_rating')
            ->groupBy('account_id')
            ->get();

        // 2. В цикле прибавляем рейтинг к текущему значению каждого игрока
        foreach ($players_rating as $row) {
            // Пропускаем игроков, у которых сумма за эти дни равна 0 или меньше, чтобы не делать лишних запросов
            if ($row->total_rating <= 0) {
                continue;
            }

            DB::table('accounts')
                ->where('id', $row->account_id)
                ->increment('rating', $row->total_rating); // Прибавляет total_rating к текущему rating в БД
        }
    }
}
