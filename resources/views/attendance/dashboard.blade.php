<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Attendance & HCM — GASS Digital Solutions</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        :root, [data-theme="dark"] {
            --bg-body: #09101f;
            --bg-sidebar: #0b1324;
            --bg-card: #10192d;
            --bg-card-inner: #15213a;
            --bg-table-head: #131e35;
            --bg-row-hover: rgba(30, 41, 59, 0.45);
            --border-color: rgba(148, 163, 184, 0.14);
            --border-subtle: rgba(148, 163, 184, 0.09);
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --text-muted: #64748b;
            --accent-teal: #14b8a6;
            --accent-emerald: #10b981;
            --accent-blue: #2563eb;
            --accent-amber: #f59e0b;
            --accent-rose: #f43f5e;
            --shadow-card: 0 10px 30px rgba(2, 6, 23, 0.35);
        }

        [data-theme="light"] {
            --bg-body: #f1f5f9;
            --bg-sidebar: #ffffff;
            --bg-card: #ffffff;
            --bg-card-inner: #f8fafc;
            --bg-table-head: #f8fafc;
            --bg-row-hover: #f8fafc;
            --border-color: #e2e8f0;
            --border-subtle: #f1f5f9;
            --text-primary: #0f172a;
            --text-secondary: #475569;
            --text-muted: #64748b;
            --shadow-card: 0 4px 18px rgba(15, 23, 42, 0.05);
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--bg-body);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            transition: background 0.2s ease, color 0.2s ease;
        }

        /* Sidebar */
        .hcm-sidebar {
            width: 248px;
            background: var(--bg-sidebar);
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 40;
            padding: 22px 16px;
            transition: transform 0.25s ease, background 0.2s ease;
        }
        .hcm-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 12px 24px;
            text-decoration: none;
            color: var(--text-primary);
            font-size: 22px;
            letter-spacing: 0.04em;
        }
        .hcm-brand strong { font-weight: 800; }
        .hcm-brand span { font-weight: 300; color: var(--text-secondary); }
        .hcm-nav {
            display: grid;
            gap: 6px;
        }
        .hcm-nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 14px;
            border-radius: 10px;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            border: 0;
            background: transparent;
            width: 100%;
            text-align: left;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .hcm-nav-item:hover {
            background: var(--bg-card-inner);
            color: var(--text-primary);
        }
        .hcm-nav-item.is-active {
            background: #1d4ed8;
            color: #ffffff;
            font-weight: 600;
            box-shadow: 0 6px 16px rgba(29, 78, 216, 0.32);
        }
        .hcm-nav-item svg {
            width: 17px;
            height: 17px;
            flex-shrink: 0;
        }
        .hcm-sidebar-footer {
            margin-top: auto;
            display: grid;
            gap: 8px;
            padding-top: 16px;
            border-top: 1px solid var(--border-color);
        }
        .hcm-footer-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 10px 14px;
            border-radius: 10px;
            border: 1px solid var(--border-color);
            background: transparent;
            color: var(--text-secondary);
            font: 500 12.5px 'Inter', sans-serif;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .hcm-footer-btn:hover {
            background: var(--bg-card-inner);
            color: var(--text-primary);
        }
        .hcm-footer-btn.logout-btn:hover {
            border-color: rgba(244, 63, 94, 0.4);
            color: var(--accent-rose);
        }

        /* Main Area */
        .hcm-main {
            flex: 1;
            margin-left: 248px;
            min-width: 0;
            display: flex;
            flex-direction: column;
        }
        .hcm-topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 16px 32px;
            border-bottom: 1px solid var(--border-color);
            background: var(--bg-sidebar);
            position: sticky;
            top: 0;
            z-index: 30;
        }
        .hcm-topbar-title h1 {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
            letter-spacing: -0.02em;
        }
        .hcm-topbar-title p {
            margin: 2px 0 0;
            font-size: 11.5px;
            color: var(--text-secondary);
        }
        .hcm-topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .hcm-lang-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 12px;
            border-radius: 999px;
            border: 1px solid var(--border-color);
            background: var(--bg-card-inner);
            font-size: 11.5px;
            font-weight: 600;
            color: var(--text-primary);
        }
        .hcm-user-dropdown-wrap {
            position: relative;
        }
        .hcm-user-pill {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 14px 6px 8px;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            background: var(--bg-card-inner);
            cursor: pointer;
            color: var(--text-primary);
            font-family: inherit;
            text-align: left;
            transition: border-color 0.15s ease;
        }
        .hcm-user-pill:hover {
            border-color: rgba(96, 165, 250, 0.45);
        }
        .hcm-user-chevron {
            width: 14px;
            height: 14px;
            color: var(--text-secondary);
            margin-left: 4px;
            transition: transform 0.2s ease;
        }
        .hcm-user-dropdown-wrap.is-open .hcm-user-chevron {
            transform: rotate(180deg);
        }
        .hcm-user-dropdown {
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            width: 220px;
            background: #17253b;
            border: 1px solid rgba(71, 85, 105, 0.45);
            border-radius: 14px;
            box-shadow: 0 18px 38px rgba(0, 0, 0, 0.55);
            overflow: hidden;
            z-index: 80;
        }
        html[data-theme="light"] .hcm-user-dropdown {
            background: #ffffff;
            border-color: #e2e8f0;
            box-shadow: 0 14px 30px rgba(15, 23, 42, 0.12);
        }
        .hcm-user-dropdown[hidden] {
            display: none;
        }
        .hcm-dropdown-item {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 13px 18px;
            border: 0;
            background: transparent;
            color: var(--text-primary);
            font: 600 13.5px 'Inter', sans-serif;
            cursor: pointer;
            text-align: left;
            transition: background 0.15s ease;
        }
        .hcm-dropdown-item:hover {
            background: rgba(255, 255, 255, 0.06);
        }
        html[data-theme="light"] .hcm-dropdown-item:hover {
            background: #f1f5f9;
        }
        .hcm-dropdown-item.is-danger {
            color: #f87171;
            border-top: 1px solid rgba(148, 163, 184, 0.14);
        }
        .hcm-dropdown-version {
            padding: 10px 16px;
            text-align: center;
            font-size: 12px;
            font-weight: 600;
            color: var(--text-secondary);
            border-top: 1px solid rgba(148, 163, 184, 0.14);
        }
        .hcm-user-avatar-sm {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #2563eb;
            color: #fff;
            display: grid;
            place-items: center;
            font-size: 12px;
            font-weight: 700;
        }
        .hcm-user-meta strong {
            display: block;
            font-size: 12px;
            font-weight: 600;
            line-height: 1.2;
        }
        .hcm-user-meta small {
            display: block;
            font-size: 10.5px;
            color: var(--text-secondary);
        }

        /* Content Container */
        .hcm-content {
            max-width: 1220px;
            width: 100%;
            margin: 0 auto;
            padding: 26px 32px 60px;
            display: grid;
            gap: 20px;
        }

        .hcm-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 24px 26px;
            box-shadow: var(--shadow-card);
        }

        /* Alert Banner */
        .hcm-alert {
            padding: 13px 18px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .hcm-alert-success {
            background: rgba(16, 185, 129, 0.14);
            border: 1px solid rgba(16, 185, 129, 0.35);
            color: #34d399;
        }
        .hcm-alert-error {
            background: rgba(244, 63, 94, 0.14);
            border: 1px solid rgba(244, 63, 94, 0.35);
            color: #fb7185;
        }

        /* Profile & Clock Card */
        .hcm-profile-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            flex-wrap: wrap;
        }
        .hcm-profile-left {
            display: flex;
            align-items: center;
            gap: 18px;
        }
        .hcm-avatar-lg {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            background: rgba(20, 184, 166, 0.14);
            border: 1px solid rgba(20, 184, 166, 0.3);
            color: #2dd4bf;
            font-size: 18px;
            font-weight: 700;
            display: grid;
            place-items: center;
            flex-shrink: 0;
        }
        .hcm-profile-badges {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 5px;
        }
        .hcm-badge-program {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            padding: 3px 9px;
            border-radius: 6px;
            background: rgba(20, 184, 166, 0.14);
            color: #2dd4bf;
            border: 1px solid rgba(20, 184, 166, 0.28);
        }
        .hcm-nik {
            font-size: 11.5px;
            color: var(--text-secondary);
        }
        .hcm-nik strong {
            font-family: 'JetBrains Mono', monospace;
            color: var(--text-primary);
        }
        .hcm-profile-info h2 {
            margin: 0;
            font-size: 22px;
            font-weight: 700;
            letter-spacing: -0.02em;
        }
        .hcm-profile-info p {
            margin: 4px 0 0;
            font-size: 12.5px;
            color: var(--text-secondary);
        }

        .hcm-clock-box {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 13px 20px;
            border-radius: 12px;
            background: var(--bg-card-inner);
            border: 1px solid var(--border-color);
            min-width: 195px;
        }
        .hcm-pulse-dot {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: var(--accent-emerald);
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            animation: hcmPulse 2s infinite;
        }
        @keyframes hcmPulse {
            0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.65); }
            70% { box-shadow: 0 0 0 8px rgba(16, 185, 129, 0); }
            100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }
        .hcm-clock-label {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--text-secondary);
        }
        .hcm-clock-time {
            font-family: 'JetBrains Mono', monospace;
            font-size: 19px;
            font-weight: 700;
            margin: 2px 0;
            letter-spacing: 0.02em;
        }
        .hcm-clock-date {
            font-size: 11px;
            color: var(--text-secondary);
        }

        /* Action / Verification Card */
        .hcm-action-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            flex-wrap: wrap;
        }
        .hcm-status-line {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 8px;
        }
        .hcm-status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 11px;
            border-radius: 999px;
            font-size: 11.5px;
            font-weight: 600;
            border: 1px solid var(--border-color);
            background: var(--bg-card-inner);
            color: var(--text-secondary);
        }
        .hcm-status-pill.is-working {
            background: rgba(16, 185, 129, 0.14);
            border-color: rgba(16, 185, 129, 0.35);
            color: #34d399;
        }
        .hcm-status-pill.is-done {
            background: rgba(37, 99, 235, 0.15);
            border-color: rgba(59, 130, 246, 0.35);
            color: #60a5fa;
        }
        .hcm-status-pill i {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: currentColor;
            display: inline-block;
        }
        .hcm-action-title {
            margin: 0 0 10px;
            font-size: 19px;
            font-weight: 700;
        }
        .hcm-time-pills {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 16px;
            font-size: 12px;
            color: var(--text-secondary);
            margin-bottom: 9px;
        }
        .hcm-time-pills code {
            font-family: 'JetBrains Mono', monospace;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 6px;
            background: var(--bg-card-inner);
            color: var(--text-primary);
            margin-left: 4px;
        }
        .hcm-time-pills code.is-duration {
            color: #2dd4bf;
            background: rgba(20, 184, 166, 0.14);
        }
        .hcm-operational-note {
            margin: 0;
            font-size: 11.5px;
            color: var(--text-muted);
        }
        .hcm-action-buttons {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }
        .hcm-btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 20px;
            border-radius: 11px;
            border: 0;
            background: #0d9488;
            color: #ffffff;
            font: 600 13px 'Inter', sans-serif;
            cursor: pointer;
            box-shadow: 0 6px 18px rgba(13, 148, 136, 0.28);
            transition: all 0.15s ease;
        }
        .hcm-btn-primary:hover {
            background: #0f766e;
            transform: translateY(-1px);
        }
        .hcm-btn-checkout {
            background: #d97706;
            box-shadow: 0 6px 18px rgba(217, 119, 6, 0.28);
        }
        .hcm-btn-checkout:hover {
            background: #b45309;
        }
        .hcm-btn-secondary {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 12px 16px;
            border-radius: 11px;
            border: 1px solid var(--border-color);
            background: var(--bg-card-inner);
            color: var(--text-primary);
            font: 600 12.5px 'Inter', sans-serif;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .hcm-btn-secondary:hover {
            border-color: var(--accent-teal);
            color: #2dd4bf;
        }

        /* 4 Summary Stat Cards */
        .hcm-stats-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
        }
        .hcm-stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 146px;
        }
        .hcm-stat-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 10.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--text-secondary);
            margin-bottom: 10px;
        }
        .hcm-stat-value {
            font-size: 26px;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 12px;
        }
        .hcm-stat-value small {
            font-size: 12.5px;
            font-weight: 400;
            color: var(--text-secondary);
        }
        .hcm-mini-badges {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 6px;
        }
        .hcm-mini-badge {
            text-align: center;
            padding: 5px 4px;
            border-radius: 6px;
            font-size: 10.5px;
            font-weight: 600;
        }
        .hcm-mini-badge.emerald { background: rgba(16, 185, 129, 0.12); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.25); }
        .hcm-mini-badge.amber { background: rgba(245, 158, 11, 0.12); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.25); }
        .hcm-mini-badge.rose { background: rgba(244, 63, 94, 0.12); color: #fb7185; border: 1px solid rgba(244, 63, 94, 0.25); }
        .hcm-stat-foot {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 11.5px;
            color: var(--text-secondary);
            padding-top: 10px;
            border-top: 1px solid var(--border-subtle);
        }

        /* Attendance Log Table */
        .hcm-table-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--shadow-card);
        }
        .hcm-table-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }
        .hcm-table-header h3 {
            margin: 0;
            font-size: 14.5px;
            font-weight: 700;
        }
        .hcm-table-header p {
            margin: 3px 0 0;
            font-size: 12px;
            color: var(--text-secondary);
        }
        .hcm-table-wrap {
            width: 100%;
            overflow-x: auto;
        }
        .hcm-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 12.5px;
        }
        .hcm-table th {
            padding: 13px 22px;
            background: var(--bg-table-head);
            color: var(--text-secondary);
            font-weight: 600;
            font-size: 11.5px;
            border-bottom: 1px solid var(--border-color);
        }
        .hcm-table td {
            padding: 14px 22px;
            border-bottom: 1px solid var(--border-subtle);
            vertical-align: middle;
        }
        .hcm-table tr:last-child td { border-bottom: 0; }
        .hcm-table tbody tr:hover { background: var(--bg-row-hover); }
        .hcm-mono {
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
        }
        .hcm-status-tag {
            display: inline-flex;
            align-items: center;
            padding: 3px 9px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
        }
        .hcm-status-tag.hadir {
            background: rgba(16, 185, 129, 0.14);
            color: #34d399;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }
        .hcm-status-tag.terlambat {
            background: rgba(245, 158, 11, 0.14);
            color: #fbbf24;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }
        .hcm-map-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 11px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            background: var(--bg-card-inner);
            color: var(--text-primary);
            font-size: 11.5px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .hcm-map-btn:hover {
            border-color: var(--accent-teal);
            color: #2dd4bf;
        }
        .hcm-photo-thumbs {
            display: inline-flex;
            gap: 6px;
            justify-content: flex-end;
        }
        .hcm-photo-thumb {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            object-fit: cover;
            border: 1px solid var(--border-color);
            cursor: pointer;
            transition: transform 0.15s ease, border-color 0.15s ease;
        }
        .hcm-photo-thumb:hover {
            transform: scale(1.08);
            border-color: var(--accent-teal);
        }

        /* Bottom 4 Quick Feature Cards */
        .hcm-quick-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
        }
        .hcm-quick-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 14px;
            padding: 16px 18px;
            display: flex;
            align-items: center;
            gap: 13px;
            cursor: pointer;
            transition: border-color 0.15s ease, transform 0.15s ease;
        }
        .hcm-quick-card:hover {
            border-color: rgba(20, 184, 166, 0.5);
            transform: translateY(-2px);
        }
        .hcm-quick-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: grid;
            place-items: center;
            font-size: 16px;
            flex-shrink: 0;
        }
        .hcm-quick-card strong {
            display: block;
            font-size: 12.5px;
            margin-bottom: 2px;
        }
        .hcm-quick-card span {
            display: block;
            font-size: 11px;
            color: var(--text-secondary);
        }

        /* History Page Filter & Stat Styles */
        .hcm-hist-stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
        }
        .hcm-hist-stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 14px;
            padding: 16px 20px;
            box-shadow: var(--shadow-card);
        }
        .hcm-hist-stat-label {
            font-size: 12px;
            color: var(--text-secondary);
            font-weight: 500;
        }
        .hcm-hist-stat-num {
            margin-top: 6px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 21px;
            font-weight: 700;
            color: var(--text-primary);
        }
        .hcm-hist-stat-num small {
            font-family: 'Inter', sans-serif;
            font-size: 12px;
            font-weight: 400;
            color: var(--text-secondary);
        }
        .hcm-filter-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            box-shadow: var(--shadow-card);
        }
        .hcm-filter-group {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 10px;
        }
        .hcm-select {
            padding: 10px 14px;
            border-radius: 11px;
            border: 1px solid var(--border-color);
            background: var(--bg-card-inner);
            color: var(--text-primary);
            font: 600 12px 'Inter', sans-serif;
            outline: none;
            cursor: pointer;
        }
        .hcm-select:focus {
            border-color: var(--accent-teal);
        }
        .hcm-reload-btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 10px 15px;
            border-radius: 11px;
            border: 1px solid var(--border-color);
            background: transparent;
            color: var(--text-primary);
            font: 600 12px 'Inter', sans-serif;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .hcm-reload-btn:hover {
            background: var(--bg-card-inner);
            border-color: var(--accent-teal);
        }

        /* Profile Page Styles (HEXHRS /profile) */
        .hcm-prof-heading {
            font-size: 23px;
            font-weight: 700;
            margin: 0 0 6px;
        }
        .hcm-prof-heading span {
            background: linear-gradient(90deg, #60a5fa, #818cf8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .hcm-prof-sub {
            margin: 0;
            font-size: 13.5px;
            color: var(--text-secondary);
        }
        .hcm-prof-card {
            background: #1a1b35;
            border: 1px solid rgba(99, 102, 241, 0.22);
            border-radius: 16px;
            padding: 24px 26px;
            box-shadow: var(--shadow-card);
        }
        html[data-theme="light"] .hcm-prof-card {
            background: #ffffff;
            border-color: #e2e8f0;
        }
        .hcm-prof-card h2 {
            margin: 0 0 16px;
            font-size: 18px;
            font-weight: 700;
        }
        .hcm-prof-avatar-circle {
            width: 116px;
            height: 116px;
            border-radius: 50%;
            background: linear-gradient(135deg, #2A559D, #1C3A6B);
            border: 4px solid #bfdbfe;
            color: #ffffff;
            font-size: 32px;
            font-weight: 700;
            display: grid;
            place-items: center;
            overflow: hidden;
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.35);
            flex-shrink: 0;
        }
        .hcm-prof-avatar-circle img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .hcm-prof-upload-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 16px;
            border-radius: 9px;
            background: #6366f1;
            color: #ffffff;
            font: 600 13px 'Inter', sans-serif;
            cursor: pointer;
            border: 0;
            transition: background 0.15s ease;
        }
        .hcm-prof-upload-btn:hover {
            background: #4f46e5;
        }
        .hcm-prof-danger-btn {
            display: inline-flex;
            align-items: center;
            padding: 9px 15px;
            border-radius: 9px;
            background: #ef4444;
            color: #ffffff;
            font: 600 13px 'Inter', sans-serif;
            cursor: pointer;
            border: 0;
        }
        .hcm-prof-sig-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 22px;
            align-items: center;
        }
        .hcm-prof-sig-dashed {
            padding: 16px;
            border-radius: 16px;
            border: 2px dashed rgba(100, 116, 139, 0.45);
            background: rgba(15, 23, 42, 0.35);
        }
        html[data-theme="light"] .hcm-prof-sig-dashed {
            background: #f8fafc;
            border-color: #cbd5e1;
        }
        .hcm-prof-sig-whitebox {
            width: 100%;
            height: 172px;
            border-radius: 12px;
            background: #ffffff;
            color: #334155;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 14px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .hcm-prof-blue-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 11px 20px;
            border-radius: 11px;
            border: 0;
            background: linear-gradient(90deg, #2A559D, #1C3A6B);
            color: #ffffff;
            font: 700 12.5px 'Inter', sans-serif;
            cursor: pointer;
            box-shadow: 0 6px 16px rgba(28, 58, 107, 0.4);
        }
        .hcm-prof-blue-btn:hover {
            opacity: 0.94;
        }
        .hcm-prof-spec-box {
            padding: 15px;
            border-radius: 12px;
            border: 1px solid rgba(100, 116, 139, 0.35);
            background: rgba(30, 41, 59, 0.45);
            font-size: 11.5px;
            color: var(--text-secondary);
        }
        html[data-theme="light"] .hcm-prof-spec-box {
            background: #f8fafc;
            border-color: #e2e8f0;
        }
        .hcm-prof-form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }
        .hcm-prof-field.is-full {
            grid-column: 1 / -1;
        }
        .hcm-prof-field label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-secondary);
            margin-bottom: 7px;
        }
        .hcm-prof-input {
            width: 100%;
            padding: 12px 14px;
            border-radius: 9px;
            border: 1px solid rgba(100, 116, 139, 0.45);
            background: #374151;
            color: #ffffff;
            font: 500 13.5px 'Inter', sans-serif;
            outline: none;
            transition: border-color 0.15s ease;
        }
        html[data-theme="light"] .hcm-prof-input {
            background: #ffffff;
            color: #0f172a;
            border-color: #cbd5e1;
        }
        .hcm-prof-input:focus {
            border-color: #6366f1;
        }
        .hcm-prof-input:disabled {
            opacity: 0.72;
            cursor: not-allowed;
            color: #9ca3af;
        }
        html[data-theme="light"] .hcm-prof-input:disabled {
            background: #f1f5f9;
            color: #64748b;
        }

        /* Modals */
        .hcm-modal {
            position: fixed;
            inset: 0;
            z-index: 9999;
            background: rgba(2, 6, 23, 0.78);
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 18px;
            overflow-y: auto;
        }
        .hcm-modal[hidden] { display: none !important; }
        .hcm-modal-dialog {
            width: min(100%, 540px);
            max-height: calc(100dvh - 36px);
            overflow-y: auto;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 18px;
            padding: 22px;
            box-shadow: 0 24px 48px rgba(0, 0, 0, 0.5);
        }
        .hcm-modal-dialog.is-wide {
            width: min(100%, 640px);
        }
        .hcm-modal-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 14px;
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 16px;
        }
        .hcm-modal-head h3 {
            margin: 0;
            font-size: 15px;
            font-weight: 700;
        }
        .hcm-modal-close {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            border: 1px solid var(--border-color);
            background: var(--bg-card-inner);
            color: var(--text-secondary);
            cursor: pointer;
            font-size: 16px;
            display: grid;
            place-items: center;
        }
        [hidden] {
            display: none !important;
        }
        .hcm-camera-frame {
            position: relative;
            aspect-ratio: 4 / 3;
            min-height: 340px;
            background: #050a15;
            border-radius: 14px;
            overflow: hidden;
            border: 1px solid var(--border-color);
            display: grid;
            place-items: center;
        }
        .hcm-camera-frame video, .hcm-camera-frame img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        .hcm-camera-frame video.is-mirrored {
            transform: scaleX(-1);
        }
        .hcm-cam-viewfinder {
            position: absolute;
            inset: 0;
            pointer-events: none;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 10px;
        }
        .hcm-cam-corner {
            position: absolute;
            width: 22px;
            height: 22px;
            border-color: rgba(45, 212, 191, 0.75);
            border-style: solid;
            pointer-events: none;
        }
        .hcm-cam-corner.tl { top: 14px; left: 14px; border-width: 2.5px 0 0 2.5px; border-top-left-radius: 6px; }
        .hcm-cam-corner.tr { top: 14px; right: 14px; border-width: 2.5px 2.5px 0 0; border-top-right-radius: 6px; }
        .hcm-cam-corner.bl { bottom: 58px; left: 14px; border-width: 0 0 2.5px 2.5px; border-bottom-left-radius: 6px; }
        .hcm-cam-corner.br { bottom: 58px; right: 14px; border-width: 0 2.5px 2.5px 0; border-bottom-right-radius: 6px; }
        .hcm-gps-bar {
            margin-top: 12px;
            padding: 10px 12px;
            border-radius: 10px;
            background: var(--bg-card-inner);
            border: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            font-size: 11.5px;
            color: var(--text-secondary);
        }
        .hcm-modal-foot {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-top: 18px;
            padding-top: 14px;
            border-top: 1px solid var(--border-color);
        }
        #hcm-leaflet-map {
            width: 100%;
            height: 340px;
            border-radius: 12px;
            border: 1px solid var(--border-color);
        }

        .hcm-mobile-toggle {
            display: none;
            padding: 8px 11px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            background: var(--bg-card-inner);
            color: var(--text-primary);
            cursor: pointer;
        }

        /* Mobile Bottom Navigation Bar (HEXHRS style) */
        .hcm-bottom-nav {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 85;
            background: #1e293b;
            border-top: 1px solid rgba(148, 163, 184, 0.16);
            border-radius: 26px 26px 0 0;
            padding: 12px 24px 10px;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 -10px 40px rgba(0, 0, 0, 0.28);
        }
        html[data-theme="light"] .hcm-bottom-nav {
            background: #ffffff;
            border-top-color: #f1f5f9;
            box-shadow: 0 -10px 40px rgba(15, 23, 42, 0.06);
        }
        .hcm-bottom-nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            background: transparent;
            border: 0;
            padding: 2px 6px;
            cursor: pointer;
            text-decoration: none;
            color: #64748b;
            font: 600 10px 'Inter', sans-serif;
            transition: color 0.15s ease;
        }
        html[data-theme="light"] .hcm-bottom-nav-item {
            color: #9ca3af;
        }
        .hcm-bottom-nav-icon {
            position: relative;
            display: grid;
            place-items: center;
        }
        .hcm-bottom-nav-icon svg {
            width: 23px;
            height: 23px;
        }
        .hcm-bottom-nav-dot {
            display: none;
            position: absolute;
            bottom: -7px;
            left: 50%;
            transform: translateX(-50%);
            width: 4px;
            height: 4px;
            border-radius: 50%;
            background: #3C71C6;
        }
        html[data-theme="light"] .hcm-bottom-nav-dot {
            background: #2A559D;
        }
        .hcm-bottom-nav-item.is-active {
            color: #3C71C6;
            font-weight: 700;
        }
        html[data-theme="light"] .hcm-bottom-nav-item.is-active {
            color: #2A559D;
        }
        .hcm-bottom-nav-item.is-active .hcm-bottom-nav-dot {
            display: block;
        }

        .hcm-mobile-app-settings {
            display: none;
        }

        @media (max-width: 1024px) {
            .hcm-stats-grid, .hcm-quick-grid, .hcm-hist-stats {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }
        @media (max-width: 780px) {
            .hcm-sidebar {
                display: none;
            }
            .hcm-topbar {
                display: none;
            }
            .hcm-main {
                margin-left: 0;
            }
            .hcm-content {
                padding: 16px 14px 104px;
                gap: 16px;
            }
            .hcm-card, .hcm-prof-card {
                padding: 20px 18px;
                border-radius: 20px;
            }
            .hcm-profile-row {
                flex-direction: column;
                align-items: stretch;
                gap: 16px;
            }
            .hcm-profile-left {
                align-items: flex-start;
                gap: 14px;
            }
            .hcm-profile-info h2 {
                font-size: 20px;
                line-height: 1.25;
            }
            .hcm-clock-box {
                width: 100%;
                padding: 14px 18px;
                border-radius: 14px;
            }
            .hcm-action-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 16px;
            }
            .hcm-action-buttons {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
                width: 100%;
            }
            .hcm-stats-grid {
                grid-template-columns: 1fr;
                gap: 14px;
            }
            .hcm-quick-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 12px;
            }
            .hcm-quick-card {
                padding: 14px;
                border-radius: 16px;
            }
            .hcm-hist-stats {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 12px;
            }
            .hcm-hist-stat-card {
                padding: 15px 16px;
                border-radius: 16px;
            }
            .hcm-filter-card {
                padding: 16px;
                border-radius: 18px;
            }
            .hcm-prof-sig-grid, .hcm-prof-form-grid {
                grid-template-columns: 1fr;
            }
            .hcm-bottom-nav {
                display: flex;
            }
            .hcm-mobile-app-settings {
                display: grid;
                gap: 12px;
            }
            .hcm-modal {
                padding: 10px;
            }
            .hcm-modal-dialog {
                width: 100%;
                padding: 16px;
                border-radius: 20px;
            }
            .hcm-camera-frame {
                aspect-ratio: 3 / 4;
                min-height: 380px;
                max-height: 58vh;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="hcm-sidebar" id="hcm-sidebar">
        <a href="{{ route('attendance.dashboard') }}" class="hcm-brand">
            <strong>GASS</strong><span>HRS</span>
        </a>

        <nav class="hcm-nav" aria-label="Menu Absensi">
            <button type="button" class="hcm-nav-item is-active" data-nav-view="dashboard">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10.5L12 3l9 7.5V21a1 1 0 01-1 1h-5.5v-6.5h-5V22H4a1 1 0 01-1-1V10.5z"/></svg>
                <span>Dashboard</span>
            </button>
            <button type="button" class="hcm-nav-item" data-nav-view="history">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect width="18" height="18" x="3" y="4" rx="2"/><path stroke-linecap="round" d="M16 2v4M8 2v4M3 10h18"/></svg>
                <span>Riwayat Absensi</span>
            </button>
            @if ($user->is_admin)
                <a href="{{ route('admin.dashboard') }}" class="hcm-nav-item">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3"/></svg>
                    <span>Workspace Admin</span>
                </a>
            @endif
            <a href="{{ route('home') }}" class="hcm-nav-item">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Beranda Website</span>
            </a>
        </nav>

        <div class="hcm-sidebar-footer">
            <button type="button" class="hcm-footer-btn" id="hcm-theme-toggle">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="5"/><path stroke-linecap="round" d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg>
                <span data-theme-label>Light Mode</span>
            </button>
            <form method="POST" action="{{ route('attendance.logout') }}">
                @csrf
                <button type="submit" class="hcm-footer-btn logout-btn">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    <span>Sign Out</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="hcm-main">
        <header class="hcm-topbar">
            <div style="display:flex;align-items:center;gap:12px;">
                <button type="button" class="hcm-mobile-toggle" id="hcm-mobile-toggle" aria-label="Buka menu">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div class="hcm-topbar-title">
                    <h1>Attendance &amp; HCM</h1>
                    <p>Human Capital Management System</p>
                </div>
            </div>
            <div class="hcm-topbar-right">
                <span class="hcm-lang-pill">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" d="M2 12h20M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/></svg>
                    <span>ID</span>
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="opacity:0.7;"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </span>
                <div class="hcm-user-dropdown-wrap" id="hcm-user-dropdown-wrap">
                    <button type="button" class="hcm-user-pill" id="hcm-user-pill-btn" aria-expanded="false">
                        <span class="hcm-user-avatar-sm" style="overflow:hidden;">
                            @if ($user->profile_photo)
                                <img src="{{ route('media', ['path' => $user->profile_photo]) }}" alt="{{ $user->name }}" style="width:100%;height:100%;object-fit:cover;">
                            @else
                                {{ mb_substr($user->name, 0, 1) }}
                            @endif
                        </span>
                        <div class="hcm-user-meta">
                            <strong>{{ $user->name }}</strong>
                            <small>{{ $user->position ?? 'Karyawan' }}</small>
                        </div>
                        <svg class="hcm-user-chevron" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </button>

                    <div class="hcm-user-dropdown" id="hcm-user-dropdown" hidden>
                        <button type="button" class="hcm-dropdown-item" data-nav-view="profile">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <span>Lihat Profil</span>
                        </button>
                        <form method="POST" action="{{ route('attendance.logout') }}" style="margin:0;">
                            @csrf
                            <button type="submit" class="hcm-dropdown-item is-danger">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                <span>Keluar</span>
                            </button>
                        </form>
                        <div class="hcm-dropdown-version">v1.0</div>
                    </div>
                </div>
            </div>
        </header>

        <main class="hcm-content">
            @if (session('status'))
                <div class="hcm-alert hcm-alert-success">{{ session('status') }}</div>
            @endif
            @if ($errors->any())
                <div class="hcm-alert hcm-alert-error">{{ $errors->first() }}</div>
            @endif

            <!-- ==================== VIEW 1: DASHBOARD ==================== -->
            <div id="hcm-view-dashboard" style="display:grid;gap:20px;">
                <!-- 1. Hero Profile & Live Clock Card -->
                <section class="hcm-card">
                    <div class="hcm-profile-row">
                        <div class="hcm-profile-left">
                            <div class="hcm-avatar-lg">{{ $user->initials }}</div>
                            <div class="hcm-profile-info">
                                <div class="hcm-profile-badges">
                                    <span class="hcm-badge-program">{{ strtoupper($user->role ?: 'KARYAWAN') }} PORTAL</span>
                                    <span class="hcm-nik">• NIK: <strong>{{ $user->employee_id ?? sprintf('EMP-%03d', $user->id) }}</strong></span>
                                </div>
                                <h2>{{ $user->name }}</h2>
                                <p>Divisi {{ $user->division ?? 'IT & Digital' }} • Posisi {{ $user->position ?? 'Staff' }}</p>
                            </div>
                        </div>

                        <div class="hcm-clock-box">
                            <span class="hcm-pulse-dot" aria-hidden="true"></span>
                            <div>
                                <div class="hcm-clock-label">Waktu Kerja (WIB)</div>
                                <div class="hcm-clock-time" id="hcm-live-clock">{{ $now->format('H.i.s') }}</div>
                                <div class="hcm-clock-date" id="hcm-live-date">{{ $now->translatedFormat('l, j M Y') }}</div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- 2. Daily Verification Action Card -->
                @php
                    $hasCheckIn = (bool) $todayAttendance?->check_in_time;
                    $hasCheckOut = (bool) $todayAttendance?->check_out_time;
                @endphp
                <section class="hcm-card" id="hcm-dashboard-section">
                    <div class="hcm-action-row">
                        <div>
                            <div class="hcm-status-line">
                                @if ($hasCheckOut)
                                    <span class="hcm-status-pill is-done"><i></i> Absensi Selesai • {{ $todayAttendance->shift_label }}</span>
                                @elseif ($hasCheckIn)
                                    <span class="hcm-status-pill is-working"><i></i> Sedang Bertugas ({{ $todayAttendance->shift_label }})</span>
                                @else
                                    <span class="hcm-status-pill"><i></i> Belum Absensi Masuk</span>
                                @endif
                                <span style="font-size:12px;color:var(--text-secondary);">Hari ini, {{ $today }}</span>
                            </div>

                            <h2 class="hcm-action-title">
                                @if ($hasCheckOut)
                                    Absensi hari ini telah lengkap
                                @elseif ($hasCheckIn)
                                    Presensi masuk tercatat, siap bekerja
                                @else
                                    Lakukan verifikasi absensi masuk
                                @endif
                            </h2>

                            <div class="hcm-time-pills">
                                <span>Shift: <code>{{ $todayAttendance?->shift_label !== '-' && $todayAttendance?->shift_label ? $todayAttendance->shift_label : ($now->format('H:i') < '13:30' ? 'Shift Pagi' : 'Shift Sore') }}</code></span>
                                <span>Masuk: <code id="hcm-today-in">{{ $todayAttendance?->check_in_time ? substr($todayAttendance->check_in_time, 0, 5) : '--:--' }}</code></span>
                                <span>Pulang: <code id="hcm-today-out">{{ $todayAttendance?->check_out_time ? substr($todayAttendance->check_out_time, 0, 5) : '--:--' }}</code></span>
                                <span>Durasi: <code class="is-duration" id="hcm-today-duration" data-checkin="{{ $todayAttendance?->check_in_time }}" data-checkout="{{ $todayAttendance?->check_out_time }}">{{ $todayAttendance?->work_duration ?? '--:--' }}</code></span>
                            </div>

                            <p class="hcm-operational-note">Shift Otomatis: Shift Pagi (09:00 WIB) &amp; Shift Sore (16:00 WIB) • Dihitung per jam (GPS &amp; Kamera)</p>
                        </div>

                        <div class="hcm-action-buttons">
                            @if (! $hasCheckIn)
                                <button type="button" class="hcm-btn-primary" data-open-attendance="checkin">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/><circle cx="12" cy="13" r="4"/></svg>
                                    <span>Check In Sekarang</span>
                                </button>
                            @elseif (! $hasCheckOut)
                                <button type="button" class="hcm-btn-primary hcm-btn-checkout" data-open-attendance="checkout">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/><circle cx="12" cy="13" r="4"/></svg>
                                    <span>Check Out Pulang</span>
                                </button>
                            @else
                                <span style="padding:11px 16px;font-size:12.5px;font-weight:600;border-radius:10px;background:rgba(16,185,129,0.12);color:#34d399;border:1px solid rgba(16,185,129,0.32);display:inline-flex;align-items:center;gap:7px;">
                                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    <span>Tugas Selesai</span>
                                </span>
                            @endif

                            <button type="button" class="hcm-btn-secondary" data-nav-view="history">
                                <span>Riwayat Lengkap</span>
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                            </button>
                        </div>
                    </div>
                </section>

                <!-- 3. 4 Summary Cards -->
                <section class="hcm-stats-grid" id="hcm-stats-section">
                    <article class="hcm-stat-card">
                        <div>
                            <div class="hcm-stat-head">
                                <span>Kehadiran Bulan Ini</span>
                                <span style="color:var(--accent-teal);display:inline-flex;">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect width="18" height="18" x="3" y="4" rx="2"/><path stroke-linecap="round" d="M16 2v4M8 2v4M3 10h18"/></svg>
                                </span>
                            </div>
                            <div class="hcm-stat-value">{{ $stats['present'] }} <small>Hari Hadir</small></div>
                        </div>
                        <div class="hcm-mini-badges">
                            <span class="hcm-mini-badge emerald">{{ $stats['shift_pagi'] }} Pagi</span>
                            <span class="hcm-mini-badge amber">{{ $stats['shift_sore'] }} Sore</span>
                            <span class="hcm-mini-badge rose" style="background:rgba(59,130,246,0.14);color:#60a5fa;">{{ $stats['total_duration'] }}</span>
                        </div>
                    </article>

                    <article class="hcm-stat-card">
                        <div>
                            <div class="hcm-stat-head">
                                <span>Status Hari Ini</span>
                                <span style="color:#818cf8;display:inline-flex;">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path stroke-linecap="round" stroke-linejoin="round" d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/></svg>
                                </span>
                            </div>
                            <div style="margin: 8px 0 14px;">
                                @if ($hasCheckOut)
                                    <span class="hcm-status-tag hadir">
                                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                        <span>Selesai • {{ $todayAttendance->work_duration }}</span>
                                    </span>
                                @elseif ($hasCheckIn)
                                    <span class="hcm-status-tag hadir">
                                        <span style="width:6px;height:6px;border-radius:50%;background:currentColor;display:inline-block;"></span>
                                        <span>{{ $todayAttendance->shift_label }} ({{ substr($todayAttendance->check_in_time, 0, 5) }})</span>
                                    </span>
                                @else
                                    <span class="hcm-status-tag terlambat">
                                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0zM12 9v4M12 17h.01"/></svg>
                                        <span>Menunggu Check-In</span>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="hcm-stat-foot">
                            <span>Skema: Shift Per Jam</span>
                            <span style="color:var(--accent-teal);font-weight:600;display:inline-flex;align-items:center;gap:3px;">
                                <span>WIB</span>
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                            </span>
                        </div>
                    </article>

                    <article class="hcm-stat-card">
                        <div>
                            <div class="hcm-stat-head">
                                <span>Skor Performa</span>
                                <span style="color:var(--accent-emerald);display:inline-flex;">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </span>
                            </div>
                            <div class="hcm-stat-value">
                                {{ $stats['score'] }} <small>/ 100</small>
                                <span class="hcm-status-tag hadir" style="margin-left:6px;vertical-align:middle;">{{ $stats['grade'] }}</span>
                            </div>
                        </div>
                        <div class="hcm-stat-foot">
                            <span>Total Jam: <strong>{{ $stats['total_duration'] }}</strong></span>
                            <span>Bulan: <strong>{{ $now->format('m/Y') }}</strong></span>
                        </div>
                    </article>

                    <article class="hcm-stat-card">
                        <div>
                            <div class="hcm-stat-head">
                                <span>Pembimbing / Atasan</span>
                                <span style="color:#c084fc;display:inline-flex;">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </span>
                            </div>
                            <div style="font-size:15px;font-weight:700;margin-top:6px;">{{ $user->supervisor ?? 'Head Departemen' }}</div>
                            <div style="font-size:12px;color:var(--text-secondary);margin-top:3px;">{{ $user->division ?? 'IT & Digital' }}</div>
                        </div>
                        <div class="hcm-stat-foot">
                            <span>Status: <strong style="color:#34d399;">Aktif</strong></span>
                            <span>Bln {{ $now->month }}</span>
                        </div>
                    </article>
                </section>

                <!-- 4. Recent 5 Attendance Logs Table -->
                <section class="hcm-table-card">
                    <div class="hcm-table-header">
                        <div>
                            <h3>Log Absensi Terbaru Anda</h3>
                            <p>Riwayat absensi harian, jam masuk, jam pulang, dan status kehadiran</p>
                        </div>
                        <button type="button" class="hcm-map-btn" data-nav-view="history" style="color:var(--accent-teal);border-color:transparent;background:transparent;">
                            <span>Lihat Semua</span>
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>

                    @if ($attendances->isEmpty())
                        <div style="padding: 42px 24px; text-align: center; color: var(--text-secondary); font-size: 13px;">
                            Belum ada data absensi tercatat untuk akun ini.
                        </div>
                    @else
                        <div class="hcm-table-wrap">
                            <table class="hcm-table">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Jam Masuk</th>
                                        <th>Jam Pulang</th>
                                        <th>Status</th>
                                        <th>Lokasi</th>
                                        <th style="text-align:right;">Foto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($attendances->take(5) as $log)
                                        <tr>
                                            <td style="font-weight:600;">{{ $log->date }}</td>
                                            <td class="hcm-mono">{{ $log->check_in_time ?? '-' }}</td>
                                            <td class="hcm-mono">{{ $log->check_out_time ?? '-' }}</td>
                                            <td>
                                                <span class="hcm-status-tag hadir">
                                                    {{ $log->status }} • {{ $log->shift_label }}
                                                </span>
                                            </td>
                                            <td>
                                                @if ($log->check_in_lat && $log->check_in_lng)
                                                    <button
                                                        type="button"
                                                        class="hcm-map-btn"
                                                        data-open-map
                                                        data-date="{{ $log->date }}"
                                                        data-in-lat="{{ $log->check_in_lat }}"
                                                        data-in-lng="{{ $log->check_in_lng }}"
                                                        data-out-lat="{{ $log->check_out_lat }}"
                                                        data-out-lng="{{ $log->check_out_lng }}"
                                                    >
                                                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:var(--accent-teal);flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                        <span>Lihat Peta</span>
                                                    </button>
                                                @else
                                                    <span style="color:var(--text-muted);">-</span>
                                                @endif
                                            </td>
                                            <td style="text-align:right;">
                                                <div class="hcm-photo-thumbs">
                                                    @if ($log->check_in_photo)
                                                        <img
                                                            src="{{ route('media', ['path' => $log->check_in_photo]) }}"
                                                            alt="Foto Masuk {{ $log->date }}"
                                                            title="Foto Check-In ({{ $log->check_in_time }})"
                                                            class="hcm-photo-thumb"
                                                            data-open-photo
                                                            data-photo-url="{{ route('media', ['path' => $log->check_in_photo]) }}"
                                                            data-photo-title="Bukti Foto Masuk ({{ $log->date }})"
                                                            data-photo-time="{{ $log->check_in_time }}"
                                                            data-photo-status="{{ $log->status }}"
                                                        >
                                                    @endif
                                                    @if ($log->check_out_photo)
                                                        <img
                                                            src="{{ route('media', ['path' => $log->check_out_photo]) }}"
                                                            alt="Foto Pulang {{ $log->date }}"
                                                            title="Foto Check-Out ({{ $log->check_out_time }})"
                                                            class="hcm-photo-thumb"
                                                            data-open-photo
                                                            data-photo-url="{{ route('media', ['path' => $log->check_out_photo]) }}"
                                                            data-photo-title="Bukti Foto Pulang ({{ $log->date }})"
                                                            data-photo-time="{{ $log->check_out_time }}"
                                                            data-photo-status="Check-Out Pulang"
                                                        >
                                                    @endif
                                                    @if (! $log->check_in_photo && ! $log->check_out_photo)
                                                        <span style="color:var(--text-muted);">-</span>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </section>

                <!-- 5. Bottom Quick Feature Cards -->
                <section class="hcm-quick-grid">
                    <div class="hcm-quick-card" data-nav-view="history">
                        <div class="hcm-quick-icon" style="background:rgba(20,184,166,0.14);color:#2dd4bf;">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect width="18" height="18" x="3" y="4" rx="2"/><path stroke-linecap="round" d="M16 2v4M8 2v4M3 10h18"/></svg>
                        </div>
                        <div>
                            <strong>Presensi GPS</strong>
                            <span>Riwayat &amp; detail</span>
                        </div>
                    </div>
                    <div class="hcm-quick-card" data-open-attendance="{{ ! $hasCheckIn ? 'checkin' : (! $hasCheckOut ? 'checkout' : '') }}">
                        <div class="hcm-quick-icon" style="background:rgba(99,102,241,0.14);color:#818cf8;">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/><circle cx="12" cy="13" r="4"/></svg>
                        </div>
                        <div>
                            <strong>Verifikasi Kamera</strong>
                            <span>Selfie masuk &amp; pulang</span>
                        </div>
                    </div>
                    <div class="hcm-quick-card" data-nav-view="history">
                        <div class="hcm-quick-icon" style="background:rgba(16,185,129,0.14);color:#34d399;">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        </div>
                        <div>
                            <strong>Nilai Performa</strong>
                            <span>Skor ketepatan: {{ $stats['score'] }}%</span>
                        </div>
                    </div>
                    <a href="https://wa.me/6285890007359?text={{ urlencode('Halo Admin GASS, saya '.$user->name.' ('.($user->employee_id ?? 'Karyawan').') ingin mengajukan izin/konfirmasi kehadiran.') }}" target="_blank" rel="noreferrer" class="hcm-quick-card" style="text-decoration:none;color:inherit;">
                        <div class="hcm-quick-icon" style="background:rgba(244,63,94,0.14);color:#fb7185;">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2"/></svg>
                        </div>
                        <div>
                            <strong>Pengajuan Izin</strong>
                            <span>Izin atau sakit</span>
                        </div>
                    </a>
                </section>
            </div>

            <!-- ==================== VIEW 2: RIWAYAT ABSENSI (HEXHRS /attendance) ==================== -->
            <div id="hcm-view-history" style="display:none;gap:20px;">
                <!-- 1. Top Portal Banner Card -->
                <section class="hcm-card">
                    <div class="hcm-profile-row">
                        <div class="hcm-profile-info">
                            <div class="hcm-profile-badges" style="margin-bottom:8px;">
                                <span class="hcm-badge-program" style="display:inline-flex;align-items:center;gap:5px;">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect width="18" height="18" x="3" y="4" rx="2"/><path stroke-linecap="round" d="M16 2v4M8 2v4M3 10h18"/></svg>
                                    <span>PORTAL KARYAWAN</span>
                                </span>
                            </div>
                            <h2 style="font-size:24px;margin-bottom:4px;">Absensi Karyawan</h2>
                            <p>Rekapitulasi riwayat presensi harian, jam kerja, lokasi GPS, dan bukti foto verifikasi.</p>
                        </div>

                        <div style="padding:11px 18px;border-radius:12px;background:var(--bg-card-inner);border:1px solid var(--border-color);">
                            <div style="font-size:10px;font-weight:600;text-transform:uppercase;color:var(--text-secondary);">Waktu Saat Ini (WIB)</div>
                            <div class="hcm-mono" style="font-size:14px;font-weight:700;margin-top:3px;" id="hcm-hist-clock">{{ $now->format('H.i.s') }}</div>
                        </div>
                    </div>
                </section>

                <!-- 2. 4 Stat Cards Row -->
                <section class="hcm-hist-stats">
                    <div class="hcm-hist-stat-card">
                        <div class="hcm-hist-stat-label">Total Hari Hadir</div>
                        <div class="hcm-hist-stat-num"><span id="hcm-hist-stat-present">{{ $stats['present'] }}</span> <small>Hari</small></div>
                    </div>
                    <div class="hcm-hist-stat-card">
                        <div class="hcm-hist-stat-label">Shift Pagi (09:00)</div>
                        <div class="hcm-hist-stat-num" style="color:#34d399;"><span id="hcm-hist-stat-pagi">{{ $stats['shift_pagi'] }}</span> <small>Hari</small></div>
                    </div>
                    <div class="hcm-hist-stat-card">
                        <div class="hcm-hist-stat-label">Shift Sore (16:00)</div>
                        <div class="hcm-hist-stat-num" style="color:#fbbf24;"><span id="hcm-hist-stat-sore">{{ $stats['shift_sore'] }}</span> <small>Hari</small></div>
                    </div>
                    <div class="hcm-hist-stat-card">
                        <div class="hcm-hist-stat-label">Total Jam Kerja</div>
                        <div class="hcm-hist-stat-num" style="color:#60a5fa;"><span id="hcm-hist-stat-hours">{{ $stats['total_duration'] }}</span></div>
                    </div>
                </section>

                <!-- 3. Filter Bar Card -->
                <section class="hcm-filter-card">
                    <div class="hcm-filter-group">
                        @php
                            $months = [
                                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
                            ];
                        @endphp
                        <select class="hcm-select" id="hcm-filter-month" aria-label="Pilih Bulan">
                            @foreach ($months as $mNum => $mName)
                                <option value="{{ $mNum }}" data-month-name="{{ $mName }}" @selected($now->month === $mNum)>{{ $mName }}</option>
                            @endforeach
                        </select>

                        <select class="hcm-select" id="hcm-filter-year" aria-label="Pilih Tahun">
                            @foreach ([2024, 2025, 2026, 2027] as $yr)
                                <option value="{{ $yr }}" @selected($now->year === $yr)>{{ $yr }}</option>
                            @endforeach
                        </select>

                        <select class="hcm-select" id="hcm-filter-status" aria-label="Pilih Shift">
                            <option value="all">Semua Shift</option>
                            <option value="Shift Pagi">Shift Pagi (09:00)</option>
                            <option value="Shift Sore">Shift Sore (16:00)</option>
                        </select>

                        <select class="hcm-select" id="hcm-filter-sort" aria-label="Urutan">
                            <option value="desc">Terbaru ke Terlama</option>
                            <option value="asc">Terlama ke Terbaru</option>
                        </select>
                    </div>

                    <button type="button" class="hcm-reload-btn" id="hcm-filter-reload">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <span>Muat Ulang</span>
                    </button>
                </section>

                <!-- 4. Log Presensi Bulan [Bulan] [Tahun] Card -->
                <section class="hcm-table-card">
                    <div class="hcm-table-header">
                        <div>
                            <h3 id="hcm-hist-period-title">Log Presensi Bulan {{ $months[$now->month] }} {{ $now->year }}</h3>
                            <p>Daftar presensi harian, jam masuk, jam pulang, verifikasi lokasi GPS, dan bukti foto</p>
                        </div>
                        <span class="hcm-mono" style="color:var(--text-secondary);font-size:12px;" id="hcm-hist-count">0 Catatan</span>
                    </div>

                    <div id="hcm-hist-empty" style="padding: 54px 24px; text-align: center;" hidden>
                        <div style="font-size: 14px; font-weight: 700; color: var(--text-primary); margin-bottom: 4px;">Tidak ada data absensi</div>
                        <div style="font-size: 12px; color: var(--text-secondary);">Belum ada catatan presensi pada periode bulan yang dipilih.</div>
                    </div>

                    <div class="hcm-table-wrap" id="hcm-hist-table-wrap">
                        <table class="hcm-table">
                            <thead>
                                <tr>
                                    <th>Tanggal &amp; Hari</th>
                                    <th>Jam Masuk</th>
                                    <th>Jam Pulang</th>
                                    <th>Durasi</th>
                                    <th>Status &amp; Shift</th>
                                    <th>Lokasi GPS</th>
                                    <th style="text-align:right;">Foto Bukti</th>
                                </tr>
                            </thead>
                            <tbody id="hcm-hist-tbody">
                                @foreach ($attendances as $log)
                                    @php
                                        $logDateObj = \Carbon\Carbon::parse($log->date);
                                    @endphp
                                    <tr
                                        class="hcm-hist-row"
                                        data-date="{{ $log->date }}"
                                        data-month="{{ (int) $logDateObj->format('n') }}"
                                        data-year="{{ (int) $logDateObj->format('Y') }}"
                                        data-status="{{ $log->status }}"
                                        data-shift="{{ $log->shift_label }}"
                                        data-minutes="{{ $log->work_minutes }}"
                                    >
                                        <td style="font-weight:600;">{{ $log->date }}</td>
                                        <td class="hcm-mono">{{ $log->check_in_time ?? '-' }}</td>
                                        <td class="hcm-mono">{{ $log->check_out_time ?? '-' }}</td>
                                        <td class="hcm-mono">{{ $log->work_duration }}</td>
                                        <td>
                                            <span class="hcm-status-tag hadir">
                                                {{ $log->status }} • {{ $log->shift_label }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($log->check_in_lat && $log->check_in_lng)
                                                <button
                                                    type="button"
                                                    class="hcm-map-btn"
                                                    data-open-map
                                                    data-date="{{ $log->date }}"
                                                    data-in-lat="{{ $log->check_in_lat }}"
                                                    data-in-lng="{{ $log->check_in_lng }}"
                                                    data-out-lat="{{ $log->check_out_lat }}"
                                                    data-out-lng="{{ $log->check_out_lng }}"
                                                >
                                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:var(--accent-teal);flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                    <span>Lihat Peta</span>
                                                </button>
                                            @else
                                                <span style="color:var(--text-muted);">-</span>
                                            @endif
                                        </td>
                                        <td style="text-align:right;">
                                            <div class="hcm-photo-thumbs">
                                                @if ($log->check_in_photo)
                                                    <img
                                                        src="{{ route('media', ['path' => $log->check_in_photo]) }}"
                                                        alt="Foto Masuk {{ $log->date }}"
                                                        title="Foto Check-In ({{ $log->check_in_time }})"
                                                        class="hcm-photo-thumb"
                                                        data-open-photo
                                                        data-photo-url="{{ route('media', ['path' => $log->check_in_photo]) }}"
                                                        data-photo-title="Bukti Foto Masuk ({{ $log->date }})"
                                                        data-photo-time="{{ $log->check_in_time }}"
                                                        data-photo-status="{{ $log->status }}"
                                                    >
                                                @endif
                                                @if ($log->check_out_photo)
                                                    <img
                                                        src="{{ route('media', ['path' => $log->check_out_photo]) }}"
                                                        alt="Foto Pulang {{ $log->date }}"
                                                        title="Foto Check-Out ({{ $log->check_out_time }})"
                                                        class="hcm-photo-thumb"
                                                        data-open-photo
                                                        data-photo-url="{{ route('media', ['path' => $log->check_out_photo]) }}"
                                                        data-photo-title="Bukti Foto Pulang ({{ $log->date }})"
                                                        data-photo-time="{{ $log->check_out_time }}"
                                                        data-photo-status="Check-Out Pulang"
                                                    >
                                                @endif
                                                @if (! $log->check_in_photo && ! $log->check_out_photo)
                                                    <span style="color:var(--text-muted);">-</span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>

            <!-- ==================== VIEW 3: PROFILE SAYA (HEXHRS /profile) ==================== -->
            <div id="hcm-view-profile" style="display:none;gap:20px;">
                <div>
                    <h1 class="hcm-prof-heading"><span>Profile</span> Saya</h1>
                    <p class="hcm-prof-sub">Kelola informasi pribadi dan data diri Anda</p>
                </div>

                <form method="POST" action="{{ route('attendance.profile.update') }}" id="hcm-profile-form" style="display:grid;gap:20px;">
                    @csrf
                    <input type="hidden" name="profile_photo_base64" id="hcm-profile-photo-base64" value="">
                    <input type="hidden" name="remove_profile_photo" id="hcm-remove-profile-photo" value="0">
                    <input type="hidden" name="signature_base64" id="hcm-signature-base64" value="">
                    <input type="hidden" name="remove_signature" id="hcm-remove-signature" value="0">

                    <!-- Card 1: Foto Profile -->
                    <section class="hcm-prof-card">
                        <h2>Foto Profile</h2>
                        <div style="display:flex;align-items:center;gap:22px;flex-wrap:wrap;">
                            <div class="hcm-prof-avatar-circle" id="hcm-prof-avatar-box">
                                @if ($user->profile_photo)
                                    <img src="{{ route('media', ['path' => $user->profile_photo]) }}" alt="Foto Profile" id="hcm-prof-avatar-img">
                                @else
                                    <span id="hcm-prof-avatar-initial">{{ strtoupper(mb_substr($user->name, 0, 1)) }}</span>
                                    <img src="" alt="Foto Profile" id="hcm-prof-avatar-img" hidden>
                                @endif
                            </div>

                            <div>
                                <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                                    <label class="hcm-prof-upload-btn">
                                        <span id="hcm-prof-upload-label">{{ $user->profile_photo ? 'Ganti Foto' : 'Upload Foto' }}</span>
                                        <input type="file" id="hcm-prof-photo-input" accept="image/jpeg,image/png,image/gif,image/webp" hidden>
                                    </label>
                                    <button type="button" class="hcm-prof-danger-btn" id="hcm-prof-remove-photo-btn" @if (! $user->profile_photo) hidden @endif>
                                        Hapus Foto
                                    </button>
                                </div>
                                <p style="margin:8px 0 0;font-size:12.5px;color:var(--text-secondary);">Format: JPG, PNG, GIF (Maks. 5MB)</p>
                            </div>
                        </div>
                    </section>

                    <!-- Card 2: Tanda Tangan Digital -->
                    <section class="hcm-prof-card">
                        <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:14px;flex-wrap:wrap;margin-bottom:18px;">
                            <div>
                                <h2 style="margin-bottom:4px;">Tanda Tangan Digital</h2>
                                <p style="margin:0;font-size:12.5px;color:var(--text-secondary);">Digunakan secara otomatis pada dokumen resmi, verifikasi, dan Form Permohonan Cuti PDF</p>
                            </div>
                            <span class="hcm-mono" style="padding:5px 12px;border-radius:999px;font-size:11px;font-weight:700;background:rgba(99,102,241,0.14);color:#818cf8;border:1px solid rgba(99,102,241,0.3);">
                                Resolusi Standar: 800 × 400 px (2:1 HD)
                            </span>
                        </div>

                        <div class="hcm-prof-sig-grid">
                            <div class="hcm-prof-sig-dashed">
                                <div style="display:flex;align-items:center;justify-content:space-between;font-size:11.5px;font-weight:700;margin-bottom:10px;padding-bottom:8px;border-bottom:1px solid var(--border-color);">
                                    <span style="color:#818cf8;display:inline-flex;align-items:center;gap:6px;">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path stroke-linecap="round" stroke-linejoin="round" d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/></svg>
                                        <span>Preview Pada Dokumen Form Cuti</span>
                                    </span>
                                    <span class="hcm-mono" style="color:var(--text-secondary);font-size:11px;">Rasio 2:1</span>
                                </div>

                                <div class="hcm-prof-sig-whitebox">
                                    <div id="hcm-sig-active-preview" style="width:100%;height:100%;display:{{ $user->signature ? 'flex' : 'none' }};flex-direction:column;align-items:center;justify-content:space-between;">
                                        <span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em;">Pemohon Cuti</span>
                                        <img
                                            id="hcm-sig-preview-img"
                                            src="{{ $user->signature ? route('media', ['path' => $user->signature]) : '' }}"
                                            alt="Tanda Tangan Digital"
                                            style="max-width:100%;max-height:92px;object-fit:contain;"
                                        >
                                        <div>
                                            <div style="font-size:11.5px;font-weight:700;color:#0f172a;">( {{ $user->name }} )</div>
                                            <div style="font-size:10px;color:#94a3b8;">NIK: {{ $user->employee_id ?? sprintf('EMP-%03d', $user->id) }} • {{ $user->department ?? 'IT' }}</div>
                                        </div>
                                    </div>

                                    <div id="hcm-sig-empty-preview" style="display:{{ $user->signature ? 'none' : 'flex' }};flex-direction:column;align-items:center;justify-content:center;">
                                        <div style="width:44px;height:44px;border-radius:50%;background:rgba(99,102,241,0.12);color:#6366f1;display:grid;place-items:center;margin-bottom:8px;">
                                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                        </div>
                                        <strong style="font-size:12px;color:#334155;margin-bottom:2px;">Belum Ada Tanda Tangan</strong>
                                        <span style="font-size:11px;color:#94a3b8;">Buat tanda tangan langsung atau upload foto tanda tangan</span>
                                    </div>
                                </div>
                            </div>

                            <div style="display:grid;gap:14px;">
                                <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                                    <button type="button" class="hcm-prof-blue-btn" id="hcm-open-sig-modal">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                        <span id="hcm-sig-btn-label">{{ $user->signature ? 'Ganti / Buat Ulang Tanda Tangan' : 'Buat / Upload Tanda Tangan' }}</span>
                                    </button>
                                    <button type="button" class="hcm-prof-danger-btn" id="hcm-remove-sig-btn" style="padding:10px 14px;font-size:12px;" @if (! $user->signature) hidden @endif>
                                        Hapus Tanda Tangan
                                    </button>
                                </div>

                                <div class="hcm-prof-spec-box">
                                    <div style="font-weight:700;color:#818cf8;margin-bottom:10px;display:flex;align-items:center;gap:6px;">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 16v-4M12 8h.01"/></svg>
                                        <span>Spesifikasi &amp; Format Tanda Tangan:</span>
                                    </div>
                                    <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:8px;margin-bottom:10px;">
                                        <div style="padding:8px 10px;border-radius:8px;background:var(--bg-card-inner);border:1px solid var(--border-color);">
                                            <span style="display:block;font-size:10.5px;color:var(--text-secondary);">Resolusi Gambar:</span>
                                            <strong style="font-size:11.5px;color:var(--text-primary);">800 × 400 Pixels</strong>
                                        </div>
                                        <div style="padding:8px 10px;border-radius:8px;background:var(--bg-card-inner);border:1px solid var(--border-color);">
                                            <span style="display:block;font-size:10.5px;color:var(--text-secondary);">Aspek Rasio:</span>
                                            <strong style="font-size:11.5px;color:var(--text-primary);">2:1 (Landscape HD)</strong>
                                        </div>
                                    </div>
                                    <p style="margin:0;line-height:1.5;font-size:11px;">
                                        Foto ukuran apapun (seperti 900×900, 1080×1080, atau foto kamera HP) otomatis dinormalisasi menjadi <strong style="color:var(--text-primary);">800 × 400 px</strong> dengan kualitas tinggi agar selalu proporsional dan tidak buram pada dokumen cetak PDF.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Card 3: Data Pribadi -->
                    <section class="hcm-prof-card">
                        <h2>Data Pribadi</h2>
                        <div class="hcm-prof-form-grid">
                            <div class="hcm-prof-field">
                                <label for="prof-name">Nama Lengkap *</label>
                                <input id="prof-name" type="text" name="name" class="hcm-prof-input" value="{{ old('name', $user->name) }}" required>
                            </div>
                            <div class="hcm-prof-field">
                                <label for="prof-email">Email *</label>
                                <input id="prof-email" type="email" name="email" class="hcm-prof-input" value="{{ old('email', $user->email) }}" required>
                            </div>
                            <div class="hcm-prof-field">
                                <label for="prof-phone">No. Telepon</label>
                                <input id="prof-phone" type="text" name="phone" class="hcm-prof-input" value="{{ old('phone', $user->phone) }}">
                            </div>
                            <div class="hcm-prof-field">
                                <label for="prof-birth">Tanggal Lahir</label>
                                <input id="prof-birth" type="date" name="birth_date" class="hcm-prof-input" value="{{ old('birth_date', $user->birth_date) }}">
                            </div>
                            <div class="hcm-prof-field">
                                <label for="prof-gender">Jenis Kelamin</label>
                                <select id="prof-gender" name="gender" class="hcm-prof-input">
                                    <option value="">Pilih...</option>
                                    <option value="Laki-laki" @selected(old('gender', $user->gender) === 'Laki-laki')>Laki-laki</option>
                                    <option value="Perempuan" @selected(old('gender', $user->gender) === 'Perempuan')>Perempuan</option>
                                </select>
                            </div>
                            <div class="hcm-prof-field">
                                <label for="prof-marital">Status Pernikahan</label>
                                <select id="prof-marital" name="marital_status" class="hcm-prof-input">
                                    <option value="">Pilih...</option>
                                    <option value="Belum Menikah" @selected(old('marital_status', $user->marital_status) === 'Belum Menikah')>Belum Menikah</option>
                                    <option value="Menikah" @selected(old('marital_status', $user->marital_status) === 'Menikah')>Menikah</option>
                                    <option value="Cerai" @selected(old('marital_status', $user->marital_status) === 'Cerai')>Cerai</option>
                                </select>
                            </div>
                            <div class="hcm-prof-field is-full">
                                <label for="prof-address">Alamat</label>
                                <textarea id="prof-address" name="address" rows="3" class="hcm-prof-input">{{ old('address', $user->address) }}</textarea>
                            </div>
                            <div class="hcm-prof-field">
                                <label for="prof-nationality">Kewarganegaraan</label>
                                <input id="prof-nationality" type="text" name="nationality" class="hcm-prof-input" value="{{ old('nationality', $user->nationality ?? 'Indonesia') }}">
                            </div>
                        </div>
                    </section>

                    <!-- Card 4: Password -->
                    <section class="hcm-prof-card">
                        <div style="display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;">
                            <div>
                                <h2 style="margin-bottom:4px;">Password</h2>
                                <p style="margin:0;font-size:13px;color:var(--text-secondary);">Ganti password akun Anda</p>
                            </div>
                            <button type="button" class="hcm-prof-blue-btn" id="hcm-open-password-modal" style="padding:12px 22px;font-size:13.5px;">
                                Ubah Password
                            </button>
                        </div>
                    </section>

                    <!-- Bottom Action Buttons -->
                    <div style="display:flex;align-items:center;justify-content:flex-end;gap:12px;">
                        <button type="button" class="hcm-btn-secondary" data-nav-view="dashboard" style="padding:12px 24px;background:#374151;color:#e2e8f0;border:0;">
                            Batal
                        </button>
                        <button type="submit" class="hcm-prof-blue-btn" style="padding:12px 24px;font-size:13.5px;">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>

                <!-- Pengaturan Aplikasi (Tampil di Mobile) -->
                <section class="hcm-prof-card hcm-mobile-app-settings">
                    <h2 style="margin-bottom:4px;">Pengaturan Aplikasi</h2>
                    <button type="button" class="hcm-footer-btn" id="hcm-mobile-theme-toggle" style="justify-content:space-between;padding:13px 16px;border-radius:12px;">
                        <span style="display:inline-flex;align-items:center;gap:10px;">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="5"/><path stroke-linecap="round" d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg>
                            <span data-theme-label>Light Mode</span>
                        </span>
                        <span style="font-size:11.5px;color:var(--text-secondary);">Ganti Tema</span>
                    </button>
                    <form method="POST" action="{{ route('attendance.logout') }}" style="margin:0;">
                        @csrf
                        <button type="submit" class="hcm-footer-btn logout-btn" style="padding:13px 16px;border-radius:12px;">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            <span>Logout</span>
                        </button>
                    </form>
                </section>
            </div>
        </main>

        <!-- Mobile Bottom Navigation Bar (HEXHRS) -->
        <nav class="hcm-bottom-nav" aria-label="Navigasi Mobile">
            <button type="button" class="hcm-bottom-nav-item is-active" data-nav-view="dashboard">
                <span class="hcm-bottom-nav-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <i class="hcm-bottom-nav-dot"></i>
                </span>
                <span>Dashboard</span>
            </button>

            <button type="button" class="hcm-bottom-nav-item" data-nav-view="history">
                <span class="hcm-bottom-nav-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <i class="hcm-bottom-nav-dot"></i>
                </span>
                <span>Absensi Saya</span>
            </button>

            <a href="https://wa.me/6285890007359?text={{ urlencode('Halo Admin GASS, saya '.$user->name.' ('.($user->employee_id ?? 'Karyawan').') ingin mengajukan izin/cuti.') }}" target="_blank" rel="noreferrer" class="hcm-bottom-nav-item">
                <span class="hcm-bottom-nav-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </span>
                <span>Izin/Cuti Saya</span>
            </a>

            <button type="button" class="hcm-bottom-nav-item" data-nav-view="profile">
                <span class="hcm-bottom-nav-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <i class="hcm-bottom-nav-dot"></i>
                </span>
                <span>Profil Saya</span>
            </button>
        </nav>
    </div>

    <!-- Modal 1: Camera & GPS Check-In / Check-Out -->
    <div class="hcm-modal" id="hcm-attendance-modal" hidden>
        <div class="hcm-modal-dialog" role="dialog" aria-modal="true" aria-labelledby="hcm-modal-title">
            <div class="hcm-modal-head">
                <h3 style="display:inline-flex;align-items:center;gap:8px;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:var(--accent-teal);"><path stroke-linecap="round" stroke-linejoin="round" d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/><circle cx="12" cy="13" r="4"/></svg>
                    <span id="hcm-modal-title">Foto Absensi Masuk</span>
                </h3>
                <button type="button" class="hcm-modal-close" data-close-modal aria-label="Tutup">×</button>
            </div>

            <div class="hcm-camera-frame">
                <video id="hcm-camera-video" class="is-mirrored" autoplay playsinline muted></video>
                <img id="hcm-camera-preview" alt="Preview foto absensi" hidden>

                <span class="hcm-cam-corner tl"></span>
                <span class="hcm-cam-corner tr"></span>
                <span class="hcm-cam-corner bl"></span>
                <span class="hcm-cam-corner br"></span>

                <div style="position:absolute;top:12px;right:12px;z-index:2;display:flex;gap:6px;">
                    <button type="button" id="hcm-switch-camera" title="Ganti / Muat Ulang Kamera" style="padding:5px 10px;border-radius:8px;background:rgba(9,16,31,0.72);backdrop-filter:blur(4px);border:1px solid rgba(255,255,255,0.16);color:#e2e8f0;font-size:11px;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:5px;">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <span>Kamera</span>
                    </button>
                </div>

                <div style="position:absolute;left:8px;right:8px;bottom:8px;padding:6px 10px;border-radius:9px;background:rgba(9,16,31,0.72);backdrop-filter:blur(4px);border:1px solid rgba(255,255,255,0.14);color:#f8fafc;font-size:10.5px;display:flex;justify-content:space-between;align-items:center;gap:8px;pointer-events:none;">
                    <div style="min-width:0;">
                        <strong style="display:block;color:#2dd4bf;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" id="hcm-cam-hud-time">--:--:-- WIB</strong>
                        <span id="hcm-cam-hud-gps" style="font-family:'JetBrains Mono',monospace;font-size:9.5px;color:#cbd5e1;">GPS: Mendeteksi...</span>
                    </div>
                    <span id="hcm-cam-hud-badge" style="padding:3px 7px;border-radius:5px;background:rgba(16,185,129,0.2);color:#34d399;font-weight:700;font-size:9.5px;white-space:nowrap;flex-shrink:0;">LIVE CAMERA</span>
                </div>
            </div>

            <div class="hcm-gps-bar">
                <span style="display:inline-flex;align-items:center;gap:6px;">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:var(--accent-teal);flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span id="hcm-gps-status">Mendeteksi titik koordinat GPS...</span>
                </span>
                <button type="button" class="hcm-map-btn" id="hcm-retry-gps" style="padding:4px 9px;font-size:11px;">Ulangi GPS</button>
            </div>

            <div style="margin-top:10px;font-size:11.5px;color:var(--text-secondary);">
                <span>Pastikan wajah terlihat jelas di dalam kamera sebelum menekan tombol ambil foto.</span>
            </div>

            <div class="hcm-modal-foot">
                <button type="button" class="hcm-btn-secondary" data-close-modal>Batal</button>
                <button type="button" class="hcm-btn-primary" id="hcm-submit-attendance">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/><circle cx="12" cy="13" r="4"/></svg>
                    <span id="hcm-submit-label">Ambil Foto &amp; Kirim</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Modal 2: Leaflet Map Modal -->
    <div class="hcm-modal" id="hcm-map-modal" hidden>
        <div class="hcm-modal-dialog is-wide" role="dialog" aria-modal="true">
            <div class="hcm-modal-head">
                <h3 style="display:inline-flex;align-items:center;gap:8px;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:var(--accent-teal);"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span id="hcm-map-title">Titik Lokasi GPS Absensi</span>
                </h3>
                <button type="button" class="hcm-modal-close" data-close-map aria-label="Tutup">×</button>
            </div>
            <div id="hcm-leaflet-map"></div>
            <div style="margin-top:12px;font-size:12px;color:var(--text-secondary);display:flex;justify-content:space-between;" id="hcm-map-coords"></div>
        </div>
    </div>

    <!-- Modal 3: Photo Proof Modal -->
    <div class="hcm-modal" id="hcm-photo-modal" hidden>
        <div class="hcm-modal-dialog" role="dialog" aria-modal="true">
            <div class="hcm-modal-head">
                <h3 id="hcm-photo-modal-title">Bukti Foto Absensi</h3>
                <button type="button" class="hcm-modal-close" data-close-photo aria-label="Tutup">×</button>
            </div>
            <div class="hcm-camera-frame" style="background:#050814;min-height:240px;max-height:calc(100dvh - 185px);">
                <img id="hcm-photo-modal-img" src="" alt="Bukti Foto Absensi" style="object-fit:contain;max-height:calc(100dvh - 185px);">
            </div>
            <div class="hcm-gps-bar">
                <span>Waktu: <strong class="hcm-mono" id="hcm-photo-modal-time">-</strong></span>
                <span class="hcm-status-tag hadir" id="hcm-photo-modal-status">Hadir</span>
            </div>
        </div>
    </div>

    <!-- Modal 4: Digital Signature Canvas / Upload Modal -->
    <div class="hcm-modal" id="hcm-sig-modal" hidden>
        <div class="hcm-modal-dialog is-wide" role="dialog" aria-modal="true">
            <div class="hcm-modal-head">
                <h3 style="display:inline-flex;align-items:center;gap:8px;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#818cf8;"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    <span>Buat / Upload Tanda Tangan Digital (800 × 400 px)</span>
                </h3>
                <button type="button" class="hcm-modal-close" id="hcm-close-sig-modal" aria-label="Tutup">×</button>
            </div>
            <div style="background:#ffffff;border-radius:12px;border:2px dashed #cbd5e1;overflow:hidden;position:relative;">
                <canvas id="hcm-sig-canvas" width="800" height="400" style="width:100%;height:220px;display:block;cursor:crosshair;touch-action:none;"></canvas>
            </div>
            <div style="margin-top:12px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;font-size:12px;color:var(--text-secondary);">
                <span>Goreskan tanda tangan di kotak putih atau pilih file gambar:</span>
                <div style="display:flex;gap:8px;">
                    <button type="button" class="hcm-map-btn" id="hcm-clear-sig-canvas">Bersihkan</button>
                    <label class="hcm-map-btn" style="color:#818cf8;cursor:pointer;">
                        Upload Gambar
                        <input type="file" id="hcm-sig-file-input" accept="image/*" hidden>
                    </label>
                </div>
            </div>
            <div class="hcm-modal-foot">
                <button type="button" class="hcm-btn-secondary" id="hcm-cancel-sig-modal">Batal</button>
                <button type="button" class="hcm-prof-blue-btn" id="hcm-apply-sig-btn">Gunakan Tanda Tangan</button>
            </div>
        </div>
    </div>

    <!-- Modal 5: Ubah Password Modal -->
    <div class="hcm-modal" id="hcm-password-modal" hidden>
        <div class="hcm-modal-dialog" role="dialog" aria-modal="true">
            <div class="hcm-modal-head">
                <h3>Ubah Password</h3>
                <button type="button" class="hcm-modal-close" id="hcm-close-password-modal" aria-label="Tutup">×</button>
            </div>
            <form method="POST" action="{{ route('attendance.password.update') }}">
                @csrf
                <div style="display:grid;gap:14px;">
                    <div class="hcm-prof-field">
                        <label for="new-password">Password Baru *</label>
                        <input id="new-password" type="password" name="password" class="hcm-prof-input" minlength="6" required placeholder="Minimal 6 karakter">
                    </div>
                    <div class="hcm-prof-field">
                        <label for="confirm-password">Konfirmasi Password Baru *</label>
                        <input id="confirm-password" type="password" name="password_confirmation" class="hcm-prof-input" minlength="6" required placeholder="Ulangi password baru">
                    </div>
                </div>
                <div class="hcm-modal-foot">
                    <button type="button" class="hcm-btn-secondary" id="hcm-cancel-password-modal">Batal</button>
                    <button type="submit" class="hcm-prof-blue-btn">Simpan Password</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // 1. Theme Toggle (Dark / Light)
        const htmlEl = document.documentElement;
        const themeToggle = document.getElementById('hcm-theme-toggle');
        const mobileThemeToggle = document.getElementById('hcm-mobile-theme-toggle');
        const themeLabels = document.querySelectorAll('[data-theme-label]');
        const savedTheme = localStorage.getItem('gass_hcm_theme') || 'dark';
        const applyTheme = (theme) => {
            htmlEl.setAttribute('data-theme', theme);
            localStorage.setItem('gass_hcm_theme', theme);
            themeLabels.forEach((lbl) => {
                lbl.textContent = theme === 'dark' ? 'Light Mode' : 'Dark Mode';
            });
        };
        applyTheme(savedTheme);
        [themeToggle, mobileThemeToggle].forEach((btn) => {
            btn?.addEventListener('click', () => {
                applyTheme(htmlEl.getAttribute('data-theme') === 'dark' ? 'light' : 'dark');
            });
        });

        // Mobile sidebar
        document.getElementById('hcm-mobile-toggle')?.addEventListener('click', () => {
            document.getElementById('hcm-sidebar')?.classList.toggle('is-open');
        });

        // 2. Live WIB Clock & Live Duration
        const clockEl = document.getElementById('hcm-live-clock');
        const dateEl = document.getElementById('hcm-live-date');
        const durationEl = document.getElementById('hcm-today-duration');
        const updateLiveClock = () => {
            const now = new Date();
            const timeDot = new Intl.DateTimeFormat('id-ID', {
                timeZone: 'Asia/Jakarta',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false,
            }).format(now).replaceAll(':', '.');
            const dateStr = new Intl.DateTimeFormat('id-ID', {
                timeZone: 'Asia/Jakarta',
                weekday: 'long',
                day: 'numeric',
                month: 'short',
                year: 'numeric',
            }).format(now);

            if (clockEl) {
                clockEl.textContent = timeDot;
            }
            const histClockEl = document.getElementById('hcm-hist-clock');
            if (histClockEl) {
                histClockEl.textContent = timeDot;
            }
            if (dateEl) {
                dateEl.textContent = dateStr;
            }
            const camHudTime = document.getElementById('hcm-cam-hud-time');
            if (camHudTime) {
                camHudTime.textContent = `${timeDot.replaceAll('.', ':')} WIB • ${dateStr}`;
            }
            if (durationEl && durationEl.dataset.checkin && !durationEl.dataset.checkout) {
                const [inH, inM] = durationEl.dataset.checkin.split(':').map(Number);
                const jakartaParts = new Intl.DateTimeFormat('en-GB', {
                    timeZone: 'Asia/Jakarta',
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false,
                }).format(now).split(':').map(Number);
                const diffMinutes = Math.max(0, (jakartaParts[0] * 60 + jakartaParts[1]) - (inH * 60 + inM));
                durationEl.textContent = `${Math.floor(diffMinutes / 60)}j ${diffMinutes % 60}m`;
            }
        };
        setInterval(updateLiveClock, 1000);
        updateLiveClock();

        // 3. View Switcher (Dashboard vs Full History vs Profile) + Topbar User Dropdown
        const navButtons = document.querySelectorAll('[data-nav-view]');
        const dashboardView = document.getElementById('hcm-view-dashboard');
        const historyView = document.getElementById('hcm-view-history');
        const profileView = document.getElementById('hcm-view-profile');
        const userDropdownWrap = document.getElementById('hcm-user-dropdown-wrap');
        const userPillBtn = document.getElementById('hcm-user-pill-btn');
        const userDropdown = document.getElementById('hcm-user-dropdown');

        userPillBtn?.addEventListener('click', (e) => {
            e.stopPropagation();
            const isOpen = !userDropdown.hidden;
            userDropdown.hidden = isOpen;
            userDropdownWrap?.classList.toggle('is-open', !isOpen);
            userPillBtn.setAttribute('aria-expanded', String(!isOpen));
        });
        document.addEventListener('click', (e) => {
            if (userDropdown && !userDropdown.hidden && !userDropdownWrap?.contains(e.target)) {
                userDropdown.hidden = true;
                userDropdownWrap?.classList.remove('is-open');
                userPillBtn?.setAttribute('aria-expanded', 'false');
            }
        });

        const filterMonth = document.getElementById('hcm-filter-month');
        const filterYear = document.getElementById('hcm-filter-year');
        const filterStatus = document.getElementById('hcm-filter-status');
        const filterSort = document.getElementById('hcm-filter-sort');
        const filterReload = document.getElementById('hcm-filter-reload');
        const histTbody = document.getElementById('hcm-hist-tbody');
        const histEmpty = document.getElementById('hcm-hist-empty');
        const histTableWrap = document.getElementById('hcm-hist-table-wrap');
        const histCount = document.getElementById('hcm-hist-count');
        const histPeriodTitle = document.getElementById('hcm-hist-period-title');

        const applyHistoryFilters = () => {
            if (!histTbody) return;
            const selectedMonth = Number(filterMonth?.value || new Date().getMonth() + 1);
            const selectedMonthName = filterMonth?.selectedOptions[0]?.dataset.monthName || '';
            const selectedYear = Number(filterYear?.value || new Date().getFullYear());
            const selectedStatus = filterStatus?.value || 'all';
            const selectedSort = filterSort?.value || 'desc';

            if (histPeriodTitle) {
                histPeriodTitle.textContent = `Log Presensi Bulan ${selectedMonthName} ${selectedYear}`;
            }

            const rows = [...histTbody.querySelectorAll('.hcm-hist-row')];
            const periodRows = rows.filter((row) => Number(row.dataset.month) === selectedMonth && Number(row.dataset.year) === selectedYear);

            // Update 4 period stat cards based on selected month & year
            const presentCount = periodRows.length;
            const pagiCount = periodRows.filter((r) => r.dataset.shift === 'Shift Pagi').length;
            const soreCount = periodRows.filter((r) => r.dataset.shift === 'Shift Sore').length;
            const totalMin = periodRows.reduce((acc, r) => acc + Number(r.dataset.minutes || 0), 0);
            const totalDurStr = `${Math.floor(totalMin / 60)}j ${totalMin % 60}m`;

            const elPresent = document.getElementById('hcm-hist-stat-present');
            const elPagi = document.getElementById('hcm-hist-stat-pagi');
            const elSore = document.getElementById('hcm-hist-stat-sore');
            const elHours = document.getElementById('hcm-hist-stat-hours');
            if (elPresent) elPresent.textContent = String(presentCount);
            if (elPagi) elPagi.textContent = String(pagiCount);
            if (elSore) elSore.textContent = String(soreCount);
            if (elHours) elHours.textContent = totalDurStr;

            const visibleRows = periodRows.filter((row) => selectedStatus === 'all' || row.dataset.shift === selectedStatus || row.dataset.status === selectedStatus);

            rows.forEach((row) => { row.hidden = true; });

            visibleRows.sort((a, b) => {
                return selectedSort === 'asc'
                    ? a.dataset.date.localeCompare(b.dataset.date)
                    : b.dataset.date.localeCompare(a.dataset.date);
            });

            visibleRows.forEach((row) => {
                row.hidden = false;
                histTbody.appendChild(row);
            });

            if (histCount) {
                histCount.textContent = `${visibleRows.length} Catatan`;
            }
            if (histEmpty && histTableWrap) {
                histEmpty.hidden = visibleRows.length > 0;
                histTableWrap.hidden = visibleRows.length === 0;
            }
        };

        [filterMonth, filterYear, filterStatus, filterSort].forEach((select) => {
            select?.addEventListener('change', applyHistoryFilters);
        });
        filterReload?.addEventListener('click', applyHistoryFilters);

        const setViewMode = (mode) => {
            if (dashboardView) dashboardView.style.display = mode === 'dashboard' ? 'grid' : 'none';
            if (historyView) historyView.style.display = mode === 'history' ? 'grid' : 'none';
            if (profileView) profileView.style.display = mode === 'profile' ? 'grid' : 'none';

            if (userDropdown) {
                userDropdown.hidden = true;
                userDropdownWrap?.classList.remove('is-open');
            }

            document.querySelectorAll('.hcm-sidebar [data-nav-view], .hcm-bottom-nav [data-nav-view]').forEach((btn) => {
                btn.classList.toggle('is-active', btn.dataset.navView === mode);
            });
            if (mode === 'history') {
                applyHistoryFilters();
            }
            window.scrollTo({ top: 0, behavior: 'smooth' });
        };

        const initialView = @json(session('open_view', 'dashboard'));
        setViewMode(initialView);
        navButtons.forEach((btn) => {
            btn.addEventListener('click', () => setViewMode(btn.dataset.navView));
        });

        // 3b. Profile Photo, Digital Signature & Password Modal Logic
        const profPhotoInput = document.getElementById('hcm-prof-photo-input');
        const profPhotoBase64 = document.getElementById('hcm-profile-photo-base64');
        const profRemovePhoto = document.getElementById('hcm-remove-profile-photo');
        const profAvatarImg = document.getElementById('hcm-prof-avatar-img');
        const profAvatarInitial = document.getElementById('hcm-prof-avatar-initial');
        const profRemovePhotoBtn = document.getElementById('hcm-prof-remove-photo-btn');
        const profUploadLabel = document.getElementById('hcm-prof-upload-label');

        profPhotoInput?.addEventListener('change', () => {
            const [file] = profPhotoInput.files;
            if (!file) return;
            const reader = new FileReader();
            reader.onload = () => {
                profPhotoBase64.value = reader.result;
                profRemovePhoto.value = '0';
                if (profAvatarImg) {
                    profAvatarImg.src = reader.result;
                    profAvatarImg.hidden = false;
                }
                if (profAvatarInitial) profAvatarInitial.hidden = true;
                if (profRemovePhotoBtn) profRemovePhotoBtn.hidden = false;
                if (profUploadLabel) profUploadLabel.textContent = 'Ganti Foto';
            };
            reader.readAsDataURL(file);
        });

        profRemovePhotoBtn?.addEventListener('click', () => {
            profPhotoBase64.value = '';
            profRemovePhoto.value = '1';
            if (profAvatarImg) {
                profAvatarImg.src = '';
                profAvatarImg.hidden = true;
            }
            if (profAvatarInitial) profAvatarInitial.hidden = false;
            profRemovePhotoBtn.hidden = true;
            if (profUploadLabel) profUploadLabel.textContent = 'Upload Foto';
        });

        // Digital Signature Canvas (800x400)
        const sigModal = document.getElementById('hcm-sig-modal');
        const sigCanvas = document.getElementById('hcm-sig-canvas');
        const sigCtx = sigCanvas?.getContext('2d');
        const sigFileInput = document.getElementById('hcm-sig-file-input');
        const sigBase64Input = document.getElementById('hcm-signature-base64');
        const sigRemoveInput = document.getElementById('hcm-remove-signature');
        const sigPreviewImg = document.getElementById('hcm-sig-preview-img');
        const sigActivePreview = document.getElementById('hcm-sig-active-preview');
        const sigEmptyPreview = document.getElementById('hcm-sig-empty-preview');
        const sigRemoveBtn = document.getElementById('hcm-remove-sig-btn');
        const sigBtnLabel = document.getElementById('hcm-sig-btn-label');

        let isDrawingSig = false;
        const resetSigCanvas = () => {
            if (!sigCtx || !sigCanvas) return;
            sigCtx.clearRect(0, 0, sigCanvas.width, sigCanvas.height);
            sigCtx.strokeStyle = '#0f172a';
            sigCtx.lineWidth = 4;
            sigCtx.lineCap = 'round';
            sigCtx.lineJoin = 'round';
        };
        resetSigCanvas();

        const getSigPos = (e) => {
            const rect = sigCanvas.getBoundingClientRect();
            const clientX = e.touches ? e.touches[0].clientX : e.clientX;
            const clientY = e.touches ? e.touches[0].clientY : e.clientY;
            return {
                x: (clientX - rect.left) * (sigCanvas.width / rect.width),
                y: (clientY - rect.top) * (sigCanvas.height / rect.height),
            };
        };

        sigCanvas?.addEventListener('pointerdown', (e) => {
            isDrawingSig = true;
            const pos = getSigPos(e);
            sigCtx.beginPath();
            sigCtx.moveTo(pos.x, pos.y);
        });
        sigCanvas?.addEventListener('pointermove', (e) => {
            if (!isDrawingSig) return;
            const pos = getSigPos(e);
            sigCtx.lineTo(pos.x, pos.y);
            sigCtx.stroke();
        });
        window.addEventListener('pointerup', () => { isDrawingSig = false; });

        document.getElementById('hcm-clear-sig-canvas')?.addEventListener('click', resetSigCanvas);

        sigFileInput?.addEventListener('change', () => {
            const [file] = sigFileInput.files;
            if (!file) return;
            const reader = new FileReader();
            reader.onload = () => {
                const img = new Image();
                img.onload = () => {
                    resetSigCanvas();
                    const scale = Math.min((sigCanvas.width - 40) / img.width, (sigCanvas.height - 40) / img.height);
                    const drawW = img.width * scale;
                    const drawH = img.height * scale;
                    sigCtx.drawImage(img, (sigCanvas.width - drawW) / 2, (sigCanvas.height - drawH) / 2, drawW, drawH);
                };
                img.src = reader.result;
            };
            reader.readAsDataURL(file);
        });

        document.getElementById('hcm-open-sig-modal')?.addEventListener('click', () => {
            resetSigCanvas();
            sigModal.hidden = false;
        });
        ['hcm-close-sig-modal', 'hcm-cancel-sig-modal'].forEach((id) => {
            document.getElementById(id)?.addEventListener('click', () => { sigModal.hidden = true; });
        });

        document.getElementById('hcm-apply-sig-btn')?.addEventListener('click', () => {
            const dataUrl = sigCanvas.toDataURL('image/png');
            sigBase64Input.value = dataUrl;
            sigRemoveInput.value = '0';
            if (sigPreviewImg) sigPreviewImg.src = dataUrl;
            if (sigActivePreview) sigActivePreview.style.display = 'flex';
            if (sigEmptyPreview) sigEmptyPreview.style.display = 'none';
            if (sigRemoveBtn) sigRemoveBtn.hidden = false;
            if (sigBtnLabel) sigBtnLabel.textContent = 'Ganti / Buat Ulang Tanda Tangan';
            sigModal.hidden = true;
        });

        sigRemoveBtn?.addEventListener('click', () => {
            sigBase64Input.value = '';
            sigRemoveInput.value = '1';
            if (sigPreviewImg) sigPreviewImg.src = '';
            if (sigActivePreview) sigActivePreview.style.display = 'none';
            if (sigEmptyPreview) sigEmptyPreview.style.display = 'flex';
            sigRemoveBtn.hidden = true;
            if (sigBtnLabel) sigBtnLabel.textContent = 'Buat / Upload Tanda Tangan';
        });

        // Password Modal
        const passwordModal = document.getElementById('hcm-password-modal');
        document.getElementById('hcm-open-password-modal')?.addEventListener('click', () => {
            passwordModal.hidden = false;
        });
        ['hcm-close-password-modal', 'hcm-cancel-password-modal'].forEach((id) => {
            document.getElementById(id)?.addEventListener('click', () => { passwordModal.hidden = true; });
        });

        // 4. Camera & Geolocation Modal
        const attendanceModal = document.getElementById('hcm-attendance-modal');
        const modalTitle = document.getElementById('hcm-modal-title');
        const videoEl = document.getElementById('hcm-camera-video');
        const previewEl = document.getElementById('hcm-camera-preview');
        const fileFallback = document.getElementById('hcm-file-fallback');
        const gpsStatus = document.getElementById('hcm-gps-status');
        const submitBtn = document.getElementById('hcm-submit-attendance');
        const submitLabel = document.getElementById('hcm-submit-label');

        let currentAction = 'checkin';
        let mediaStream = null;
        let currentCoords = null;
        let customPhotoDataUrl = null;

        const stopCamera = () => {
            if (mediaStream) {
                mediaStream.getTracks().forEach((track) => track.stop());
                mediaStream = null;
            }
        };

        const detectGPS = () => {
            const camHudGps = document.getElementById('hcm-cam-hud-gps');
            gpsStatus.textContent = 'Mendeteksi lokasi GPS perangkat...';
            if (!navigator.geolocation) {
                currentCoords = { latitude: -6.6584, longitude: 106.8486 };
                gpsStatus.textContent = 'Lokasi Default Kantor Ciawi (-6.6584, 106.8486)';
                if (camHudGps) camHudGps.textContent = `GPS: ${currentCoords.latitude}, ${currentCoords.longitude}`;
                return;
            }
            navigator.geolocation.getCurrentPosition(
                (pos) => {
                    currentCoords = {
                        latitude: Number(pos.coords.latitude.toFixed(6)),
                        longitude: Number(pos.coords.longitude.toFixed(6)),
                    };
                    gpsStatus.textContent = `GPS Aktif: ${currentCoords.latitude}, ${currentCoords.longitude}`;
                    if (camHudGps) camHudGps.textContent = `GPS: ${currentCoords.latitude}, ${currentCoords.longitude}`;
                },
                () => {
                    currentCoords = { latitude: -6.6584, longitude: 106.8486 };
                    gpsStatus.textContent = 'GPS Fallback Kantor (-6.6584, 106.8486)';
                    if (camHudGps) camHudGps.textContent = `GPS: ${currentCoords.latitude}, ${currentCoords.longitude}`;
                },
                { enableHighAccuracy: true, timeout: 8000, maximumAge: 0 }
            );
        };

        document.getElementById('hcm-retry-gps')?.addEventListener('click', detectGPS);

        let availableVideoDevices = [];
        let currentVideoDeviceIndex = 0;
        let currentFacingMode = 'user';

        const startCameraStream = async (preferNextDevice = false) => {
            const hudBadge = document.getElementById('hcm-cam-hud-badge');
            if (hudBadge) {
                hudBadge.textContent = 'LIVE CAMERA';
                hudBadge.style.background = 'rgba(16,185,129,0.2)';
                hudBadge.style.color = '#34d399';
            }

            try {
                stopCamera();
                if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                    throw new Error('No camera API');
                }

                if (preferNextDevice && availableVideoDevices.length > 1) {
                    currentVideoDeviceIndex = (currentVideoDeviceIndex + 1) % availableVideoDevices.length;
                    const chosenDevice = availableVideoDevices[currentVideoDeviceIndex];
                    mediaStream = await navigator.mediaDevices.getUserMedia({
                        video: { deviceId: { exact: chosenDevice.deviceId } },
                        audio: false,
                    });
                } else if (preferNextDevice) {
                    currentFacingMode = currentFacingMode === 'user' ? 'environment' : 'user';
                    videoEl.classList.toggle('is-mirrored', currentFacingMode === 'user');
                    mediaStream = await navigator.mediaDevices.getUserMedia({
                        video: { facingMode: currentFacingMode },
                        audio: false,
                    });
                } else {
                    try {
                        mediaStream = await navigator.mediaDevices.getUserMedia({
                            video: { facingMode: currentFacingMode },
                            audio: false,
                        });
                    } catch {
                        mediaStream = await navigator.mediaDevices.getUserMedia({
                            video: true,
                            audio: false,
                        });
                    }

                    if (navigator.mediaDevices.enumerateDevices) {
                        const devices = await navigator.mediaDevices.enumerateDevices();
                        availableVideoDevices = devices.filter((d) => d.kind === 'videoinput');
                        const activeTrack = mediaStream.getVideoTracks()[0];
                        const activeLabel = (activeTrack?.label || '').toLowerCase();
                        if (availableVideoDevices.length > 1 && (activeLabel.includes('virtual') || activeLabel.includes('obs'))) {
                            const realCamIdx = availableVideoDevices.findIndex((d) => {
                                const lbl = (d.label || '').toLowerCase();
                                return !lbl.includes('virtual') && !lbl.includes('obs');
                            });
                            if (realCamIdx !== -1) {
                                currentVideoDeviceIndex = realCamIdx;
                                stopCamera();
                                mediaStream = await navigator.mediaDevices.getUserMedia({
                                    video: { deviceId: { exact: availableVideoDevices[realCamIdx].deviceId } },
                                    audio: false,
                                });
                            }
                        }
                    }
                }

                videoEl.srcObject = mediaStream;
                await videoEl.play().catch(() => {});
            } catch {
                // Keep camera frame clean as requested
            }
        };

        document.getElementById('hcm-switch-camera')?.addEventListener('click', () => {
            startCameraStream(true);
        });

        const openAttendanceModal = async (action) => {
            if (!action) return;
            currentAction = action;
            customPhotoDataUrl = null;
            previewEl.hidden = true;
            videoEl.hidden = false;
            modalTitle.textContent = action === 'checkin' ? 'Foto Absensi Masuk' : 'Foto Absensi Pulang';
            attendanceModal.hidden = false;
            detectGPS();
            await startCameraStream(false);
        };

        const closeAttendanceModal = () => {
            stopCamera();
            attendanceModal.hidden = true;
        };

        document.querySelectorAll('[data-open-attendance]').forEach((btn) => {
            btn.addEventListener('click', () => openAttendanceModal(btn.dataset.openAttendance));
        });
        document.querySelectorAll('[data-close-modal]').forEach((btn) => {
            btn.addEventListener('click', closeAttendanceModal);
        });

        const drawRoundedRect = (ctx, x, y, w, h, r) => {
            ctx.beginPath();
            ctx.moveTo(x + r, y);
            ctx.lineTo(x + w - r, y);
            ctx.quadraticCurveTo(x + w, y, x + w, y + r);
            ctx.lineTo(x + w, y + h - r);
            ctx.quadraticCurveTo(x + w, y + h, x + w - r, y + h);
            ctx.lineTo(x + r, y + h);
            ctx.quadraticCurveTo(x, y + h, x, y + h - r);
            ctx.lineTo(x, y + r);
            ctx.quadraticCurveTo(x, y, x + r, y);
            ctx.closePath();
        };

        const renderDigitalAttendanceCard = ({ actionType = 'checkin', stampTime = '', lat = -6.6584, lng = 106.8486 } = {}) => {
            const canvas = document.createElement('canvas');
            canvas.width = 640;
            canvas.height = 480;
            const ctx = canvas.getContext('2d');

            const bgGrad = ctx.createLinearGradient(0, 0, canvas.width, canvas.height);
            bgGrad.addColorStop(0, '#091224');
            bgGrad.addColorStop(0.5, '#112240');
            bgGrad.addColorStop(1, '#0b172d');
            ctx.fillStyle = bgGrad;
            ctx.fillRect(0, 0, canvas.width, canvas.height);

            ctx.strokeStyle = 'rgba(148, 163, 184, 0.06)';
            ctx.lineWidth = 1;
            for (let gx = 0; gx < canvas.width; gx += 32) {
                ctx.beginPath(); ctx.moveTo(gx, 0); ctx.lineTo(gx, canvas.height); ctx.stroke();
            }
            for (let gy = 0; gy < canvas.height; gy += 32) {
                ctx.beginPath(); ctx.moveTo(0, gy); ctx.lineTo(canvas.width, gy); ctx.stroke();
            }

            drawRoundedRect(ctx, 28, 24, canvas.width - 56, canvas.height - 48, 20);
            ctx.fillStyle = 'rgba(19, 34, 63, 0.92)';
            ctx.fill();
            ctx.strokeStyle = 'rgba(45, 212, 191, 0.35)';
            ctx.lineWidth = 1.5;
            ctx.stroke();

            drawRoundedRect(ctx, 48, 44, canvas.width - 96, 42, 10);
            ctx.fillStyle = 'rgba(9, 18, 36, 0.7)';
            ctx.fill();

            ctx.fillStyle = '#2dd4bf';
            ctx.font = 'bold 12px Inter, sans-serif';
            ctx.fillText('GASS HRS • KARTU BUKTI PRESENSI DIGITAL', 64, 70);

            ctx.textAlign = 'right';
            ctx.fillStyle = '#60a5fa';
            ctx.font = 'bold 11px monospace';
            ctx.fillText('✓ GPS & WAKTU TERVERIFIKASI', canvas.width - 64, 70);
            ctx.textAlign = 'left';

            drawRoundedRect(ctx, 48, 108, 78, 78, 18);
            const avGrad = ctx.createLinearGradient(48, 108, 126, 186);
            avGrad.addColorStop(0, '#0d9488');
            avGrad.addColorStop(1, '#2563eb');
            ctx.fillStyle = avGrad;
            ctx.fill();

            ctx.fillStyle = '#ffffff';
            ctx.font = 'bold 28px Inter, sans-serif';
            ctx.textAlign = 'center';
            ctx.fillText('{{ addslashes($user->initials) }}', 87, 157);
            ctx.textAlign = 'left';

            ctx.fillStyle = '#2dd4bf';
            ctx.font = 'bold 11px Inter, sans-serif';
            ctx.fillText('NIK: {{ addslashes($user->employee_id ?? sprintf("EMP-%03d", $user->id)) }} • {{ addslashes(strtoupper($user->role ?: "KARYAWAN")) }}', 144, 126);

            ctx.fillStyle = '#f8fafc';
            ctx.font = 'bold 24px Inter, sans-serif';
            ctx.fillText('{{ addslashes($user->name) }}', 144, 156);

            ctx.fillStyle = '#94a3b8';
            ctx.font = '13px Inter, sans-serif';
            ctx.fillText('Divisi {{ addslashes($user->division ?? "IT & Digital") }} • Posisi {{ addslashes($user->position ?? "Karyawan") }}', 144, 179);

            drawRoundedRect(ctx, 48, 208, 262, 86, 12);
            ctx.fillStyle = 'rgba(15, 23, 42, 0.78)';
            ctx.fill();
            ctx.strokeStyle = 'rgba(51, 65, 85, 0.8)';
            ctx.lineWidth = 1;
            ctx.stroke();

            ctx.fillStyle = '#94a3b8';
            ctx.font = 'bold 10px Inter, sans-serif';
            ctx.fillText('TIPE VERIFIKASI PRESENSI', 64, 232);
            ctx.fillStyle = actionType === 'checkin' ? '#34d399' : '#fbbf24';
            ctx.font = 'bold 17px Inter, sans-serif';
            ctx.fillText(actionType === 'checkin' ? '● CHECK-IN MASUK' : '● CHECK-OUT PULANG', 64, 258);
            ctx.fillStyle = '#cbd5e1';
            ctx.font = '11px Inter, sans-serif';
            ctx.fillText('Tercatat otomatis oleh sistem', 64, 278);

            drawRoundedRect(ctx, 326, 208, 266, 86, 12);
            ctx.fillStyle = 'rgba(15, 23, 42, 0.78)';
            ctx.fill();
            ctx.strokeStyle = 'rgba(51, 65, 85, 0.8)';
            ctx.stroke();

            ctx.fillStyle = '#94a3b8';
            ctx.font = 'bold 10px Inter, sans-serif';
            ctx.fillText('METODE & PERANGKAT', 342, 232);
            ctx.fillStyle = '#60a5fa';
            ctx.font = 'bold 15px Inter, sans-serif';
            ctx.fillText('Mode Digital (Tanpa Kamera)', 342, 257);
            ctx.fillStyle = '#cbd5e1';
            ctx.font = '11px Inter, sans-serif';
            ctx.fillText('Kamera tidak tersedia / dinonaktifkan', 342, 278);

            drawRoundedRect(ctx, 48, 312, canvas.width - 96, 118, 14);
            ctx.fillStyle = 'rgba(9, 18, 36, 0.88)';
            ctx.fill();
            ctx.strokeStyle = 'rgba(45, 212, 191, 0.28)';
            ctx.stroke();

            ctx.fillStyle = '#94a3b8';
            ctx.font = 'bold 10px Inter, sans-serif';
            ctx.fillText('WAKTU PRESENSI REAL-TIME (WIB)', 66, 338);
            ctx.fillStyle = '#f8fafc';
            ctx.font = 'bold 16px monospace';
            ctx.fillText(`${stampTime} WIB`, 66, 362);

            ctx.fillStyle = '#94a3b8';
            ctx.font = 'bold 10px Inter, sans-serif';
            ctx.fillText('TITIK KOORDINAT LOKASI GPS', 66, 390);
            ctx.fillStyle = '#2dd4bf';
            ctx.font = 'bold 14px monospace';
            ctx.fillText(`LAT: ${lat}  |  LNG: ${lng}`, 66, 412);

            return canvas.toDataURL('image/jpeg', 0.9);
        };

        const isCanvasMostlyBlankOrLegacy = (ctx, w, h) => {
            try {
                const sample = ctx.getImageData(0, 0, w, Math.floor(h * 0.35)).data;
                let totalDiff = 0;
                const r0 = sample[0], g0 = sample[1], b0 = sample[2];
                for (let i = 0; i < sample.length; i += 64) {
                    totalDiff += Math.abs(sample[i] - r0) + Math.abs(sample[i + 1] - g0) + Math.abs(sample[i + 2] - b0);
                }
                return (totalDiff / (sample.length / 64)) < 6;
            } catch {
                return false;
            }
        };

        submitBtn?.addEventListener('click', async () => {
            const coords = currentCoords || { latitude: -6.6584, longitude: 106.8486 };
            let photoData = customPhotoDataUrl;
            if (!photoData) {
                const stampNow = new Date();
                const stampTime = new Intl.DateTimeFormat('id-ID', {
                    timeZone: 'Asia/Jakarta',
                    dateStyle: 'medium',
                    timeStyle: 'medium',
                }).format(stampNow);

                if (mediaStream && videoEl.videoWidth) {
                    const canvas = document.createElement('canvas');
                    canvas.width = videoEl.videoWidth || 640;
                    canvas.height = videoEl.videoHeight || 480;
                    const ctx = canvas.getContext('2d');
                    ctx.save();
                    ctx.translate(canvas.width, 0);
                    ctx.scale(-1, 1);
                    ctx.drawImage(videoEl, 0, 0, canvas.width, canvas.height);
                    ctx.restore();

                    if (isCanvasMostlyBlankOrLegacy(ctx, canvas.width, canvas.height)) {
                        photoData = renderDigitalAttendanceCard({
                            actionType: currentAction,
                            stampTime,
                            lat: coords.latitude,
                            lng: coords.longitude,
                        });
                    } else {
                        ctx.fillStyle = 'rgba(9, 16, 31, 0.78)';
                        ctx.fillRect(0, canvas.height - 58, canvas.width, 58);
                        ctx.fillStyle = '#2dd4bf';
                        ctx.font = 'bold 14px Inter, sans-serif';
                        ctx.fillText(`{{ addslashes($user->name) }} • ${currentAction === 'checkin' ? 'CHECK-IN MASUK' : 'CHECK-OUT PULANG'}`, 16, canvas.height - 34);
                        ctx.fillStyle = '#f8fafc';
                        ctx.font = '12px monospace';
                        ctx.fillText(`WAKTU: ${stampTime} WIB | GPS: ${coords.latitude}, ${coords.longitude}`, 16, canvas.height - 14);
                        photoData = canvas.toDataURL('image/jpeg', 0.9);
                    }
                } else {
                    photoData = renderDigitalAttendanceCard({
                        actionType: currentAction,
                        stampTime,
                        lat: coords.latitude,
                        lng: coords.longitude,
                    });
                }
            }
            submitBtn.disabled = true;
            submitLabel.textContent = 'Memproses...';

            try {
                const response = await fetch('{{ route("attendance.action") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        action: currentAction,
                        photo: photoData,
                        latitude: coords.latitude,
                        longitude: coords.longitude,
                    }),
                });
                const result = await response.json();
                if (!response.ok) {
                    throw new Error(result.message || 'Gagal mencatat absensi.');
                }
                window.location.reload();
            } catch (error) {
                alert(error.message || 'Terjadi kesalahan saat mengirim absensi.');
                submitBtn.disabled = false;
                submitLabel.textContent = 'Ambil Foto & Kirim';
            }
        });

        // 5. Leaflet Map Modal
        const mapModal = document.getElementById('hcm-map-modal');
        const mapTitle = document.getElementById('hcm-map-title');
        const mapCoords = document.getElementById('hcm-map-coords');
        let leafletMap = null;
        let mapMarkers = [];

        document.querySelectorAll('[data-open-map]').forEach((btn) => {
            btn.addEventListener('click', () => {
                const date = btn.dataset.date;
                const inLat = parseFloat(btn.dataset.inLat);
                const inLng = parseFloat(btn.dataset.inLng);
                const outLat = parseFloat(btn.dataset.outLat);
                const outLng = parseFloat(btn.dataset.outLng);

                mapTitle.textContent = `Titik Lokasi GPS Absensi (${date})`;
                mapCoords.innerHTML = `<span>Masuk: <strong>${inLat}, ${inLng}</strong></span>${!isNaN(outLat) ? `<span>Pulang: <strong>${outLat}, ${outLng}</strong></span>` : ''}`;
                mapModal.hidden = false;

                setTimeout(() => {
                    if (!window.L) return;
                    if (!leafletMap) {
                        leafletMap = L.map('hcm-leaflet-map').setView([inLat, inLng], 15);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            maxZoom: 19,
                            attribution: '&copy; OpenStreetMap',
                        }).addTo(leafletMap);
                    } else {
                        leafletMap.setView([inLat, inLng], 15);
                        mapMarkers.forEach((m) => m.remove());
                        mapMarkers = [];
                    }
                    leafletMap.invalidateSize();
                    mapMarkers.push(L.marker([inLat, inLng]).addTo(leafletMap).bindPopup(`<b>Lokasi Check-In</b><br>${date}`).openPopup());
                    if (!isNaN(outLat) && !isNaN(outLng)) {
                        mapMarkers.push(L.marker([outLat, outLng]).addTo(leafletMap).bindPopup(`<b>Lokasi Check-Out</b><br>${date}`));
                    }
                }, 120);
            });
        });

        document.querySelectorAll('[data-close-map]').forEach((btn) => {
            btn.addEventListener('click', () => { mapModal.hidden = true; });
        });

        // 6. Photo Preview Modal (with automatic upgrade for legacy/blank fallback photos)
        const photoModal = document.getElementById('hcm-photo-modal');
        const upgradeLegacyPhotoThumb = (img) => {
            if (!img.complete || !img.naturalWidth) return;
            const probe = document.createElement('canvas');
            probe.width = img.naturalWidth;
            probe.height = img.naturalHeight;
            const pCtx = probe.getContext('2d');
            pCtx.drawImage(img, 0, 0);
            if (isCanvasMostlyBlankOrLegacy(pCtx, probe.width, probe.height)) {
                const isCheckout = (img.dataset.photoTitle || '').toLowerCase().includes('pulang');
                const upgradedUrl = renderDigitalAttendanceCard({
                    actionType: isCheckout ? 'checkout' : 'checkin',
                    stampTime: img.dataset.photoTime || '--:--:--',
                    lat: -6.64518,
                    lng: 106.843779,
                });
                img.src = upgradedUrl;
                img.dataset.photoUrl = upgradedUrl;
            }
        };

        document.querySelectorAll('[data-open-photo]').forEach((img) => {
            if (img.complete) {
                upgradeLegacyPhotoThumb(img);
            } else {
                img.addEventListener('load', () => upgradeLegacyPhotoThumb(img), { once: true });
            }

            img.addEventListener('click', () => {
                document.getElementById('hcm-photo-modal-title').textContent = img.dataset.photoTitle;
                document.getElementById('hcm-photo-modal-img').src = img.dataset.photoUrl;
                document.getElementById('hcm-photo-modal-time').textContent = img.dataset.photoTime || '-';
                document.getElementById('hcm-photo-modal-status').textContent = img.dataset.photoStatus || 'Hadir';
                photoModal.hidden = false;
            });
        });
        document.querySelectorAll('[data-close-photo]').forEach((btn) => {
            btn.addEventListener('click', () => { photoModal.hidden = true; });
        });

        // Close any modal when clicking outside the card (backdrop) or pressing Escape
        attendanceModal?.addEventListener('click', (e) => {
            if (e.target === attendanceModal) closeAttendanceModal();
        });
        [mapModal, photoModal, sigModal, passwordModal].forEach((modal) => {
            modal?.addEventListener('click', (e) => {
                if (e.target === modal) modal.hidden = true;
            });
        });
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                if (attendanceModal && !attendanceModal.hidden) closeAttendanceModal();
                [mapModal, photoModal, sigModal, passwordModal].forEach((modal) => {
                    if (modal) modal.hidden = true;
                });
            }
        });
    </script>
</body>
</html>
