<!DOCTYPE html>
<html lang="bn">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Admin') — IOM</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Inter',sans-serif;background:#f0f4f8;color:#1a202c}
.layout{display:flex;min-height:100vh}

/* Sidebar */
.sidebar{width:250px;background:linear-gradient(180deg,#1a3a5c 0%,#0f2440 100%);color:#fff;position:fixed;top:0;left:0;height:100vh;overflow-y:auto;z-index:100;transition:.3s}
.sidebar-brand{padding:20px 18px;border-bottom:1px solid rgba(255,255,255,.1);display:flex;align-items:center;gap:10px}
.sidebar-brand img{width:38px;height:38px;border-radius:8px;object-fit:cover;background:#fff}
.sidebar-brand span{font-size:13px;font-weight:700;line-height:1.3;color:#e2e8f0}
.nav-section{padding:18px 0 6px}
.nav-label{font-size:10px;font-weight:600;color:rgba(255,255,255,.4);letter-spacing:1px;text-transform:uppercase;padding:0 18px;margin-bottom:6px}
.nav-item{display:flex;align-items:center;gap:10px;padding:10px 18px;color:rgba(255,255,255,.75);font-size:13px;font-weight:500;text-decoration:none;transition:.2s;border-left:3px solid transparent}
.nav-item:hover,.nav-item.active{background:rgba(255,255,255,.08);color:#fff;border-left-color:#4fc3f7}
.nav-item svg{width:17px;height:17px;opacity:.8;flex-shrink:0}

/* Topbar */
.main{margin-left:250px;flex:1;display:flex;flex-direction:column}
.topbar{background:#fff;border-bottom:1px solid #e2e8f0;padding:0 24px;height:60px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:50}
.topbar-left{font-size:15px;font-weight:600;color:#1a202c}
.topbar-right{display:flex;align-items:center;gap:16px}
.topbar-user{display:flex;align-items:center;gap:8px;font-size:13px;color:#4a5568}
.topbar-user strong{color:#1a202c}
.btn-logout{background:#fee2e2;color:#dc2626;border:none;padding:6px 14px;border-radius:6px;font-size:12px;font-weight:600;cursor:pointer;text-decoration:none}
.btn-logout:hover{background:#fca5a5}

/* Content */
.content{padding:24px;flex:1}
.page-header{margin-bottom:20px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px}
.page-title{font-size:20px;font-weight:700;color:#1a202c}
.page-sub{font-size:13px;color:#718096;margin-top:2px}

/* Cards */
.card{background:#fff;border-radius:12px;border:1px solid #e2e8f0;overflow:hidden}
.card-header{padding:16px 20px;border-bottom:1px solid #f0f4f8;display:flex;align-items:center;justify-content:space-between}
.card-title{font-size:14px;font-weight:600;color:#1a202c}
.card-body{padding:20px}

/* Stat cards */
.stats-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;margin-bottom:24px}
.stat-card{background:#fff;border-radius:12px;padding:20px;border:1px solid #e2e8f0;display:flex;align-items:center;gap:16px}
.stat-icon{width:48px;height:48px;border-radius:10px;display:flex;align-items:center;justify-content:center}
.stat-icon svg{width:22px;height:22px;color:#fff}
.stat-val{font-size:24px;font-weight:700;color:#1a202c}
.stat-lbl{font-size:12px;color:#718096;font-weight:500}

/* Table */
.dt-wrapper{overflow:hidden}
.dt-toolbar{display:flex;align-items:center;justify-content:space-between;padding:12px 16px;background:#f7fafc;border-bottom:1px solid #e2e8f0;gap:12px;flex-wrap:wrap}
.dt-select-actions{display:flex;align-items:center;gap:10px}
.dt-check-label{display:flex;align-items:center;gap:6px;font-size:13px;cursor:pointer;color:#4a5568}
.dt-selected-count{font-size:12px;color:#4fc3f7;font-weight:600}
.dt-export-btns{display:flex;gap:8px}
.btn-export{display:inline-flex;align-items:center;gap:5px;padding:7px 14px;border-radius:7px;font-size:12px;font-weight:600;text-decoration:none;cursor:pointer;transition:.2s}
.btn-pdf{background:#fff1f2;color:#e11d48;border:1px solid #fecdd3}
.btn-pdf:hover{background:#ffe4e6}
.btn-excel{background:#f0fdf4;color:#16a34a;border:1px solid #bbf7d0}
.btn-excel:hover{background:#dcfce7}
.dt-table-wrap{overflow-x:auto}
.dt-table{width:100%;border-collapse:collapse}
.dt-table thead tr{background:#f7fafc}
.dt-table thead th{padding:11px 14px;text-align:left;font-size:12px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;white-space:nowrap}
.dt-table tbody td{padding:11px 14px;font-size:13px;color:#2d3748;border-bottom:1px solid #f0f4f8}
.dt-table tbody tr:hover{background:#f7fafc}

/* Badges */
.badge{display:inline-block;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600}
.badge-green{background:#f0fdf4;color:#16a34a}
.badge-red{background:#fff1f2;color:#e11d48}
.badge-yellow{background:#fefce8;color:#ca8a04}
.badge-blue{background:#eff6ff;color:#2563eb}
.badge-gray{background:#f3f4f6;color:#6b7280}

/* Buttons */
.btn{display:inline-flex;align-items:center;gap:6px;padding:8px 18px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;border:none;text-decoration:none;transition:.2s}
.btn-primary{background:#1a3a5c;color:#fff}
.btn-primary:hover{background:#0f2440}
.btn-success{background:#16a34a;color:#fff}
.btn-danger{background:#dc2626;color:#fff}
.btn-sm{padding:5px 12px;font-size:12px}
.btn-outline{background:transparent;border:1px solid #e2e8f0;color:#4a5568}
.btn-outline:hover{background:#f7fafc}

/* Forms */
.form-group{margin-bottom:16px}
.form-label{display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px}
.form-control{width:100%;padding:9px 12px;border:1px solid #d1d5db;border-radius:8px;font-size:13px;color:#1a202c;transition:.2s;font-family:inherit;background:#fff}
.form-control:focus{outline:none;border-color:#4fc3f7;box-shadow:0 0 0 3px rgba(79,195,247,.15)}
select.form-control{appearance:none}
.form-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:16px}

/* Alerts */
.alert{padding:12px 16px;border-radius:8px;font-size:13px;margin-bottom:16px}
.alert-success{background:#f0fdf4;border:1px solid #bbf7d0;color:#16a34a}
.alert-danger{background:#fff1f2;border:1px solid #fecdd3;color:#dc2626}

/* Pagination */
.pagination{display:flex;padding:16px;justify-content:flex-end;width:100%}
.pagination nav {display:flex;align-items:center;justify-content:space-between;width:100%;background:#fff;border-radius:8px}
.pagination nav p {font-size:13px;color:#4a5568;margin:0}
.pagination nav p span {font-weight:600}
.pagination nav .relative.z-0 {display:inline-flex;gap:4px;margin-left:auto}
.pagination nav .relative.z-0 a,
.pagination nav .relative.z-0 span[aria-current="page"],
.pagination nav .relative.z-0 span[aria-disabled="true"] {padding:6px 12px;border-radius:6px;font-size:12px;text-decoration:none;border:1px solid #e2e8f0;color:#4a5568;background:#fff;cursor:pointer;transition:.2s;display:inline-flex;align-items:center;justify-content:center}
.pagination nav .relative.z-0 a:hover {background:#f7fafc}
.pagination nav .relative.z-0 span[aria-current="page"] {background:#1a3a5c;color:#fff;border-color:#1a3a5c;font-weight:600}
.pagination nav .relative.z-0 svg {width:16px;height:16px}
/* Mobile pagination styles */
.pagination nav .flex.justify-between.flex-1 {display:flex;gap:8px;width:100%;justify-content:space-between}
.pagination nav .flex.justify-between.flex-1 a,
.pagination nav .flex.justify-between.flex-1 span {padding:6px 12px;border-radius:6px;font-size:12px;text-decoration:none;border:1px solid #e2e8f0;color:#4a5568;background:#fff}

@media(max-width:768px){
  .sidebar{transform:translateX(-100%)}
  .sidebar.open{transform:translateX(0)}
  .main{margin-left:0}
  .stats-grid{grid-template-columns:1fr 1fr}
  .hamburger{display:flex !important}
  .overlay{display:block}
  .overlay.active{opacity:1;pointer-events:all}
}
.hamburger{display:none;flex-direction:column;justify-content:center;align-items:center;gap:5px;width:38px;height:38px;border:none;background:transparent;cursor:pointer;border-radius:8px;margin-right:10px}
.hamburger span{display:block;width:22px;height:2px;background:#1a202c;border-radius:2px;transition:.3s}
.hamburger.open span:nth-child(1){transform:translateY(7px) rotate(45deg)}
.hamburger.open span:nth-child(2){opacity:0}
.hamburger.open span:nth-child(3){transform:translateY(-7px) rotate(-45deg)}
.overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:99;opacity:0;pointer-events:none;transition:opacity .3s}
</style>
@stack('styles')
</head>
<body>
<div class="layout">

{{-- Sidebar --}}
<aside class="sidebar">
  <div class="sidebar-brand">
    <img src="{{ asset('uploads/logos/' . (\App\Models\SystemSetting::get('logo','') ?: 'default.png')) }}" alt="Logo">
    <span>Islamic Online<br>Madrasah</span>
  </div>

  <div class="nav-section">
    <div class="nav-label">Overview</div>
    <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6"/></svg>
      Dashboard
    </a>
  </div>

  <div class="nav-section">
    <div class="nav-label">Academic</div>
    <a href="{{ route('admin.courses.index') }}" class="nav-item {{ request()->routeIs('admin.courses*') ? 'active' : '' }}">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
      Courses
    </a>
    <a href="{{ route('admin.semesters.index') }}" class="nav-item {{ request()->routeIs('admin.semesters*') ? 'active' : '' }}">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
      Semesters
    </a>
    <a href="{{ route('admin.batches.index') }}" class="nav-item {{ request()->routeIs('admin.batches*') ? 'active' : '' }}">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
      Batches
    </a>
    <a href="{{ route('admin.subjects.index') }}" class="nav-item {{ request()->routeIs('admin.subjects*') ? 'active' : '' }}">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
      Subjects
    </a>
    <a href="{{ route('admin.modules.index') }}" class="nav-item {{ request()->routeIs('admin.modules*') ? 'active' : '' }}">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
      Modules
    </a>
    <a href="{{ route('admin.classes.index') }}" class="nav-item {{ request()->routeIs('admin.classes*') ? 'active' : '' }}">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.882v6.236a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
      Classes
    </a>
    <a href="{{ route('admin.academic-calendars.index') }}" class="nav-item {{ request()->routeIs('admin.academic-calendars*') ? 'active' : '' }}">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
      Academic Calendar
    </a>
  </div>

  <div class="nav-section">
    <div class="nav-label">People</div>
    <a href="{{ route('admin.admissions.index') }}" class="nav-item {{ request()->routeIs('admin.admissions*') ? 'active' : '' }}">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
      Admissions
    </a>
    <a href="{{ route('admin.students.index') }}" class="nav-item {{ request()->routeIs('admin.students*') ? 'active' : '' }}">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
      Students
    </a>
    <a href="{{ route('admin.teachers.index') }}" class="nav-item {{ request()->routeIs('admin.teachers*') ? 'active' : '' }}">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
      Teachers
    </a>
    <a href="{{ route('admin.routines.index') }}" class="nav-item {{ request()->routeIs('admin.routines*') ? 'active' : '' }}">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
      Routines
    </a>
  </div>

  <div class="nav-section">
    <div class="nav-label">Academic Activity</div>
    <a href="{{ route('admin.attendance.index') }}" class="nav-item {{ request()->routeIs('admin.attendance*') ? 'active' : '' }}">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      Attendance
    </a>
    <a href="{{ route('admin.exams.index') }}" class="nav-item {{ request()->routeIs('admin.exams*') ? 'active' : '' }}">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
      Exams
    </a>
    <a href="{{ route('admin.questions.index') }}" class="nav-item {{ request()->routeIs('admin.questions*') ? 'active' : '' }}">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      Questions
    </a>
    <a href="{{ route('admin.results.index') }}" class="nav-item {{ request()->routeIs('admin.results*') ? 'active' : '' }}">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
      Results
    </a>
    <a href="{{ route('admin.certificates.index') }}" class="nav-item {{ request()->routeIs('admin.certificates*') ? 'active' : '' }}">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.5 8h.01M12 8h.01M16.5 8h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      Certificates
    </a>
    <a href="{{ route('admin.quiz.index') }}" class="nav-item {{ request()->routeIs('admin.quiz*') ? 'active' : '' }}">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      Quiz Management
    </a>
  </div>

  <div class="nav-section">
    <div class="nav-label">Management</div>
    <a href="{{ route('admin.readmissions.index') }}" class="nav-item {{ request()->routeIs('admin.readmissions*') ? 'active' : '' }}">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
      Readmissions
    </a>
    <a href="{{ route('admin.transfers.index') }}" class="nav-item {{ request()->routeIs('admin.transfers*') ? 'active' : '' }}">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
      Batch Transfers
    </a>
    <a href="{{ route('admin.reports.index') }}" class="nav-item {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
      Reports & Analytics
    </a>
    <a href="{{ route('admin.forms.index') }}" class="nav-item {{ request()->routeIs('admin.forms*') ? 'active' : '' }}">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
      Form Builder
    </a>
    <a href="{{ route('admin.surveys.index') }}" class="nav-item {{ request()->routeIs('admin.surveys*') ? 'active' : '' }}">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
      Surveys
    </a>
  </div>

  <div class="nav-section">
    <div class="nav-label">Finance</div>
    <a href="{{ route('admin.fee-structures.index') }}" class="nav-item {{ request()->routeIs('admin.fee-structures*') ? 'active' : '' }}">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      Fee Structures
    </a>
    <a href="{{ route('admin.payments.index') }}" class="nav-item {{ request()->routeIs('admin.payments*') ? 'active' : '' }}">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
      Payments
    </a>
    <a href="{{ route('admin.scholarships.index') }}" class="nav-item {{ request()->routeIs('admin.scholarships*') ? 'active' : '' }}">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a4 4 0 00-4-4H5.45a4 4 0 00-3.393 6.1l6.55 10.9a1 1 0 001.786 0l6.55-10.9A4 4 0 0018.55 2H16a4 4 0 00-4 4v2z"/></svg>
      Scholarships
    </a>
  </div>

  <div class="nav-section">
    <div class="nav-label">Communication</div>
    <a href="{{ route('admin.notices.index') }}" class="nav-item {{ request()->routeIs('admin.notices*') ? 'active' : '' }}">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
      Notices
    </a>
    <a href="{{ route('admin.support.index') }}" class="nav-item {{ request()->routeIs('admin.support*') ? 'active' : '' }}">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
      Support
    </a>
  </div>

  <div class="nav-section">
    <div class="nav-label">System</div>
    <a href="{{ route('admin.audit.index') }}" class="nav-item {{ request()->routeIs('admin.audit*') ? 'active' : '' }}">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
      Audit Trail
    </a>
    <a href="{{ route('admin.settings.index') }}" class="nav-item {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
      Settings
    </a>
    <form method="POST" action="{{ route('logout') }}" style="margin:0">
      @csrf
      <button type="submit" class="nav-item" style="width:100%;background:none;border:none;cursor:pointer;text-align:left;color:rgba(255,255,255,.75)">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
        Logout
      </button>
    </form>
  </div>
</aside>

{{-- Main --}}
<div class="main">
  <div class="topbar">
    <div style="display:flex;align-items:center">
      <button class="hamburger" id="sidebarToggle" aria-label="Menu">
        <span></span><span></span><span></span>
      </button>
      <div class="topbar-left">@yield('page-title', 'Dashboard')</div>
    </div>
    <div class="topbar-right" style="display:flex;align-items:center;gap:18px">
      <a href="{{ route('notifications.index') }}" style="position:relative;color:#4a5568;display:flex;align-items:center;text-decoration:none" title="Notifications">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width:20px;height:20px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
        @php
          $unreadCount = \App\Models\UserNotification::where('user_id', auth()->id())->where('is_read', false)->count();
        @endphp
        @if($unreadCount > 0)
          <span style="position:absolute;top:-6px;right:-6px;background:#ef4444;color:#fff;font-size:9px;font-weight:700;padding:1px 5px;border-radius:50%;line-height:1">{{ $unreadCount }}</span>
        @endif
      </a>
      <div class="topbar-user">
        Welcome, <strong>{{ auth()->user()->name }}</strong>
      </div>
    </div>
  </div>

  <div class="content">
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if(session('warning'))
      <div class="alert" style="background:#fffbeb;border:1px solid #fef3c7;color:#d97706">{{ session('warning') }}</div>
    @endif
    @if(session('info'))
      <div class="alert" style="background:#eff6ff;border:1px solid #dbeafe;color:#2563eb">{{ session('info') }}</div>
    @endif

    @yield('content')
  </div>
</div>

</div>
<div class="overlay" id="sidebarOverlay"></div>
@stack('scripts')
<script>
(function(){
  var btn     = document.getElementById('sidebarToggle');
  var sidebar = document.querySelector('.sidebar');
  var overlay = document.getElementById('sidebarOverlay');
  function open()  { sidebar.classList.add('open'); btn.classList.add('open'); overlay.classList.add('active'); }
  function close() { sidebar.classList.remove('open'); btn.classList.remove('open'); overlay.classList.remove('active'); }
  btn.addEventListener('click', function(){ sidebar.classList.contains('open') ? close() : open(); });
  overlay.addEventListener('click', close);
  // close on nav item click (mobile UX)
  document.querySelectorAll('.nav-item').forEach(function(a){ a.addEventListener('click', close); });
})();
</script>
</body>
</html>
