# Requirements Document

## Introduction

The Keeption Vault Owner/Founder Dashboard is a private, secret command center accessible only to the platform owner. It provides a comprehensive view of platform health, user metrics, revenue, analytics, and operational controls — all behind a separate authentication system completely isolated from the regular user auth flow. The dashboard is a single-page Blade application with JS-driven sidebar navigation, styled with the same dark theme as the user dashboard.

## Glossary

- **Owner**: The sole platform founder/operator who has exclusive access to the command center
- **Command_Center**: The private dashboard at `/owner/command` accessible only after owner authentication
- **Owner_Auth**: The authentication subsystem that validates the owner's credentials against `.env`-stored values, independent of Supabase
- **Session**: The server-side PHP session tracking the owner's authenticated state
- **Lockout**: A temporary ban on login attempts after repeated failures
- **Metric_Card**: A UI widget on the Home section displaying a single KPI value with label and trend indicator
- **Section**: A named content area within the single-page dashboard (Home, Free Users, Pro Users, Teams, Revenue, Analytics, Alerts, Communications, Settings)
- **Sidebar**: The persistent left-hand navigation panel listing all Sections
- **Mock_Data**: Static placeholder values used in place of live database queries during initial implementation
- **OWNER_EMAIL**: Environment variable holding the owner's login email
- **OWNER_PASSWORD_HASH**: Environment variable holding the bcrypt hash of the owner's password
- **OwnerController**: The Laravel controller handling all `/owner/*` routes
- **Rate_Limiter**: The mechanism tracking failed login attempts per IP address

---

## Requirements

### Requirement 1: Secret Route Exposure

**User Story:** As the platform owner, I want the owner routes to be completely hidden from public navigation, so that no regular user can discover or access the command center.

#### Acceptance Criteria

1. THE Command_Center SHALL be accessible only at the path `/owner/command`
2. THE Owner_Auth login page SHALL be accessible only at the path `/owner/login`
3. THE System SHALL never link to `/owner/login` or `/owner/command` from any public-facing page, navigation menu, or sitemap
4. WHEN an unauthenticated request is made to `/owner/command`, THE OwnerController SHALL redirect the request to `/owner/login`
5. WHEN an authenticated owner requests `/owner/login`, THE OwnerController SHALL redirect the request to `/owner/command`

---

### Requirement 2: Owner Authentication

**User Story:** As the platform owner, I want to log in with my email and password verified against environment variables, so that my credentials are never stored in a database.

#### Acceptance Criteria

1. THE Owner_Auth SHALL read the owner's email from the `OWNER_EMAIL` environment variable
2. THE Owner_Auth SHALL read the owner's bcrypt password hash from the `OWNER_PASSWORD_HASH` environment variable
3. WHEN the owner submits the login form, THE OwnerController SHALL compare the submitted email against `OWNER_EMAIL` using a constant-time string comparison
4. WHEN the owner submits the login form, THE OwnerController SHALL verify the submitted password against `OWNER_PASSWORD_HASH` using PHP's `password_verify()` function
5. WHEN both the email and password are valid, THE OwnerController SHALL create a session entry `owner_authenticated = true` and redirect to `/owner/command`
6. WHEN either the email or password is invalid, THE OwnerController SHALL return the login view with a generic error message that does not reveal which field failed
7. THE Owner_Auth SHALL operate independently of Supabase and the existing `AuthController`

---

### Requirement 3: Session Management

**User Story:** As the platform owner, I want my session to expire after 2 hours of inactivity, so that an unattended browser cannot be exploited.

#### Acceptance Criteria

1. WHEN the owner successfully authenticates, THE Session SHALL record the authentication timestamp as `owner_auth_time`
2. WHILE the owner is navigating the Command_Center, THE OwnerController SHALL verify that the elapsed time since `owner_auth_time` does not exceed 7200 seconds on every request
3. WHEN the elapsed time since `owner_auth_time` exceeds 7200 seconds, THE OwnerController SHALL destroy the owner session and redirect to `/owner/login`
4. WHEN the owner clicks the logout control, THE OwnerController SHALL destroy the owner session and redirect to `/owner/login`
5. THE Session SHALL store only `owner_authenticated` and `owner_auth_time` — no owner credentials SHALL be stored in the session

---

### Requirement 4: Brute-Force Lockout

**User Story:** As the platform owner, I want failed login attempts to trigger a lockout, so that automated credential-stuffing attacks are blocked.

#### Acceptance Criteria

