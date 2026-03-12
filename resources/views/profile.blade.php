@extends('layouts.app')

@section('title', 'My Profile - U-KAY HUB')

@php
    $username = old('username') ?? explode('@', $user->email)[0];
    $activePanel = request('panel');

    if (!$activePanel) {
        $activePanel = $errors->has('current_password') || $errors->has('new_password') ? 'change-password' : 'profile';
    }

    if (!in_array($activePanel, ['profile', 'change-password', 'my-orders', 'notifications'], true)) {
        $activePanel = 'profile';
    }
@endphp

@push('styles')
<style>
    /* ─────────────────────────────────────────────
   ACCOUNT SHELL
───────────────────────────────────────────── */
.account-shell {
    max-width: 1400px;
    margin: 3rem auto;
    padding: 0 2rem;
    display: grid;
    grid-template-columns: 260px minmax(0, 1fr);
    gap: 2rem;
    align-items: start;
    font-family: 'DM Sans', 'Segoe UI', sans-serif;
}

/* ─────────────────────────────────────────────
   SIDEBAR
───────────────────────────────────────────── */
.account-sidebar {
    background: #ffffff;
    border: 1px solid #eef0f4;
    border-radius: 8px;
    padding: 2rem 1.6rem;
    box-shadow: 0 2px 16px rgba(15, 23, 42, 0.045);
    position: sticky;
    top: 2rem;
}

.account-user {
    display: flex;
    align-items: center;
    gap: 1.1rem;
    padding-bottom: 1.6rem;
    border-bottom: 1px solid #f1f3f7;
    margin-bottom: 1.8rem;
}

.account-avatar {
    width: 54px;
    height: 54px;
    border-radius: 50%;
    border: 2px solid #f0f0f0;
    color: #c4c9d4;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    background: #f8f9fb;
    flex-shrink: 0;
    transition: border-color 0.2s ease;
}

.account-avatar:hover {
    border-color: #ee4d2d;
}

.account-user-name {
    font-size: 1.55rem;
    font-weight: 700;
    color: #0f172a;
    line-height: 1.25;
    word-break: break-word;
    letter-spacing: -0.01em;
}

.account-edit {
    margin-top: .45rem;
    font-size: 1.2rem;
    color: #94a3b8;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: .35rem;
    transition: color 0.18s ease;
}

.account-edit:hover {
    color: #ee4d2d;
}

.account-edit i {
    font-size: 1.05rem;
}

.account-link-block {
    margin-top: .4rem;
    display: grid;
    gap: 1.5rem;
}

.account-group {
    display: grid;
    gap: .5rem;
}

.account-link-item {
    font-size: 1.42rem;
    font-weight: 600;
    color: #334155;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: .9rem;
    border: none;
    background: transparent;
    text-align: left;
    cursor: pointer;
    padding: .55rem .7rem;
    line-height: 1.2;
    border-radius: 6px;
    transition: background 0.18s ease, color 0.18s ease;
    letter-spacing: -0.01em;
}

.account-link-item i {
    color: #94a3b8;
    width: 1.9rem;
    text-align: center;
    font-size: 1.6rem;
    flex-shrink: 0;
    transition: color 0.18s ease;
}

.account-link-item.active,
.account-link-item:hover {
    color: #ee4d2d;
    background: #fff5f3;
}

.account-link-item.active i,
.account-link-item:hover i {
    color: #ee4d2d;
}

.account-group-label {
    display: inline-flex;
    align-items: center;
    gap: 1rem;
}

.account-sublinks {
    padding-left: 4rem;
    padding-top: .7rem;
    display: grid;
    gap: 1.1rem;
    max-height: 120px;
    opacity: 1;
    overflow: hidden;
    transform: translateY(0);
    transition: max-height .28s cubic-bezier(.4,0,.2,1),
                opacity .22s ease,
                transform .22s ease;
}

.account-group:not(.open) .account-sublinks {
    max-height: 0;
    opacity: 0;
    transform: translateY(-6px);
    pointer-events: none;
}

.account-sublink {
    border: none;
    background: transparent;
    color: #64748b;
    text-align: left;
    cursor: pointer;
    font-size: 1.38rem;
    font-weight: 400;
    padding: .3rem .5rem;
    line-height: 1.25;
    border-radius: 5px;
    transition: color 0.18s ease, background 0.18s ease;
}

.account-sublink.active,
.account-sublink:hover {
    color: #ee4d2d;
    background: #fff5f3;
}

