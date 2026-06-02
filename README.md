# Keeption Vault

> Your Files. Your Rules.

Keeption Vault is a private, encrypted cloud storage platform designed for people who take their privacy seriously. Store photos, videos, music, documents, and any file type — with end-to-end encryption, zero ads, and zero tracking.

---

## What is Keeption Vault?

Keeption Vault gives you a secure personal vault in the cloud. Unlike Google Drive or Dropbox, we don't scan your files, sell your data, or show you ads. Everything you upload is encrypted and only accessible by you.

---

## Features

- 🔐 **End-to-end encryption** — your files are encrypted before they leave your device
- ☁️ **Cloud storage** — access your files from anywhere
- 🖼️ **In-browser file preview** — view images, videos, audio, and PDFs without downloading
- 🔗 **Shared links** — share any file with a secure link
- 🕐 **Version history** — restore previous versions of your files
- 🔍 **Smart search** — find files by name or content (Pro)
- 💥 **Self-destructing links** — links that expire after one view (Pro)
- 📷 **Auto camera backup** — automatically back up your photos (Pro)
- 👥 **Collaborative folders** — share folders with your team (Teams)
- 📋 **Audit trail** — see every action in your workspace (Teams)
- 🛡️ **Admin dashboard** — manage your team and storage (Teams)

---

## Plans

| Plan | Storage | Max File Size | Price |
|------|---------|---------------|-------|
| **Free** | 5 GB | 500 MB | $0/mo |
| **Pro** | 100 GB | 10 GB | $3/mo |
| **Teams** | 500 GB shared | 50 GB | $8/mo |

All plans include end-to-end encryption, in-browser preview, and zero ads.

---

## Who is it for?

- **Individuals** who want private cloud storage without being the product
- **Creators** who store large media files and need version history
- **Teams** who need shared storage with access controls and audit logs
- **Anyone** tired of Big Tech reading their files

---

## Tech Stack

- **Backend:** Laravel 11 (PHP 8.2)
- **Database:** MySQL
- **Frontend:** Blade templates, Tailwind CSS
- **Auth:** Session-based authentication
- **Encryption:** End-to-end, zero-knowledge architecture
- **Email:** Resend (transactional emails)

---

## Services & Tools Used

| Service | What it's used for | URL |
|---------|-------------------|-----|
| **Resend** | Transactional emails (password reset, confirmations) | [resend.com](https://resend.com) |
| **XAMPP** | Local development server (Apache + MySQL + PHP) | [apachefriends.org](https://www.apachefriends.org) |
| **phpMyAdmin** | MySQL database GUI (comes with XAMPP) | `localhost/phpmyadmin` |
| **Laravel** | PHP backend framework | [laravel.com](https://laravel.com) |
| **Tailwind CSS** | Utility-first CSS framework | [tailwindcss.com](https://tailwindcss.com) |
| **Vite** | Frontend asset bundler | [vitejs.dev](https://vitejs.dev) |
| **Lucide Icons** | Icon library used throughout the UI | [lucide.dev](https://lucide.dev) |
| **Google Fonts** | Clash Display + Epilogue typefaces | [fonts.google.com](https://fonts.google.com) |
| **Composer** | PHP dependency manager | [getcomposer.org](https://getcomposer.org) |
| **Node.js / npm** | JavaScript runtime and package manager | [nodejs.org](https://nodejs.org) |

---

## Email Setup (Resend)

Password reset emails are sent via [Resend](https://resend.com) (free — 3,000 emails/mo).

To configure:
1. Create a free account at resend.com
2. Go to **API Keys** → Create API Key
3. Add to `.env`:

```
MAIL_MAILER=smtp
MAIL_HOST=smtp.resend.com
MAIL_PORT=465
MAIL_USERNAME=resend
MAIL_PASSWORD=re_your_api_key_here
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="Keeption Vault"
```

4. Run `php artisan config:clear`

The forgot password flow:
- User clicks "Forgot password?" on the login page
- Enters their email → reset link sent to inbox
- Link expires after 60 minutes
- User sets a new password (min. 12 characters)
- Redirected to login

---

## Status

Currently in active development. Core features are built and working. Payments, real file storage, and mobile camera backup are coming next.

---

*Keeption Vault — Store everything. Share your way. Stay private.*
