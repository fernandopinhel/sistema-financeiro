# Hierarquia de Componentes — Finanças FP

Levantamento feito a partir do uso real em `resources/views/**/*.blade.php` e `resources/css/app.css` (não é uma lista genérica — cada item abaixo corresponde a um padrão que já existe hoje na aplicação).

## Átomos

| Componente | Variantes | Estados | Origem no código |
|---|---|---|---|
| **Button** | `primary` · `secondary` · `ghost` · `danger` | default, hover, focus, disabled, loading | `.fp-btn`, `.fp-btn-primary/secondary/ghost/danger`, `.fp-btn-sm`, `.fp-btn-full` |
| **Input (texto)** | default · com ícone à esquerda | default, focus, error, disabled | `.fp-input`, `.fp-input-icon` |
| **Select** | default · `sm` (topbar/dashboard) | default, focus, disabled | `.fp-select`, `.fp-select-sm` |
| **Label** | field label | — | `.fp-label` |
| **Toggle Switch** | on/off | default, focus | usado em "Lembrete ativo" (recorrentes) |
| **Avatar** | com imagem · com iniciais (fallback) | — | `.fp-user-avatar`, `.fp-user-initials` |
| **Icon** | stroke 16/18/20/24px | — | SVGs inline heroicons-style, `stroke-width="2"` |
| **Divider** | horizontal | — | `.fp-dropdown hr` |
| **Link** | default · `sm` | default, hover | `.fp-link`, `.fp-link-sm` |
| **Color Dot** | cor dinâmica (categoria) | — | bolinha de cor ao lado do nome da categoria |

## Moléculas

| Componente | Composição | Variantes/Estados | Origem no código |
|---|---|---|---|
| **Form Field** | Label + Input/Select + mensagem de erro | error visível/oculto | padrão repetido em todos os `create`/`edit` |
| **Radio Card (seletor de Tipo)** | radio nativo (`sr-only`) + ícone + título + subtítulo, estilizado como card | `income` (verde/emerald) · `expense` (vermelho/red), unchecked/checked | bloco "Receita / Despesa" em transações e recorrentes |
| **Alert / Banner** | ícone + texto | `success` · `error` | `.fp-alert-success`, `.fp-alert-error` |
| **Nav Item** | ícone + label | default, hover, active | `.fp-nav a`, classe `.active` |
| **Dropdown Menu Item** | ícone + label | default, hover, `danger` (ex: Sair) | `.fp-dropdown a/button`, `.danger` |
| **Search/Filter Input** | ícone de busca + input | default, focus | `.fp-cat-search`, filtro de categorias no dashboard |
| **Empty State** | ícone + mensagem + opcional CTA | — | listas vazias (transações/categorias/recorrentes) |
| **Modal Header** | título + botão fechar (X) | — | modal "Nova Categoria", modais de confirmação de exclusão |
| **Pagination Item** | número/seta | default, active, disabled | paginação de Categorias/Transações |
| **Type Badge** | pill colorida (Visível/Oculto no dashboard) | `visible` · `hidden` | coluna "Dashboard" na listagem de categorias |

## Organismos

| Componente | Composição | Origem no código |
|---|---|---|
| **Sidebar** | logo + seções de navegação + menu do usuário | `.fp-sidebar`, `layouts/app.blade.php` |
| **Topbar** | título da página + ações (filtros, export) | `.fp-topbar` |
| **User Menu Dropdown** | Avatar (trigger) + Dropdown Menu | `.fp-user-btn` + `.fp-dropdown` |
| **Form Card** | Card + múltiplos Form Fields + botões de ação | telas de criar/editar transação e recorrente |
| **List/Table Row** | Color Dot + nome + badge + ações (editar/duplicar/excluir) | listagem de categorias e transações |
| **Confirm Modal** | Modal Header + texto + botões (cancelar/confirmar) | exclusão de transação/categoria/recorrente |
| **Cookie Consent Banner** | texto + botões aceitar/recusar | `components/cookie-banner.blade.php` |
| **Dashboard Summary** | cards de resumo (receita/despesa/saldo) + gráfico + filtro de categorias | `dashboard.blade.php` |
| **Type Selector** | 2 Radio Cards lado a lado (Receita/Despesa) | usado em transações e recorrentes |

## Templates / Páginas

| Página | Organismos usados |
|---|---|
| **Guest Layout** (login, registro, recuperar senha) | Guest Logo + Form Card + Alert |
| **App Layout** (autenticado) | Sidebar + Topbar + conteúdo |
| **Dashboard** | App Layout + Dashboard Summary |
| **Lista** (transações/categorias/recorrentes) | App Layout + List/Table Row (N) + Pagination + Empty State + Confirm Modal |
| **Formulário** (criar/editar) | App Layout + Form Card + Type Selector (quando aplicável) |

---

## Achados de inconsistência (corrigir antes de portar pro Figma)

1. **Azul primário duplicado.** O sidebar/logo usa `--fp-accent` (`#4361EE`), mas a maioria dos botões inline nas telas de formulário usa `indigo-600` do Tailwind (`#4F46E5`) — duas cores de marca "azul primário" diferentes convivendo. Recomendação: migrar tudo para o token `action.primary.default`.
2. **Verde de sucesso duplicado.** Existe `--fp-success` (`#2EC4B6`, teal) definido no CSS mas não usado em nenhuma view; as views usam `emerald-*` do Tailwind. Recomendação: adotar `emerald` como fonte da verdade e remover `--fp-success` ou realinhar seu valor.
3. **Vermelho de erro duplicado.** Mesma situação: `--fp-danger` (`#E63946`) nos componentes `.fp-*`, mas `red-*` do Tailwind nas views inline.
4. **Dois sistemas de estilo coexistindo.** Um baseado em classes semânticas `.fp-*` + CSS vars (layout, auth, sidebar) e outro em utilitárias Tailwind cruas (`slate`/`indigo`/`emerald`/`red`) usadas diretamente nas telas de conteúdo (transações, categorias, recorrentes). O DS deve unificar os dois em uma única fonte de tokens.