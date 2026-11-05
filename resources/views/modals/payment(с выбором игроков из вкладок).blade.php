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
           /* font-family: Georgia, serif; */
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

       .tab-bar {
           display: flex;
           align-items: stretch;
           background: #161e2b;
           border-bottom: 2px solid var(--red);
           overflow-x: auto;
           scrollbar-width: none;
       }

       .tab-bar::-webkit-scrollbar {
           display: none;
       }

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

       .tab-item:hover {
           background: #1f2937;
       }

       .tab-item.active {
           background: var(--surface);
           border-bottom: 2px solid var(--gold);
           margin-bottom: -2px;
       }

       .tab-name-display {
           font-size: 13px;
           color: var(--muted);
           max-width: 130px;
           overflow: hidden;
           text-overflow: ellipsis;
       }

       .tab-item.active .tab-name-display {
           color: var(--gold);
       }

       .tab-name-input {
           font-size: 13px;
           background: transparent;
           border: none;
           border-bottom: 1px solid var(--gold-dim);
           color: var(--gold);
           outline: none;
           width: 100px;
           font-family: inherit;
           padding: 1px 2px;
       }

       .tab-name-picker {
           display: flex;
           align-items: center;
           gap: 10px;
           padding: 10px 16px;
           background: #1a2233;
           border-bottom: 1px solid var(--border);
       }

       .tab-name-picker__label {
           font-size: 12px;
           color: var(--muted);
       }

       .tab-name-select {
           font-size: 13px;
           background: #111827;
           border: 1px solid var(--gold-dim);
           border-radius: 4px;
           color: var(--gold);
           outline: none;
           font-family: inherit;
           padding: 4px 8px;
           cursor: pointer;
       }

       .tab-badge {
           font-size: 10px;
           font-family: monospace;
           color: var(--muted);
           background: #0f1623;
           border-radius: 10px;
           padding: 1px 6px;
       }

       .tab-item.active .tab-badge {
           background: #1a2233;
       }

       .tab-close {
           background: none;
           border: none;
           color: #4b5563;
           font-size: 16px;
           cursor: pointer;
           padding: 4px 6px;
           border-radius: 4px;
           line-height: 1;
           transition: color .15s;
       }

       .tab-close:hover {
           color: var(--red);
       }

       .btn-add-tab {
           display: flex;
           align-items: center;
           justify-content: center;
           padding: 0 16px;
           height: 44px;
           background: none;
           border: none;
           color: var(--muted);
           font-size: 22px;
           cursor: pointer;
           flex-shrink: 0;
           transition: color .15s;
       }

       .btn-add-tab:hover {
           color: var(--gold);
       }

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
       .progress-wrap {
           height: 3px;
           background: var(--border);
       }

       .progress-bar {
           height: 100%;
           background: var(--gold);
           transition: width .4s ease;
       }


       .picker {
           padding: 20px 20px 24px;
           display: flex;
           flex-direction: column;
           gap: 16px;
       }

       .picker-section-label {
           font-size: 11px;
           text-transform: uppercase;
           letter-spacing: .08em;
           color: var(--muted);
           padding-bottom: 6px;
           border-bottom: 1px solid var(--border);
       }

       /* Tab source selector */
       .source-tabs {
           display: flex;
           gap: 6px;
           flex-wrap: wrap;
       }

       .btn-source-tab {
           padding: 5px 12px;
           border-radius: 20px;
           font-size: 12px;
           font-family: inherit;
           cursor: pointer;
           border: 1px solid var(--border);
           background: #1a2233;
           color: var(--muted);
           transition: all .15s;
       }

       .btn-source-tab:hover {
           border-color: var(--gold-dim);
           color: var(--gold);
       }

       .btn-source-tab.active {
           border-color: var(--gold);
           color: var(--gold);
           background: #1f2b40;
       }

       /* Player checklist */
       .player-checklist {
           display: flex;
           flex-direction: column;
           gap: 4px;
           max-height: 260px;
           overflow-y: auto;
           scrollbar-width: thin;
           scrollbar-color: var(--border) transparent;
       }

       .check-row {
           display: flex;
           align-items: center;
           gap: 10px;
           padding: 8px 12px;
           border-radius: 8px;
           cursor: pointer;
           border: 1px solid transparent;
           transition: background .12s, border-color .12s;
       }

       .check-row:hover {
           background: #1e2a3a;
       }

       .check-row.selected {
           background: #1a2b1a;
           border-color: #2a4a2a;
       }

       .check-box {
           width: 16px;
           height: 16px;
           flex-shrink: 0;
           accent-color: var(--gold);
           cursor: pointer;
       }

       .check-name {
           font-size: 14px;
           flex: 1;
       }

       .check-all-row {
           display: flex;
           align-items: center;
           justify-content: space-between;
           padding: 4px 12px;
       }

       .btn-check-all {
           font-size: 11px;
           color: var(--muted);
           background: none;
           border: none;
           cursor: pointer;
           font-family: inherit;
           transition: color .15s;
           padding: 0;
       }

       .btn-check-all:hover {
           color: var(--gold);
       }

       .selected-count {
           font-size: 11px;
           color: var(--muted);
           font-family: monospace;
       }

       /* New player input */
       .new-player-row {
           display: flex;
           gap: 8px;
       }

       /* Action bar */
       .picker-actions {
           display: flex;
           gap: 8px;
           justify-content: flex-end;
           align-items: center;
           padding-top: 4px;
           border-top: 1px solid var(--border);
       }

       .picker-summary {
           flex: 1;
           font-size: 12px;
           color: var(--muted);
           font-family: monospace;
       }

       .btn-picker-add {
           background: var(--gold-dim);
           border: none;
           border-radius: 8px;
           color: #fff;
           font-size: 13px;
           font-weight: bold;
           font-family: inherit;
           padding: 9px 20px;
           cursor: pointer;
           transition: background .15s;
       }

       .btn-picker-add:hover {
           background: var(--gold);
           color: #111;
       }

       .btn-picker-add:disabled {
           opacity: .35;
           cursor: not-allowed;
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

       .field:focus {
           border-color: var(--gold-dim);
       }

       .field::placeholder {
           color: var(--muted);
       }

       .field-amount {
           text-align: right;
           font-family: monospace;
       }

       .field-method.is-card {
           color: #7eb8e8;
       }

       .field-method.is-cash {
           color: #4ade80;
       }

       .field-method.is-free {
           color: #c084fc;
       }

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

       .btn-confirm.unpaid {
           background: var(--red);
       }

       .btn-confirm.paid {
           background: var(--green);
       }

       .btn-confirm.is-free {
           background: var(--purple);
       }

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

       .btn-icon.active {
           border-color: var(--gold);
           color: var(--gold);
       }

       .btn-icon.edit-active {
           border-color: var(--gold-dim);
           color: var(--gold);
       }

       .btn-icon:hover {
           border-color: var(--gold-dim);
           color: var(--gold);
       }

       .btn-icon.remove:hover {
           border-color: var(--red);
           color: var(--red);
       }

       .comment-row {
           padding: 0 12px 10px 58px;
       }

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

       .name-input:focus {
           border-color: var(--gold-dim);
       }

       .name-input::placeholder {
           color: var(--muted);
       }

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

       .btn-add:hover {
           background: #dc2626;
       }

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

       .summary {
           display: flex;
           gap: 20px;
           flex-wrap: wrap;
       }

       .summary-item {
           display: flex;
           flex-direction: column;
           gap: 3px;
       }

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

       .summary-value.gold {
           color: var(--gold);
       }

       .summary-value.purple {
           color: #c084fc;
       }

       .btn-reset {
           background: none;
           border: 1px solid var(--border);
           border-radius: 6px;
           color: var(--muted);
           font-size: 12px;
           padding: 7px 14px;
           cursor: pointer;
           font-family: inherit;
           transition: border-color .15s, color .15s;
       }

       .btn-reset:hover {
           border-color: var(--red);
           color: var(--red);
       }

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

       .btn-send:hover {
           background: var(--gold);
           color: #111;
       }

       .btn-send:disabled {
           opacity: .45;
           cursor: not-allowed;
       }

       .send-status {
           font-size: 12px;
           font-family: monospace;
       }

       .send-status.ok {
           color: #4ade80;
       }

       .send-status.err {
           color: #f87171;
       }
   </style>


   <div class="v-modal" style="width: 100%; max-width: 900px">
       <button class="v-modal__close" data-vclose>
           <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
               stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
               class="lucide lucide-x-icon lucide-x">
               <path d="M18 6 6 18" />
               <path d="m6 6 12 12" />
           </svg>
       </button>
       <div class="v-modal__content">

           <div class="panel" x-data="app()">

               <!-- Tab bar -->
               <div class="tab-bar">
                   <template x-for="(tab, ti) in tabs" :key="tab.id">
                       <div class="tab-item" :class="{ active: ti === activeTab }" @click="activeTab = ti">
                           <span class="tab-name-display" x-text="tab.name"></span>
                           <span class="tab-badge"
                               x-text="`${tab.players.filter(p=>p.confirmed).length}/${tab.players.length}`"></span>
                           <button class="tab-close" @click.stop="removeTab(ti)" x-show="tabs.length > 1"
                               title="Закрыть">×</button>
                       </div>
                   </template>
                   <button class="btn-add-tab" @click="addTab()" title="Новая вкладка">+</button>
               </div>

               <!-- Tab name selector (shown below tab-bar for new tabs) -->
               <template x-if="currentTab.renaming">
                   <div class="tab-name-picker">
                       <span class="tab-name-picker__label">Название вкладки:</span>
                       <select class="tab-name-select" x-model="currentTab.name" @change="currentTab.renaming = false"
                           @keydown.escape="currentTab.renaming = false">
                           <option value="Выберите тип вкладки">Выберите тип вкладки</option>
                           <option value="Иммунитет/музыка">Иммунитет/музыка</option>
                           <option value="Прочее">Прочее</option>
                       </select>
                   </div>
               </template>

               <!-- Header -->
               <div class="panel-header">
                   <span class="panel-title">🃏 <span x-text="currentTab.name"></span></span>
                   <span class="panel-meta" x-text="`${confirmedCount} / ${currentTab.players.length} оплатили`"></span>
               </div>

               <!-- Progress -->
               <div class="progress-wrap">
                   <div class="progress-bar"
                       :style="`width:${currentTab.players.length ? confirmedCount/currentTab.players.length*100 : 0}%`">
                   </div>
               </div>

               <!-- ══ EMPTY STATE: player picker ══ -->
               <template x-if="currentTab.players.length === 0">
                   <div class="picker">

                       <!-- Source tab selector -->
                       <template x-if="otherTabsWithPlayers.length > 0">
                           <div>
                               <div class="picker-section-label" style="margin-bottom:10px">Выбрать игроков из вкладки
                               </div>
                               <div class="source-tabs">
                                   <template x-for="(tab, ti) in otherTabsWithPlayers" :key="tab.id">
                                       <button class="btn-source-tab" :class="{ active: pickerSourceId === tab.id }"
                                           @click="pickerSourceId = tab.id; pickerSelected = []"
                                           x-text="tab.name"></button>
                                   </template>
                               </div>
                           </div>
                       </template>

                       <!-- Checklist from selected source tab -->
                       <template x-if="pickerSource">
                           <div>
                               <div class="check-all-row">
                                   <button class="btn-check-all" @click="toggleAll()">
                                       <span
                                           x-text="pickerSelected.length === pickerSource.players.length ? 'Снять всё' : 'Выбрать всех'"></span>
                                   </button>
                                   <span class="selected-count"
                                       x-text="pickerSelected.length ? `Выбрано: ${pickerSelected.length}` : ''"></span>
                               </div>
                               <div class="player-checklist">
                                   <template x-for="p in pickerSource.players" :key="p.id">
                                       <div class="check-row" :class="{ selected: pickerSelected.includes(p.name) }"
                                           @click="togglePick(p.name)">
                                           <input type="checkbox" class="check-box"
                                               :checked="pickerSelected.includes(p.name)"
                                               @click.stop="togglePick(p.name)" />
                                           <span class="check-name"
                                               x-text="p.nickname ? p.nickname + (p.name ? ' (' + p.name + ')' : '') : p.name"></span>

                                       </div>
                                   </template>
                               </div>
                           </div>
                       </template>

                       <!-- New player input -->
                       <div>
                           <div class="picker-section-label" style="margin-bottom:10px">
                               <span
                                   x-text="otherTabsWithPlayers.length ? 'Или добавить нового игрока' : 'Добавить игрока'"></span>
                           </div>
                           <div class="new-player-row">
                               <input class="field" type="text" placeholder="Имя нового игрока..."
                                   x-model="pickerNewName" @keydown.enter="addPickerNew()"
                                   style="font-size:14px; padding: 8px 12px;" />
                               <button class="btn-add" @click="addPickerNew()" style="flex-shrink:0">+</button>
                           </div>
                           <!-- Staged new names -->
                           <template x-if="pickerNewPlayers.length > 0">
                               <div style="margin-top:8px; display:flex; flex-wrap:wrap; gap:6px;">
                                   <template x-for="(name, ni) in pickerNewPlayers" :key="ni">
                                       <span
                                           style="display:flex;align-items:center;gap:4px;background:#1a2233;border:1px solid var(--border);border-radius:20px;padding:3px 10px;font-size:12px;">
                                           <span x-text="name"></span>
                                           <button @click="pickerNewPlayers.splice(ni,1)"
                                               style="background:none;border:none;color:#6b7280;cursor:pointer;font-size:14px;line-height:1;padding:0 2px;">×</button>
                                       </span>
                                   </template>
                               </div>
                           </template>
                       </div>

                       <!-- Action bar -->
                       <div class="picker-actions">
                           <span class="picker-summary" x-text="pickerSummary"></span>
                           <button class="btn-picker-add"
                               :disabled="pickerSelected.length === 0 && pickerNewPlayers.length === 0"
                               @click="applyPicker()">
                               Добавить
                               <template x-if="pickerTotal > 0">
                                   <span x-text="`(${pickerTotal})`"></span>
                               </template>
                           </button>
                       </div>

                   </div>
               </template>

               <!-- ══ PLAYER LIST (non-empty) ══ -->
               <template x-if="currentTab.players.length > 0">
                   <div>
                       <div class="player-list">
                           <template x-for="(p, i) in currentTab.players" :key="p.id ?? '_' + i">
                               <div class="player-row"
                                   :class="{
                                       confirmed: p.confirmed,
                                       free: p.confirmed && p.method === 'free',
                                       editing: p.confirmed && p.editing
                                   }">
                                   <div class="row-main">
                                       <span class="seat" x-text="i + 1"></span>

                                       <span class="player-name"
                                           :style="p.confirmed ? (p.method==='free' ? 'color:#d8b4fe' : 'color:#86efac'): ''"
                                           x-text="p.nickname || p.name"></span>



                                       <input class="field field-amount" type="number" min="0"
                                           placeholder="0 ₽" x-model.number="p.amount"
                                           :disabled="(p.confirmed && !p.editing) || p.method === 'free'" />

                                       <select class="field field-method"
                                           :class="{ 'is-card': p.method==='card', 'is-cash': p.method==='cash', 'is-free': p.method==='free' }"
                                           x-model="p.method" :disabled="p.confirmed && !p.editing"
                                           @change="if (p.method === 'free') p.amount = 0">
                                           <option value="">— способ —</option>
                                           <option value="card">💳 Карта</option>
                                           <option value="cash">💵 Наличные</option>
                                           <option value="free">🆓 Бесплатно</option>
                                       </select>

                                       <button class="btn-confirm"
                                           :class="(p.confirmed && !p.editing) ? (p.method==='free' ? 'is-free' : 'paid') :
                                           'unpaid'"
                                           @click="confirm(p)">
                                           <span x-text="(p.confirmed && !p.editing) ? '✓' : '✕'"></span>
                                           <span
                                               x-text="(p.confirmed && !p.editing) ? (p.method==='free' ? 'Бесплатно' : 'Оплачено') : 'Подтвердить'"></span>
                                       </button>

                                       <button class="btn-icon" :class="{ active: p.comment }" title="Комментарий"
                                           @click="p.commentOpen = !p.commentOpen">💬</button>

                                       <template x-if="p.confirmed">
                                           <button class="btn-icon" :class="{ 'edit-active': p.editing }"
                                               :title="p.editing ? 'Отменить' : 'Редактировать'"
                                               @click="p.editing = !p.editing"><span
                                                   x-text="p.editing ? '✕' : '✎'"></span></button>
                                       </template>
                                       <template x-if="!p.confirmed">
                                           <button class="btn-icon remove" title="Удалить"
                                               @click="removePlayer(i)">×</button>
                                       </template>
                                   </div>

                                   <div class="comment-row" x-show="p.commentOpen" x-transition>
                                       <input class="field" type="text" placeholder="Комментарий к игроку..."
                                           x-model="p.comment" style="font-size:12px; color:#d1d5db;" />
                                   </div>
                               </div>
                           </template>
                       </div>

                       <div class="add-bar">
                           <input class="name-input" type="text" placeholder="Имя игрока..." x-model="newName"
                               @keydown.enter="addPlayer()" />
                           <button class="btn-add" @click="addPlayer()">+</button>
                       </div>

                       <div class="panel-footer">
                           <div class="summary">
                               <div class="summary-item">
                                   <span class="summary-label">Итого</span>
                                   <span class="summary-value gold"
                                       x-text="total.toLocaleString('ru-RU') + ' ₽'"></span>
                               </div>
                               <div class="summary-item">
                                   <span class="summary-label">Карта</span>
                                   <span class="summary-value" x-text="byCard.toLocaleString('ru-RU') + ' ₽'"></span>
                               </div>
                               <div class="summary-item">
                                   <span class="summary-label">Наличные</span>
                                   <span class="summary-value" x-text="byCash.toLocaleString('ru-RU') + ' ₽'"></span>
                               </div>
                               <div class="summary-item">
                                   <span class="summary-label">Бесплатно</span>
                                   <span class="summary-value purple" x-text="freeCount + ' чел.'"></span>
                               </div>
                           </div>
                           <button class="btn-reset" @click="resetTab()">Сбросить</button>
                           <span class="send-status" :class="sendStatus" x-show="sendMessage"
                               x-text="sendMessage"></span>
                           <button class="btn-send" :disabled="sending" @click="sendAll()">
                               <span x-text="sending ? 'Отправка...' : 'Отправить'"></span>
                           </button>
                       </div>
                   </div>
               </template>

           </div>
       </div>
   </div>

   <script>
       const DEFAULT_USERS = @json(
           $users->map(fn($u) => [
                   'id' => $u->id,
                   'name' => $u->name,
               ]));

       function app() {
           let _pid = 100;
           let _tid = 0;

           const makePlayer = (user) => ({
               id: user.id,
               name: user.name,
               amount: 0,
               method: '',
               comment: '',
               confirmed: false,
               editing: false,
               commentOpen: false
           });

           const makeTab = (name, withPlayers) => ({
               id: _tid++,
               name,
               renaming: false,
               players: withPlayers ?
                   DEFAULT_USERS.map(makePlayer) : []
           });

           // Загрузка состояния из localStorage
           const loadState = () => {
               const saved = localStorage.getItem('mafia-payment-state');
               if (!saved) return;

               try {
                   const data = JSON.parse(saved);
                   // Восстанавливаем вкладки и активную вкладку
                   this.tabs = data.tabs || this.tabs;
                   this.activeTab = data.activeTab ?? this.activeTab;

                   // Пересчитываем счётчик ID вкладок, чтобы новые не конфликтовали
                   let maxTid = 0;
                   this.tabs.forEach(t => {
                       if (t.id > maxTid) maxTid = t.id;
                   });
                   _tid = maxTid + 1;
               } catch (e) {
                   console.warn('Не удалось восстановить состояние:', e);
               }
           };

           // Сохранение состояния в localStorage
           const saveState = () => {
               const state = {
                   tabs: this.tabs,
                   activeTab: this.activeTab,
               };
               localStorage.setItem('mafia-payment-state', JSON.stringify(state));
           };

           return {
               tabs: [makeTab('{{ $table->is_dop ? 'ДопИгра' : 'Игра' }}', true)],
               activeTab: 0,
               newName: '',

               // picker state
               pickerSourceId: null,
               pickerSelected: [],
               pickerNewName: '',
               pickerNewPlayers: [],

               // send state
               sending: false,
               sendStatus: '',
               sendMessage: '',

               get currentTab() {
                   return this.tabs[this.activeTab];
               },
               get otherTabsWithPlayers() {
                   return this.tabs.filter((t, i) => i !== this.activeTab && t.players.length > 0);
               },
               get pickerSource() {
                   return this.otherTabsWithPlayers.find(t => t.id === this.pickerSourceId) || null;
               },
               get pickerTotal() {
                   return this.pickerSelected.length + this.pickerNewPlayers.length;
               },
               get pickerSummary() {
                   const parts = [];
                   if (this.pickerSelected.length) parts.push(`из вкладки: ${this.pickerSelected.length}`);
                   if (this.pickerNewPlayers.length) parts.push(`новых: ${this.pickerNewPlayers.length}`);
                   return parts.join(' · ');
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
                       .filter(p => p.confirmed && p.method === 'free')
                       .length;
               },

               togglePick(name) {
                   const idx = this.pickerSelected.indexOf(name);
                   if (idx === -1) {
                       this.pickerSelected.push(name);
                   } else {
                       this.pickerSelected.splice(idx, 1);
                   }
                   this.saveState();
               },

               toggleAll() {
                   if (!this.pickerSource) return;
                   const allNames = this.pickerSource.players.map(p => p.name);
                   if (this.pickerSelected.length === allNames.length) {
                       this.pickerSelected = [];
                   } else {
                       this.pickerSelected = [...allNames];
                   }
                   this.saveState();
               },

               addPickerNew() {
                   if (!this.pickerNewName.trim()) return;
                   this.pickerNewPlayers.push(this.pickerNewName.trim());
                   this.pickerNewName = '';
                   this.saveState();
               },

               applyPicker() {
                   if (this.pickerTotal === 0) return;

                   const playersToAdd = [];

                   // Добавляем выбранных из другой вкладки
                   if (this.pickerSelected.length > 0 && this.pickerSource) {
                       const selectedNamesSet = new Set(this.pickerSelected);
                       playersToAdd.push(...this.pickerSource.players
                           .filter(p => selectedNamesSet.has(p.name))
                           .map(makePlayer));
                   }

                   // Добавляем новых
                   if (this.pickerNewPlayers.length > 0) {
                       // Для новых игроков генерируем временные ID (можно улучшить, если есть API для создания)
                       let tempId = 1000;
                       playersToAdd.push(...this.pickerNewPlayers.map(name => ({
                           id: tempId++,
                           name,
                           amount: 0,
                           method: '',
                           comment: '',
                           confirmed: false,
                           editing: false,
                           commentOpen: false
                       })));
                   }

                   this.currentTab.players.push(...playersToAdd);

                   // Сброс пикера
                   this.pickerSourceId = null;
                   this.pickerSelected = [];
                   this.pickerNewPlayers = [];

                   this.saveState();
               },

               addPlayer() {
                   if (!this.newName.trim()) return;

                   let tempId = 2000;
                   const existingIds = new Set(this.currentTab.players.map(p => p.id));
                   while (existingIds.has(tempId)) tempId++;

                   this.currentTab.players.push({
                       id: tempId,
                       name: this.newName.trim(),
                       amount: 0,
                       method: '',
                       comment: '',
                       confirmed: false,
                       editing: false,
                       commentOpen: false
                   });

                   this.newName = '';
                   this.saveState();
               },

               removePlayer(index) {
                   this.currentTab.players.splice(index, 1);
                   this.saveState();
               },

               confirm(player) {
                   player.confirmed = !player.confirmed;
                   if (player.confirmed) {
                       player.editing = false;
                       if (player.method === 'free') player.amount = 0;
                   }
                   this.saveState();
               },

               resetTab() {
                   this.currentTab.players = [];
                   this.saveState();
               },

               addTab() {
                   const name = `Игра ${this.tabs.length + 1}`;
                   this.tabs.push(makeTab(name, false));
                   this.activeTab = this.tabs.length - 1;
                   this.pickerSourceId = this.tabs[0]?.id ?? null;
                   this.pickerSelected = [];
                   this.pickerNewPlayers = [];

                   this.$nextTick(() => {
                       this.tabs[this.activeTab].renaming = true;
                   });

                   this.saveState();
               },

               removeTab(ti) {
                   if (this.tabs.length <= 1) return;
                   this.tabs.splice(ti, 1);
                   if (this.activeTab >= this.tabs.length) {
                       this.activeTab = Math.max(0, this.tabs.length - 1);
                   }
                   this.saveState();
               },

               sendAll() {
                   this.sending = true;
                   this.sendMessage = '';

                   const payload = this.tabs.map(tab => ({
                       tabId: tab.id,
                       tabName: tab.name,
                       players: tab.players.map(p => ({
                           id: p.id,
                           name: p.name,
                           amount: p.amount,
                           method: p.method,
                           comment: p.comment,
                           confirmed: p.confirmed
                       }))
                   }));

                   fetch('/send-payment-data', {
                           method: 'POST',
                           headers: {
                               'Content-Type': 'application/json',
                               'X-CSRF-TOKEN': '{{ csrf_token() }}'
                           },
                           body: JSON.stringify({
                               tabs: payload
                           })
                       })
                       .then(res => {
                           if (res.ok) {
                               this.sendStatus = 'ok';
                               this.sendMessage = '✓ Отправлено';
                               //    localStorage.removeItem('mafia-payment-state'); // очищаем после успешной отправки
                           } else {
                               this.sendStatus = 'err';
                               this.sendMessage = `✕ Ошибка ${res.status}`;
                           }
                       })
                       .catch(e => {
                           this.sendStatus = 'err';
                           this.sendMessage = '✕ Сеть недоступна';
                       })
                       .finally(() => {
                           this.sending = false;
                       });
               },

               // Вспомогательные методы для загрузки и сохранения состояния
               loadState() {
                   const saved = localStorage.getItem('mafia-payment-state');
                   if (!saved) return;

                   try {
                       const data = JSON.parse(saved);
                       this.tabs = data.tabs || this.tabs;
                       this.activeTab = data.activeTab ?? this.activeTab;

                       // Пересчитываем счётчик ID вкладок, чтобы новые не конфликтовали
                       let maxTid = 0;
                       this.tabs.forEach(t => {
                           if (t.id > maxTid) maxTid = t.id;
                       });
                       _tid = maxTid + 1;
                   } catch (e) {
                       console.warn('Не удалось восстановить состояние:', e);
                   }
               },

               saveState() {
                   const state = {
                       tabs: this.tabs,
                       activeTab: this.activeTab,
                   };
                   localStorage.setItem('mafia-payment-state', JSON.stringify(state));
               },

               init() {
                   this.loadState();
               }
           };
       }

       // Инициализация приложения
       document.addEventListener('alpine:init', () => {
           Alpine.data('app', app);
       });
   </script>
