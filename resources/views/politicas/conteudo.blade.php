{{--
    resources/views/politicas/conteudo.blade.php

    CORREÇÕES APLICADAS:
    ─────────────────────────────────────────────────────────────
    • A função privacyCenter() foi migrada para Alpine.data() via
      alpine:init, garantindo que o componente esteja registrado
      ANTES de o Alpine processar o x-data="privacyCenter()" no DOM.
    • O bloco <script> foi movido para ANTES do markup Alpine.
    • SEM window.location.reload() — o app.blade.php escuta
      fp:consent-updated e chama gtag('consent','update',...).
--}}

{{-- Script ANTES do markup Alpine --}}
<script>
    document.addEventListener('alpine:init', function () {
        Alpine.data('privacyCenter', function () {
            return {
                status:        'pending',
                saving:        false,
                saved:         false,
                pendingStatus: null,
    
                init() {
                    this.status = this._readStatus();
    
                    // Sincroniza quando o cookie-banner atualiza (mesma aba)
                    window.addEventListener('fp:consent-updated', (e) => {
                        this.status  = e.detail;
                        this.saving  = false;
                        this.saved   = false;
                    });
                },
    
                update(newStatus) {
                    if (this.saving || this.status === newStatus) return;
    
                    this.saving        = true;
                    this.saved         = false;
                    this.pendingStatus = newStatus;
    
                    // 1. Persiste PRIMEIRO no cookie + localStorage
                    //    É essencial que isso aconteça ANTES do dispatchEvent,
                    //    pois ao recusar o listener vai fazer reload() imediatamente
                    //    e o servidor precisa ler o cookie atualizado.
                    this._persist(newStatus);
    
                    // 2. Limpa cookies de terceiros ao recusar
                    if (newStatus === 'rejected') {
                        this._clearThirdPartyCookies();
                    }
    
                    // 3. Atualiza estado visual (só visível se aceitar, pois recusar = reload)
                    this.status = newStatus;
                    this.saving = false;
                    this.saved  = newStatus === 'accepted'; // ao recusar não mostra confirmação
    
                    // 4. Notifica os layouts:
                    //    - ao aceitar → injeção dinâmica dos scripts (app/guest.blade.php)
                    //    - ao recusar → window.location.reload() (app/guest.blade.php)
                    //    O banner de cookies também fecha ao receber este evento.
                    window.dispatchEvent(
                        new CustomEvent('fp:consent-updated', { detail: newStatus })
                    );
                },
    
                _readStatus() {
                    try {
                        const ls = localStorage.getItem('fp_cookie_consent');
                        if (ls) return ls;
                    } catch (_) {}
                    const match = document.cookie.match(/(?:^|; )fp_cookie_consent=([^;]*)/);
                    return match ? decodeURIComponent(match[1]) : 'pending';
                },
    
                _persist(status) {
                    const exp = new Date();
                    exp.setFullYear(exp.getFullYear() + 1);
                    document.cookie = [
                        'fp_cookie_consent=' + status,
                        'expires=' + exp.toUTCString(),
                        'path=/',
                        'SameSite=Lax'
                    ].join('; ');
    
                    try { localStorage.setItem('fp_cookie_consent', status); } catch (_) {}
                },
    
                _clearThirdPartyCookies() {
                    const names = [
                        '_ga', '_gid', '_gat',
                        '_hjid', '_hjAbsoluteSessionInProgress',
                        '_hjFirstSeen', '_hjIncludedInSessionSample'
                    ];
                    const host = window.location.hostname.replace(/^www\./, '');
                    names.forEach(name => {
                        const exp = 'expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
                        document.cookie = `${name}=; ${exp}`;
                        document.cookie = `${name}=; ${exp} domain=${host};`;
                        document.cookie = `${name}=; ${exp} domain=.${host};`;
                    });
                }
            };
        });
    });
    </script>
    
    <div
        x-data="privacyCenter()"
        x-init="init()"
        class="max-w-3xl mx-auto bg-white rounded-2xl border border-slate-200/80
               shadow-sm p-6 sm:p-10 my-6 text-slate-700 leading-relaxed">
    
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight mb-1">
            Diretrizes de Privacidade e Segurança
        </h1>
        <p class="text-xs text-slate-400 mb-8">Última atualização: Maio de 2026</p>
    
        <div class="space-y-6 text-sm">
    
            <section>
                <h2 class="text-base font-bold text-slate-900 mb-2">1. Coleta e Uso de Dados</h2>
                <p>
                    O <strong>Finanças FP</strong> preza pela total transparência no tratamento dos seus dados,
                    em conformidade com a legislação brasileira vigente
                    (LGPD — Lei nº 13.709/18).
                </p>
            </section>
    
            <section>
                <h2 class="text-base font-bold text-slate-900 mb-2">2. Ferramentas de Monitoramento e Performance</h2>
                <p class="mb-3">
                    Para aprimorar a usabilidade e compreender o comportamento de navegação,
                    integramos de forma <strong>opcional</strong> os seguintes serviços:
                </p>
                <ul class="list-disc pl-5 space-y-2">
                    <li>
                        <strong>Google Analytics & Tag Manager:</strong>
                        Avaliação agregada e anônima de páginas visitadas e fluxos de uso.
                    </li>
                    <li>
                        <strong>Hotjar:</strong>
                        Mapas de calor e gravação de sessões (sem dados pessoais identificáveis)
                        para melhorias de UX.
                    </li>
                </ul>
                <p class="mt-3 text-xs text-slate-500">
                    Esses scripts são carregados <strong>exclusivamente</strong> após consentimento
                    explícito e nunca em sessões de usuários que escolherem recusar.
                </p>
            </section>
    
            <section>
                <h2 class="text-base font-bold text-slate-900 mb-2">3. Seus Direitos Legais</h2>
                <p>
                    Você pode revogar ou conceder suas permissões de telemetria a qualquer momento
                    no Centro de Preferências abaixo. Você também tem o direito à
                    <strong>Exclusão Definitiva de Conta</strong> e a todos os seus dados associados.
                </p>
            </section>
    
            {{-- ── Centro de Preferências ── --}}
            <div class="mt-8 p-5 bg-slate-50 border border-slate-200 rounded-2xl">
                <h3 class="text-sm font-bold text-slate-900 mb-1 flex items-center gap-2">
                    <span aria-hidden="true">🛡️</span>
                    Centro de Preferências de Privacidade
                </h3>
                <p class="text-xs text-slate-500 mb-5">
                    Suas alterações entram em vigor <strong>imediatamente</strong> e são
                    sincronizadas com o banner de cookies em todas as páginas.
                </p>
    
                {{-- Status atual --}}
                <div class="mb-5 flex items-center gap-2 text-xs">
                    <span class="font-semibold text-slate-600">Status atual:</span>
    
                    <template x-if="status === 'accepted'">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full
                                     font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Rastreamento opcional ativo
                        </span>
                    </template>
    
                    <template x-if="status === 'rejected'">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full
                                     font-bold bg-red-100 text-red-700 border border-red-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                            Rastreamento opcional bloqueado
                        </span>
                    </template>
    
                    <template x-if="status === 'pending'">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full
                                     font-bold bg-amber-100 text-amber-700 border border-amber-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                            Aguardando sua decisão
                        </span>
                    </template>
                </div>
    
                {{-- Botões de ação --}}
                <div class="flex flex-wrap items-center gap-3">
                    <button
                        type="button"
                        @click="update('rejected')"
                        :disabled="saving"
                        :class="status === 'rejected'
                            ? 'bg-red-600 text-white border-red-600 shadow-sm'
                            : 'bg-white text-slate-700 border-slate-300 hover:bg-slate-50'"
                        class="px-4 py-2.5 text-xs font-bold rounded-xl border transition-all
                               disabled:opacity-60 disabled:cursor-not-allowed focus:outline-none
                               focus:ring-2 focus:ring-offset-1 focus:ring-red-400">
                        <span x-show="!(saving && pendingStatus === 'rejected')">
                            Recusar Analytics & Hotjar
                        </span>
                        <span x-show="saving && pendingStatus === 'rejected'" x-cloak>
                            Salvando…
                        </span>
                    </button>
    
                    <button
                        type="button"
                        @click="update('accepted')"
                        :disabled="saving"
                        :class="status === 'accepted'
                            ? 'bg-indigo-600 text-white border-indigo-600 shadow-sm'
                            : 'bg-white text-slate-700 border-slate-300 hover:bg-slate-50'"
                        class="px-4 py-2.5 text-xs font-bold rounded-xl border transition-all
                               disabled:opacity-60 disabled:cursor-not-allowed focus:outline-none
                               focus:ring-2 focus:ring-offset-1 focus:ring-indigo-400">
                        <span x-show="!(saving && pendingStatus === 'accepted')">
                            Permitir Rastreamento de Performance
                        </span>
                        <span x-show="saving && pendingStatus === 'accepted'" x-cloak>
                            Salvando…
                        </span>
                    </button>
                </div>
    
                {{-- Confirmação inline sem reload --}}
                <p x-show="saved" x-cloak
                   x-transition:enter="transition ease-out duration-300"
                   x-transition:enter-start="opacity-0 translate-y-1"
                   x-transition:enter-end="opacity-100 translate-y-0"
                   class="mt-4 text-xs font-semibold text-emerald-600 flex items-center gap-1.5"
                   role="status">
                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                    </svg>
                    Preferência salva e aplicada imediatamente.
                </p>
            </div>
        </div>
    
        {{-- Botão de volta — somente em contexto não autenticado --}}
        @if(!Auth::check())
        <div class="mt-8 pt-6 border-t border-slate-100 flex justify-center">
            <a href="{{ route(request('origem', 'login')) }}"
               class="text-xs font-bold text-indigo-600 hover:text-indigo-700
                      flex items-center gap-1 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                </svg>
                Voltar para o Login
            </a>
        </div>
        @endif
    </div>