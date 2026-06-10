<!DOCTYPE html>
<html lang="bn">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Islamic Online Madrasah — IOM</title>
<meta name="description" content="ইসলামিক অনলাইন মাদ্রাসা — বাংলাদেশের সেরা অনলাইন ইসলামি শিক্ষা প্রতিষ্ঠান">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Inter',sans-serif;color:#1a202c;overflow-x:hidden}

/* Nav */
nav{background:rgba(15,36,64,.97);backdrop-filter:blur(12px);position:fixed;top:0;left:0;right:0;z-index:100;padding:0 5%;display:flex;align-items:center;justify-content:space-between;height:68px}
.nav-brand{display:flex;align-items:center;gap:12px;text-decoration:none}
.nav-logo{width:42px;height:42px;background:linear-gradient(135deg,#4fc3f7,#0288d1);border-radius:10px;display:flex;align-items:center;justify-content:center;font-weight:800;color:#fff;font-size:16px}
.nav-name{color:#fff;font-weight:700;font-size:15px;line-height:1.3}
.nav-name span{display:block;font-size:11px;font-weight:400;color:rgba(255,255,255,.6)}
.nav-links{display:flex;gap:24px;align-items:center}
.nav-links a{color:rgba(255,255,255,.8);text-decoration:none;font-size:14px;font-weight:500;transition:.2s}
.nav-links a:hover{color:#fff}
.btn-nav{background:linear-gradient(135deg,#4fc3f7,#0288d1);color:#fff!important;padding:8px 20px;border-radius:8px;font-weight:600!important}

/* Hero */
.hero{min-height:100vh;background:linear-gradient(135deg,#0f2440 0%,#1a3a5c 40%,#0d47a1 100%);display:flex;align-items:center;padding:100px 5% 60px;position:relative;overflow:hidden}
.hero-inner{display:grid;grid-template-columns:1fr 1fr;gap:60px;align-items:center;width:100%;max-width:1200px;margin:0 auto;position:relative;z-index:1}
.hero-image-wrap{position:relative;display:flex;justify-content:center;align-items:center}
.hero-image-wrap::before{content:'';position:absolute;width:380px;height:380px;background:radial-gradient(circle,rgba(79,195,247,.25) 0%,transparent 70%);border-radius:50%;z-index:0}
.hero-image-wrap img{width:340px;height:400px;object-fit:cover;border-radius:20px;box-shadow:0 30px 80px rgba(0,0,0,.5);position:relative;z-index:1;border:3px solid rgba(79,195,247,.3)}
.hero::before{content:'';position:absolute;top:-20%;right:-10%;width:600px;height:600px;background:radial-gradient(circle,rgba(79,195,247,.15) 0%,transparent 70%);border-radius:50%}
.hero::after{content:'';position:absolute;bottom:-10%;left:-5%;width:400px;height:400px;background:radial-gradient(circle,rgba(2,136,209,.2) 0%,transparent 70%);border-radius:50%}
.hero-content{position:relative;z-index:1}
.hero-badge{display:inline-block;background:rgba(79,195,247,.15);border:1px solid rgba(79,195,247,.3);color:#4fc3f7;padding:6px 16px;border-radius:20px;font-size:12px;font-weight:600;letter-spacing:.5px;text-transform:uppercase;margin-bottom:20px}
.hero h1{font-size:52px;font-weight:800;color:#fff;line-height:1.15;margin-bottom:20px}
.hero h1 span{color:#4fc3f7}
.hero p{font-size:17px;color:rgba(255,255,255,.75);line-height:1.7;margin-bottom:32px;max-width:480px}
.hero-btns{display:flex;gap:16px;flex-wrap:wrap}
.btn-hero-primary{background:linear-gradient(135deg,#4fc3f7,#0288d1);color:#fff;padding:14px 32px;border-radius:10px;font-size:15px;font-weight:700;text-decoration:none;transition:.2s;display:inline-flex;align-items:center;gap:8px}
.btn-hero-primary:hover{transform:translateY(-2px);box-shadow:0 8px 25px rgba(79,195,247,.4)}
.btn-hero-outline{background:rgba(255,255,255,.1);border:2px solid rgba(255,255,255,.3);color:#fff;padding:12px 28px;border-radius:10px;font-size:15px;font-weight:600;text-decoration:none;transition:.2s}
.btn-hero-outline:hover{background:rgba(255,255,255,.2)}
.hero-stats{display:flex;gap:32px;margin-top:48px;padding-top:32px;border-top:1px solid rgba(255,255,255,.1)}
.hero-stat .val{font-size:30px;font-weight:800;color:#4fc3f7}
.hero-stat .lbl{font-size:12px;color:rgba(255,255,255,.6);margin-top:2px}

/* Courses */
.section{padding:80px 5%}
.section-header{text-align:center;margin-bottom:48px}
.section-tag{display:inline-block;background:#eff6ff;color:#2563eb;padding:5px 14px;border-radius:20px;font-size:12px;font-weight:600;margin-bottom:12px}
.section-title{font-size:36px;font-weight:800;color:#0f2440;margin-bottom:12px}
.section-sub{font-size:16px;color:#718096;max-width:500px;margin:0 auto;line-height:1.6}

.courses-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:24px}
.course-card{background:#fff;border-radius:16px;border:1px solid #e2e8f0;overflow:hidden;transition:.3s;cursor:pointer}
.course-card:hover{transform:translateY(-4px);box-shadow:0 20px 40px rgba(0,0,0,.1)}
.course-card-top{height:10px;background:linear-gradient(90deg,#4fc3f7,#0288d1)}
.course-card-body{padding:24px}
.course-icon{width:52px;height:52px;background:#eff6ff;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:24px;margin-bottom:16px}
.course-card h3{font-size:17px;font-weight:700;color:#0f2440;margin-bottom:8px}
.course-card p{font-size:13px;color:#718096;line-height:1.6}
.course-meta{display:flex;gap:16px;margin-top:16px;padding-top:16px;border-top:1px solid #f0f4f8}
.course-meta span{font-size:12px;color:#9ca3af;display:flex;align-items:center;gap:4px}

/* Features */
.features{background:#f7fafc;padding:80px 5%}
.features-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:24px}
.feature-item{background:#fff;padding:28px;border-radius:14px;border:1px solid #e2e8f0;text-align:center}
.feature-icon{width:56px;height:56px;border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:26px}
.feature-item h3{font-size:15px;font-weight:700;color:#0f2440;margin-bottom:8px}
.feature-item p{font-size:13px;color:#718096;line-height:1.6}

/* Admission Form */
.admission-section{padding:80px 5%;background:linear-gradient(135deg,#0f2440,#1a3a5c)}
.admission-inner{display:grid;grid-template-columns:1fr 1fr;gap:60px;align-items:start;max-width:1100px;margin:0 auto}
.admission-info h2{font-size:36px;font-weight:800;color:#fff;margin-bottom:16px}
.admission-info p{font-size:15px;color:rgba(255,255,255,.75);line-height:1.7;margin-bottom:24px}
.admission-bullets{list-style:none}
.admission-bullets li{display:flex;align-items:center;gap:10px;color:rgba(255,255,255,.85);font-size:14px;padding:8px 0}
.admission-bullets li::before{content:'✓';width:22px;height:22px;background:rgba(79,195,247,.2);border:1px solid rgba(79,195,247,.5);border-radius:50%;display:flex;align-items:center;justify-content:center;color:#4fc3f7;font-weight:700;font-size:11px;flex-shrink:0}
.admission-form-card{background:#fff;border-radius:16px;padding:32px}
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
footer{background:#0a1929;color:rgba(255,255,255,.6);text-align:center;padding:28px 5%;font-size:13px}
footer strong{color:#4fc3f7}

@media(max-width:768px){
  .hero{padding:100px 5% 40px}
  .hero-inner{grid-template-columns:1fr}
  .hero-image-wrap{display:none}
  .hero h1{font-size:32px}
  .hero-stats{flex-wrap:wrap;gap:20px}
  .admission-inner{grid-template-columns:1fr}
  nav .nav-links{display:none}
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
    <a href="#courses">Courses</a>
    <a href="#features">Features</a>
    <a href="#admission">Admission</a>
    <a href="{{ route('login') }}" class="btn-nav">Login →</a>
  </div>
</nav>

<!-- Hero -->
<section class="hero">
  <div class="hero-inner">
  <div class="hero-content">
    <div class="hero-badge">🕌 বাংলাদেশের সেরা অনলাইন মাদ্রাসা</div>
    <h1>ঘরে বসে শিখুন <span>ইসলামি শিক্ষা</span> সহজে</h1>
    <p>আধুনিক প্রযুক্তির মাধ্যমে বিশ্বমানের ইসলামি শিক্ষা গ্রহণ করুন। Live class, recorded lecture, এবং certified course — সব এক জায়গায়।</p>
    <div class="hero-btns">
      <a href="#admission" class="btn-hero-primary">📝 ভর্তির আবেদন করুন</a>
      <a href="#courses" class="btn-hero-outline">Courses দেখুন</a>
    </div>
    <div class="hero-stats">
      <div class="hero-stat"><div class="val">৫০০+</div><div class="lbl">Active Students</div></div>
      <div class="hero-stat"><div class="val">২০+</div><div class="lbl">Expert Teachers</div></div>
      <div class="hero-stat"><div class="val">১৫+</div><div class="lbl">Courses</div></div>
      <div class="hero-stat"><div class="val">১০০%</div><div class="lbl">Online</div></div>
    </div>
  </div>
  <div class="hero-image-wrap">
    <img src="{{ asset('images/hero-mosque.jpg') }}" alt="কুরআন শরীফ">
  </div>
  </div>
</section>

<!-- Courses -->
<section class="section" id="courses">
  <div class="section-header">
    <div class="section-tag">Our Courses</div>
    <h2 class="section-title">আমাদের কোর্সসমূহ</h2>
    <p class="section-sub">বিভিন্ন মেয়াদি কোর্স থেকে আপনার পছন্দের কোর্সটি বেছে নিন</p>
  </div>
  <div class="courses-grid">
    @forelse(\App\Models\Course::where('status','active')->take(6)->get() as $c)
    <div class="course-card">
      <div class="course-card-top"></div>
      <div class="course-card-body">
        <div class="course-icon">📖</div>
        <h3>{{ $c->name }}</h3>
        <p>{{ $c->description ?? 'এই কোর্সে ভর্তি হয়ে ইসলামি জ্ঞান অর্জন করুন।' }}</p>
        <div class="course-meta">
          <span>⏱ {{ $c->duration_years }} বছর</span>
          <span>🏷 {{ ucfirst(str_replace('_',' ',$c->type)) }}</span>
          <span>👥 {{ $c->batches()->count() }} Batch</span>
        </div>
      </div>
    </div>
    @empty
    @foreach([['📖','আলিম কোর্স','ব্যাপক ইসলামি শিক্ষার কোর্স','৩ বছর'],['📚','হিফজ কোর্স','কুরআনুল কারিম হিফজ প্রোগ্রাম','২ বছর'],['🖊','কুরআন শিক্ষা','সহীহ কুরআন তিলাওয়াত শেখা','৬ মাস'],['🌙','ফিকহ কোর্স','ইসলামি আইন ও বিধান','১ বছর'],['✨','আরবি ভাষা','আরবি ভাষা শেখার বিশেষ কোর্স','৬ মাস'],['📿','হাদিস কোর্স','হাদিস শাস্ত্র অধ্যয়ন','২ বছর']] as [$icon,$name,$desc,$dur])
    <div class="course-card">
      <div class="course-card-top"></div>
      <div class="course-card-body">
        <div class="course-icon">{{ $icon }}</div>
        <h3>{{ $name }}</h3>
        <p>{{ $desc }}</p>
        <div class="course-meta"><span>⏱ {{ $dur }}</span></div>
      </div>
    </div>
    @endforeach
    @endforelse
  </div>
</section>

<!-- Features -->
<section class="features" id="features">
  <div class="section-header">
    <div class="section-tag">Why IOM</div>
    <h2 class="section-title">কেন আমাদের বেছে নেবেন?</h2>
  </div>
  <div class="features-grid">
    <div class="feature-item">
      <div class="feature-icon" style="background:#eff6ff">📹</div>
      <h3>Live Class</h3>
      <p>Google Meet ও Zoom-এ সরাসরি শিক্ষকের সাথে পড়ার সুযোগ</p>
    </div>
    <div class="feature-item">
      <div class="feature-icon" style="background:#f0fdf4">🎓</div>
      <h3>Certificate</h3>
      <p>কোর্স সম্পন্ন করলে QR-verified সার্টিফিকেট প্রদান</p>
    </div>
    <div class="feature-item">
      <div class="feature-icon" style="background:#fef9c3">📊</div>
      <h3>MCQ Exam</h3>
      <p>অটোমেটিক গ্রেডিং সহ অনলাইন পরীক্ষা ব্যবস্থা</p>
    </div>
    <div class="feature-item">
      <div class="feature-icon" style="background:#fdf4ff">💳</div>
      <h3>Easy Payment</h3>
      <p>SSLCommerz-এর মাধ্যমে নিরাপদ অনলাইন পেমেন্ট</p>
    </div>
    <div class="feature-item">
      <div class="feature-icon" style="background:#fff1f2">📱</div>
      <h3>যেকোনো ডিভাইস</h3>
      <p>মোবাইল, ট্যাবলেট বা কম্পিউটার — যেকোনো ডিভাইসে পড়ুন</p>
    </div>
    <div class="feature-item">
      <div class="feature-icon" style="background:#f0fdf4">🕌</div>
      <h3>বিশেষজ্ঞ শিক্ষক</h3>
      <p>অভিজ্ঞ আলেমদের তত্ত্বাবধানে মানসম্পন্ন ইসলামি শিক্ষা</p>
    </div>
  </div>
</section>

<!-- Admission Form -->
<section class="admission-section" id="admission">
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
</section>

<!-- Footer -->
<footer>
  <p>© {{ date('Y') }} <strong>Islamic Online Madrasah (IOM)</strong> — সকল অধিকার সংরক্ষিত</p>
  <p style="margin-top:6px"><a href="{{ route('login') }}" style="color:#4fc3f7;text-decoration:none">Admin/Student Login</a> | <a href="{{ route('support.public') }}" style="color:#4fc3f7;text-decoration:none">Support</a></p>
</footer>

<script>
// Smooth scroll
document.querySelectorAll('a[href^="#"]').forEach(a => {
  a.addEventListener('click', e => {
    const t = document.querySelector(a.getAttribute('href'));
    if(t){ e.preventDefault(); t.scrollIntoView({behavior:'smooth',block:'start'}); }
  });
});
</script>
</body>
</html>
