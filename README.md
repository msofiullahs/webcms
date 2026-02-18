# WebCMS

A modern, full-featured Content Management System built with Laravel 11, Filament v3, Inertia.js, Vue 3, and Tailwind CSS 4. Features a WordPress-inspired experience with a visual drag-and-drop page builder, multi-language support, and a themeable frontend.

## Tech Stack

| Layer | Technology |
|-------|-----------|
| Backend | Laravel 11, PHP 8.2+ |
| Admin Panel | Filament v3 |
| Frontend | Vue 3, Inertia.js v2 |
| Styling | Tailwind CSS v4, @tailwindcss/typography |
| Build Tool | Vite 6 |
| i18n | spatie/laravel-translatable |
| Settings | spatie/laravel-settings |

## Features

### Content Management
- **Posts** — Create and publish blog posts with featured images, excerpts, scheduled publishing, and SEO meta fields.
- **Pages** — Build dynamic pages using a visual drag-and-drop block editor (no code required).
- **Block Editor** — 8 block types: Hero, Text, Image, Gallery, CTA, Contact Form, Latest Posts, and Column Layouts with nesting support.
- **Column Layouts** — 2-4 column grids with layout presets (equal, 2/3+1/3, 1/3+2/3, etc.) and gap controls. Blocks can be nested inside columns.

### Navigation
- **Menu Management** — Create menus for Header, Footer, and Sidebar locations with hierarchical (parent-child) menu items.

### Internationalization
- **Multi-Language** — Full translation support for posts, pages, and menus via JSON column storage.
- **Translation Editor** — Manage translations directly from the admin panel.
- **Language Switcher** — Frontend language selector with RTL/LTR direction support.

### Theming
- **WordPress-like Theme System** — Themes override default styles via structured CSS files.
- **Theme Upload** — Install new themes by uploading a ZIP file from the admin panel.
- **Activate/Delete** — Switch between themes or remove them.
- **Built-in Themes** — Includes a Default (blue/green) and Modern (purple/amber) theme.
- **Theme CSS Files** — Each theme can include targeted stylesheets: `theme.css`, `header.css`, `footer.css`, `blocks.css`, `pages.css`.

### Contact
- **Contact Form** — Frontend form with validation that stores submissions and optionally sends email notifications.
- **Submission Management** — View, track read/unread status, and manage contact messages from the admin panel (with unread badge count).

### Admin Dashboard
- **Filament Admin Panel** at `/admin` with dark mode, collapsible sidebar, and dashboard widgets (stats, recent posts, recent contacts).
- **Settings Pages** — General (site branding), Homepage (page selector), Email (notifications), Language, and Theme management.

### Frontend
- **Dark Mode Toggle** — Light/dark mode switching.
- **Responsive Design** — Mobile-first layout with Tailwind CSS.
- **SEO Ready** — Meta title and description fields for posts and pages.

## Requirements

- PHP 8.2+
- Composer
- Node.js 18+ & npm
- MySQL 8.0+

## Installation

```bash
# Clone the repository
git clone <repository-url> webcms
cd webcms

# Install PHP dependencies
composer install

# Install JS dependencies
npm install

# Environment setup
cp .env.example .env
php artisan key:generate
```

Configure your `.env` file with database credentials:

```dotenv
DB_DATABASE=webcms
DB_USERNAME=root
DB_PASSWORD=your_password
```

```bash
# Run migrations
php artisan migrate

# Seed sample content (optional)
php artisan db:seed --class=SampleContentSeeder

# Build frontend assets
npm run build

# Or start the dev server
npm run dev
```

### Create an Admin User

```bash
php artisan make:filament-user
```

## Usage

### Admin Panel

Access the admin panel at `/admin`. From there you can:

- **Content > Posts** — Manage blog posts.
- **Content > Pages** — Build pages using the block editor.
- **Content > Contact Messages** — View form submissions.
- **Appearance > Menus** — Configure site navigation.
- **Appearance > Themes** — Upload, activate, or delete themes.
- **Settings** — Configure site branding, homepage, email, and language options.

