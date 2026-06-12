{{--
    resources/views/components/cookie-banner.blade.php

    LÓGICA DE EXIBIÇÃO:
    ─────────────────────────────────────────────────────────────
    • Aparece SEMPRE que fp_cookie_consent NÃO estiver definido
    • Some imediatamente após Aceitar ou Recusar
    • Não reaparece enquanto o cookie existir (accepted ou rejected)

    INTEGRAÇÃO GTM / GA4 / HOTJAR:
    ─────────────────────────────────────────────────────────────
    • Ao ACEITAR → persiste cookie → dispara fp:consent-updated('accepted')
      → app/guest.blade escuta → injeta GA4 + Hotjar dinamicamente (sem reload)
      Scripts ainda não existiam na sessão → injeção dinâmica é suficiente.

    • Ao RECUSAR → limpa cookies de terceiros → persiste cookie
      → dispara fp:consent-updated('rejected')
      → app/guest.blade escuta → window.location.reload()
      Na próxima request, o servidor lê cookie='rejected' e NÃO injeta
      nenhum script de rastreamento (GTM, GA4, Hotjar).

    POR QUE RELOAD AO RECUSAR?
    gtag('consent','update') só afeta hits futuros — não desfaz scripts
    que já foram carregados e já dispararam no page load atual.
    O reload é a única forma garantida de parar coleta já iniciada (LGPD).

    REGISTRO ALPINE:
    ─────────────────────────────────────────────────────────────
    • A função cookieBanner() é registrada via Alpine.data() no evento
      alpine:init, garantindo que o componente existe ANTES de o Alpine
      processar o x-data="cookieBanner()" no DOM.
--}}

{{-- Script ANTES do markup Alpine para garantir que a função exista --}}
<script>
    /**
     * Registra o componente Alpine antes que o Alpine processe o DOM.
     * alpine:init dispara antes de Alpine varrer os x-data do documento.
     */
    document.addEventListener('alpine:init', function () {
        Alpine.data('cookieBanner', function () {
            return {
                open: false,
    
                init() {
                    const decision = this._readDecision();
    
                    if (!decision) {
                        // Pequeno delay para o layout carregar antes de animar o banner
                        setTimeout(() => { this.open = true; }, 800);
                    }
    
                    // Escuta mudanças feitas na página de Privacidade (mesma aba)
                    window.addEventListener('fp:consent-updated', () => {
                        this.open = false;
                    });
                },
    
                accept() {
                    this._save('accepted');
                    this.open = false;
    
                    window.dispatchEvent(
                        new CustomEvent('fp:consent-updated', { detail: 'accepted' })
                    );
                },
    
                decline() {
                    // 1. Limpa cookies de terceiros ANTES de tudo
                    this._clearThirdPartyCookies();
    
                    // 2. Persiste 'rejected' ANTES do dispatchEvent.
                    //    O listener em app/guest.blade.php chama window.location.reload()
                    //    e o servidor precisa ler o cookie atualizado na próxima request.
                    this._save('rejected');
    
                    // 3. Fecha o banner (evita flicker antes do reload)
                    this.open = false;
    
                    // 4. Notifica o layout → reload() é chamado lá
                    window.dispatchEvent(
                        new CustomEvent('fp:consent-updated', { detail: 'rejected' })
                    );
                },
    
                _readDecision() {
                    try {
                        const ls = localStorage.getItem('fp_cookie_consent');
                        if (ls === 'accepted' || ls === 'rejected') return ls;
                    } catch (_) {}
    
                    const match = document.cookie.match(
                        /(?:^|;\s*)fp_cookie_consent=([^;]+)/
                    );
                    if (match) {
                        const val = decodeURIComponent(match[1]);
                        if (val === 'accepted' || val === 'rejected') return val;
                    }
    
                    return null;
                },
    
                _save(status) {
                    const exp = new Date();
                    exp.setFullYear(exp.getFullYear() + 1);
    
                    document.cookie = [
                        `fp_cookie_consent=${status}`,
                        `expires=${exp.toUTCString()}`,
                        'path=/',
                        'SameSite=Lax'
                    ].join('; ');
    
                    try {
                        localStorage.setItem('fp_cookie_consent', status);
                    } catch (_) {}
                },
    
                _clearThirdPartyCookies() {
                    const names = [
                        '_ga', '_gid', '_gat',
                        '_hjid', '_hjAbsoluteSessionInProgress',
                        '_hjFirstSeen', '_hjIncludedInSessionSample',
                        '_hjSessionUser', '_hjSession'
                    ];
                    const host    = window.location.hostname;
                    const noWww   = host.replace(/^www\./, '');
                    const expired = 'expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
    
                    names.forEach(name => {
                        document.cookie = `${name}=; ${expired}`;
                        document.cookie = `${name}=; ${expired} domain=${host};`;
                        document.cookie = `${name}=; ${expired} domain=${noWww};`;
                        document.cookie = `${name}=; ${expired} domain=.${noWww};`;
                    });
                }
            };
        });
    });
    </script>
    
    <div
        x-data="cookieBanner()"
        x-init="init()"
        x-show="open"
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-4"
        class="fixed bottom-0 inset-x-0 z-[9999]
               bg-white border-t border-slate-200 shadow-2xl
               py-5 px-4 sm:px-6"
        role="dialog"
        aria-modal="true"
        aria-label="Aviso de consentimento de cookies">
    
        <div class="max-w-7xl mx-auto
                    flex flex-col md:flex-row
                    items-start md:items-center
                    justify-between gap-4">
    
            {{-- Texto informativo --}}
            <div class="flex-1 text-xs sm:text-sm leading-relaxed text-slate-600">
                <p class="font-bold text-slate-900 mb-1">🍪 Privacidade &amp; Cookies</p>
                <p>
                    Usamos cookies essenciais para o funcionamento do sistema e, com sua
                    permissão, ferramentas como
                    <strong>Google Analytics, Google Tag Manager e Hotjar</strong>
                    para melhorar sua experiência.
                    <a href="{{ route('privacidade') }}"
                       class="text-indigo-600 underline underline-offset-2 font-medium
                              hover:text-indigo-700 transition-colors">
                        Saiba mais
                    </a>.
                </p>
            </div>
    
            {{-- Ações --}}
            <div class="flex items-center gap-2.5 flex-shrink-0 w-full md:w-auto">
                <button
                    @click="decline()"
                    type="button"
                    class="flex-1 md:flex-none px-5 py-2.5 text-xs font-bold
                           text-slate-700 bg-slate-100 hover:bg-slate-200
                           rounded-xl transition-colors
                           focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-1">
                    Recusar
                </button>
                <button
                    @click="accept()"
                    type="button"
                    class="flex-1 md:flex-none px-5 py-2.5 text-xs font-bold
                           text-white bg-indigo-600 hover:bg-indigo-700
                           rounded-xl shadow-sm transition-colors
                           focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-1">
                    Aceitar Todos
                </button>
            </div>
        </div>
    </div>