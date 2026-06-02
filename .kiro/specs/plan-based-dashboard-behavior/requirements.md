# Requirements Document

## Introduction

Plan-Based Dashboard Behavior for Keeption Vault introduces a tiered plan system (Free, Pro, Teams) that controls storage limits, file size caps, shared link quotas, version history retention, feature access, and visual indicators across the dashboard. The system reads the user's plan from Supabase on login and applies a plan configuration object that gates every feature. Upgrade prompts, warning banners, and locked-feature modals guide users toward higher tiers. Downgrade behavior includes grace periods and staged file deletion to protect user data.

---

## Glossary

- **Dashboard**: The main authenticated view of Keeption Vault rendered via Blade templates.
- **Plan_Config**: A server-side configuration object derived from the user's plan field, containing all limits and feature flags for the current session.
- **Plan_Detector**: The Laravel service responsible for reading the user's plan from Supabase and constructing the Plan_Config on login.
- **Storage_Bar**: The visual storage usage indicator shown in the topbar and sidebar.
- **Upload_Controller**: The Laravel controller that handles file upload requests.
- **Link_Controller**: The Laravel controller that handles shared link creation and management.
- **Version_Manager**: The Laravel service responsible for version history retention and cleanup.
- **Upgrade_Modal**: The UI modal that prompts the user to upgrade their plan.
- **Warning_Banner**: The dismissible banner displayed at the top of the dashboard when storage thresholds are crossed.
- **Audit_Trail**: The complete, immutable log of all team member actions within a Teams workspace.
- **Admin_Dashboard**: The restricted admin-only page available to Teams plan administrators.
- **Grace_Period**: The 30-day window after a downgrade during which the user can download and delete but not upload.
- **Pending_Deletion_Folder**: A virtual folder holding files queued for permanent deletion after the grace period expires.
- **Free_Plan**: The $0/mo tier with 5 GB storage, 500 MB max file size, 5 active shared links, and 7-day version history.
- **Pro_Plan**: The $3/mo tier with 100 GB storage, 10 GB max file size, unlimited shared links, and 90-day version history.
- **Teams_Plan**: The $8/mo per-user tier with 500 GB shared storage, 50 GB max file size, unlimited shared links, and 180-day version history.
- **Supabase**: The backend database and auth provider storing user records including the plan field.
- **Chunked_Upload**: A multi-part upload mechanism with pause, resume, progress bar, speed, and ETA display.
- **Self_Destruct_Link**: A shared link that automatically deletes itself after a set time or number of views.
- **AI_Smart_Search**: A search feature that queries file names, document content, photo faces/objects, and video speech using natural language.
- **Auto_Camera_Backup**: A feature connecting the mobile app to automatically back up photos and videos in the background.
- **Duplicate_Finder**: A tool that scans files for duplicates, limited to 100 files on the Free plan.
- **Collaborative_Folder**: A folder with real-time sync, presence indicators, and attributed change history for Teams members.

---

## Requirements

### Requirement 1: Plan Detection and Configuration Loading

**User Story:** As a user, I want the dashboard to load with the correct plan limits and feature flags, so that every feature reflects my current subscription tier.

#### Acceptance Criteria

1. WHEN a user successfully authenticates, THE Plan_Detector SHALL read the user's `plan` field from the Supabase users table and construct a Plan_Config object containing storage cap, file size limit, link limit, version history days, and feature flags for Self_Destruct_Link, AI_Smart_Search, Auto_Camera_Backup, Collaborative_Folder, Audit_Trail, and Admin_Dashboard.
2. THE Plan_Config SHALL support exactly three plan values: `free`, `pro`, and `teams`.
3. WHEN the Plan_Config is constructed, THE Dashboard SHALL apply all limits and feature flags before rendering any component.
4. IF the user's `plan` field is absent or contains an unrecognised value, THEN THE Plan_Detector SHALL default to the Free_Plan configuration and log a warning.
5. WHEN a user's plan is upgraded in Supabase, THE Dashboard SHALL reflect the new Plan_Config on the next page load without requiring a cache flush.

---

### Requirement 2: Free Plan — Storage Limits and Upload Enforcement

**User Story:** As a Free plan user, I want clear feedback when I approach or reach my storage limit, so that I understand my usage and can decide whether to upgrade.

#### Acceptance Criteria

