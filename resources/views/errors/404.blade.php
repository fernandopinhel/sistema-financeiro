@include('errors.layout', [
    'code'        => 404,
    'title'       => 'Página não encontrada',
    'description' => 'A página que você está procurando não existe ou foi movida. Verifique o endereço ou navegue pelo menu.',
    'iconBg'      => 'rgba(67,97,238,.1)',
    'codeColor'   => '#4361EE',
    'icon'        => '<svg fill="none" viewBox="0 0 24 24" stroke="#4361EE" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15.182 16.318A4.486 4.486 0 0012.016 15a4.486 4.486 0 00-3.198 1.318M21 12a9 9 0 11-18 0 9 9 0 0118 0zM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75zm-.375 0h.008v.015h-.008V9.75zm5.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75zm-.375 0h.008v.015h-.008V9.75z"/></svg>',
    'actions'     => '
        <a href="javascript:history.back()" class="err-btn-primary">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
            Voltar
        </a>
        <a href="' . url('/') . '" class="err-btn-ghost">Ir para o início</a>
    ',
])