# Design System — Finanças FP

Proposta de Design System extraída do estado real do código (`resources/css/app.css` + `resources/views/**/*.blade.php`), estruturada para uso no Figma via **Tokens Studio** com sincronização bidirecional pelo GitHub.

## O que tem aqui

| Arquivo | Conteúdo |
|---|---|
| `tokens/` | Tokens em formato Tokens Studio (Core → Semantic → Component), prontos para importar no plugin |
| `COMPONENT_HIERARCHY.md` | Inventário de átomos, moléculas, organismos e páginas, com variantes/estados reais e origem no código |
| `FIGMA_SYNC_ROADMAP.md` | Roteiro técnico passo a passo: config do Tokens Studio, sync com GitHub, build para CSS/Tailwind, automação em CI |

## Principais achados

1. **Dois sistemas de estilo coexistem hoje**: classes semânticas `.fp-*` (layout, sidebar, autenticação) e utilitárias Tailwind cruas — `slate`/`indigo`/`emerald`/`red` (telas de transações, categorias, recorrentes). O DS unifica os dois numa única fonte de tokens.
2. **3 inconsistências de cor de marca** encontradas (azul primário, verde de sucesso e vermelho de erro cada um com 2 valores diferentes rodando em paralelo) — detalhadas em `COMPONENT_HIERARCHY.md`, com recomendação de qual valor manter em cada caso.
3. O projeto já tem, sem saber, um proto-design-system: as classes `.fp-btn`, `.fp-input`, `.fp-card`, `.fp-alert` em `app.css` já seguem uma lógica de variantes/tokens (`--fp-accent`, `--fp-radius`, `--fp-shadow`). O trabalho aqui é formalizar isso, resolver as duplicidades, e espelhar no Figma — não começar do zero.

## Plano de documentação do arquivo Figma

Estrutura de páginas recomendada dentro do arquivo Figma:

1. **Cover** — nome do DS, versão, link para este repositório/pasta, changelog resumido.
2. **Foundations** — paleta (core + semantic, cada swatch anotado com o nome do token), escala tipográfica (specimen com cada estilo de texto), escala de espaçamento (régua visual), escala de radius, escala de sombra/elevação, grid de ícones.
3. **Components** — organizados por Átomos / Moléculas / Organismos (mesma hierarquia de `COMPONENT_HIERARCHY.md`); cada componente em um frame mostrando todas as variantes × estados lado a lado, anotado com o token que cada propriedade usa.
4. **Patterns** — telas completas montadas a partir dos componentes (Login, Dashboard, Lista, Formulário), servindo de referência de handoff.
5. **Changelog** — log datado de mudanças de token/componente, correlacionado com commits/tags da pasta `design-system/tokens` no GitHub.

### Guia de uso por componente (dentro da página Components)

Para cada componente, incluir:
- **Quando usar / quando não usar** (ex: `Button danger` só para ações destrutivas irreversíveis, nunca para "Cancelar").
- **Anatomia**: quais partes são obrigatórias vs. opcionais (ex: ícone no Alert é opcional).
- **Estados de acessibilidade**: foco visível, contraste mínimo (o app já usa `focus:ring-3` — documentar esse padrão como obrigatório em todo componente interativo).
- **Snippet de código equivalente**: a classe Blade/CSS que implementa aquele componente hoje (`.fp-btn.fp-btn-primary`), para o dev não precisar adivinhar.

## Próximos passos sugeridos

Ver `FIGMA_SYNC_ROADMAP.md` para o passo a passo técnico completo. Resumo da ordem recomendada:

1. Resolver as 3 inconsistências de cor no código (Fase 0).
2. Importar `tokens/` no Tokens Studio e configurar o tema `fp-light` (Fase 1-2).
3. Conectar o Sync com GitHub na branch `design-tokens` (Fase 3).
4. Montar o pipeline Style Dictionary → CSS/Tailwind (Fase 4).
5. (Opcional) Automatizar em CI e componentizar o Blade para Code Connect (Fases 5-6).