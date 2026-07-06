# DS Builder (plugin interno do Figma)

Plugin com uma UI de dois botões que constrói, cada um, uma página do arquivo
Figma a partir dos valores em `design-system/tokens/` e dos SVGs reais das
views Blade, copiados diretamente para `code.js` (a Figma Plugin API não lê
arquivos do disco):

- **Construir Foundations** — cores, tipografia, espaçamento, radius, sombra.
- **Construir Components** — Icons (SVG reais importados como vetor), Button
  (todas as variantes/tamanhos/estados), Icon Button, Input, Select (fechado
  e aberto), Label, Toggle, Avatar, Divider, Link, Color Dot, Badge, Alert,
  Card, Dropdown Menu (usuário), Chart (mockup estático) e os organismos
  Sidebar e Topbar — tudo como Components de verdade do Figma (aparecem em
  Assets).

## Instalar no Figma

1. Abra o arquivo `Financas_FP` no **app desktop do Figma** (plugins de
   desenvolvimento não rodam na versão web).
2. Menu → **Plugins** → **Development** → **Import plugin from manifest…**
3. Selecione o arquivo `design-system/figma-plugin/manifest.json` deste
   repositório.
4. Menu → **Plugins** → **Development** → **Finanças FP — DS Builder** → Run.
5. Na janela do plugin, clique em **"Construir Foundations"** e/ou
   **"Construir Components"**.

Se você editar `code.js` depois de já ter importado o plugin, não precisa
reimportar — só rodar de novo (Figma recarrega o arquivo a cada execução).

## O que ele faz

- Cria (ou reaproveita) as páginas **Foundations** e **Components**.
- Cria Color Styles, Text Styles e Effect Styles **pelo nome exato do token**
  (ex: `action/primary/default`, `heading/page`, `shadow/card`) — se um style
  com esse nome já existir (como os que você já criou manualmente via Tokens
  Studio), ele reaproveita em vez de duplicar.
- Importa os ícones **reais** do app (copiados de `layouts/app.blade.php` e
  `transactions/index.blade.php`) como vetores via `figma.createNodeFromSvg`,
  não formas genéricas — home, transações, categorias, recorrentes,
  privacidade, busca, editar, duplicar, excluir, mais, fechar, seta de
  receita/despesa, sair, usuário.
- Os componentes (`Button/primary/md/default`, `Input/focus`, `Icon/home`,
  `Sidebar/default`, etc.) são `ComponentNode`s reais — aparecem no painel
  **Assets** desta biblioteca, agrupados pela barra `/` no nome, prontos pra
  arrastar/instanciar.

## Limitações

- Só roda dentro do Figma desktop — não existe forma remota de disparar isso.
- Rodar o mesmo botão **duas vezes** duplica os frames/componentes visuais —
  os *estilos* não duplicam (são reaproveitados pelo nome), mas os desenhos
  sim. Pra rebuildar do zero, apague o conteúdo da página antes de rodar de
  novo.
- Se os nomes dos styles criados manualmente no Tokens Studio não baterem
  exatamente com os daqui (ex: maiúscula/minúscula diferente), o plugin cria
  um style novo em paralelo em vez de reaproveitar — nesse caso, apague o
  duplicado que não quiser manter.
- O **Chart** é um mockup estático (barras com alturas fixas) — não é um
  componente de design system de verdade, é só uma referência visual do
  gráfico "Gastos por Mês" do dashboard. Fica marcado no próprio canvas.
- Estados como `hover`/`focus` são representações visuais estáticas (o
  componente "parece" com hover), não states reais do Figma acionados por
  interação — para isso seria preciso configurar variant properties
  interativas manualmente depois.
- Ainda não gera moléculas/organismos mais específicos (Form Field completo
  com validação, Radio Card, Empty State, Pagination, Dashboard Summary
  inteiro, Confirm Modal) — é um próximo passo natural se fizer falta.