1. THE Storage_Bar SHALL display a total capacity of 5 GB for Free_Plan users.
2. THE Storage_Bar SHALL fill proportionally to the ratio of bytes used to 5 GB.
3. WHEN a Free_Plan user's storage usage reaches or exceeds 4 GB (80% of 5 GB), THE Dashboard SHALL display a yellow Warning_Banner reading "You're using 80% of your free storage — upgrade to Pro for 100 GB."
4. WHEN a Free_Plan user's storage usage reaches or exceeds 4.75 GB (95% of 5 GB), THE Dashboard SHALL replace the yellow Warning_Banner with a red Warning_Banner reading "Almost full — uploads will stop at 5 GB."
5. WHEN a Free_Plan user's storage usage reaches or exceeds 5 GB, THE Upload_Controller SHALL disable the upload button in the UI.
6. WHEN a Free_Plan user clicks the disabled upload button, THE Dashboard SHALL display an Upgrade_Modal with the message "Storage full — upgrade to continue uploading" and an "Upgrade to Pro" call-to-action button.
7. WHEN a Free_Plan user attempts to upload a single file whose size exceeds 500 MB, THE Upload_Controller SHALL reject the upload before transfer begins and THE Dashboard SHALL display a toast notification reading "File too large — Free plan allows up to 500 MB per file. Upgrade to Pro for 10 GB per file."
8. FOR ALL Free_Plan upload attempts, THE Upload_Controller SHALL evaluate file size and storage headroom before initiating any data transfer.

---

### Requirement 3: Free Plan — Shared Links Quota

**User Story:** As a Free plan user, I want to know how many shared links I have active, so that I can manage them within my plan's limit.

#### Acceptance Criteria

1. THE Link_Controller SHALL enforce a maximum of 5 simultaneously active shared links for Free_Plan users.
2. THE My_Shared_Links page SHALL display a counter in the format "X of 5 links used" reflecting the current count of active links.
3. WHEN a Free_Plan user's active link count reaches or exceeds 4, THE counter SHALL render in red.
4. WHEN a Free_Plan user attempts to create a shared link and already has 5 active links, THE Link_Controller SHALL reject the creation request and THE Dashboard SHALL display a modal reading "Link limit reached — Free plan allows 5 active links. Delete an existing link or upgrade to Pro for unlimited links."
5. WHEN a Free_Plan user deletes an active shared link, THE Link_Controller SHALL decrement the active link count immediately and reflect the updated count in the UI counter.

---

### Requirement 4: Free Plan — Version History Retention

**User Story:** As a Free plan user, I want to understand my version history limits, so that I know how far back I can restore files.

#### Acceptance Criteria

1. THE Version_Manager SHALL retain file versions for a maximum of 7 days for Free_Plan users.
2. WHEN a file version's age exceeds 7 days for a Free_Plan user, THE Version_Manager SHALL permanently delete that version.
3. THE Version_History panel SHALL display a label reading "Free plan — 7 days of history. Upgrade for 90 days."
4. THE Version_Manager SHALL evaluate version age daily and delete all versions exceeding the plan's retention limit.

---

### Requirement 5: Free Plan — Locked Pro Features

**User Story:** As a Free plan user, I want to see which Pro features exist, so that I can understand what I would gain by upgrading.

#### Acceptance Criteria

1. THE Dashboard SHALL render Self_Destruct_Link, AI_Smart_Search, and Auto_Camera_Backup as visible but non-functional UI elements with a padlock icon and a gold "Pro" badge.
2. WHEN a Free_Plan user clicks a locked feature element, THE Dashboard SHALL display an Upgrade_Modal prompting the user to upgrade to Pro.
3. THE Duplicate_Finder SHALL limit its scan to a maximum of 100 files for Free_Plan users and SHALL display a label indicating this limit.
4. WHEN a Free_Plan user initiates a Duplicate_Finder scan, THE Duplicate_Finder SHALL stop processing after evaluating 100 files and SHALL notify the user that the scan was limited.

---

### Requirement 6: Free Plan — Dashboard Visual Indicators

**User Story:** As a Free plan user, I want visual cues that identify my plan tier, so that I always know my current plan at a glance.

#### Acceptance Criteria

1. THE Dashboard topbar user dropdown SHALL display a "Free Plan" pill badge adjacent to the user's name.
2. THE Storage_Bar SHALL render with a plain green gradient for Free_Plan users.
3. THE sidebar upgrade button SHALL render with a soft glow animation for Free_Plan users.

---

### Requirement 7: Pro Plan — Storage Limits and Upload Enforcement

**User Story:** As a Pro plan user, I want a 100 GB storage cap with large-file upload support, so that I can store and transfer significantly larger files.

#### Acceptance Criteria

