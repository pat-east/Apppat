# AppPat

## What this codebase does

This repository is a custom PHP web application framework plus a bundled application built on top of it.

At a high level, it provides:

- A front controller entrypoint (`index.php`) that boots the application.
- A custom runtime (`Core`) that initializes configuration, logging, routing, models, controllers, middleware, shortcodes, themes, and setup checks.
- A small public site (`/`, `/about`) defined in `App/`.
- Built-in application modules under `Core/Modules/` for:
  - user registration, login, logout, password recovery
  - session-backed user context
  - TOTP-based MFA
  - a dashboard
  - CRM-style contact/entity/address management
  - mail system test actions
  - status pages and setup checks

The project is positioned in its own views as a security-focused PHP application framework. That is consistent with the code: a lot of the infrastructure is centered on CSRF/nonces, CSP/security headers, sessions, MFA, and secure defaults.

## Runtime flow

The request lifecycle is straightforward:

1. `index.php` creates `App` and calls `init()` and `run()`.
2. `App.php` creates a `Bootstrapper`, which loads Composer, defaults, config, logging, and then boots `Core`.
3. `Core::init()` initializes:
   - the event bus
   - session handling
   - error handling
   - router
   - asset manager
   - theme manager
   - built-in modules from `Core/Modules`
   - application code from `App/`
   - Eloquent/Illuminate database models
   - middleware
   - controllers
   - shortcode registration
   - setup subsystem
   - `UserContext`
   - dashboard module
4. `App::init()` selects the default theme (`UikitTheme`).
5. `Core::run()` checks whether setup is complete. If not, it forces the request to `/setup`.
6. For normal requests, the router finds a matching route, middleware runs, the controller/view produces a result, output middleware post-processes the HTML, and the final content is echoed.

## Architectural structure

### 1. Entry layer

- `index.php`: front controller.
- `App.php`: application-specific bootstrap customization.
- `Core/Bootstrapper.php`: framework boot sequence.

This separation suggests the repository is meant to support framework-level code in `Core/` and project-level code in `App/`.

### 2. Core framework

`Core/` contains the reusable framework primitives:

- `Core/Core.php`
  - central service container-like object
  - owns router, assets, themes, models, controllers, middleware, setup, and error handling
- `Core/inc/`
  - low-level infrastructure classes
  - config, defaults, logger, helper utilities, HTTP abstractions, sessions, crypto, mail, database access, themes, assets, events, sanitization
- `Core/Routes/`
  - route types:
    - `ViewRoute`
    - `CtrlRoute`
    - `RegexViewRoute`
    - `RegexCtrlRoute`
- `Core/HttpResults/`
  - response abstractions such as view rendering and redirects
- `Core/Middleware/`
  - input and output middleware
- `Core/Shortcodes/`
  - shortcode implementations registered dynamically
- `Core/Setup/`
  - setup checks and setup UI
- `Core/Modules/`
  - built-in business/application modules

### 3. Application layer

`App/` is the app-specific layer. It now contains:

- `App/Home/HomeController.php`
  - registers `/` and `/about`
- `App/Home/Views/`
  - `HomeView`
  - `AboutView`
- `App/inc/DefaultMailTemplate.php`
- `App/Fqdn/`
  - FQDN and sub-domain inventory management

This means the current repository is not just a bare framework. It ships with default app behavior, default modules, and app-level admin tooling.

### 4. Presentation layer

- `Themes/`
  - theme implementations
  - currently `App.php` selects `UikitTheme`
  - `SetupTheme` is used during the setup flow
- `Views/`
  - shared error/status templates such as `404.php` and `500.php`
- `public/assets/`
  - frontend packages managed with npm
  - current dependencies: UIkit, Bootstrap, jQuery, Kern UX

The theme layer is responsible for the full page shell. Views render content only; the active theme wraps them in layout, navigation, footer, asset includes, and user bar.

## Routing and request handling

The router is custom and in-memory.

- Controllers register routes during initialization.
- Routes carry:
  - path or regex pattern
  - HTTP method
  - a result handler closure
  - input middleware
  - output middleware
