# 💰 Finanças FP

> Sistema web de gestão financeira pessoal — controle de receitas, despesas e categorias com dashboard analítico, exportação de relatórios e rastreamento de comportamento via GTM/GA4/Hotjar.

---

## 📌 Sobre o projeto

O **Finanças FP** é uma aplicação web construída com Laravel 12 + Alpine.js que permite ao usuário registrar e acompanhar suas finanças pessoais de forma simples e visual. O sistema inclui:

- Dashboard com gráfico de gastos mensais e cards de resumo
- CRUD completo de transações (receitas e despesas)
- Categorias com cores personalizáveis e indicador de dashboard
- Contas recorrentes (modelos de receita/despesa mensal com lembrete de pendência no dashboard)
- Exportação de relatórios em **Excel (CSV)** e **PDF**
- Autenticação completa com reCAPTCHA v2 e login social via **Google (OAuth)**
- Upload de avatar de perfil
- Banner de consentimento de cookies (LGPD)
- Rastreamento via **Google Tag Manager**, **Google Analytics 4** e **Hotjar**
- E-mails transacionais com branding personalizado

---

## ⚙️ Tecnologias utilizadas

| Camada | Tecnologia |
|---|---|
| Back-end | PHP 8.2 · Laravel 12 |
| Front-end | Alpine.js 3 · Tailwind CSS 3 |
| Build | Vite 6 · Laravel Vite Plugin |
| Banco de dados | MySQL 8 |
| Autenticação | Laravel Breeze (customizado) · Laravel Socialite (login Google) |
| Anti-bot | Google reCAPTCHA v2 |
| Analytics | Google Tag Manager · GA4 · Hotjar |
| E-mail | SMTP (Gmail / Mailtrap) |
| Fontes | DM Sans · DM Mono (Google Fonts) |

---

## 🚀 Rodando o projeto localmente

### Pré-requisitos

- PHP >= 8.2 com extensões: `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `curl`
- Composer >= 2.x
- Node.js >= 18.x + npm
- MySQL >= 8.0

### 1. Clone o repositório

```bash
git clone https://github.com/fernandopinhel/sistema-financeiro.git
cd sistema-financeiro
```

### 2. Instale as dependências

```bash
composer install
npm install
```

### 3. Configure o ambiente

```bash
cp .env.example .env
php artisan key:generate
```

Edite o `.env` com suas credenciais locais (banco de dados, e-mail, etc.).

### 4. Configure o banco de dados

```bash
php artisan migrate
```

### 5. Crie o link de storage (avatars)

```bash
php artisan storage:link
```

### 6. Compile os assets

```bash
# Desenvolvimento (hot reload)
npm run dev

# Produção
npm run build
```

### 7. Inicie o servidor

```bash
php artisan serve
```

Acesse: **http://localhost:8000**

---

## 🔑 Variáveis de ambiente — `.env`

```env
APP_NAME="Finanças FP"
APP_ENV=local
APP_KEY=             # gerado com php artisan key:generate
APP_DEBUG=true
APP_URL=http://localhost

# ── Banco de dados ──────────────────────────────────
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=financas
DB_USERNAME=root
DB_PASSWORD=

# ── E-mail ──────────────────────────────────────────
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=465
MAIL_USERNAME=seu@email.com
MAIL_PASSWORD=sua_senha_de_app
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@seudominio.com"
MAIL_FROM_NAME="${APP_NAME}"

# ── reCAPTCHA v2 ────────────────────────────────────
# Chaves de TESTE (dev): qualquer resposta é aceita
RECAPTCHA_SITE_KEY=6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI
RECAPTCHA_SECRET_KEY=6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe
# Produção: https://www.google.com/recaptcha/admin/create

# ── Analytics (opcional) ────────────────────────────
GTM_ID=GTM-XXXXXXX
GOOGLE_ANALYTICS_ID=G-XXXXXXXXXX
HOTJAR_ID=
HOTJAR_SV=6
```

---

## 🛠️ Configuração do Google Tag Manager, GA4 e Hotjar

### Por que usar GTM?

O **Google Tag Manager** é o orquestrador central. Em vez de adicionar múltiplos snippets de terceiros diretamente no HTML, o GTM permite gerenciar todas as tags (GA4, Hotjar, Meta Pixel, etc.) por um único contêiner — sem tocar no código da aplicação.

### Arquitetura implementada (Consent Mode v2)

```
Primeira visita
    │
    ▼
gtag('consent', 'default', { analytics_storage: 'denied' })  ← antes do GTM
    │
    ▼
GTM carrega normalmente (tag assistant consegue detectar)
    │
    ▼
Banner de cookies aparece após 1,2s
    │
    ├─ Usuário ACEITA → gtag('consent', 'update', { analytics_storage: 'granted' })
    │                 → Hotjar injetado dinamicamente
    │                 → GA4 começa a coletar dados
    │
    └─ Usuário RECUSA → analytics_storage permanece 'denied'
                      → GA4 opera em modo anônimo (sem PII)
                      → Hotjar não é carregado
