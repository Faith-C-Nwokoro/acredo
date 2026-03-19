# Acredo Protocol — Laravel 11.48

> Full-stack DeFi web application — Structured Credit & Yield Protocol on Stacks blockchain.
> Built with Laravel 11.48, Blade templates, vanilla JS, and Chart.js.

---

## 📋 Requirements

| Tool | Version |
|------|---------|
| PHP  | 8.2 or 8.3 |
| Composer | 2.x |
| Node.js (optional, for dev tools) | 18+ |

---

## 🚀 Quick Start

```bash
# 1. Clone / unzip the project
cd acredo

# 2. Install PHP dependencies
composer install

# 3. Copy environment file
cp .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Create the SQLite database
touch database/database.sqlite

# 6. (Optional) Clear caches
php artisan config:clear
php artisan view:clear

# 7. Start the dev server
php artisan serve
```

Then open **http://localhost:8000** in your browser.

---

## 🗂 Project Structure

```
acredo/
├── app/
│   ├── Http/Controllers/
│   │   ├── DashboardController.php     # / route
│   │   ├── BorrowController.php        # /borrow
│   │   ├── MarketplaceController.php   # /marketplace
│   │   ├── VaultController.php         # /vault
│   │   ├── PoolController.php          # /pool
│   │   ├── ProfileController.php       # /profile
│   │   └── WalletController.php        # wallet connect/disconnect
│   └── Services/
│       └── MockDataService.php         # All mock data (swap for real DB/API later)
│
├── resources/views/
│   ├── layouts/
│   │   └── app.blade.php               # Master layout (sidebar, header, JS globals)
│   └── pages/
│       ├── dashboard.blade.php         # Overview, stats, positions
│       ├── borrow.blade.php            # 3-tab: Reputation / NFT / Yield loans
│       ├── marketplace.blade.php       # Open requests + funded loans
│       ├── vault.blade.php             # Deposit, yield chart, borrow against yield
│       ├── pool.blade.php              # Liquidity pool metrics + LP positions
│       └── profile.blade.php          # Rep breakdown, loan history, vault history
│
├── routes/
│   └── web.php                         # All page + API routes
│
└── config/                             # Standard Laravel config files
```

---

## 🎨 Design System

| Token | Value | Usage |
|-------|-------|-------|
| `--bg` | `#0A0A0F` | App background |
| `--surface` | `#111118` | Cards, sidebar |
| `--elevated` | `#1A1A24` | Inputs, hover |
| `--border` | `#2A2A3A` | All borders |
| `--primary` | `#6366F1` | Indigo — CTAs, active |
| `--accent` | `#06B6D4` | Cyan — badges, highlights |
| `--success` | `#10B981` | Green — health safe |
| `--warning` | `#F59E0B` | Amber — health caution |
| `--danger` | `#EF4444` | Red — health critical |

---

## 📄 Pages

### Dashboard `/`
- 4 stat cards: Reputation Score, Borrow Limit, Active Loans, Total Deposited
- Animated SVG reputation ring (indigo → cyan gradient)
- Quick Actions (3 deep-link cards)
- Active Loans table with Repay buttons

### Borrow `/borrow`
Three tabs:
1. **Reputation Loan** — amount/rate/duration form, live loan summary, tier benefits table
2. **NFT Loan** — wallet NFT grid (colorful SVG placeholders), click-to-select, 40% LTV calculator
3. **Yield Borrow** — vault balance, projected yield calc, live health factor semicircle gauge

### Marketplace `/marketplace`
- Filter bar (tier, sort, BNS search)
- 8 expandable loan request cards
- Fund Loan modal with confirmation
- My Funded Loans tab (2 active positions)

### Vault `/vault`
- Current position card with 90-day yield area chart (Chart.js)
- Deposit form with projected yield preview
- Collapsible withdraw section with warning
- Large health factor gauge
- Active borrow card + Repay button

### Pool `/pool`
- 3 metric cards: TVL, APY, Utilization (with progress bar)
- Lending Pool & Yield Pool tabs with APY history line charts
- LP Positions table

### Profile `/profile`
- User card: BNS name, full address (copyable), animated rep ring
- Reputation breakdown table with signal weights
- Full loan history (6 rows, colour-coded by status)
- Vault & Pool activity log

---

## 🔗 Mock → Real Contracts

All blockchain interactions are stubbed in `app/Services/MockDataService.php`.
When your Clarity smart contracts are ready:

1. Replace `MockDataService::wallet()` with a real Stacks API call
2. Replace the `/api/transaction` route handler with actual `@stacks/connect` calls
   (this app is pure server-side PHP; Stacks.js calls should be made client-side or
   via a dedicated API layer)

For a real integration pattern, implement an `AcredoContractService` that wraps
the Stacks API, and inject it via the service container.

---

## 🔐 Session-Based Wallet (Mock)

The "Connect Wallet" button calls `POST /wallet/connect`, which stores mock wallet
data in the PHP session. Disconnect clears the session. Replace with Hiro Wallet
`@stacks/connect` front-end flow when integrating real contracts.

---

## 📦 Dependencies

| Package | Purpose |
|---------|---------|
| `laravel/framework ^11.48` | PHP framework |
| `Chart.js 4.4` (CDN) | Yield & APY charts |
| Inter font (Google Fonts CDN) | Typography |
| JetBrains Mono (Google Fonts CDN) | Monospace addresses |

Zero npm/node dependencies — everything runs with PHP only.

---

## 🌐 Deployment

### Apache / Nginx
Point the web root to the `public/` directory.

### Nginx example
```nginx
server {
    listen 80;
    server_name acredo.yourdomain.com;
    root /var/www/acredo/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

### Environment
```
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:...  # php artisan key:generate
APP_URL=https://acredo.yourdomain.com
SESSION_DRIVER=file
```

---

*Acredo Protocol — Hackathon MVP · Built with Laravel 11.48*