/* ─────────────────────────────────────────────
   MAIN PANEL
───────────────────────────────────────────── */
.profile-panel {
    background: #ffffff;
    border: 1px solid #eef0f4;
    border-radius: 8px;
    box-shadow: 0 2px 24px rgba(15, 23, 42, 0.05);
    overflow: hidden;
    min-height: 80vh;
}

.profile-head {
    padding: 2rem 2.2rem 1.6rem;
    border-bottom: 1px solid #f1f3f7;
    background: #fafbfd;
}

.profile-head h1 {
    margin: 0;
    font-size: 2.6rem;
    color: #0f172a;
    font-weight: 700;
    letter-spacing: -0.025em;
    line-height: 1.2;
    font-family: 'DM Sans', 'Segoe UI', sans-serif;
    text-transform: none;
}

.profile-head p {
    margin-top: .5rem;
    font-size: 1.38rem;
    color: #64748b;
    line-height: 1.5;
}

.profile-head .crumb {
    margin-top: .6rem;
    font-size: 1.22rem;
    color: #94a3b8;
    display: flex;
    align-items: center;
    gap: .4rem;
}

/* ─────────────────────────────────────────────
   PROFILE VIEWS
───────────────────────────────────────────── */
.profile-view {
    display: none;
}

.profile-view.active {
    display: block;
}

.profile-body {
    padding: 2rem 2.2rem 2.4rem;
    display: grid;
    grid-template-columns: minmax(0, 1fr) 260px;
    gap: 2rem;
}

.profile-form {
    padding-right: 2rem;
    border-right: 1px solid #f1f3f7;
}

.profile-row {
    display: grid;
    grid-template-columns: 140px minmax(0, 1fr);
    gap: 1.2rem;
    align-items: start;
    margin-bottom: 1.4rem;
}

.profile-label {
    text-align: right;
    font-size: 1.45rem;
    color: #64748b;
    padding-top: .9rem;
    font-weight: 500;
    letter-spacing: -0.005em;
}

.profile-value,
.profile-input,
.profile-textarea {
    font-size: 1.55rem;
    color: #0f172a;
    font-family: inherit;
}

.profile-value {
    padding-top: .9rem;
    line-height: 1.4;
}

.profile-input,
.profile-textarea {
    width: 100%;
    border: 1.5px solid #e8ecf2;
    border-radius: 6px;
    padding: .9rem 1.1rem;
    background: #f8fafc;
    transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
    outline: none;
    color: #0f172a;
}

.profile-input:hover,
.profile-textarea:hover {
    border-color: #cbd5e1;
    background: #fff;
}

.profile-input:focus,
.profile-textarea:focus {
    border-color: #ee4d2d;
    background: #fff;
    box-shadow: 0 0 0 3.5px rgba(238, 77, 45, 0.1);
}

.profile-textarea {
    min-height: 90px;
    resize: vertical;
    line-height: 1.55;
}

.profile-help {
    margin-top: .5rem;
    font-size: 1.25rem;
    color: #94a3b8;
    line-height: 1.4;
}

.profile-error {
    margin-top: .45rem;
    font-size: 1.22rem;
    color: #e11d48;
    display: flex;
    align-items: center;
    gap: .35rem;
}

.profile-link {
    color: #2563eb;
    text-decoration: none;
    margin-left: .6rem;
    font-size: 1.38rem;
    font-weight: 500;
    transition: color 0.18s ease;
}

.profile-link:hover {
    color: #ee4d2d;
    text-decoration: underline;
}

.password-grid {
    display: grid;
    gap: 1.1rem;
}

.profile-submit {
    margin-top: 1.4rem;
    border: none;
    background: #ee4d2d;
    color: #fff;
    font-size: 1.55rem;
    font-weight: 700;
    border-radius: 6px;
    padding: 1.05rem 2.6rem;
    cursor: pointer;
    letter-spacing: 0.01em;
    transition: background 0.2s ease, transform 0.15s ease, box-shadow 0.2s ease;
    box-shadow: 0 4px 14px rgba(238, 77, 45, 0.28);
    font-family: inherit;
}

.profile-submit:hover {
    background: #d94228;
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(238, 77, 45, 0.35);
}

.profile-submit:active {
    transform: translateY(0);
    box-shadow: 0 2px 8px rgba(238, 77, 45, 0.22);
}

/* ─────────────────────────────────────────────
   AVATAR UPLOAD
───────────────────────────────────────────── */
.profile-media {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding-top: .6rem;
    gap: 0;
}

.profile-media-avatar {
    width: 124px;
    height: 124px;
    border-radius: 50%;
    border: 2.5px solid #eef0f4;
    color: #c4c9d4;
    font-size: 5.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f4f6f9;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
    box-shadow: 0 0 0 5px #f4f6f9;
}

