<!DOCTYPE html>
<html lang="bn">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>ভর্তির আবেদন — Islamic Online Madrasah (IOM)</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Inter',sans-serif;color:#1a202c;background: linear-gradient(135deg,#0f2440,#1a3a5c);min-height: 100vh;display: flex;flex-direction: column;}

/* Nav */
nav{background:rgba(15,36,64,.97);backdrop-filter:blur(12px);position:fixed;top:0;left:0;right:0;z-index:100;padding:0 5%;display:flex;align-items:center;justify-content:space-between;height:68px}
.nav-brand{display:flex;align-items:center;gap:12px;text-decoration:none}
.nav-logo{width:42px;height:42px;background:linear-gradient(135deg,#4fc3f7,#0288d1);border-radius:10px;display:flex;align-items:center;justify-content:center;font-weight:800;color:#fff;font-size:16px}
.nav-name{color:#fff;font-weight:700;font-size:15px;line-height:1.3}
.nav-name span{display:block;font-size:11px;font-weight:400;color:rgba(255,255,255,.6)}
.nav-links a{color:rgba(255,255,255,.8);text-decoration:none;font-size:14px;font-weight:500;transition:.2s}
.nav-links a:hover{color:#fff}

/* Container */
.admission-container{flex: 1; padding: 120px 5% 60px; max-width: 1100px; margin: 0 auto; width: 100%;}
.admission-inner{display:grid;grid-template-columns:1fr 1fr;gap:60px;align-items:start}
.admission-info h2{font-size:36px;font-weight:800;color:#fff;margin-bottom:16px}
.admission-info p{font-size:15px;color:rgba(255,255,255,.75);line-height:1.7;margin-bottom:24px}
.admission-bullets{list-style:none}
.admission-bullets li{display:flex;align-items:center;gap:10px;color:rgba(255,255,255,.85);font-size:14px;padding:8px 0}
.admission-bullets li::before{content:'✓';width:22px;height:22px;background:rgba(79,195,247,.2);border:1px solid rgba(79,195,247,.5);border-radius:50%;display:flex;align-items:center;justify-content:center;color:#4fc3f7;font-weight:700;font-size:11px;flex-shrink:0}

/* Form */
.admission-form-card{background:#fff;border-radius:16px;padding:32px;box-shadow: 0 20px 40px rgba(0,0,0,0.3);}
.admission-form-card h3{font-size:20px;font-weight:700;color:#0f2440;margin-bottom:6px}
.admission-form-card p{font-size:13px;color:#718096;margin-bottom:24px}
.form-group{margin-bottom:14px}
.form-label{display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:5px;text-transform:uppercase;letter-spacing:.3px}
.form-control{width:100%;padding:9px 12px;border:1.5px solid #d1d5db;border-radius:8px;font-size:13px;font-family:inherit;transition:.2s}
.form-control:focus{outline:none;border-color:#4fc3f7;box-shadow:0 0 0 3px rgba(79,195,247,.15)}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.btn-apply{width:100%;padding:12px;background:linear-gradient(135deg,#4fc3f7,#0288d1);color:#fff;border:none;border-radius:9px;font-size:14px;font-weight:700;cursor:pointer;font-family:inherit;margin-top:8px;transition:.2s}
.btn-apply:hover{opacity:.9;transform:translateY(-1px)}
.alert-success{background:#f0fdf4;border:1px solid #bbf7d0;color:#16a34a;padding:12px 16px;border-radius:8px;font-size:13px;margin-bottom:16px}

/* Footer */
footer{background:#0a1929;color:rgba(255,255,255,.6);text-align:center;padding:28px 5%;font-size:13px;margin-top: auto;}
footer strong{color:#4fc3f7}

@media(max-width:768px){
  .admission-inner{grid-template-columns:1fr; gap: 40px;}
  .admission-container{padding-top: 100px;}
}
</style>
</head>
<body>

<!-- Nav -->
<nav>
  <a href="/" class="nav-brand">
    <div class="nav-logo">IM</div>
    <div class="nav-name">Islamic Online Madrasah <span>ইসলামিক অনলাইন মাদ্রাসা</span></div>
  </a>
  <div class="nav-links">
    <a href="/">← হোম পেজে ফিরে যান</a>
  </div>
</nav>

<!-- Main Container -->
<main class="admission-container">
  <div class="admission-inner">
    <div class="admission-info">
      <h2>ভর্তির আবেদন করুন</h2>
      <p>আজই আবেদন করুন এবং ইসলামি শিক্ষার এক নতুন যাত্রা শুরু করুন। আমাদের বিশেষজ্ঞ দল আপনার আবেদন পর্যালোচনা করে শীঘ্রই যোগাযোগ করবে।</p>
      <ul class="admission-bullets">
        <li>অনলাইনে সহজে আবেদন করুন</li>
        <li>Approval পেলে Email-এ Login credentials পাবেন</li>
        <li>বিভিন্ন কোর্স ও Batch থেকে বেছে নিন</li>
        <li>Scholarship সুবিধা পাওয়ার সুযোগ</li>
        <li>২৪/৭ Student Support সেবা</li>
      </ul>
    </div>
    
    <div class="admission-form-card">
      <h3>📝 ভর্তির আবেদন ফর্ম</h3>
      <p>সকল তথ্য সঠিকভাবে পূরণ করুন</p>

      @if(session('success'))
        <div class="alert-success">✅ {{ session('success') }}</div>
      @endif

      <form method="POST" action="{{ route('admission.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
          <label class="form-label">Course *</label>
          <select name="course_id" class="form-control" required>
            <option value="">কোর্স বেছে নিন</option>
            @foreach(\App\Models\Course::where('status','active')->get() as $c)
              <option value="{{ $c->id }}" {{ old('course_id')==$c->id?'selected':'' }}>{{ $c->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">পূর্ণ নাম *</label>
            <input type="text" name="applicant_name" class="form-control" placeholder="আপনার নাম" value="{{ old('applicant_name') }}" required>
          </div>
          <div class="form-group">
            <label class="form-label">Email *</label>
            <input type="email" name="applicant_email" class="form-control" placeholder="email@example.com" value="{{ old('applicant_email') }}" required>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">মোবাইল *</label>
            <input type="text" name="applicant_phone" class="form-control" placeholder="01XXXXXXXXX" value="{{ old('applicant_phone') }}" required>
          </div>
          <div class="form-group">
            <label class="form-label">লিঙ্গ</label>
            <select name="gender" class="form-control">
              <option value="">Select</option>
              <option value="male" {{ old('gender')==='male'?'selected':'' }}>পুরুষ</option>
              <option value="female" {{ old('gender')==='female'?'selected':'' }}>মহিলা</option>
            </select>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">অভিভাবকের নাম</label>
            <input type="text" name="guardian_name" class="form-control" placeholder="Guardian name" value="{{ old('guardian_name') }}">
          </div>
          <div class="form-group">
            <label class="form-label">অভিভাবকের মোবাইল</label>
            <input type="text" name="guardian_phone" class="form-control" placeholder="01XXXXXXXXX" value="{{ old('guardian_phone') }}">
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">ঠিকানা</label>
          <input type="text" name="address" class="form-control" placeholder="আপনার ঠিকানা" value="{{ old('address') }}">
        </div>
        <button type="submit" class="btn-apply">📨 আবেদন জমা দিন</button>
      </form>
    </div>
  </div>
</main>

<!-- Footer -->
<footer>
  <p>© {{ date('Y') }} <strong>Islamic Online Madrasah (IOM)</strong> — সকল অধিকার সংরক্ষিত</p>
</footer>

</body>
</html>