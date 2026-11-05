{{--
    pay-report.blade.php

    Переменные контроллера:
      $users       — Collection<User>      (id, name) — все игроки стола
      $payEntities — Collection<PayEntity> (с загруженным ->account, если нужен name)
                     Поля: id, game_id, account_id, amount, table_id, type, method, club_id

    Пример контроллера:
        $users       = $table->users;                          // все участники
        $payEntities = PayEntity::where('game_id', $game->id)->get();
        return view('...', compact('users', 'payEntities', 'table'));
--}}

@php
    /**
     * Строим массив вкладок — одна вкладка на каждый уникальный table_id.
     * Для каждой вкладки берём всех $users и смотрим, есть ли у них
     * запись в $payEntities с этим table_id.
     */
    $byTable = $payEntities->groupBy('table_id')->sortKeys();

    $tabs = $byTable->map(function ($entries, $tableId) use ($users) {
        // Dominant type — самый частый type среди записей этого стола
        $dominantType = $entries
            ->groupBy('type')
            ->map->count()
            ->sortDesc()
            ->keys()
            ->first() ?? 'Стол ' . $tableId;

        // Суммарная оплата по account_id для этого стола
        $paidMap = $entries->groupBy('account_id')->map(function ($group) {
            return [
                'amount'        => $group->sum('amount'),
                'method'        => $group->first()->method ?? '',
                'pay_entity_id' => $group->first()->id,
            ];
        });

        // Список игроков: все $users, у кого есть запись — confirmed=true
        $players = $users->map(function ($u) use ($paidMap) {
            $paid = $paidMap->get($u->id);
            return [
                'id'            => $u->id,
                'name'          => $u->name,
                'amount'        => $paid ? (int) $paid['amount'] : 0,
                'method'        => $paid ? ($paid['method'] ?? '') : '',
                'comment'       => '',
                'confirmed'     => $paid !== null,
                'editing'       => false,
                'commentOpen'   => false,
                'pay_entity_id' => $paid ? $paid['pay_entity_id'] : null,
            ];
        })->values()->toArray();

        return [
            'id'       => $tableId,
            'name'     => 'Стол ' . $tableId . ' · ' . $dominantType,
            'type'     => $dominantType,
            'renaming' => false,
            'players'  => $players,
        ];
    })->values()->toArray();
@endphp