.profile-media-avatar:hover {
    border-color: #cbd5e1;
    box-shadow: 0 0 0 5px #eef0f4;
}

.profile-media-btn {
    margin-top: 1.6rem;
    border: 1.5px solid #e2e8f0;
    background: #fff;
    color: #334155;
    font-size: 1.35rem;
    font-weight: 600;
    border-radius: 10px;
    padding: .8rem 1.8rem;
    cursor: pointer;
    transition: border-color 0.18s ease, color 0.18s ease, background 0.18s ease, box-shadow 0.18s ease;
    font-family: inherit;
    letter-spacing: -0.005em;
}

.profile-media-btn:hover {
    border-color: #ee4d2d;
    color: #ee4d2d;
    background: #fff5f3;
    box-shadow: 0 2px 8px rgba(238, 77, 45, 0.1);
}

.profile-media-note {
    margin-top: 1.2rem;
    font-size: 1.2rem;
    color: #94a3b8;
    text-align: center;
    line-height: 1.6;
    max-width: 180px;
}

/* ─────────────────────────────────────────────
   CHANGE PASSWORD SECTION
───────────────────────────────────────────── */
.password-body {
    padding: 2rem 2.2rem 2.4rem;
}

.password-card {
    max-width: 720px;
}

.password-row {
    display: grid;
    grid-template-columns: 190px minmax(0, 1fr);
    gap: 1.2rem;
    align-items: start;
    margin-bottom: 1.4rem;
}

.password-label {
    text-align: right;
    font-size: 1.45rem;
    color: #64748b;
    padding-top: .9rem;
    font-weight: 500;
}

/* ─────────────────────────────────────────────
   ORDERS SECTION
───────────────────────────────────────────── */
.orders-body {
    padding: 1.4rem 1.8rem 2rem;
}

.orders-loading,
.orders-error {
    font-size: 1.4rem;
    color: #64748b;
    border: 1.5px dashed #e2e8f0;
    border-radius: 6px;
    padding: 1.8rem 1rem;
    text-align: center;
    background: #fafbfd;
}

.orders-error {
    color: #b91c1c;
    border-color: #fecaca;
    background: #fff8f8;
}

/* ── My Purchase (Shopee-style) ── */
.po-tabs-wrap {
    display: flex;
    overflow-x: auto;
    border-bottom: 1.5px solid #ebebeb;
    margin-bottom: 1.2rem;
    scrollbar-width: none;
}
.po-tabs-wrap::-webkit-scrollbar { display: none; }

