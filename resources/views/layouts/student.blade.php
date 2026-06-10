<!DOCTYPE html>
<html lang="bn">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>@yield('title','Student') — IOM</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Inter',sans-serif;background:#f0f4f8;color:#1a202c}
.layout{display:flex;min-height:100vh}
.sidebar{width:240px;background:linear-gradient(180deg,#065f46 0%,#064e3b 100%);color:#fff;position:fixed;top:0;left:0;height:100vh;overflow-y:auto;z-index:100}
.sidebar-brand{padding:20px 16px;border-bottom:1px solid rgba(255,255,255,.1);text-align:center}
.sidebar-brand .avatar{width:54px;height:54px;border-radius:50%;background:rgba(255,255,255,.2);display:inline-flex;align-items:center;justify-content:center;font-size:20px;font-weight:700;color:#fff;margin-bottom:8px}
.sidebar-brand h3{font-size:13px;font-weight:700;color:#ecfdf5}
.sidebar-brand p{font-size:11px;color:rgba(255,255,255,.6);margin-top:2px}
.nav-label{font-size:10px;font-weight:600;color:rgba(255,255,255,.4);letter-spacing:1px;text-transform:uppercase;padding:16px 16px 4px}
.nav-item{display:flex;align-items:center;gap:10px;padding:10px 16px;color:rgba(255,255,255,.75);font-size:13px;font-weight:500;text-decoration:none;transition:.2s;border-left:3px solid transparent}
.nav-item:hover,.nav-item.active{background:rgba(255,255,255,.1);color:#fff;border-left-color:#6ee7b7}
.nav-item svg{width:17px;height:17px;flex-shrink:0}
.main{margin-left:240px;flex:1;display:flex;flex-direction:column}
.topbar{background:#fff;border-bottom:1px solid #e2e8f0;padding:0 24px;height:60px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:50}
.topbar-title{font-size:15px;font-weight:600;color:#1a202c}
.content{padding:24px;flex:1}
.card{background:#fff;border-radius:12px;border:1px solid #e2e8f0;overflow:hidden;margin-bottom:20px}
.card-header{padding:16px 20px;border-bottom:1px solid #f0f4f8;display:flex;align-items:center;justify-content:space-between}
.card-title{font-size:14px;font-weight:600;color:#1a202c}
.card-body{padding:20px}
.stat-card{background:#fff;border-radius:12px;padding:20px;border:1px solid #e2e8f0;display:flex;align-items:center;gap:16px}
.stats-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:16px;margin-bottom:20px}
.stat-icon{width:44px;height:44px;border-radius:10px;display:flex;align-items:center;justify-content:center}
.stat-icon svg{width:20px;height:20px;color:#fff}
.stat-val{font-size:22px;font-weight:700;color:#1a202c}
.stat-lbl{font-size:12px;color:#718096}
.badge{display:inline-block;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600}
.badge-green{background:#f0fdf4;color:#16a34a}
.badge-red{background:#fff1f2;color:#e11d48}
.badge-yellow{background:#fefce8;color:#ca8a04}
.badge-blue{background:#eff6ff;color:#2563eb}
.badge-gray{background:#f3f4f6;color:#6b7280}
.btn{display:inline-flex;align-items:center;gap:6px;padding:8px 18px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;border:none;text-decoration:none;transition:.2s}
.btn-primary{background:#065f46;color:#fff}.btn-primary:hover{background:#064e3b}
.btn-outline{background:transparent;border:1px solid #e2e8f0;color:#4a5568}.btn-outline:hover{background:#f7fafc}
.btn-sm{padding:5px 12px;font-size:12px}
.dt-table{width:100%;border-collapse:collapse}
.dt-table thead tr{background:#f7fafc}
.dt-table thead th{padding:10px 14px;text-align:left;font-size:12px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0}
.dt-table tbody td{padding:10px 14px;font-size:13px;border-bottom:1px solid #f0f4f8}
.alert{padding:12px 16px;border-radius:8px;font-size:13px;margin-bottom:16px}
.alert-success{background:#f0fdf4;border:1px solid #bbf7d0;color:#16a34a}
.progress-bar{background:#e2e8f0;border-radius:99px;height:8px;overflow:hidden}
.progress-fill{background:linear-gradient(90deg,#065f46,#10b981);height:100%;border-radius:99px;transition:.4s}

@media(max-width:768px){
  .sidebar{transform:translateX(-100%)}
  .sidebar.open{transform:translateX(0)}
  .main{margin-left:0}
  .stats-grid{grid-template-columns:1fr 1fr}
  .hamburger{display:flex !important}
  .overlay{display:block}
  .overlay.active{opacity:1;pointer-events:all}
}
.sidebar{transition: transform 0.3s ease}
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
<aside class="sidebar">
  <div class="sidebar-brand">
    <div class="avatar">{{ substr(auth()->user()->name,0,1) }}</div>
    <h3>{{ auth()->user()->name }}</h3>
    <p>{{ auth()->user()->studentProfile->student_id ?? 'Student' }}</p>
  </div>
  <div class="nav-label">Main</div>
  <a href="{{ route('student.dashboard') }}" class="nav-item {{ request()->routeIs('student.dashboard') ? 'active':'' }}">
    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6"/></svg>
    Dashboard
  </a>
  <a href="{{ route('student.learning.index') }}" class="nav-item {{ request()->routeIs('student.learning*') ? 'active':'' }}">
    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.882v6.236a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
    My Classes
  </a>
  <a href="{{ route('student.exams.index') }}" class="nav-item {{ request()->routeIs('student.exams*') ? 'active':'' }}">
    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
    Exams
  </a>
  <a href="{{ route('student.results.index') }}" class="nav-item {{ request()->routeIs('student.results*') ? 'active':'' }}">
    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
    Results
  </a>
  <a href="{{ route('student.attendance.index') }}" class="nav-item {{ request()->routeIs('student.attendance*') ? 'active':'' }}">
    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    Attendance
  </a>
  <a href="{{ route('student.payments.index') }}" class="nav-item {{ request()->routeIs('student.payments*') ? 'active':'' }}">
    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
    Payments
  </a>
  <a href="{{ route('student.notices.index') }}" class="nav-item {{ request()->routeIs('student.notices*') ? 'active':'' }}">
    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
    Notices
  </a>
  <a href="{{ route('student.quiz.index') }}" class="nav-item {{ request()->routeIs('student.quiz*') ? 'active':'' }}">
    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
    Quiz Rooms
  </a>
  <a href="{{ route('student.readmission.index') }}" class="nav-item {{ request()->routeIs('student.readmission*') ? 'active':'' }}">
    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
    Readmission
  </a>
  <a href="{{ route('student.support.index') }}" class="nav-item {{ request()->routeIs('student.support*') ? 'active':'' }}">
    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
    Support
  </a>
  <a href="{{ route('student.profile.index') }}" class="nav-item {{ request()->routeIs('student.profile*') ? 'active':'' }}">
    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
    My Profile
  </a>
  <div style="padding:16px">
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button class="nav-item" style="width:100%;background:none;border:none;cursor:pointer;color:rgba(255,255,255,.75)">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
        Logout
      </button>
    </form>
  </div>
</aside>
<div class="main">
  <div class="topbar">
    <div style="display:flex;align-items:center">
      <button class="hamburger" id="sidebarToggle" aria-label="Menu">
        <span></span><span></span><span></span>
      </button>
      <div class="topbar-title">@yield('page-title','Dashboard')</div>
    </div>
    <div style="display:flex;align-items:center;gap:18px">
      <a href="{{ route('notifications.index') }}" style="position:relative;color:#4a5568;display:flex;align-items:center;text-decoration:none" title="Notifications">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width:20px;height:20px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
        @php
          $unreadCount = \App\Models\UserNotification::where('user_id', auth()->id())->where('is_read', false)->count();
        @endphp
        @if($unreadCount > 0)
          <span style="position:absolute;top:-6px;right:-6px;background:#ef4444;color:#fff;font-size:9px;font-weight:700;padding:1px 5px;border-radius:50%;line-height:1">{{ $unreadCount }}</span>
        @endif
      </a>
      <div style="font-size:13px;color:#718096">Islamic Online Madrasah</div>
    </div>
  </div>
  <div class="content">
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert alert-danger" style="background:#fff1f2;border:1px solid #fecdd3;color:#e11d48;padding:12px 16px;border-radius:8px;font-size:13px;margin-bottom:16px">{{ session('error') }}</div>@endif
    @if(session('warning'))<div class="alert alert-warning" style="background:#fffbeb;border:1px solid #fef3c7;color:#d97706;padding:12px 16px;border-radius:8px;font-size:13px;margin-bottom:16px">{{ session('warning') }}</div>@endif
    @if(session('info'))<div class="alert alert-info" style="background:#eff6ff;border:1px solid #dbeafe;color:#2563eb;padding:12px 16px;border-radius:8px;font-size:13px;margin-bottom:16px">{{ session('info') }}</div>@endif
    @yield('content')
  </div>
</div>
</div>
<div class="overlay" id="sidebarOverlay"></div>
<script>
(function(){
  var btn     = document.getElementById('sidebarToggle');
  var sidebar = document.querySelector('.sidebar');
  var overlay = document.getElementById('sidebarOverlay');
  function open()  { sidebar.classList.add('open'); btn.classList.add('open'); overlay.classList.add('active'); }
  function close() { sidebar.classList.remove('open'); btn.classList.remove('open'); overlay.classList.remove('active'); }
  btn.addEventListener('click', function(){ sidebar.classList.contains('open') ? close() : open(); });
  overlay.addEventListener('click', close);
  document.querySelectorAll('.nav-item').forEach(function(a){ a.addEventListener('click', close); });
})();
</script>
@stack('scripts')
</body>
</html>