```

> **Por que o GTM não estava funcionando antes?**
> O snippet do GTM só era injetado se `fp_cookie_consent === 'accepted'`. Na primeira visita, o cookie não existe — portanto o GTM nunca carregava e o Tag Assistant reportava "tag não encontrada". A solução correta é: **GTM sempre carrega**, mas o consentimento controla o que o GTM pode fazer.

### Passo a passo — configurar na sua conta

#### Google Tag Manager

1. Acesse [tagmanager.google.com](https://tagmanager.google.com)
2. Crie uma **conta** e um **contêiner** do tipo Web
3. Copie o **ID do contêiner** (formato `GTM-XXXXXXX`)
4. Adicione ao `.env`:
   ```env
   GTM_ID=GTM-XXXXXXX
   ```
5. Dentro do GTM, crie uma tag **Google Analytics: GA4 Configuration** apontando para seu `G-XXXXXXXXXX`
6. Configure o **Consentimento**: na tag GA4, em *Configurações avançadas > Consentimento*, marque `analytics_storage`

#### Google Analytics 4

1. Acesse [analytics.google.com](https://analytics.google.com)
2. Crie uma propriedade GA4 e um **fluxo de dados Web**
3. Copie o **Measurement ID** (formato `G-XXXXXXXXXX`)
4. Adicione ao `.env`:
   ```env
   GOOGLE_ANALYTICS_ID=G-XXXXXXXXXX
   ```
5. **Se usar GTM**: configure a tag GA4 dentro do GTM (o `.env` `GOOGLE_ANALYTICS_ID` vira fallback quando GTM não estiver configurado)

#### Hotjar

1. Acesse [hotjar.com](https://www.hotjar.com) e crie um site
2. Copie o **Site ID** (número inteiro, ex: `1234567`)
3. Adicione ao `.env`:
   ```env
   HOTJAR_ID=1234567
   HOTJAR_SV=6
   ```
4. O Hotjar é carregado server-side quando aceito, e também injetado dinamicamente via JS quando o usuário aceita no banner

#### Verificando com Tag Assistant

1. Instale a extensão [Tag Assistant](https://chrome.google.com/webstore/detail/tag-assistant-legacy-by-g/kejamtimkhoiaagdeadpomejhfhpkaba)
2. Acesse seu site
3. Clique em **Connect** no Tag Assistant
4. O GTM deve aparecer **imediatamente** (antes de qualquer interação com cookies)
5. Após aceitar o banner, o GA4 deve começar a disparar eventos

---

## 🔒 Privacidade e consentimento de cookies (LGPD)

Este sistema implementa uma arquitetura de consentimento em conformidade com a **Lei Geral de Proteção de Dados (LGPD — Lei 13.709/18)** e alinhada ao **GDPR** europeu.

### Princípios aplicados

| Princípio | Implementação |
|---|---|
| **Opt-in** | Analytics negado por padrão; usuário deve aceitar ativamente |
| **Granularidade** | Cookies essenciais sempre ativos; analíticos são opcionais |
| **Revogação** | Página de Privacidade permite alterar o consentimento a qualquer momento |
| **Transparência** | Banner informa quais ferramentas serão ativadas |
| **Minimização** | `ads_data_redaction: true` ativado; sem coleta de PII |

### Como o consentimento funciona

```
Cookie: fp_cookie_consent
  ├─ 'accepted'  → GTM com analytics_storage: granted + Hotjar ativo
  ├─ 'rejected'  → GTM com analytics_storage: denied + Hotjar inativo
  └─ null/outro  → Mesmo comportamento de 'rejected' (opt-in por padrão)