1. THE Storage_Bar SHALL display a total capacity of 100 GB for Pro_Plan users.
2. WHEN a Pro_Plan user's storage usage reaches or exceeds 80 GB (80% of 100 GB), THE Dashboard SHALL display a yellow Warning_Banner prompting the user to consider the Teams plan.
3. WHEN a Pro_Plan user's storage usage reaches or exceeds 95 GB (95% of 100 GB), THE Dashboard SHALL replace the yellow Warning_Banner with a red Warning_Banner.
4. WHEN a Pro_Plan user attempts to upload a single file whose size exceeds 10 GB, THE Upload_Controller SHALL reject the upload before transfer begins and THE Dashboard SHALL display a toast notification mentioning the Teams plan as the next tier.
5. WHEN a Pro_Plan user uploads a file, THE Upload_Controller SHALL use Chunked_Upload and THE Dashboard SHALL display a progress bar, upload speed, estimated time remaining, and pause/resume controls.
6. WHEN a Pro_Plan user pauses a Chunked_Upload, THE Upload_Controller SHALL preserve the upload state so that resuming continues from the last successfully transferred chunk.

---

### Requirement 8: Pro Plan — Shared Links

**User Story:** As a Pro plan user, I want unlimited shared links with full configuration options, so that I can share files without restrictions.

#### Acceptance Criteria

1. THE Link_Controller SHALL impose no upper limit on active shared links for Pro_Plan users.
2. THE My_Shared_Links page SHALL NOT display a link usage counter for Pro_Plan users.
3. WHEN a Pro_Plan user creates a shared link, THE Link_Controller SHALL expose configuration options for expiry date, password protection, view limit, and download permission.

---

### Requirement 9: Pro Plan — Self-Destruct Links

**User Story:** As a Pro plan user, I want to create self-destructing shared links, so that sensitive files are automatically inaccessible after a defined condition is met.

#### Acceptance Criteria

1. THE Link_Controller SHALL make the Self_Destruct_Link creation wizard fully accessible to Pro_Plan users.
2. WHEN a Pro_Plan user creates a Self_Destruct_Link, THE Link_Controller SHALL accept a time-based expiry, a view-count limit, or both as destruction conditions.
3. WHEN a Self_Destruct_Link's destruction condition is met, THE Link_Controller SHALL automatically deactivate the link and prevent further access.
4. WHEN a Pro_Plan user submits the Self_Destruct_Link creation form, THE Dashboard SHALL require the user to type the word "CONFIRM" in a confirmation text box before the link is created.

---

### Requirement 10: Pro Plan — Version History Retention

**User Story:** As a Pro plan user, I want 90 days of version history, so that I can restore files from much further back.

#### Acceptance Criteria

1. THE Version_Manager SHALL retain file versions for a maximum of 90 days for Pro_Plan users.
2. WHEN a file version's age exceeds 90 days for a Pro_Plan user, THE Version_Manager SHALL permanently delete that version.
3. THE Version_History panel SHALL display a label reading "Pro plan — 90 days of history."

---

### Requirement 11: Pro Plan — AI Smart Search

**User Story:** As a Pro plan user, I want to search my vault using natural language and content-aware queries, so that I can find files without remembering exact names.

#### Acceptance Criteria

1. THE AI_Smart_Search SHALL be fully accessible to Pro_Plan users with no usage limits.
2. THE AI_Smart_Search SHALL accept natural language queries and match against file names, document text content, faces in photos, objects in images, and spoken words in videos.
3. WHEN a Pro_Plan user submits a search query, THE AI_Smart_Search SHALL return results ranked by relevance.

---

### Requirement 12: Pro Plan — Auto Camera Backup

**User Story:** As a Pro plan user, I want automatic background photo and video backup from my mobile device, so that my media is always safely stored without manual uploads.

#### Acceptance Criteria

1. THE Auto_Camera_Backup feature SHALL be fully accessible to Pro_Plan users.
2. WHEN Auto_Camera_Backup is enabled and the mobile app is running in the background, THE Auto_Camera_Backup SHALL automatically upload new photos and videos to the vault.
3. THE Photos page SHALL display an "All photos backed up" status indicator when Auto_Camera_Backup is active and no pending items remain.

---

### Requirement 13: Pro Plan — Dashboard Visual Indicators

**User Story:** As a Pro plan user, I want visual cues that distinguish my Pro tier, so that I can immediately recognise my upgraded status.

#### Acceptance Criteria

1. THE Dashboard topbar user dropdown SHALL display a "Pro" badge in electric green adjacent to the user's name for Pro_Plan users.
2. THE Storage_Bar SHALL render with a green-to-cyan gradient for Pro_Plan users.
3. THE sidebar upgrade button SHALL change its label to "Manage Plan" and SHALL link to the billing page for Pro_Plan users.
4. THE user avatar SHALL render with a subtle green glow ring for Pro_Plan users.
5. WHEN a user upgrades from Free_Plan to Pro_Plan, THE Dashboard SHALL display a welcome modal on the next login reading "Welcome to Pro — your vault has been upgraded."