- If no route matches:
  - requests without a trailing slash are redirected to the slash version
  - otherwise a 404 view route is returned

Two route styles are supported:

- exact routes for normal pages/actions
- regex routes for parameterized URLs such as password recovery or CRM actions

Result handling is separated from routing:

- `ViewHttpResult` instantiates a view and passes it to the active theme
- `RedirectHttpResult` sends a redirect header

## Middleware and security model

Security is one of the clearest design goals in this codebase.

### Input middleware

Input middleware is attached per route and currently handles:

- request argument parsing and sanitization
- CSRF token or nonce verification for state-changing routes

`CtrlRoute` and `RegexCtrlRoute` automatically attach:

- `VerifyCsrfTokenOrNonceMiddleware`
- `SanitizeRequestArgumentsMiddleware`

This means controller actions are expected to be protected by default.

### Output middleware

Output middleware processes rendered output before it is sent.

Mandatory output middleware includes:

- `SecurityHeaderMiddleware`
  - `X-Frame-Options`
  - `X-Content-Type-Options`
  - `Referrer-Policy`
  - `Permissions-Policy`
  - `Cross-Origin-*`
  - HSTS outside dev environments
- `CspHeaderMiddleware`
  - generates a request nonce
  - builds CSP directives
- `ShortcodeMiddleware`
  - parses shortcode syntax embedded in rendered HTML

### Other security-related primitives

- `Session`
  - configures secure session cookie settings
- `CsrfToken`
  - stores valid CSRF tokens in the PHP session
- `Nonce`
  - generates/verifies nonces using `NONCE_PAYLOAD` from `.env`
- `Crypto`
  - utility support for random data, hashes, UUIDs, and RSA-related work
- MFA support
  - implemented via `spomky-labs/otphp`

## Data layer

The persistence model uses `illuminate/database` (Eloquent/Capsule) rather than a fully custom ORM.

### How it works

- `Config` reads MySQL settings from `.env`.
- `ModelManager` boots Capsule and makes it global.
- Any class extending `DatabaseModel` is discovered and instantiated.
- `DatabaseModel` checks whether its table exists and creates it if it does not.

So the framework auto-creates schema lazily from model classes at startup.

### Main database-backed areas

From the current tree, the main database models are:

- `UserModel`
  - users, credentials, roles, TOTP state
- `UserPasswordRecoveryModel`
  - password recovery tokens and status
- `CrmBaseModel`
  - CRM entity base data
- `CrmContactModel`
  - CRM contact data
- `CrmAddressModel`
  - CRM address data

### Database access style

The codebase mixes two styles:

- Eloquent/Capsule for application models
- raw `mysqli` in `MySqlClient` for setup checks and low-level queries

## Built-in modules

The bundled application logic lives mainly in `Core/Modules/`.

### User module

`Core/Modules/User/` provides:

- login/logout
- registration
- password recovery
- user dashboard/profile pages
- user roles and privileges
- user encryption support
- TOTP integration helpers

`UserContext` is the per-request identity object. It reads the current user from the PHP session and exposes:

- authentication state
- current user model
- roles/privileges
- user credentials helpers
- CRM access

### User MFA module

`Core/Modules/UserMfa/` adds:

- MFA recovery
- enable TOTP
- disable TOTP

### Dashboard module

`Core/Modules/Dashboard/` exposes `/dashboard` and dashboard item routing. It uses the event bus and the current login state to decide whether the user sees the dashboard or the login view.

### CRM module

`Core/Modules/Crm/` implements a small CRM domain:

- entity records
- contact data
- addresses
- debtor/creditor/common types

Routes are regex-based and accept a `branch` and `method`, for example create/update flows for basic/contact/address sections.

### System module

`Core/Modules/System/` currently exposes a mail test action used to verify SMTP/mail delivery configuration.

### Status module

`Core/Modules/Status/` contains status views such as the 400 page.

## App modules

The `App/` layer now contains its own feature modules on top of the framework and built-in core modules.

### FQDN inventory module

`App/Fqdn/` manages the domain inventory.

It provides:

