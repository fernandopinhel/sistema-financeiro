@include('errors.layout', [
    'code'        => 400,
    'title'       => 'Requisição inválida',
    'description' => 'Os dados enviados estão incompletos ou mal formatados. Verifique as informações e tente novamente.',
    'iconBg'      => 'rgba(244,162,97,.12)',
    'codeColor'   => '#F4A261',
    'icon'        => '<svg fill="none" viewBox="0 0 24 24" stroke="#F4A261" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>',
    'actions'     => '
        <a href="javascript:history.back()" class="err-btn-primary">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
            Voltar
        </a>
        <a href="' . url('/') . '" class="err-btn-ghost">Ir para o início</a>
    ',
])