---

### Requirement 14: Teams Plan — Storage and Upload

**User Story:** As a Teams plan member, I want a shared 500 GB storage pool with large-file support, so that my team can collaborate on large assets.

#### Acceptance Criteria

1. THE Storage_Bar SHALL display the team's total shared capacity of 500 GB for Teams_Plan users.
2. THE topbar Storage_Bar tooltip SHALL display a per-member storage usage breakdown on hover for Teams_Plan users.
3. WHEN a Teams_Plan user attempts to upload a single file whose size exceeds 50 GB, THE Upload_Controller SHALL reject the upload before transfer begins and SHALL display a toast notification.
4. WHEN a Teams_Plan user uploads a file, THE Upload_Controller SHALL use Chunked_Upload with pause and resume controls.

---

### Requirement 15: Teams Plan — Collaborative Folders

**User Story:** As a Teams plan member, I want real-time collaborative folders, so that my team can work on shared files simultaneously with full visibility of each other's actions.

#### Acceptance Criteria

1. THE Collaborative_Folder SHALL sync changes in real time across all Teams_Plan members viewing the same folder.
2. WHEN a Teams_Plan member is viewing a Collaborative_Folder, THE Dashboard SHALL display that member's avatar as a presence indicator within the folder view.
3. WHEN a Teams_Plan member is renaming a file or folder, THE Dashboard SHALL display a typing indicator to other members viewing the same location.
4. WHEN a Teams_Plan member adds, deletes, or modifies a file, THE Audit_Trail SHALL attribute the action to that member with the format "Added by [name]" or "Deleted by [name]" in the activity log.

---

### Requirement 16: Teams Plan — Audit Trail

**User Story:** As a Teams plan administrator, I want a complete audit trail of all team actions, so that I can monitor activity and investigate incidents.

#### Acceptance Criteria

1. THE Audit_Trail SHALL record every upload, download, deletion, share, link creation, folder change, and login performed by any Teams_Plan member.
2. EACH Audit_Trail entry SHALL contain the action type, file name, team member name, date and time, and device identifier.
3. THE Audit_Trail view SHALL support filtering by team member, action type, and date range.
4. THE Audit_Trail view SHALL provide an export function that produces a CSV file containing all visible entries.

---

### Requirement 17: Teams Plan — Admin Dashboard

**User Story:** As a Teams plan administrator, I want a dedicated admin dashboard, so that I can manage team members, storage, shared links, and billing from one place.

#### Acceptance Criteria

1. THE Admin_Dashboard SHALL be accessible only to users with the Admin role within a Teams_Plan workspace.
2. THE Admin_Dashboard Team_Members panel SHALL display each member's name, email, role, storage used, and last active timestamp.
3. THE Admin_Dashboard Team_Members panel SHALL allow the Admin to change a member's role, reset a member's password, remove a member, invite a new member by email, and assign roles of Admin, Editor, or Viewer.
4. THE Admin_Dashboard Storage_Overview panel SHALL display a visual per-member storage breakdown, total pool usage, and a projection of when additional storage will be needed.
5. THE Admin_Dashboard Shared_Links panel SHALL display all active shared links created by any team member and SHALL allow the Admin to view, edit, or revoke any link.
6. THE Admin_Dashboard Billing panel SHALL display the current plan, seat count, next billing date, payment method, and invoice history, and SHALL allow the Admin to add or remove seats and download invoices.

---

### Requirement 18: Teams Plan — Version History Retention

**User Story:** As a Teams plan member, I want 180 days of attributed version history, so that I can trace who made each change and restore from a long history window.

#### Acceptance Criteria

1. THE Version_Manager SHALL retain file versions for a maximum of 180 days for Teams_Plan users.
2. WHEN a file version's age exceeds 180 days for a Teams_Plan user, THE Version_Manager SHALL permanently delete that version.
3. THE Version_History panel SHALL display a label reading "Teams plan — 180 days of history."
4. EACH version entry in the Version_History panel SHALL display the name of the Teams_Plan member who created that version.

---

### Requirement 19: Teams Plan — Priority Support

**User Story:** As a Teams plan member, I want priority support access, so that I can get help quickly when issues arise.

#### Acceptance Criteria

1. THE sidebar SHALL display a "Priority Support" button for Teams_Plan users.
2. WHEN a Teams_Plan user clicks the Priority Support button, THE Dashboard SHALL open a support chat modal displaying a "Response within 2 hours" badge.