```

### Cookies utilizados

| Cookie | Finalidade | Duração | Tipo |
|---|---|---|---|
| `fp_cookie_consent` | Armazena decisão do usuário | 1 ano | Essencial |
| `XSRF-TOKEN` | Proteção contra CSRF | Sessão | Essencial |
| `laravel_session` | Sessão autenticada | 2h | Essencial |
| `_ga`, `_gid` | Google Analytics | 2 anos / 24h | Analítico (opt-in) |
| `_hjid`, `_hjSession` | Hotjar | 1 ano / sessão | Analítico (opt-in) |

---

## 📂 Estrutura de pastas

```
sistema-financeiro/
│
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/                    # Autenticação (login, registro, reset, login Google)
│   │   │   ├── CategoryController.php   # CRUD de categorias
│   │   │   ├── ProfileController.php    # Perfil + upload de avatar
│   │   │   ├── RecurringTemplateController.php # CRUD de contas recorrentes
│   │   │   └── TransactionController.php # Transações + dashboard + exports
│   │   └── Requests/
│   │       ├── Auth/LoginRequest.php    # Validação com reCAPTCHA
│   │       └── ProfileUpdateRequest.php
│   ├── Mail/
│   │   └── ResetPasswordMail.php        # E-mail de redefinição personalizado
│   ├── Models/
│   │   ├── Category.php
│   │   ├── RecurringTemplate.php
│   │   ├── Transaction.php
│   │   └── User.php
│   ├── Providers/
│   │   └── AppServiceProvider.php       # Registro do mail customizado
│   ├── Rules/
│   │   └── ReCaptcha.php                # Rule de validação reCAPTCHA
│   └── Services/
│       └── RecurringService.php         # Calcula recorrentes pendentes do mês
│
├── config/
│   ├── services.php                     # Chaves de reCAPTCHA e serviços
│   └── tracking.php                     # GTM_ID, GA_ID, HOTJAR_ID
│
├── database/
│   └── migrations/                      # Migrations de users, transactions, categories
│
├── public/
│   ├── favicon.ico
│   └── favicon.svg                      # Favicon com iniciais FP
│
├── resources/
│   ├── css/
│   │   └── app.css                      # Design tokens + componentes FP
│   ├── js/
│   │   ├── app.js                       # Alpine.js entry point
│   │   └── bootstrap.js                 # Axios config
│   └── views/
│       ├── auth/                        # Login, register, forgot, reset, verify
│       ├── categories/
│       │   └── index.blade.php          # CRUD de categorias
│       ├── components/
│       │   └── cookie-banner.blade.php  # Banner LGPD com Alpine.js
│       ├── emails/
│       │   └── auth/
│       │       └── reset-password.blade.php # Template de e-mail
│       ├── exports/
│       │   └── transactions_pdf.blade.php   # Template de PDF
│       ├── layouts/
│       │   ├── app.blade.php            # Layout autenticado (sidebar + GTM)
│       │   └── guest.blade.php          # Layout público (login/register)
│       ├── partials/                    # Modais de exclusão, empty states
│       ├── politicas/
│       │   └── conteudo.blade.php       # Centro de preferências de privacidade
│       ├── profile/
│       │   └── edit.blade.php           # Edição de perfil + avatar
│       ├── recurring/
│       │   ├── create.blade.php         # Nova conta recorrente
│       │   ├── edit.blade.php           # Editar conta recorrente
│       │   └── index.blade.php          # Lista de recorrentes
│       ├── transactions/
│       │   ├── create.blade.php
│       │   ├── edit.blade.php
│       │   └── index.blade.php          # Lista com modais de duplicar/excluir
│       └── dashboard.blade.php          # Dashboard com gráfico + filtros
│
├── routes/
│   ├── auth.php                         # Rotas de autenticação
│   └── web.php                          # Rotas principais
│
├── .env.example                         # Template de variáveis de ambiente
├── vite.config.js
├── tailwind.config.js
└── package.json
```

---

## 🤝 Contribuição e boas práticas

### Fluxo de trabalho Git

```bash
# 1. Crie uma branch descritiva
git checkout -b feat/nome-da-funcionalidade

# 2. Faça commits atômicos e descritivos
git commit -m "feat: adiciona filtro de categorias no dashboard"

# 3. Abra um Pull Request para a branch main
```

### Convenções de commit

| Prefixo | Uso |
|---|---|
| `feat:` | Nova funcionalidade |
| `fix:` | Correção de bug |
| `style:` | Ajustes visuais/CSS sem lógica |
| `refactor:` | Refatoração sem mudança de comportamento |
| `docs:` | Documentação |
| `chore:` | Configurações, dependências |

### Padrões de código

- **PHP**: PSR-12 · um controller por resource · validações em FormRequest
- **Blade**: componentes anônimos para elementos reutilizáveis · `@stack`/`@push` para scripts específicos por página
- **CSS**: variáveis CSS via `--fp-*` definidas em `:root` · classes utilitárias Tailwind para layouts · classes semânticas `fp-*` para componentes
- **JS**: IIFEs para encapsulamento · sem `var` · Alpine.js para reatividade declarativa · funções globais apenas quando necessário para modais

### Antes de abrir um PR

- [ ] `php artisan test` passa sem erros
- [ ] `npm run build` compila sem warnings críticos
- [ ] Nenhum `console.log` ou `dd()` no código
- [ ] Variáveis sensíveis apenas no `.env` (nunca commitadas)
- [ ] Views novas seguem o padrão `@extends('layouts.app')` + `@section('title')`

---

## 📜 Licença

Este projeto está sob a licença **MIT**. Veja o arquivo [LICENSE](LICENSE) para detalhes.

---

<div align="center">

Feito com ☕ por **[Fernando Pinhel](https://linkedin.com/in/fernando-pinhel-designer)**

[Portfolio](https://fernandopinhel.github.io) · [GitHub](https://github.com/fernandopinhel) · [Medium](https://medium.com/@fernandopinhelll)

</div>
