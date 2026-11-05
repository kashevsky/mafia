<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AccountLink;
use App\Models\Achivment;
use App\Models\Club;
use App\Models\GameStep;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index()
    {



        $account = session()->get('account');


        if (!$account) {
            $account = Account::with('club')->first();
            // $account->club_id = 1;
            // $account->club = Club::with('games','slides')->find(1);
        } else {
            $account = Account::with('club')->find($account->id);
        }


        session()->put('account', $account);
        \Carbon\Carbon::setLocale('ru');

        $club = Club::with('videos', 'games')->find($account->club_id);
        $club->slides = $club->games->whereNotNull('is_in_slider')->where('status', 'pending');
        $accounts = Account::with('club')
            ->orderBy('rating', 'desc')
            ->where('club_id', $account->club_id)->get();
        foreach ($club->videos as &$video) {
            $video->created_at_formatted = $video->created_at->translatedFormat('d M Y');
        }

        $topAlltime = $accounts->take(10);

        // По клубам: суммарный рейтинг клуба, топ-10 клубов
        $byClub = Account::with('club')
            ->selectRaw('club_id, SUM(rating) as total_rating, COUNT(*) as members')
            ->groupBy('club_id')
            ->orderBy('total_rating', 'desc')
            ->with('club')
            ->take(10)
            ->get();


        return view('index', compact('account', 'club', 'accounts', 'topAlltime', 'byClub'));
    }

    public function invite($code)
    {

        $link = AccountLink::where('code', $code)->first();


        $account = Account::create([
            'club_id' => $link->club_id
        ]);

        $account->nickname = 'Игрок ' . $account->id;

        $account->save();
        $link->is_used = 1;
        $link->save();

        session()->put('account', $account);
        sleep(1);
        return redirect()->route('club.index');
    }

    public function rating()
    {
        $account = session()->get('account');
        $account = Account::with('club')->find($account->id);
        $club = $account->club;

        // Все игроки, отсортированные по рейтингу
        $accounts = Account::with('club')->orderBy('rating', 'desc')->get();

        // Топ-10 за все время
        $topAlltime = $accounts->take(10);

        // По клубам: суммарный рейтинг клуба, топ-10 клубов
        $byClub = Account::with('club')
            ->selectRaw('club_id, SUM(rating) as total_rating, COUNT(*) as members')
            ->groupBy('club_id')
            ->orderBy('total_rating', 'desc')
            ->with('club')
            ->take(10)
            ->get();

        return view('rating', compact('account', 'club', 'accounts', 'topAlltime', 'byClub'));
    }

    public function info()
    {
        return view('info');
    }

    public function infoSend(Request $request)
    {
        file_put_contents('rating-log.txt', '');

        return redirect()->back();
    }


}
