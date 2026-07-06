# Roteiro Técnico — Figma ⇄ Código (Single Source of Truth)

## Visão geral do fluxo

```
Figma (Tokens Studio)  ⇄  GitHub (design-system/tokens/**/*.json)  →  Style Dictionary  →  CSS vars + Tailwind config  →  Blade views
```

O JSON em `design-system/tokens/` é a fonte da verdade. Tanto o design (via plugin) quanto o código (via build) leem/escrevem esse mesmo lugar — ninguém edita hex direto no Figma nem no CSS.

---

## Fase 0 — Consolidação no código (pré-requisito)

Antes de importar qualquer coisa no Figma, resolver as 3 inconsistências listadas em `COMPONENT_HIERARCHY.md` (azul/verde/vermelho duplicados). Se isso não for feito antes, o Figma vai herdar a bagunça e a sincronização vai "engessar" dois azuis diferentes como se fossem intencionais.

- [x] Decidir cor primária única (`#4361EE`) — aplicada sobrescrevendo a escala `indigo` do Tailwind em `tailwind.config.js`
- [x] Decidir verde de sucesso único (`emerald`) — `--fp-success`/`.fp-alert-success` realinhados em `resources/css/app.css`
- [x] Decidir vermelho de erro único (`#E63946`) — aplicado sobrescrevendo a escala `red` do Tailwind em `tailwind.config.js`

## Fase 1 — Estrutura de pastas (já criada neste repositório)

```
design-system/
└── tokens/
    ├── $metadata.json      # ordem dos sets (tokenSetOrder)
    ├── $themes.json         # tema "fp-light" e quais sets ficam ativos
    ├── core/                # primitivos: paleta bruta, escala tipográfica, spacing, radius, shadow
    │   ├── color.json
    │   ├── typography.json
    │   ├── spacing.json
    │   ├── radius.json
    │   └── shadow.json
    ├── semantic/            # papéis de uso, referenciam core (ex: action.primary.default → blue.500)
    │   ├── color.json
    │   └── typography.json
    └── component/           # props de componente, referenciam semantic (ex: button.primary.bg → action.primary.default)
        ├── button.json
        ├── input.json
        ├── card.json
        └── badge.json
```

Esse é exatamente o layout "multi-file" que o **Tokens Studio** exige para sync com Git (1 arquivo = 1 token set).

## Fase 2 — Configurar o Tokens Studio no Figma

1. Instalar o plugin **Tokens Studio for Figma**.
2. Criar os token sets com os mesmos nomes/caminhos da Fase 1 (`core/color`, `core/typography`, ..., `component/badge`).
3. Importar o conteúdo de cada `.json` deste repositório no set correspondente (Tokens Studio → *Import* → colar o JSON).
4. Conferir o tema `fp-light` em **Themes** (já vem descrito em `$themes.json`).
5. Aplicar os tokens nos componentes do Figma (fills, text styles, effects, radius) — nunca hex direto.

## Fase 3 — Sync bidirecional com GitHub

1. Criar um **Personal Access Token** no GitHub com escopo `repo`.
2. No Tokens Studio: **Settings → Sync Providers → GitHub**.
   - Repositório: `fernandopinhel/sistema-financeiro`
   - Branch: `design-tokens` (branch dedicada — evita que qualquer push do Figma vá direto pra `main`)
   - Caminho: `design-system/tokens`
   - Modo: **Multi-file** (um arquivo por token set, como já está estruturado)
3. **Push** (Figma → GitHub): designer altera um token no Figma → Tokens Studio commita o JSON atualizado na branch `design-tokens`.
4. **Pull** (GitHub → Figma): se um dev editar o JSON direto (ex: durante a Fase 0), o designer clica em "Pull" no plugin e os tokens do Figma se atualizam.
5. Abrir PR de `design-tokens` → `main` sempre que uma leva de mudanças estiver pronta para ir pra produção — dá um ponto de revisão humana antes do CSS mudar de verdade.

## Fase 4 — Consumo no código (JSON → CSS/Tailwind)

1. Adicionar **Style Dictionary** como dependência de build (`npm i -D style-dictionary`).
2. Criar `design-system/style-dictionary.config.js` apontando para `design-system/tokens/**/*.json`, com dois outputs:
   - `resources/css/tokens.css` — gera as custom properties `--fp-*` automaticamente (substitui o bloco `:root` escrito à mão em `app.css`).
   - `design-system/tailwind-tokens.js` — objeto JS importado dentro de `tailwind.config.js` (`theme.extend.colors`, `spacing`, `borderRadius`, `boxShadow`), substituindo os valores hard-coded atuais (`fp-accent`, `fp-sec`, etc.).
3. Adicionar script no `package.json`:
   ```json
   "tokens:build": "style-dictionary build --config design-system/style-dictionary.config.js"
   ```
4. Encadear no build existente: `"build": "npm run tokens:build && vite build"`.

## Fase 5 — Automação (CI, opcional)

- GitHub Action disparada em push na branch `design-tokens`: roda `npm run tokens:build`, valida que o CSS/Tailwind gerados compilam, e abre PR automático contra `main` com o diff pronto para revisão.
- Assim nenhuma alteração de design vai para produção sem alguém aprovar o PR — o "propagar via GitHub/Figma API" fica automático até o ponto de revisão, não além dele.

## Fase 6 — Componentização no código (pré-requisito para Code Connect)

Hoje os componentes (botão, input, badge) são HTML/Tailwind copiado e colado em cada view — não existem como Blade components reutilizáveis. Para o Figma e o código realmente "conversarem" 1:1 (via Figma Code Connect), recomenda-se antes:

- Criar `resources/views/components/button.blade.php`, `input.blade.php`, `badge.blade.php` etc. como *anonymous components* do Blade, usando as classes `.fp-*` já existentes.
- Substituir os usos inline (`<button class="...">`) por `<x-button variant="primary">`.
- Só então mapear cada componente Figma → componente Blade via Code Connect, com nomes de variant idênticos dos dois lados (`variant="primary|secondary|ghost|danger"`, `size="sm|md"`).

Isso é opcional para o DS funcionar, mas é o que fecha o ciclo "documentação viva" — sem isso, o Figma documenta um componente que no código ainda é copy-paste.