<style>
    *,
    *::before,
    *::after {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    :root {
        --bg: #111827;
        --surface: #1f2937;
        --surface2: #263244;
        --border: #374151;
        --red: #b91c1c;
        --gold: #fbbf24;
        --gold-dim: #d97706;
        --text: #f3f4f6;
        --muted: #9ca3af;
        --green: #16a34a;
        --green-bg: #052e16;
        --green-bd: #166534;
        --purple: #7e22ce;
        --purple-bg: #1a0d2e;
        --purple-bd: #6b21a8;
        --edit-bg: #1c1a0d;
        --edit-bd: #d97706;
        --radius: 10px;
    }

    body {
        background: var(--bg);
        color: var(--text);
        min-height: 100vh;
        display: flex;
        align-items: flex-start;
        justify-content: center;
        padding: 24px;
    }

    .panel {
        width: 100%;
        max-width: 760px;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 24px 64px rgba(0, 0, 0, 0.7);
    }

    /* ── Tab bar ── */
    .tab-bar {
        display: flex;
        align-items: stretch;
        background: #161e2b;
        border-bottom: 2px solid var(--red);
        overflow-x: auto;
        scrollbar-width: none;
    }

    .tab-bar::-webkit-scrollbar { display: none; }

    .tab-item {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 0 4px 0 14px;
        height: 44px;
        cursor: pointer;
        border-right: 1px solid var(--border);
        white-space: nowrap;
        flex-shrink: 0;
        transition: background .15s;
    }

    .tab-item:hover { background: #1f2937; }

    .tab-item.active {
        background: var(--surface);
        border-bottom: 2px solid var(--gold);
        margin-bottom: -2px;
    }

    .tab-name-display {
        font-size: 13px;
        color: var(--muted);
        max-width: 160px;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .tab-item.active .tab-name-display { color: var(--gold); }

    .tab-badge {
        font-size: 10px;
        font-family: monospace;
        color: var(--muted);
        background: #0f1623;
        border-radius: 10px;
        padding: 1px 6px;
    }

    .tab-item.active .tab-badge { background: #1a2233; }

    /* ── Header ── */
    .panel-header {
        padding: 18px 28px 14px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: linear-gradient(135deg, #1f2937 60%, #2d1515);
        border-bottom: 1px solid var(--border);
    }

    .panel-title {
        font-size: 16px;
        letter-spacing: .1em;
        text-transform: uppercase;
        color: var(--gold);
        font-weight: normal;
    }

    .panel-meta {
        font-size: 12px;
        color: var(--muted);
        font-family: monospace;
    }

    /* ── Progress ── */
    .progress-wrap { height: 3px; background: var(--border); }

    .progress-bar {
        height: 100%;
        background: var(--gold);
        transition: width .4s ease;
    }

    /* ── Fields ── */
    .field {
        background: #111827;
        border: 1px solid var(--border);
        border-radius: 6px;
        color: var(--text);
        font-size: 13px;
        padding: 6px 10px;
        width: 100%;
        outline: none;
        font-family: inherit;
        transition: border-color .15s;
    }

    .field:focus { border-color: var(--gold-dim); }

    .field::placeholder { color: var(--muted); }

    .field-amount {
        text-align: right;
        font-family: monospace;
    }

    .field-method.is-card { color: #7eb8e8; }
    .field-method.is-cash { color: #4ade80; }
    .field-method.is-free { color: #c084fc; }

    .field:disabled {
        opacity: .4;
        cursor: not-allowed;
    }

    /* ── Player list ── */
    .player-list {
        padding: 14px 16px;
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .player-row {
        border-radius: var(--radius);
        overflow: hidden;
        border: 1px solid var(--border);
        background: var(--surface2);
        transition: border-color .2s, background .2s;
    }

    .player-row.confirmed {
        background: var(--green-bg);
        border-color: var(--green-bd);
    }

    .player-row.confirmed.free {
        background: var(--purple-bg);
        border-color: var(--purple-bd);
    }

    .player-row.confirmed.editing {
        background: var(--edit-bg);
        border-color: var(--edit-bd);
    }

    .row-main {
        display: grid;
        grid-template-columns: 26px 1fr 100px 130px 118px 30px 30px;
        align-items: center;
        gap: 8px;
        padding: 10px 12px;
    }

    .seat {
        font-size: 12px;
        color: var(--muted);
        font-family: monospace;
        text-align: center;
    }

    .player-name {
        font-size: 15px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* ── Buttons ── */
    .btn-confirm {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        border: none;
        border-radius: 7px;
        font-size: 11px;
        font-family: inherit;
        padding: 7px 8px;
        cursor: pointer;
        white-space: nowrap;
        font-weight: bold;
        letter-spacing: .03em;
        color: #fff;
        transition: background .15s;
    }

    .btn-confirm.unpaid  { background: var(--red); }
    .btn-confirm.paid    { background: var(--green); }
    .btn-confirm.is-free { background: var(--purple); }

    .btn-icon {
        background: none;
        border: 1px solid var(--border);
        border-radius: 6px;
        color: #6b7280;
        font-size: 14px;
        width: 30px;
        height: 30px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: border-color .15s, color .15s;
        flex-shrink: 0;
    }

    .btn-icon.active      { border-color: var(--gold);     color: var(--gold); }
    .btn-icon.edit-active { border-color: var(--gold-dim); color: var(--gold); }
    .btn-icon:hover       { border-color: var(--gold-dim); color: var(--gold); }
    .btn-icon.remove:hover { border-color: var(--red);     color: var(--red);  }

    .comment-row { padding: 0 12px 10px 58px; }

    /* ── Add bar ── */
    .add-bar {
        padding: 0 16px 14px;
        display: flex;
        gap: 8px;
    }

    .name-input {
        flex: 1;
        background: #111827;
        border: 1px solid var(--border);
        border-radius: 6px;
        color: var(--text);
        font-size: 14px;
        padding: 8px 12px;
        outline: none;
        font-family: inherit;
        transition: border-color .15s;
    }

    .name-input:focus      { border-color: var(--gold-dim); }
    .name-input::placeholder { color: var(--muted); }

    .btn-add {
        background: var(--red);
        border: none;
        border-radius: 6px;
        color: #fff;
        font-size: 22px;
        width: 40px;
        cursor: pointer;
        transition: background .15s;
    }

    .btn-add:hover { background: #dc2626; }

    /* ── Footer ── */
    .panel-footer {
        padding: 14px 28px 18px;
        border-top: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        background: linear-gradient(135deg, #1f2937 60%, #1a1010);
    }

    .summary { display: flex; gap: 20px; flex-wrap: wrap; }

    .summary-item { display: flex; flex-direction: column; gap: 3px; }

    .summary-label {
        color: var(--muted);
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: .08em;
    }

    .summary-value {
        font-family: monospace;
        font-size: 15px;
        color: var(--text);
    }

    .summary-value.gold   { color: var(--gold); }
    .summary-value.purple { color: #c084fc; }

    .btn-send {
        background: var(--gold-dim);
        border: none;
        border-radius: 6px;
        color: #fff;
        font-size: 13px;
        font-weight: bold;
        font-family: inherit;
        padding: 7px 20px;
        cursor: pointer;
        transition: background .15s, opacity .15s;
        letter-spacing: .04em;
    }

    .btn-send:hover    { background: var(--gold); color: #111; }
    .btn-send:disabled { opacity: .45; cursor: not-allowed; }

    .send-status { font-size: 12px; font-family: monospace; }
    .send-status.ok  { color: #4ade80; }
    .send-status.err { color: #f87171; }
</style>

<div class="panel" x-data="app()">

    {{-- ── Tab bar ── --}}
    <div class="tab-bar">
        <template x-for="(tab, ti) in tabs" :key="tab.id">
            <div class="tab-item" :class="{ active: ti === activeTab }" @click="activeTab = ti">
                <span class="tab-name-display" x-text="tab.name"></span>
                <span class="tab-badge"
                      x-text="`${tab.players.filter(p => p.confirmed).length}/${tab.players.length}`"></span>
            </div>
        </template>
    </div>

    {{-- ── Header ── --}}
    <div class="panel-header">
        <span class="panel-title">🃏 <span x-text="currentTab.name"></span></span>
        <span class="panel-meta"
              x-text="`${confirmedCount} / ${currentTab.players.length} оплатили`"></span>
    </div>

    {{-- ── Progress ── --}}
    <div class="progress-wrap">
        <div class="progress-bar"
             :style="`width:${currentTab.players.length ? confirmedCount / currentTab.players.length * 100 : 0}%`">
        </div>
    </div>

    {{-- ── Player list ── --}}
    <div>
        <div class="player-list">
            <template x-for="(p, i) in currentTab.players" :key="p.id ?? '_' + i">
                <div class="player-row"
                     :class="{
                         confirmed: p.confirmed,
                         free:      p.confirmed && p.method === 'free',
                         editing:   p.confirmed && p.editing
                     }">
                    <div class="row-main">
                        <span class="seat" x-text="i + 1"></span>

                        <span class="player-name"
                              :style="p.confirmed ? (p.method === 'free' ? 'color:#d8b4fe' : 'color:#86efac') : ''"
                              x-text="p.name"></span>

                        <input class="field field-amount" type="number" min="0"
                               placeholder="0 ₽"
                               x-model.number="p.amount"
                               :disabled="(p.confirmed && !p.editing) || p.method === 'free'" />

                        <select class="field field-method"
                                :class="{ 'is-card': p.method === 'card', 'is-cash': p.method === 'cash', 'is-free': p.method === 'free' }"
                                x-model="p.method"
                                :disabled="p.confirmed && !p.editing"
                                @change="if (p.method === 'free') p.amount = 0">
                            <option value="">— способ —</option>
                            <option value="card">💳 Карта</option>
                            <option value="cash">💵 Наличные</option>
                            <option value="free">🆓 Бесплатно</option>
                        </select>

                        <button class="btn-confirm"
                                :class="(p.confirmed && !p.editing)
                                    ? (p.method === 'free' ? 'is-free' : 'paid')
                                    : 'unpaid'"
                                @click="confirm(p)">
                            <span x-text="(p.confirmed && !p.editing) ? '✓' : '✕'"></span>
                            <span x-text="(p.confirmed && !p.editing)
                                ? (p.method === 'free' ? 'Бесплатно' : 'Оплачено')
                                : 'Подтвердить'"></span>
                        </button>

                        <button class="btn-icon" :class="{ active: p.comment }" title="Комментарий"
                                @click="p.commentOpen = !p.commentOpen">💬</button>

                        <template x-if="p.confirmed">
                            <button class="btn-icon" :class="{ 'edit-active': p.editing }"
                                    :title="p.editing ? 'Отменить' : 'Редактировать'"
                                    @click="p.editing = !p.editing">
                                <span x-text="p.editing ? '✕' : '✎'"></span>
                            </button>
                        </template>
                        <template x-if="!p.confirmed">
                            {{-- Неоплатившие: пустая ячейка, удалять нельзя (серверные игроки) --}}
                            <span style="width:30px;flex-shrink:0;"></span>
                        </template>
                    </div>

                    <div class="comment-row" x-show="p.commentOpen" x-transition>
                        <input class="field" type="text" placeholder="Комментарий к игроку..."
                               x-model="p.comment" style="font-size:12px; color:#d1d5db;" />
                    </div>
                </div>
            </template>
        </div>

        {{-- Add bar — добавить нового игрока вручную --}}
        <div class="add-bar">
            <input class="name-input" type="text" placeholder="Добавить игрока..."
                   x-model="newName" @keydown.enter="addPlayer()" />
            <button class="btn-add" @click="addPlayer()">+</button>
        </div>

        {{-- Footer --}}
        <div class="panel-footer">
            <div class="summary">
                <div class="summary-item">
                    <span class="summary-label">Итого</span>
                    <span class="summary-value gold"
                          x-text="total.toLocaleString('ru-RU') + ' ₽'"></span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Карта</span>
                    <span class="summary-value"
                          x-text="byCard.toLocaleString('ru-RU') + ' ₽'"></span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Наличные</span>
                    <span class="summary-value"
                          x-text="byCash.toLocaleString('ru-RU') + ' ₽'"></span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Бесплатно</span>
                    <span class="summary-value purple"
                          x-text="freeCount + ' чел.'"></span>
                </div>
            </div>

            <span class="send-status" :class="sendStatus" x-show="sendMessage"
                  x-text="sendMessage"></span>

            <button class="btn-send" :disabled="sending" @click="sendAll()">
                <span x-text="sending ? 'Отправка...' : 'Отправить'"></span>
            </button>
        </div>
    </div>

</div>

<script>
    const INITIAL_TABS = @json($tabs);

    function app() {
        let _tid = INITIAL_TABS.reduce((m, t) => Math.max(m, t.id), 0) + 1;

        return {
            tabs:      INITIAL_TABS,
            activeTab: 0,
            newName:   '',

            sending:     false,
            sendStatus:  '',
            sendMessage: '',

            get currentTab() {
                return this.tabs[this.activeTab];
            },
            get confirmedCount() {
                return this.currentTab.players.filter(p => p.confirmed).length;
            },
            get total() {
                return this.currentTab.players
                    .filter(p => p.confirmed && p.method !== 'free')
                    .reduce((s, p) => s + (p.amount || 0), 0);
            },
            get byCard() {
                return this.currentTab.players
                    .filter(p => p.confirmed && p.method === 'card')
                    .reduce((s, p) => s + (p.amount || 0), 0);
            },
            get byCash() {
                return this.currentTab.players
                    .filter(p => p.confirmed && p.method === 'cash')
                    .reduce((s, p) => s + (p.amount || 0), 0);
            },
            get freeCount() {
                return this.currentTab.players
                    .filter(p => p.confirmed && p.method === 'free').length;
            },

            confirm(player) {
                if (player.confirmed && !player.editing) {
                    // уже оплачено — снять только если редактирование
                    return;
                }
                if (player.editing) {
                    player.editing  = false;
                    player.confirmed = true;
                    return;
                }
                player.confirmed = true;
                player.editing   = false;
                if (player.method === 'free') player.amount = 0;
            },

            addPlayer() {
                if (!this.newName.trim()) return;
                let tempId = 9000;
                const ids = new Set(this.currentTab.players.map(p => p.id));
                while (ids.has(tempId)) tempId++;

                this.currentTab.players.push({
                    id:           tempId,
                    name:         this.newName.trim(),
                    amount:       0,
                    method:       '',
                    comment:      '',
                    confirmed:    false,
                    editing:      false,
                    commentOpen:  false,
                    pay_entity_id: null,
                });
                this.newName = '';
            },

            sendAll() {
                this.sending     = true;
                this.sendMessage = '';

                const payload = this.tabs.map(tab => ({
                    tabId:    tab.id,
                    tabName:  tab.name,
                    tabType:  tab.type,
                    players:  tab.players.map(p => ({
                        id:           p.id,
                        name:         p.name,
                        amount:       p.amount,
                        method:       p.method,
                        comment:      p.comment,
                        confirmed:    p.confirmed,
                        pay_entity_id: p.pay_entity_id,
                    }))
                }));

                fetch('/send-payment-data', {
                    method:  'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ tabs: payload })
                })
                .then(res => {
                    if (res.ok) {
                        this.sendStatus  = 'ok';
                        this.sendMessage = '✓ Отправлено';
                    } else {
                        this.sendStatus  = 'err';
                        this.sendMessage = `✕ Ошибка ${res.status}`;
                    }
                })
                .catch(() => {
                    this.sendStatus  = 'err';
                    this.sendMessage = '✕ Сеть недоступна';
                })
                .finally(() => { this.sending = false; });
            },
        };
    }

    document.addEventListener('alpine:init', () => {
        Alpine.data('app', app);
    });
</script>
