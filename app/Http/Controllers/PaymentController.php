<?php

namespace App\Http\Controllers;

use App\Http\Helpers\TgHelper;
use App\Http\Helpers\TransactionHelper;
use App\Models\Account;
use App\Models\AdminClub;
use App\Models\Chance;
use App\Models\Immunity;
use App\Models\Merch;
use App\Models\PayEntity;
use App\Models\Probability;
use App\Models\Receipt;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function index()
    {
        $account = session()->get('account');
        $account = Account::with('club')->find($account->id);
        $club = $account->club;
        $account = Account::find($account->id);
        $receipts = Receipt::where('account_id', $account->id)->get();


        return view('order.index', compact('account', 'receipts', 'club'));
    }

    public function makeReceipt(Request $request)
    {
        $params = $request->all();


        if (isset($params['receipt']) && is_file($params['receipt'])) {
            $file = $params['receipt'];

            // 1. Генерируем уникальное имя на латинице и сохраняем оригинальное расширение
            $extension = $file->getClientOriginalExtension();
            $name = Str::random(40) . '.' . $extension; // Требуется use Illuminate\Support\Str;

            // ИЛИ альтернативный вариант (через time() + хэш):
            // $name = time() . '_' . md5($file->getClientOriginalName()) . '.' . $file->getClientOriginalExtension();

            // 2. Перемещаем файл
            $file->move(public_path('uploads/receipts'), $name);

            // 3. Записываем в базу данных
            Receipt::create([
                'src' => '/uploads/receipts/' . $name, // Убрана лишняя конкатенация слэшей
                'account_id' => $params['account_id'],
                'status' => 'pending',
                'date' => Carbon::now()->format('Y-m-d')
            ]);


            $account =  Account::where('id', $params['account_id'])->first();
            $text = 'Новый чек!' . PHP_EOL;
            $text .= 'Игрок: ' . $account->nickname ?? $account->name . PHP_EOL;




            $admins = AdminClub::with('admin')->where('club_id', $account->club_id)->get();

            foreach ($admins as $admin) {

                if (!$admin->admin->telegram_id) {
                    continue;
                }
                $name = env('APP_URL') . '/uploads/receipts' . '/' . $name;

                TgHelper::sendPhotoWithCaption($admin->admin->telegram_id, $name, $text);
            }

            $receipts = Receipt::where('account_id', $params['account_id'])->get();
            return json_encode([
                'success' => true,
                'message' => 'Чек успешно загружен',
                'receipts' => $receipts
            ]);
        }
    }

    public function buyItem(Request $request)
    {

        $params = $request->all();


        $type = $params['type'];
        if ($type == 'merch') {
            $item = Merch::find($params['itemId']);
        } elseif ($type == 'immunities') {
            $item = Immunity::find($params['itemId']);
        } else {

            $item = Probability::find($params['itemId']);
        }

        $account = session()->get('account');
        $account = Account::find($account->id);
        $balance = $account->balance;


        if ($item->price > $balance) {
            return json_encode([
                'success' => false,
                'message' => 'Недостаточно средств',
                'error' => 'Недостаточно средств',
            ]);
        }


        TransactionHelper::makePay($item, $type, $account);

        $account = Account::find($account->id);
        $balance = $account->balance;

        return json_encode([
            'success' => true,
            'message' => 'Покупка прошла успешно',
            'points' => $balance
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        // 1. Защита от ручных гостей: если id игрока текстовый (начинается с new_),
        // зануляем его, чтобы база данных не ругалась на некорректный foreign key.
        if (isset($data['account_id']) && is_string($data['account_id']) && str_starts_with($data['account_id'], 'new_')) {
            $data['account_id'] = null;
        }

        // 2. Логику "$account->table_id" МЫ УБРАЛИ.
        // table_id теперь зафиксирован на фронтенде во вкладке и прилетает внутри $data['table_id'].
        // Дополнительно проставляем статус подтверждения, так как запись создается по клику.
        $data['is_confirmed'] = 1;

        $payEntity = PayEntity::create($data);

        return response()->json(['id' => $payEntity->id], 201);
    }

    /**
     * PUT /pay-entities/{payEntity}
     */
    public function update(Request $request, PayEntity $payEntity)
    {
        $data = $request->all();

        // Защита primary key: если прилетел строковый id гостя, убираем его из апдейта
        if (isset($data['account_id']) && is_string($data['account_id']) && str_starts_with($data['account_id'], 'new_')) {
            unset($data['account_id']);
        }

        $payEntity->update($data);

        return response()->json(['id' => $payEntity->id]);
    }

    /**
     * DELETE /pay-entities/{payEntity}
     * Этот метод ОБЯЗАТЕЛЕН. Он вызывается, когда админ снимает зеленую галочку
     * и подтверждает удаление платежа в появившемся окне confirm().
     */
    public function destroy(PayEntity $payEntity)
    {
        $payEntity->delete();

        return response()->json(['status' => 'deleted']);
    }
}
