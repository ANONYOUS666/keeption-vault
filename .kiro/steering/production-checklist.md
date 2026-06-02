---
inclusion: always
---

# Keeption Vault — Production Checklist

Things to do before going live. Update this as tasks are completed.

---

## 🔴 Must Do Before Launch

- [ ] **Set up custom SMTP (Resend)** — Supabase free tier only allows 2 signup emails/hour. Set up Resend (free, 3,000/mo) via Supabase → Authentication → Settings → SMTP Settings. Host: `smtp.resend.com`, Port: `465`, Username: `resend`, Password: your Resend API key.

- [ ] **Enable email confirmation** — Make sure Supabase → Authentication → Settings → "Enable email confirmations" is ON when going live (it should already be on).

- [ ] **Set APP_ENV=production in .env** — Change `APP_ENV=local` to `APP_ENV=production` and `APP_DEBUG=false` so error details are never shown to users.

- [ ] **Set real APP_URL in .env** — Change `APP_URL=http://localhost:8000` to your actual domain e.g. `APP_URL=https://keeptionvault.com`.

- [ ] **Add plan field to Supabase profiles table** — Run this SQL in Supabase SQL Editor: `ALTER TABLE profiles ADD COLUMN IF NOT EXISTS plan TEXT DEFAULT 'free';` so the plan is stored per user in the database.

- [ ] **Store plan in session on login** — The AuthController currently reads plan from `user_metadata`. Make sure the Supabase profile `plan` field is being read and stored in session on every login.

- [ ] **Set up Cloudflare R2 or Supabase Storage** — Currently files are stored as browser blob URLs (in-memory only, lost on refresh). Connect real file storage before launch.

- [ ] **Set up Stripe for payments** — Pro ($3/mo) and Teams ($8/mo) plans need real payment processing. Integrate Stripe Checkout or Stripe Billing.

- [x] **Remove /set-plan test route** — The route `GET /set-plan/{plan}` in routes/web.php is for testing only. Remove it before going live.

- [ ] **Secure .env file** — Make sure `.env` is in `.gitignore` and never committed to version control. It contains your Supabase keys and owner password hash.

---

## 🟡 Should Do Soon

- [ ] **Connect owner dashboard to live Supabase data** — The owner dashboard at `/owner/command` currently shows mock data. Wire it up to real Supabase queries.

- [ ] **Add plan column to Supabase and sync on upgrade** — When a user upgrades to Pro or Teams, update their `plan` field in Supabase so it persists across sessions.

- [ ] **Set up version history cleanup job** — Free plan keeps 7 days, Pro 90 days, Teams 180 days. Set up a scheduled job to delete old versions.

- [ ] **Test all three plan dashboards** — Log in as free, pro, and teams users and verify the correct sidebar items, storage limits, and features appear for each.

- [ ] **Add HTTPS / SSL certificate** — Required for production. Use Let's Encrypt (free) or your hosting provider's SSL.

- [ ] **Set up database backups** — Supabase Pro includes daily backups. On free tier, manually export your database regularly.

---

## 🟢 Nice to Have

- [ ] **Set up Resend for transactional emails** — Beyond signup confirmation, use Resend for storage warning emails, upgrade prompts, and team invitation emails.

- [ ] **Add real AI Smart Search** — Currently the AI search is a basic filename filter. Connect to an actual AI/vector search service.

- [ ] **Mobile app for camera backup** — The camera backup feature requires a mobile app. Build or integrate one.

- [ ] **Set up anomaly detection** — The Teams audit trail mentions AI anomaly detection. Implement background monitoring.

---

## ✅ Done

- [x] Owner dashboard created at `/owner/command` with separate auth
- [x] Plan-based dashboard behavior implemented (Free/Pro/Teams)
- [x] Self-Destruct Links and AI Smart Search hidden from free plan
- [x] Teams section pages built (Team Files, Audit Trail, Admin Dashboard, Team Members)
- [x] Pricing page plan buttons pass plan to register page
- [x] Register page shows plan-specific badges and button text
- [x] Storage display shows correct GB per plan (5/100/500)
- [x] File upload validates against plan file size limits