- storage of root FQDNs
- storage of sub-domains per FQDN
- activation/deactivation of sub-domains
- an admin dashboard screen for curation

The main models are:

- `FqdnModel`
  - root domains
  - `last_scanned_at` for discovery scans
- `FqdnSubdomainModel`
  - sub-domains linked to an FQDN
  - `is_active` flag used to enable or disable individual sub-domains within the inventory

### CLI usage

Examples:

- `php cli.php fqdn:list`
- `php cli.php fqdn:scan`
- `php cli.php fqdn:scan example.com`

## Themes and UI

The active runtime theme in normal operation is `Themes/UikitTheme/UikitTheme.php`.

That theme:

- enables the `uikit` asset bundle
- renders the HTML document shell
- prints a header, hero section, login/user bar, main content, and footer
- includes CSS and JS assets through `AssetManager`

The asset system is registry-based:

- assets are registered centrally in `AssetManager`
- themes/modules choose assets by symbolic ID such as `uikit` or `bootstrap`
- dependencies between assets are supported

There are multiple theme classes in the tree:

- `UikitTheme`
- `BootstrapTheme`
- `KernuxTheme`
- `SetupTheme`

Only `UikitTheme` is selected by default in `App.php`.

## Setup flow

Setup is a first-class part of the runtime.

- On `GET`, if setup checks fail, requests are redirected to `/setup`.
- The setup page is rendered with `SetupTheme`.
- `SetupTestManager` collects tests from `SetupTestRepository` implementations.
- `CoreSetupTests` validates:
  - PHP presence/version
  - `mysqli` extension
  - HTTPS outside development
  - `.env` existence
  - nonce payload configuration
  - MySQL configuration values
  - MySQL connectivity

This acts as an installation gate before the rest of the application becomes usable.

## Configuration and dependencies

### PHP/composer dependencies

The main Composer dependencies are:

- `illuminate/database`
- `spomky-labs/otphp`
- `mpdf/mpdf`
- `mpdf/qrcode`
- `phpmailer/phpmailer`

From those dependencies, the intended feature set appears to include:

- relational persistence
- TOTP-based MFA
- PDF generation / QR code generation
- SMTP or mail transport support

### Environment configuration

Configuration is loaded from `.env` through `parse_ini_file()`.

Important values include:

- `MYSQL_*`
- `NONCE_PAYLOAD`
- `USER_RSA_KEY_PAIR_BASE_DIR`
- SMTP settings
- sender information

## Code organization summary

If you need to navigate the project quickly, this is the practical map:

- `index.php`
  - request entrypoint
- `App.php`
  - chooses app-level theme and delegates to core
- `Core/`
  - framework runtime and reusable infrastructure
- `Core/Modules/`
  - built-in product features
- `App/`
  - app-specific routes/views on top of the framework
- `Themes/`
  - layout shell and frontend framework selection
- `Views/`
  - shared error/status pages
- `public/assets/`
  - npm-managed frontend assets

## Notable implementation observations

The repository is usable as a codebase map, but several details suggest it is still evolving rather than fully polished:

- The project mixes framework code and bundled application/product code in the same repository.
- `composer.json` autoload points to `src/`, but the actual application runtime loads files with manual `require/include` from the repository root.
- A lot of discovery is dynamic via `Helper::IncludeOnce()` and `get_declared_classes()`, so load order matters.
- The setup and model boot process can create database tables automatically at startup.
- The default theme references routes like `/contact`, `/license`, and auth/dashboard pages; some of those are defined by built-in modules, others appear to be placeholders.
- Error handling is centralized and intentionally fails closed by returning the 500 page.
- The framework emphasizes security headers and token checks, but some primitives still use older constructs such as MD5 for tokens/nonces and a mixed raw SQL/Eloquent approach.

## Bottom line

This codebase is a custom PHP application framework with a built-in starter application and a substantial set of bundled modules. It is structured around a central `Core` runtime, controller-registered routes, middleware-driven request handling, theme-based rendering, dynamic class discovery, and MySQL-backed models. In practical terms, it behaves like a small self-contained CMS/app platform focused on secure user management, dashboard features, and CRM-like data handling.