1. THE Rate_Limiter SHALL track failed login attempts keyed by the requesting IP address
2. WHEN a login attempt fails, THE Rate_Limiter SHALL increment the failure counter for that IP address
3. WHEN the failure counter for an IP address reaches 5, THE Rate_Limiter SHALL record a lockout timestamp for that IP
4. WHILE an IP address is locked out, THE OwnerController SHALL reject all login attempts from that IP and return the login view with a message indicating the remaining lockout duration in minutes
5. WHEN 3600 seconds have elapsed since the lockout timestamp, THE Rate_Limiter SHALL clear the lockout and failure counter for that IP address
6. WHEN a login attempt succeeds, THE Rate_Limiter SHALL clear the failure counter for that IP address

---

### Requirement 5: Login Page UI

**User Story:** As the platform owner, I want a clean, minimal login page styled consistently with the platform theme, so that the experience feels intentional and secure.

#### Acceptance Criteria

1. THE login page SHALL render using the dark theme with background color `#050810` and accent color `#00f5a0`
2. THE login page SHALL display an email input field and a password input field
3. THE login page SHALL display a submit button labeled "Access Command Center"
4. THE login page SHALL use the Clash Display and Epilogue font families consistent with the user dashboard
5. IF a login error exists, THEN THE login page SHALL display the error message visibly above the form
6. THE login page SHALL not display any Keeption branding links, navigation, or footer that could reveal the page's existence to a casual observer

---

### Requirement 6: Dashboard Layout and Navigation

**User Story:** As the platform owner, I want a persistent sidebar to navigate between all dashboard sections without a full page reload, so that I can move quickly between operational views.

#### Acceptance Criteria

1. THE Command_Center SHALL render as a single Blade file containing all Sections
2. THE Sidebar SHALL list navigation items for: Home, Free Users, Pro Users, Teams, Revenue & Finance, Platform Analytics, Alerts Center, Communications, and Platform Settings
3. WHEN the owner clicks a Sidebar navigation item, THE Command_Center SHALL show the corresponding Section and hide all other Sections without a server round-trip
4. THE Sidebar SHALL visually highlight the currently active Section's navigation item
5. THE Command_Center SHALL display the owner's email (from `OWNER_EMAIL`) in the Sidebar header as an identity indicator
6. THE Command_Center SHALL display a logout button in the Sidebar that submits a POST request to `/owner/logout`
7. THE Command_Center SHALL apply the dark theme: background `#050810`, accent `#00f5a0`, Clash Display and Epilogue fonts

---

### Requirement 7: Home Section — Metric Cards

**User Story:** As the platform owner, I want to see 8 key platform metrics at a glance on the Home section, so that I can assess platform health immediately upon login.

#### Acceptance Criteria

1. THE Home Section SHALL display exactly 8 Metric_Cards arranged in a responsive grid
2. THE 8 Metric_Cards SHALL cover the following KPIs: Total Users, Active Users (30d), Pro Subscribers, Teams Workspaces, Monthly Recurring Revenue, Total Storage Used, Support Tickets Open, and Platform Uptime
3. EACH Metric_Card SHALL display a label, a primary value, and a trend indicator (up/down/neutral)
4. THE Home Section SHALL populate all Metric_Card values with Mock_Data
5. THE Metric_Cards SHALL use the accent color `#00f5a0` for positive trend indicators

---

### Requirement 8: Free Users Section

**User Story:** As the platform owner, I want to view a table of all free-tier users, so that I can monitor adoption and identify conversion opportunities.

#### Acceptance Criteria

1. THE Free Users Section SHALL display a paginated table of free-tier user records
2. THE table SHALL include columns for: User ID, Email, Registration Date, Storage Used, Last Active, and Status
3. THE Free Users Section SHALL populate all table rows with Mock_Data
4. THE table SHALL support client-side search filtering by email
5. THE Free Users Section SHALL display a total count of free users above the table

---

### Requirement 9: Pro Users Section

**User Story:** As the platform owner, I want to view a table of all pro-tier subscribers, so that I can monitor subscription health and churn risk.

#### Acceptance Criteria

1. THE Pro Users Section SHALL display a paginated table of pro-tier user records
2. THE table SHALL include columns for: User ID, Email, Subscription Start, Next Billing Date, Storage Used, Last Active, and Status
3. THE Pro Users Section SHALL populate all table rows with Mock_Data
4. THE table SHALL support client-side search filtering by email
5. THE Pro Users Section SHALL display a total count of pro subscribers above the table

---

### Requirement 10: Teams Section

**User Story:** As the platform owner, I want to view all team workspaces, so that I can monitor enterprise adoption and workspace activity.

#### Acceptance Criteria

1. THE Teams Section SHALL display a paginated table of team workspace records
2. THE table SHALL include columns for: Workspace ID, Workspace Name, Owner Email, Member Count, Storage Used, Created Date, and Status
3. THE Teams Section SHALL populate all table rows with Mock_Data
4. THE table SHALL support client-side search filtering by workspace name
5. THE Teams Section SHALL display a total count of team workspaces above the table