---

### Requirement 20: Teams Plan — Dashboard Visual Indicators

**User Story:** As a Teams plan member, I want visual cues that identify my Teams tier, so that I can immediately recognise my team workspace context.

#### Acceptance Criteria

1. THE Dashboard topbar user dropdown SHALL display a "Teams" badge with a blue-cyan gradient adjacent to the user's name for Teams_Plan users.
2. THE sidebar SHALL display a Team section containing links to Team Files, Admin_Dashboard, Audit_Trail, and Team Members for Teams_Plan users.
3. THE Storage_Bar SHALL display the team's total shared storage with a per-member breakdown tooltip on hover for Teams_Plan users.
4. THE user avatar SHALL render with a small team icon overlay for Teams_Plan users.

---

### Requirement 21: Teams Plan — New Member Onboarding

**User Story:** As a Teams plan administrator, I want to add new members who receive an invitation and are automatically linked to the team workspace, so that onboarding is seamless.

#### Acceptance Criteria

1. WHEN a Teams_Plan Admin adds a new seat, THE system SHALL send an invitation email to the specified address.
2. WHEN the invited user accepts the invitation and completes login, THE Plan_Detector SHALL link the account to the team workspace and apply the Teams_Plan configuration.

---

### Requirement 22: Upgrade Prompts and Contextual Modals

**User Story:** As a user on any plan, I want contextual upgrade prompts at the right moments, so that I understand the value of upgrading without being interrupted unnecessarily.

#### Acceptance Criteria

1. WHEN a Free_Plan user's storage usage reaches 80%, THE Dashboard SHALL display a yellow Warning_Banner with an upgrade call-to-action.
2. WHEN a Free_Plan user's storage usage reaches 95%, THE Dashboard SHALL replace the yellow Warning_Banner with a red Warning_Banner.
3. WHEN a Free_Plan user attempts to upload a file exceeding 500 MB, THE Dashboard SHALL display an Upgrade_Modal before the upload begins.
4. WHEN a Free_Plan user attempts to create a 6th active shared link, THE Dashboard SHALL display an Upgrade_Modal.
5. WHEN a Free_Plan user clicks a locked Pro feature, THE Dashboard SHALL display an Upgrade_Modal.
6. WHEN a Free_Plan user has been active for 7 or more days, THE Dashboard home page SHALL display a non-intrusive upgrade card.
7. WHEN a Pro_Plan user's storage usage reaches 80 GB, THE Dashboard SHALL display a Warning_Banner mentioning the Teams plan.
8. WHEN a Pro_Plan user attempts to upload a file exceeding 10 GB, THE Dashboard SHALL display a modal mentioning the Teams plan.
9. THE billing page SHALL display a comparison of Pro and Teams plans for Pro_Plan users.

---

### Requirement 23: Downgrade Behavior — Storage Grace Period

**User Story:** As a user who has downgraded to a lower plan, I want a grace period to manage my files, so that I do not lose data immediately upon downgrade.

#### Acceptance Criteria

1. WHEN a user's storage usage exceeds the new plan's storage cap after a downgrade, THE system SHALL begin a 30-day Grace_Period.
2. WHILE the Grace_Period is active, THE Upload_Controller SHALL reject all new upload requests and THE Dashboard SHALL display a banner explaining the grace period.
3. WHILE the Grace_Period is active, THE Dashboard SHALL allow the user to download and delete files without restriction.
4. WHEN the 30-day Grace_Period expires and storage usage still exceeds the plan cap, THE system SHALL move the oldest excess files to the Pending_Deletion_Folder and SHALL send a notification email to the user.
5. WHEN files have been in the Pending_Deletion_Folder for 14 days, THE system SHALL permanently delete those files and SHALL send a confirmation email to the user.

---

### Requirement 24: Downgrade Behavior — Shared Links and Version History

**User Story:** As a user who has downgraded, I want my shared links and version history handled predictably, so that I understand what remains accessible.

#### Acceptance Criteria

1. WHEN a user downgrades, THE Link_Controller SHALL keep all existing shared links active for the duration of the 30-day Grace_Period.
2. WHEN the 30-day Grace_Period expires and the user's active link count exceeds the new plan's limit, THE Link_Controller SHALL deactivate the excess links and SHALL send a notification email to the user.
3. WHEN a user downgrades, THE Version_Manager SHALL immediately hide version history entries older than the new plan's retention limit from the Version_History panel.
4. THE Version_Manager SHALL retain the hidden version data for 30 days after downgrade before permanently deleting it.
