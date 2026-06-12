@include('errors.layout', [
    'code'        => 403,
    'title'       => 'Acesso não permitido',
    'description' => 'Você não tem permissão para acessar esta página. Se acredita que isso é um erro, entre em contato com o suporte.',
    'iconBg'      => 'rgba(230,57,70,.1)',
    'codeColor'   => '#E63946',
    'icon'        => '<svg fill="none" viewBox="0 0 24 24" stroke="#E63946" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>',
    'actions'     => '
        <a href="' . route('login') . '" class="err-btn-primary">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/></svg>
            Fazer login
        </a>
        <a href="' . url('/') . '" class="err-btn-ghost">Ir para o início</a>
    ',
])