.po-tab {
    flex: 0 0 auto;
    padding: .85rem 1.3rem;
    font-size: 1.28rem;
    font-weight: 500;
    color: #555;
    background: none;
    border: none;
    border-bottom: 2.5px solid transparent;
    cursor: pointer;
    white-space: nowrap;
    font-family: inherit;
    transition: color .15s, border-color .15s;
    margin-bottom: -1.5px;
}
.po-tab:hover { color: #16a34a; }
.po-tab.active { color: #16a34a; border-bottom-color: #16a34a; font-weight: 700; }

.po-list { display: grid; gap: .85rem; }

/* Card shell */
.po-card {
    background: #fff;
    border-radius: 6px;
    border: 1px solid #ebebeb;
    overflow: hidden;
    transition: box-shadow .18s;
}
.po-card:hover { box-shadow: 0 3px 16px rgba(0,0,0,.07); }

/* Card header — shop info row */
.po-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: .5rem;
    padding: .8rem 1.1rem;
    background: #fafafa;
    border-bottom: 1px solid #f0f0f0;
}
.po-shop-left {
    display: flex;
    align-items: center;
    gap: .5rem;
    flex-wrap: wrap;
}
.po-shop-icon { color: #333; font-size: 1.3rem; }
.po-shop-name { font-size: 1.32rem; font-weight: 700; color: #222; }
.po-vline { width: 1px; height: 1.3em; background: #ddd; margin: 0 .15rem; }
.po-btn-chat {
    display: inline-flex; align-items: center; gap: .3rem;
    padding: .26rem .7rem; border-radius: 4px; font-size: 1.18rem; font-weight: 600;
    text-decoration: none; cursor: pointer;
    background: #ee4d2d; color: #fff; border: 1px solid #ee4d2d;
    transition: opacity .15s;
}
.po-btn-chat:hover { opacity: .88; color: #fff; }
.po-btn-shop {
    display: inline-flex; align-items: center; gap: .3rem;
    padding: .26rem .7rem; border-radius: 4px; font-size: 1.18rem; font-weight: 600;
    text-decoration: none; cursor: pointer;
    background: #fff; color: #333; border: 1px solid #aaa;
    transition: border-color .15s;
}
.po-btn-shop:hover { border-color: #555; color: #000; }

/* Status badge (right side of header) */
.po-shop-right { display: flex; align-items: center; gap: .6rem; flex-shrink: 0; }
.po-tracking-txt {
    font-size: 1.15rem; color: #555;
    display: flex; align-items: center; gap: .3rem;
    max-width: 220px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
}
.po-badge {
    font-size: 1.15rem; font-weight: 700; letter-spacing: .05em; text-transform: uppercase;
    color: #ee4d2d; border-left: 2px solid #ee4d2d; padding-left: .5rem; white-space: nowrap;
}
.po-badge.po-b-completed  { color: #16a34a; border-color: #16a34a; }
.po-badge.po-b-cancelled  { color: #888;    border-color: #bbb; }
.po-badge.po-b-return     { color: #b45309; border-color: #b45309; }
.po-badge.po-b-topay      { color: #854d0e; border-color: #f59e0b; }
.po-badge.po-b-toship     { color: #1d4ed8; border-color: #3b82f6; }

/* Item rows */
.po-item {
    display: grid;
    grid-template-columns: 72px 1fr auto;
    gap: .85rem;
    align-items: flex-start;
    padding: .9rem 1.1rem;
}
.po-item + .po-item { border-top: 1px solid #f5f5f5; }
.po-item-img { width: 72px; height: 72px; object-fit: cover; border-radius: 6px; border: 1px solid #eee; flex-shrink: 0; }
.po-item-info { display: grid; gap: .18rem; }
.po-item-name { font-size: 1.35rem; color: #222; line-height: 1.35; }
.po-item-var  { font-size: 1.15rem; color: #888; }
.po-item-qty  { font-size: 1.15rem; color: #888; }
.po-item-price { font-size: 1.3rem; color: #333; white-space: nowrap; padding-top: .05rem; }

/* Order total row */
.po-total-row {
    text-align: right;
    padding: .55rem 1.1rem;
    border-top: 1px dashed #ebebeb;
    font-size: 1.28rem;
    color: #555;
}
.po-total-amt { font-size: 1.52rem; font-weight: 700; color: #ee4d2d; margin-left: .35rem; }

/* Action footer row */
.po-actions-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: .5rem;
    padding: .75rem 1.1rem;
    border-top: 1px solid #f0f0f0;
    background: #fafafa;
}
.po-hint { font-size: 1.1rem; color: #888; flex: 1; min-width: 0; }
.po-action-btns { display: flex; gap: .45rem; flex-wrap: wrap; }
.po-btn-action {
    display: inline-flex; align-items: center; gap: .3rem;
    padding: .42rem .95rem; border-radius: 5px; font-size: 1.2rem; font-weight: 600;
    cursor: pointer; text-decoration: none; border: 1px solid; transition: opacity .15s;
    font-family: inherit;
}
.po-btn-action:hover { opacity: .85; }
.po-btn-received   { background: #16a34a; color: #fff !important; border-color: #16a34a; }
.po-btn-not-recv   { background: #fff; color: #b91c1c !important; border-color: #b91c1c; }
.po-btn-contact    { background: #fff; color: #333 !important; border-color: #aaa; }
.po-btn-review     { background: #16a34a; color: #fff !important; border-color: #16a34a; }
.po-btn-return     { background: #fff; color: #b45309 !important; border-color: #d97706; }
.po-btn-details    { background: #fff; color: #555 !important; border-color: #ccc; }

.po-empty {
    background: #fff; border: 1.5px dashed #e2e8f0; border-radius: 6px;
    padding: 3rem 1rem; text-align: center; color: #94a3b8; font-size: 1.38rem; line-height: 1.55;
}

/* ─────────────────────────────────────────────
   NOTIFICATIONS SECTION
───────────────────────────────────────────── */
.notifications-body {
    padding: 2rem 2.2rem 2.4rem;
}

.notifications-loading,
.notifications-error {
    font-size: 1.4rem;
    color: #64748b;
    border: 1.5px dashed #e2e8f0;
    border-radius: 6px;
    padding: 1.8rem 1rem;
    text-align: center;
    background: #fafbfd;
}

.notifications-error {
    color: #b91c1c;
    border-color: #fecaca;
    background: #fff8f8;
}

.profile-notif-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.4rem;
    gap: 1rem;
}

.profile-notif-head h3 {
    margin: 0;
    font-size: 1.7rem;
    color: #0f172a;
    font-weight: 700;
    letter-spacing: -0.02em;
}

.profile-notif-btn {
    border: 1.5px solid #e2e8f0;
    background: #fff;
    color: #475569;
    font-size: 1.2rem;
    font-weight: 600;
    border-radius: 6px;
    padding: .55rem 1rem;
    cursor: pointer;
    transition: all 0.18s ease;
    font-family: inherit;
}

.profile-notif-btn:hover {
    border-color: #ee4d2d;
    color: #ee4d2d;
    background: #fff5f3;
}

.profile-notif-list {
    display: grid;
    gap: .75rem;
}

.profile-notif-item {
    display: flex;
    gap: 1.1rem;
    align-items: flex-start;
    background: #fff;
    border: 1px solid #eef0f4;
    border-radius: 6px;
    padding: 1.1rem 1.2rem;
    transition: box-shadow 0.18s ease;
}

.profile-notif-item:hover {
    box-shadow: 0 2px 12px rgba(15, 23, 42, 0.06);
}

.profile-notif-item.unread {
    border-left: 3.5px solid #16a34a;
    background: #f0fdf4;
}

.profile-notif-icon {
    width: 40px;
    height: 40px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 1rem;
}

.ni-order   { background: #dcfce7; color: #166534; }
.ni-shipped { background: #dbeafe; color: #1e40af; }
.ni-payment { background: #fef3c7; color: #92400e; }
.ni-promo   { background: #ede9fe; color: #5b21b6; }
.ni-default { background: #f1f5f9; color: #64748b; }

.profile-notif-body {
    flex: 1;
    min-width: 0;
}

.profile-notif-body h4 {
    margin: 0 0 .25rem;
    font-size: 1.4rem;
    color: #0f172a;
    font-weight: 600;
    letter-spacing: -0.01em;
    line-height: 1.3;
}

.profile-notif-body p {
    margin: 0 0 .4rem;
    font-size: 1.25rem;
    color: #64748b;
    line-height: 1.5;
}

.profile-notif-time {
    font-size: 1.1rem;
    color: #94a3b8;
}

.profile-notif-mark {
    background: none;
    border: none;
    cursor: pointer;
    color: #cbd5e1;
    padding: .2rem;
    font-size: 1.3rem;
    transition: color 0.18s ease;
    flex-shrink: 0;
}

.profile-notif-mark:hover {
    color: #94a3b8;
}

.profile-notif-empty {
    text-align: center;
    padding: 4rem 1rem;
    color: #94a3b8;
    font-size: 1.35rem;
    line-height: 1.55;
}

.profile-notif-empty i {
    font-size: 3.2rem;
    margin-bottom: .9rem;
    display: block;
    opacity: .6;
}

/* ─────────────────────────────────────────────
   RESPONSIVE
───────────────────────────────────────────── */
@media (max-width: 980px) {
    .account-shell {
        grid-template-columns: 1fr;
        margin: 1.5rem auto;
    }

    .account-sidebar {
        position: static;
    }

    .profile-body {
        grid-template-columns: 1fr;
    }

    .profile-form {
        border-right: 0;
        border-bottom: 1px solid #f1f3f7;
        padding-right: 0;
        padding-bottom: 2rem;
    }

    .profile-media {
        padding-top: .6rem;
    }
}

@media (max-width: 640px) {
    .account-shell {
        padding: 0 1.2rem;
    }

    .profile-head,
    .profile-body,
    .password-body,
    .orders-body,
    .notifications-body {
        padding-left: 1.4rem;
        padding-right: 1.4rem;
    }

    .profile-head h1 {
        font-size: 2.2rem;
    }

    .profile-row,
    .password-row {
        grid-template-columns: 1fr;
        gap: .45rem;
    }

    .profile-label,
    .password-label {
        text-align: left;
        padding-top: 0;
        font-size: 1.35rem;
    }

    .account-link-item {
        font-size: 1.35rem;
    }

    .account-sublink {
        font-size: 1.3rem;
    }

    .account-sublinks {
        padding-left: 3.2rem;
    }

    .profile-submit {
        width: 100%;
        text-align: center;
    }
}
</style>
@endpush

@section('content')
<section class="account-shell">
    <aside class="account-sidebar">
        <div class="account-user">
            <div class="account-avatar"><i class="far fa-user"></i></div>
            <div>
                <p class="account-user-name">{{ $user->name }}</p>
                <p class="account-edit"><i class="fas fa-pencil-alt"></i>Edit Profile</p>
            </div>
        </div>

        <div class="account-link-block">
            <div class="account-group {{ in_array($activePanel, ['profile', 'change-password'], true) ? 'open' : '' }}" id="accountGroup">
                <button
                    type="button"
                    class="account-link-item {{ in_array($activePanel, ['profile', 'change-password'], true) ? 'active' : '' }}"
                    id="accountToggle"
                >
                    <span class="account-group-label"><i class="far fa-user"></i>My Account</span>
                </button>
                <div class="account-sublinks" id="accountSublinks">
                    <button type="button" class="account-sublink {{ $activePanel === 'profile' ? 'active' : '' }}" data-panel="profile" data-title="My Account" data-subtitle="Manage and protect your account" data-crumb="My Account">Profile</button>
                    <button type="button" class="account-sublink {{ $activePanel === 'change-password' ? 'active' : '' }}" data-panel="change-password" data-title="Change Password" data-subtitle="Update your account password securely" data-crumb="Change Password">Change Password</button>
                </div>
            </div>
            <button
                type="button"
                class="account-link-item {{ $activePanel === 'my-orders' ? 'active' : '' }}"
                data-panel="my-orders"
                data-title="My Purchase"
                data-subtitle="Track and manage your purchases"
                data-crumb="My Purchase"
            >
                <i class="far fa-clipboard"></i>My Purchase
            </button>
            <button
                type="button"
                class="account-link-item {{ $activePanel === 'notifications' ? 'active' : '' }}"
                data-panel="notifications"
                data-title="Notifications"
                data-subtitle="Stay updated with your account alerts"
                data-crumb="Notifications"
            >
                <i class="far fa-bell"></i>Notifications
            </button>
            <a class="account-link-item" href="{{ route('wishlist') }}"><i class="far fa-heart"></i>My Wishlist</a>
        </div>
    </aside>

    <div class="profile-panel" id="profile-panel-root" data-default-panel="{{ $activePanel }}">
        <div class="profile-head">
            <h1 id="panel-title">{{ $activePanel === 'change-password' ? 'Change Password' : ($activePanel === 'my-orders' ? 'My Purchase' : ($activePanel === 'notifications' ? 'Notifications' : 'My Account')) }}</h1>
            <p id="panel-subtitle">{{ $activePanel === 'change-password' ? 'Update your account password securely' : ($activePanel === 'my-orders' ? 'Track and manage your purchases' : ($activePanel === 'notifications' ? 'Stay updated with your account alerts' : 'Manage and protect your account')) }}</p>
            <div class="crumb" id="panel-crumb">{{ $activePanel === 'change-password' ? 'Change Password' : ($activePanel === 'my-orders' ? 'My Purchase' : ($activePanel === 'notifications' ? 'Notifications' : 'My Account')) }}</div>
        </div>

        <div class="profile-view {{ $activePanel === 'profile' ? 'active' : '' }}" data-panel-view="profile">
            <div class="profile-body">
                <form class="profile-form" action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="profile-row">
                        <label class="profile-label">Username</label>
                        <div class="profile-value">{{ $username }}</div>
                    </div>

                    <div class="profile-row">
                        <label class="profile-label" for="name">Name</label>
                        <div>
                            <input class="profile-input" type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <p class="profile-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="profile-row">
                        <label class="profile-label" for="email">Email</label>
                        <div>
                            <input class="profile-input" type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <p class="profile-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="profile-row">
                        <label class="profile-label" for="phone">Phone Number</label>
                        <div>
                            <input class="profile-input" type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="Enter your phone number">
                            @error('phone')
                                <p class="profile-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="profile-row">
                        <label class="profile-label" for="address">Address</label>
                        <div>
                            <textarea class="profile-textarea" id="address" name="address" placeholder="House/Unit No., Street, Barangay, City, Province">{{ old('address', $user->address) }}</textarea>
                            <p class="profile-help">Use your complete address for faster checkout and delivery updates.</p>
                            @error('address')
                                <p class="profile-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="profile-row">
                        <span class="profile-label"></span>
                        <div>
                            <button type="submit" class="profile-submit">Save</button>
                        </div>
                    </div>
                </form>

                <div class="profile-media">
                    <div class="profile-media-avatar"><i class="far fa-user"></i></div>
                    <button type="button" class="profile-media-btn">Select Image</button>
                    <p class="profile-media-note">File size: maximum 1 MB<br>File extension: .JPEG, .PNG</p>
                </div>
            </div>
        </div>

        <div class="profile-view {{ $activePanel === 'change-password' ? 'active' : '' }}" data-panel-view="change-password">
            <div class="password-body">
                <form class="password-card" action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="name" value="{{ old('name', $user->name) }}">
                    <input type="hidden" name="email" value="{{ old('email', $user->email) }}">
                    <input type="hidden" name="phone" value="{{ old('phone', $user->phone) }}">
                    <input type="hidden" name="address" value="{{ old('address', $user->address) }}">

                    <div class="password-row">
                        <label class="password-label" for="current_password">Current Password</label>
                        <div>
                            <input class="profile-input" type="password" id="current_password" name="current_password" placeholder="Enter current password">
                            @error('current_password')
                                <p class="profile-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="password-row">
                        <label class="password-label" for="new_password">New Password</label>
                        <div>
                            <input class="profile-input" type="password" id="new_password" name="new_password" placeholder="Minimum 6 characters">
                            @error('new_password')
                                <p class="profile-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="password-row">
                        <label class="password-label" for="new_password_confirmation">Confirm Password</label>
                        <div>
                            <input class="profile-input" type="password" id="new_password_confirmation" name="new_password_confirmation" placeholder="Confirm new password">
                        </div>
                    </div>

                    <div class="password-row">
                        <span class="password-label"></span>
                        <div>
                            <p class="profile-help">Only submit this form when changing your password.</p>
                            <button type="submit" class="profile-submit">Save Password</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="profile-view {{ $activePanel === 'my-orders' ? 'active' : '' }}" data-panel-view="my-orders">
            <div class="orders-body">
                <div id="profile-orders-container" class="orders-loading">Loading orders...</div>
            </div>
        </div>

        <div class="profile-view {{ $activePanel === 'notifications' ? 'active' : '' }}" data-panel-view="notifications">
            <div class="notifications-body">
                <div id="profile-notifications-container" class="notifications-loading">Loading notifications...</div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
(function () {
    const root = document.getElementById('profile-panel-root');
    if (!root) return;

    const ordersEndpoint = '{{ route("profile.orders.panel") }}';
    const orderDetailBase = '{{ url("/profile/order") }}';
    const notificationsEndpoint = '{{ route("profile.notifications.panel") }}';
    const markReadBase = '{{ url("/notifications") }}';
    const markAllReadUrl = '{{ route("notifications.readAll") }}';
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const titleEl = document.getElementById('panel-title');
    const subtitleEl = document.getElementById('panel-subtitle');
    const crumbEl = document.getElementById('panel-crumb');
    const accountGroup = document.getElementById('accountGroup');
    const accountToggle = document.getElementById('accountToggle');
    const ordersContainer = document.getElementById('profile-orders-container');
    const notificationsContainer = document.getElementById('profile-notifications-container');
    const tabs = Array.from(document.querySelectorAll('.account-link-item[data-panel], .account-sublink[data-panel]'));
    const sublinks = Array.from(document.querySelectorAll('.account-sublink[data-panel]'));
    const views = Array.from(document.querySelectorAll('[data-panel-view]'));
    let ordersLoaded = false;
    let notificationsLoaded = false;

    async function loadOrders(tabName = 'all') {
        if (!ordersContainer) return;

        ordersContainer.className = 'orders-loading';
        ordersContainer.textContent = 'Loading orders...';

        try {
            const response = await fetch(`${ordersEndpoint}?tab=${encodeURIComponent(tabName)}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            if (!response.ok) throw new Error('Failed to load orders');

            const data = await response.json();
            ordersContainer.className = '';
            ordersContainer.innerHTML = data.html || '<div class="po-empty">No orders yet.</div>';
            ordersLoaded = true;
        } catch (error) {
            ordersContainer.className = 'orders-error';
            ordersContainer.textContent = 'Unable to load orders right now. Please try again.';
        }
    }

    /* Expose so the inline detail partial can call it for "Back" and after review */
    window.loadOrders = loadOrders;

    async function loadOrderDetail(orderId) {
        if (!ordersContainer) return;

        ordersContainer.className = 'orders-loading';
        ordersContainer.textContent = 'Loading order details...';

        try {
            const response = await fetch(`${orderDetailBase}/${orderId}/detail`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            if (!response.ok) throw new Error('Failed to load order details');

            const data = await response.json();
            ordersContainer.className = '';
            ordersContainer.innerHTML = data.html || '<div class="po-empty">Order not found.</div>';

            /* Scripts inside innerHTML are not executed by the browser.
               Re-create each <script> node so the browser runs it. */
            ordersContainer.querySelectorAll('script').forEach(oldScript => {
                const s = document.createElement('script');
                [...oldScript.attributes].forEach(a => s.setAttribute(a.name, a.value));
                s.textContent = oldScript.textContent;
                oldScript.parentNode.replaceChild(s, oldScript);
            });
        } catch (error) {
            ordersContainer.className = 'orders-error';
            ordersContainer.textContent = 'Unable to load order details. Please try again.';
        }
    }

    /* Expose so the review submit callback in the partial can reload after review */
    window.loadOrderDetail = loadOrderDetail;

    async function loadNotifications() {
        if (!notificationsContainer) return;

        notificationsContainer.className = 'notifications-loading';
        notificationsContainer.textContent = 'Loading notifications...';

        try {
            const response = await fetch(notificationsEndpoint, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error('Failed to load notifications');
            }

            const data = await response.json();
            notificationsContainer.className = '';
            notificationsContainer.innerHTML = data.html || '<div class="profile-notif-empty"><p>No notifications yet.</p></div>';
            notificationsLoaded = true;
        } catch (error) {
            notificationsContainer.className = 'notifications-error';
            notificationsContainer.textContent = 'Unable to load notifications right now. Please try again.';
        }
    }

    function activate(panelName, tabMeta) {
        tabs.forEach((tab) => {
            tab.classList.toggle('active', tab.dataset.panel === panelName);
        });

        sublinks.forEach((tab) => {
            tab.classList.toggle('active', tab.dataset.panel === panelName);
        });

        views.forEach((view) => {
            view.classList.toggle('active', view.dataset.panelView === panelName);
        });

        if (tabMeta) {
            if (titleEl) titleEl.textContent = tabMeta.dataset.title || 'My Account';
            if (subtitleEl) subtitleEl.textContent = tabMeta.dataset.subtitle || 'Manage and protect your account';
            if (crumbEl) crumbEl.textContent = tabMeta.dataset.crumb || 'My Account';
        }

        if (window.location.hash !== '#' + panelName) {
            window.history.replaceState(null, '', '#' + panelName);
        }

        /* Hide the panel header for the orders panel — it has its own tab bar */
        const profileHead = document.querySelector('.profile-head');
        if (profileHead) profileHead.style.display = panelName === 'my-orders' ? 'none' : '';

        const isAccountPanel = panelName === 'profile' || panelName === 'change-password';
        if (accountGroup) {
            accountGroup.classList.toggle('open', isAccountPanel);
        }
        if (accountToggle) {
            accountToggle.classList.remove('active');
        }

        if (panelName === 'my-orders' && !ordersLoaded) {
            loadOrders('all');
        }
        if (panelName === 'notifications' && !notificationsLoaded) {
            loadNotifications();
        }
    }

    tabs.forEach((tab) => {
        tab.addEventListener('click', function () {
            activate(this.dataset.panel, this);
        });
    });

    if (accountToggle) {
        accountToggle.addEventListener('click', function () {
            const profileTab = tabs.find((tab) => tab.dataset.panel === 'profile');
            if (profileTab) activate('profile', profileTab);
        });
    }

    if (ordersContainer) {
        ordersContainer.addEventListener('click', function (event) {
            /* Tab switch */
            const tabBtn = event.target.closest('[data-order-tab]');
            if (tabBtn) {
                loadOrders(tabBtn.getAttribute('data-order-tab') || 'all');
                return;
            }
            /* Inline "View Details" */
            const detailBtn = event.target.closest('[data-load-order]');
            if (detailBtn) {
                loadOrderDetail(detailBtn.getAttribute('data-load-order'));
            }
        });
    }

    if (notificationsContainer) {
        notificationsContainer.addEventListener('click', async function (event) {
            const markBtn = event.target.closest('[data-notif-mark]');
            if (markBtn) {
                const id = markBtn.getAttribute('data-notif-mark');
                if (!id) return;
                try {
                    await fetch(`${markReadBase}/${id}/read`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrf,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });
                    await loadNotifications();
                } catch (_) {}
                return;
            }

            const markAllBtn = event.target.closest('[data-notif-mark-all]');
            if (markAllBtn) {
                try {
                    await fetch(markAllReadUrl, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrf,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });
                    await loadNotifications();
                } catch (_) {}
            }
        });
    }

    const hashPanel = window.location.hash.replace('#', '');
    const initialTab = tabs.find((tab) => tab.dataset.panel === hashPanel)
        || tabs.find((tab) => tab.dataset.panel === root.dataset.defaultPanel)
        || tabs[0];

    if (initialTab) {
        activate(initialTab.dataset.panel, initialTab);
    }

    if (root.dataset.defaultPanel === 'my-orders') {
        loadOrders('all');
    }
    if (root.dataset.defaultPanel === 'notifications') {
        loadNotifications();
    }
})();
</script>
@endpush
