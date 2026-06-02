# Implementation Plan: Owner Dashboard

## Overview

Implement the private owner command center with isolated authentication, session management, brute-force lockout, and a single-page Blade dashboard with 9 sections — all using mock data.

## Tasks

- [x] 1. Register owner routes in routes/web.php
  - Add `GET /owner/login` → `OwnerController@showLogin`
  - Add `POST /owner/login` → `OwnerController@login`
  - Add `GET /owner/command` → `OwnerController@command`
  - Add `POST /owner/logout` → `OwnerController@logout`
  - Import `OwnerController` at the top of the file
  - _Requirements: 1.1, 1.2_

- [x] 2. Add owner credentials to environment configuration
  - Add `OWNER_EMAIL=` and `OWNER_PASSWORD_HASH=` entries to `.env.example`
  - Add the same entries to `.env` with placeholder values
  - _Requirements: 2.1, 2.2_

- [x] 3. Create the owner login Blade view
  - [x] 3.1 Create `resources/views/owner/login.blade.php`
    - Standalone page (no layout extension) with dark background `#050810`
    - Load Clash Display and Epilogue fonts via Google Fonts / CDN
    - Centered card with email input, password input, and submit button labeled "Access Command Center"
    - Display `$errors->first('email')` above the form when present
    - No Keeption branding, navigation links, or footer
    - _Requirements: 5.1, 5.2, 5.3, 5.4, 5.5, 5.6_

- [x] 4. Create the owner dashboard Blade view — layout and sidebar
  - [x] 4.1 Create `resources/views/owner/dashboard.blade.php` with full page shell
    - Dark theme: background `#050810`, accent `#00f5a0`, Clash Display + Epilogue fonts
    - Persistent sidebar listing all 9 section nav items: Home, Free Users, Pro Users, Teams, Revenue & Finance, Platform Analytics, Alerts Center, Communications, Platform Settings
    - Sidebar header showing `{{ env('OWNER_EMAIL') }}`
    - Logout button in sidebar that submits POST to `/owner/logout` via a small inline form
    - "MOCK DATA — Not connected to live database" banner in the dashboard header
    - JS-driven section switching: clicking a nav item shows the target section div and hides all others, and updates the active highlight
    - _Requirements: 6.1, 6.2, 6.3, 6.4, 6.5, 6.6, 6.7, 16.4, 17.1, 17.2, 17.3, 17.4, 17.5_

- [ ] 5. Implement Home section — metric cards
  - [ ] 5.1 Add the Home section div inside the dashboard view
    - 8 Metric_Cards in a responsive CSS grid
    - Cards: Total Users, Active Users (30d), Pro Subscribers, Teams Workspaces, Monthly Recurring Revenue, Total Storage Used, Support Tickets Open, Platform Uptime
    - Each card shows label, primary value, and trend indicator (up/down/neutral arrow)
    - Positive trend indicators use accent color `#00f5a0`
    - All values are hardcoded mock data
    - _Requirements: 7.1, 7.2, 7.3, 7.4, 7.5_

- [ ] 6. Implement user table sections (Free Users, Pro Users, Teams)
  - [ ] 6.1 Add Free Users section div
    - Total count above table
    - Paginated table columns: User ID, Email, Registration Date, Storage Used, Last Active, Status
    - Client-side email search input that filters visible rows via JS
    - All rows are mock data
    - _Requirements: 8.1, 8.2, 8.3, 8.4, 8.5_
  - [ ] 6.2 Add Pro Users section div
    - Total count above table
    - Paginated table columns: User ID, Email, Subscription Start, Next Billing Date, Storage Used, Last Active, Status
    - Client-side email search input
    - All rows are mock data
    - _Requirements: 9.1, 9.2, 9.3, 9.4, 9.5_
  - [ ] 6.3 Add Teams section div
    - Total count above table
    - Paginated table columns: Workspace ID, Workspace Name, Owner Email, Member Count, Storage Used, Created Date, Status
    - Client-side workspace name search input
    - All rows are mock data
    - _Requirements: 10.1, 10.2, 10.3, 10.4, 10.5_

- [ ] 7. Implement Revenue & Finance and Platform Analytics sections
  - [ ] 7.1 Add Revenue & Finance section div
    - Summary cards: MRR, ARR, ARPU, Churn Rate — all mock values
    - Mock monthly revenue chart using inline SVG
    - Recent transactions table: Date, User Email, Plan, Amount, Status — mock rows
    - _Requirements: 11.1, 11.2, 11.3, 11.4_
  - [ ] 7.2 Add Platform Analytics section div
    - Metrics: DAU, WAU, MAU, Avg Session Duration, Files Uploaded (30d), Storage Growth Rate — all mock
    - Mock user growth chart using inline SVG
    - Top file types table: File Type, Count, Percentage of Total — mock rows
    - _Requirements: 12.1, 12.2, 12.3, 12.4_

- [ ] 8. Implement Alerts Center, Communications, and Platform Settings sections
  - [ ] 8.1 Add Alerts Center section div
    - List of mock alert entries each with severity (info/warning/critical), timestamp, and message
    - Color coding: info = blue, warning = amber, critical = red
    - Unread alert count badge on the Alerts Center sidebar nav item
    - All entries are mock data
    - _Requirements: 13.1, 13.2, 13.3, 13.4, 13.5_
  - [ ] 8.2 Add Communications section div
    - Past announcements table: Date, Subject, Audience, Status — mock rows
    - "New Announcement" form with Subject input, Audience selector (All/Free/Pro/Teams), Message textarea, and Submit button
    - On form submit show a confirmation message "Announcement queued" via JS (no server request)
    - _Requirements: 14.1, 14.2, 14.3, 14.4_
  - [ ] 8.3 Add Platform Settings section div
    - Toggle controls for: Maintenance Mode, New Registrations Enabled, Pro Plan Available, Teams Plan Available
    - Read-only fields: App Version, Environment, Laravel Version — populated with `config('app.version')`, `app()->environment()`, `app()->version()`
    - On toggle change update visual state and show "Settings saved" confirmation via JS (no server persistence)
    - _Requirements: 15.1, 15.2, 15.3, 15.4_

- [x] 9. Final checkpoint — verify all routes and views render correctly
  - Ensure all tests pass, ask the user if questions arise.
  - Confirm `/owner/login` renders without auth, `/owner/command` redirects to login when unauthenticated
  - Confirm sidebar navigation switches sections without page reload
  - Confirm mock data banner is visible on the dashboard

## Notes

- Tasks marked with `*` are optional and can be skipped for faster MVP
- The OwnerController already exists and is complete — no changes needed to it
- All dashboard data is mock; no Supabase or database queries should be introduced
- Client-side pagination and search are JS-only; no server round-trips