---

### Requirement 11: Revenue & Finance Section

**User Story:** As the platform owner, I want to see revenue metrics and a transaction history, so that I can track financial performance.

#### Acceptance Criteria

1. THE Revenue Section SHALL display summary cards for: Monthly Recurring Revenue, Annual Recurring Revenue, Average Revenue Per User, and Churn Rate
2. THE Revenue Section SHALL display a mock monthly revenue chart rendered using an inline SVG or canvas element
3. THE Revenue Section SHALL display a table of recent mock transactions with columns: Date, User Email, Plan, Amount, and Status
4. THE Revenue Section SHALL populate all values and table rows with Mock_Data

---

### Requirement 12: Platform Analytics Section

**User Story:** As the platform owner, I want to see platform usage analytics, so that I can understand user behavior and feature adoption.

#### Acceptance Criteria

1. THE Analytics Section SHALL display metrics for: Daily Active Users, Weekly Active Users, Monthly Active Users, Average Session Duration, Files Uploaded (30d), and Storage Growth Rate
2. THE Analytics Section SHALL display a mock user growth chart rendered using an inline SVG or canvas element
3. THE Analytics Section SHALL display a top file types breakdown table with columns: File Type, Count, and Percentage of Total
4. THE Analytics Section SHALL populate all values with Mock_Data

---

### Requirement 13: Alerts Center Section

**User Story:** As the platform owner, I want to see a list of platform alerts and system notices, so that I can respond to operational issues quickly.

#### Acceptance Criteria

1. THE Alerts Section SHALL display a list of mock alert entries
2. EACH alert entry SHALL include: severity level (info/warning/critical), timestamp, and message text
3. THE Alerts Section SHALL visually distinguish severity levels using color coding: info in blue, warning in amber, critical in red
4. THE Alerts Section SHALL display a count of unread alerts in the Sidebar navigation item badge
5. THE Alerts Section SHALL populate all alert entries with Mock_Data

---

### Requirement 14: Communications Section

**User Story:** As the platform owner, I want a communications panel to draft and review platform-wide announcements, so that I can manage user messaging from the command center.

#### Acceptance Criteria

1. THE Communications Section SHALL display a list of mock past announcements with columns: Date, Subject, Audience (All/Free/Pro/Teams), and Status (Draft/Sent)
2. THE Communications Section SHALL display a "New Announcement" form with fields: Subject, Audience selector, and Message body
3. WHEN the owner submits the "New Announcement" form, THE Communications Section SHALL display a confirmation message indicating the announcement was queued (no actual send shall occur in the mock implementation)
4. THE Communications Section SHALL populate the announcements list with Mock_Data

---

### Requirement 15: Platform Settings Section

**User Story:** As the platform owner, I want to view and manage platform configuration settings, so that I can control feature flags and operational parameters.

#### Acceptance Criteria

1. THE Settings Section SHALL display toggle controls for mock feature flags including: Maintenance Mode, New Registrations Enabled, Pro Plan Available, and Teams Plan Available
2. THE Settings Section SHALL display read-only fields showing: App Version, Environment (production/staging), and Laravel Version
3. WHEN the owner toggles a feature flag, THE Settings Section SHALL update the toggle state visually and display a "Settings saved" confirmation (no server persistence shall occur in the mock implementation)
4. THE Settings Section SHALL populate all values with Mock_Data

---

### Requirement 16: Data Read-Only Constraint

**User Story:** As the platform owner, I want all dashboard data to be read-only mock placeholders in the initial implementation, so that no accidental writes are made to the live Supabase database.

#### Acceptance Criteria

1. THE Command_Center SHALL not execute any Supabase queries in the initial implementation
2. THE Command_Center SHALL not execute any database write operations in the initial implementation
3. THE OwnerController SHALL serve all dashboard data from static Mock_Data arrays defined within the controller or view
4. THE Command_Center SHALL display a visible "MOCK DATA — Not connected to live database" banner in the dashboard header to remind the owner that data is not live

---

### Requirement 17: Styling Consistency

**User Story:** As the platform owner, I want the command center to use the same visual design language as the user dashboard, so that the experience feels cohesive.

#### Acceptance Criteria

1. THE Command_Center SHALL use background color `#050810` as the primary page background
2. THE Command_Center SHALL use `#00f5a0` as the primary accent color for highlights, active states, and positive indicators
3. THE Command_Center SHALL load the Clash Display font for headings and the Epilogue font for body text, consistent with the user dashboard
4. THE Command_Center SHALL use card components with a slightly elevated background (e.g., `#0d1117` or similar) and subtle border styling consistent with the user dashboard aesthetic
5. THE Command_Center SHALL be responsive and usable at viewport widths of 1024px and above
