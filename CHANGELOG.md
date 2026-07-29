# Changelog

Todas as mudanças relevantes deste projeto são documentadas aqui.

## 2026-07-29

### PWA (instalável no Android/iOS)

- App instalável na tela de início (manifest + service worker via `vite-plugin-pwa`), com ícones próprios (normal e maskable) e cache dos assets estáticos (JS/CSS/fontes) — páginas HTML nunca ficam em cache, pra nunca mostrar dados financeiros desatualizados offline.
- Item **"Instalar App"** no menu do usuário: no Android/Chrome/Edge dispara o prompt nativo de instalação; no iOS/Safari (que não tem instalação automática) abre um modal com o passo a passo manual (Compartilhar → Adicionar à Tela de Início).

### Login com Face ID / Biometria (WebAuthn/Passkeys)

- Novo método de login opcional via `laravel/passkeys` (Face ID, Touch ID, biometria Android, Windows Hello) — **aditivo**, a senha continua funcionando normalmente.
- Tela de Perfil: seção para cadastrar, listar e remover dispositivos com passkey (com modal de confirmação de remoção, no mesmo padrão visual do modal de exclusão de conta).
- Mensagens de erro do processo (dispositivo não suportado, cancelado, etc.) traduzidas pra português.

### Dashboard: reorganização arrastável (drag-and-drop)

- Os cards de resumo (Receitas/Despesas/Saldo) e os blocos de Gráfico/Categorias agora podem ser reordenados arrastando (`@alpinejs/sort`), com suporte a toque no celular.
- A ordem escolhida é salva no navegador (localStorage) e mantida mesmo depois de trocar o filtro de mês/ano.

### Responsividade

- Paginação (Transações, Categorias, Recorrentes): versão compacta no mobile (Anterior / página atual / Próximo), versão completa a partir de tablet/desktop — antes o texto quebrava linha a linha em telas estreitas.
- Dashboard: filtros de ano/mês e botões de exportação reorganizados pra não sobrepor/cortar em larguras intermediárias; cards de resumo com breakpoint intermediário (2 colunas) pra não espremer valores grandes contra o ícone.
- Relatório de exportação em PDF: layout responsivo só na tela (antes de imprimir/salvar) — tabela com rolagem horizontal, cabeçalho e cards empilhados no mobile. O resultado impresso/PDF em si não foi alterado.

### Outros ajustes

- Link "Privacidade" removido do menu lateral (continua acessível pelo dropdown do perfil, em "Privacidade & Cookies").
- Corrigido o botão "Excel" do dashboard: faltava abrir em nova aba/contexto (`target="_blank"`), o que travava o usuário sem opção de voltar ao usar o app instalado (PWA) no celular — agora se comporta igual ao botão de PDF.