### Homepage Setup

1. Go to **Content > Pages** and create a page using the block editor.
2. Go to **Settings > Homepage** and select that page from the dropdown.
3. The selected page's blocks will render as the homepage.

### Page Builder Blocks

| Block | Description |
|-------|-------------|
| Hero | Full-width banner with title, subtitle, background image, and CTA button |
| Text | Rich text content area |
| Image | Single image with optional caption and alignment |
| Gallery | Multi-image grid gallery |
| CTA | Call-to-action section with button |
| Contact Form | Embedded contact form |
| Latest Posts | Configurable grid of recent blog posts |
| Columns | 2-4 column layout with nested blocks inside each column |

### Creating a Theme

Themes live in `resources/themes/{slug}/` and follow this structure:

```
my-theme/
  config.json          # Required — theme metadata
  css/
    theme.css          # Required — CSS variables and base styles
    header.css         # Optional — header and navigation overrides
    footer.css         # Optional — footer overrides
    blocks.css         # Optional — block component styles
    pages.css          # Optional — post and page layout styles
  fonts/               # Optional — custom font files
  images/              # Optional — theme images
```

**config.json** example:

```json
{
  "name": "My Theme",
  "description": "A custom theme",
  "version": "1.0.0",
  "author": "Your Name",
  "colors": {
    "primary": "#3B82F6",
    "secondary": "#10B981"
  },
  "fonts": {
    "heading": "Inter",
    "body": "Inter"
  }
}
```

**theme.css** defines CSS custom properties that other files can reference:

```css
:root {
  --color-primary: #3B82F6;
  --color-primary-dark: #2563EB;
  --font-heading: 'Inter', system-ui, sans-serif;
  --font-body: 'Inter', system-ui, sans-serif;
  --radius-md: 0.5rem;
  --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
}
```

To install a theme via the admin panel, ZIP the theme folder and upload it at **Appearance > Themes**. The ZIP is validated for security (allowed file types only, no path traversal).

## Project Structure

```
app/
  Filament/
    Resources/          # PostResource, PageResource, MenuResource, ContactSubmissionResource
    Pages/              # Settings pages (General, Homepage, Email, Language, Themes, Translations)
    Widgets/            # Dashboard widgets
  Http/
    Controllers/        # HomeController, PostController, PageController, ContactController, etc.
    Middleware/          # SetLocale, SetTheme
  Models/               # Post, Page, Menu, MenuItem, Theme, Language, ContactSubmission
  Services/             # ThemeService (install, delete, discover themes)
  Settings/             # GeneralSettings, HomepageSettings, EmailSettings, LanguageSettings

resources/
  js/
    Pages/              # Vue pages (Home, Post/Index, Post/Show, Page/Show, Contact)
    Components/
      Blocks/           # BlockRenderer, HeroBlock, TextBlock, ColumnsBlock, etc.
      Layout/           # Header, Footer
    Layouts/            # AppLayout
  themes/               # Theme files (default/, modern/)
  views/                # Blade templates (app.blade.php, Filament views)

routes/
  web.php               # All public routes + theme asset route
```

## Routes

| Method | URI | Description |
|--------|-----|-------------|
| GET | `/` | Homepage (renders selected page) |
| GET | `/posts` | Blog post listing |
| GET | `/posts/{slug}` | Single post |
| GET | `/contact` | Contact form |
| POST | `/contact` | Submit contact form |
| POST | `/language/{code}` | Switch language |
| GET | `/themes/{slug}/{path}` | Serve theme assets |
| GET | `/{page:slug}` | Dynamic page (catch-all) |
| GET | `/admin` | Filament admin panel |

## Development

```bash
# Start Vite dev server with HMR
npm run dev

# Build for production
npm run build

# Run PHP code formatting
./vendor/bin/pint

# Clear all caches
php artisan optimize:clear
```

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
