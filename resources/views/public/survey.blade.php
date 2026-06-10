<!DOCTYPE html>
<html lang="bn">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $survey->title }} — Survey</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: 'Inter', sans-serif; background: linear-gradient(135deg, #f0fdf4 0%, #e0f2fe 100%); color: #0f172a; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
.survey-container { width: 100%; max-width: 600px; background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(12px); border-radius: 16px; border: 1px solid rgba(255,255,255,0.7); box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05), 0 8px 10px -6px rgba(0,0,0,0.05); overflow: hidden; transition: 0.3s; }
.survey-header { background: linear-gradient(90deg, #0f2440, #1a3a5c); color: #fff; padding: 24px; text-align: center; }
.survey-title { font-size: 20px; font-weight: 700; margin-bottom: 8px; line-height: 1.3; }
.survey-desc { font-size: 13px; color: rgba(255,255,255,0.8); line-height: 1.4; }
.survey-body { padding: 32px 24px; position: relative; }
.question-card { display: none; animation: fadeIn 0.4s ease; }
.question-card.active { display: block; }
.question-label { font-size: 16px; font-weight: 600; color: #1e293b; margin-bottom: 16px; line-height: 1.4; }
.form-control { width: 100%; padding: 12px; border: 1.5px solid #cbd5e1; border-radius: 10px; font-size: 14px; transition: 0.2s; outline: none; background: #fff; }
.form-control:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
.option-label { display: flex; align-items: center; gap: 10px; padding: 12px 16px; border: 1.5px solid #e2e8f0; border-radius: 10px; cursor: pointer; transition: 0.2s; margin-bottom: 10px; }
.option-label:hover { background: #f1f5f9; border-color: #94a3b8; }
.option-label:has(input:checked) { background: #eff6ff; border-color: #2563eb; color: #1e40af; }
.option-label input[type=radio] { accent-color: #2563eb; width: 16px; height: 16px; }
.btn { display: inline-flex; align-items: center; justify-content: center; gap: 6px; padding: 12px 24px; border-radius: 10px; font-size: 14px; font-weight: 600; cursor: pointer; border: none; text-decoration: none; transition: 0.2s; width: 100%; }
.btn-primary { background: #1a3a5c; color: #fff; }
.btn-primary:hover { background: #0f2440; }
.btn-secondary { background: transparent; border: 1.5px solid #cbd5e1; color: #475569; }
.btn-secondary:hover { background: #f8fafc; }
.btn-group { display: flex; gap: 12px; margin-top: 24px; }
.progress-bar { background: #e2e8f0; height: 6px; width: 100%; position: relative; }
.progress-fill { background: linear-gradient(90deg, #2563eb, #3b82f6); height: 100%; width: 0%; transition: 0.3s; }
.alert { padding: 12px 16px; border-radius: 8px; font-size: 13px; margin-bottom: 16px; text-align: center; }
.alert-success { background: #d1fae5; border: 1px solid #a7f3d0; color: #065f46; }
.alert-danger { background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; }
.alert-info { background: #e0f2fe; border: 1px solid #bae6fd; color: #0369a1; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>
</head>
<body>

<div class="survey-container">
  <div class="survey-header">
    <div class="survey-title">{{ $survey->title }}</div>
    @if($survey->description)
      <div class="survey-desc">{{ $survey->description }}</div>
    @endif
  </div>

  <div class="progress-bar">
    <div class="progress-fill" id="progressFill"></div>
  </div>

  <div class="survey-body">
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
      <div style="text-align:center;margin-top:20px">
        <span style="font-size:32px">🎉</span>
      </div>
    @elseif(session('error'))
      <div class="alert alert-danger">{{ session('error') }}</div>
    @elseif(session('info'))
      <div class="alert alert-info">{{ session('info') }}</div>
    @else
      <form action="{{ route('surveys.public.submit', $survey->slug) }}" method="POST" id="surveyForm">
        @csrf

        {{-- Respondent Info Card (Guest only) --}}
        @guest
        <div class="question-card active" id="card_respondent">
          <div class="question-label">সার্ভে শুরু করার আগে অনুগ্রহ করে আপনার নাম ও ইমেইল প্রদান করুন:</div>
          <div class="form-group" style="margin-bottom:16px">
            <label style="font-size:12px;font-weight:600;color:#475569;display:block;margin-bottom:6px">নাম *</label>
            <input type="text" name="respondent_name" id="resp_name" class="form-control" placeholder="আপনার নাম লিখুন..." required>
          </div>
          <div class="form-group">
            <label style="font-size:12px;font-weight:600;color:#475569;display:block;margin-bottom:6px">ইমেইল *</label>
            <input type="email" name="respondent_email" id="resp_email" class="form-control" placeholder="আপনার ইমেইল..." required>
          </div>
          <div class="btn-group">
            <button type="button" class="btn btn-primary" onclick="nextStep('respondent')">শুরু করুন →</button>
          </div>
        </div>
        @endguest

        {{-- Dynamic Question Cards --}}
        @foreach($survey->questions as $index => $q)
          @php
            $isStart = $q->is_start;
            // If logged in, the first question is active by default. If guest, respondent card is active.
            $isActive = auth()->check() ? $isStart : false;
          @endphp
          <div class="question-card {{ $isActive ? 'active' : '' }}" id="card_{{ $q->question_key }}" data-key="{{ $q->question_key }}" data-type="{{ $q->type }}" data-required="{{ $q->required ? '1' : '0' }}">
            <div class="question-label">{{ $q->label }} @if($q->required)*@endif</div>

            {{-- Text input --}}
            @if($q->type === 'text')
              <input type="text" name="q_{{ $q->question_key }}" id="input_{{ $q->question_key }}" class="form-control" placeholder="উত্তর লিখুন...">
            
            {{-- Textarea input --}}
            @elseif($q->type === 'textarea')
              <textarea name="q_{{ $q->question_key }}" id="input_{{ $q->question_key }}" class="form-control" rows="4" placeholder="উত্তর লিখুন..."></textarea>
            
            {{-- Number input --}}
            @elseif($q->type === 'number')
              <input type="number" name="q_{{ $q->question_key }}" id="input_{{ $q->question_key }}" class="form-control" placeholder="উত্তর...">
            
            {{-- Date input --}}
            @elseif($q->type === 'date')
              <input type="date" name="q_{{ $q->question_key }}" id="input_{{ $q->question_key }}" class="form-control">

            {{-- Select dropdown --}}
            @elseif($q->type === 'select')
              <select name="q_{{ $q->question_key }}" id="input_{{ $q->question_key }}" class="form-control">
                <option value="">উত্তর বেছে নিন...</option>
                @if(is_array($q->options))
                  @foreach($q->options as $opt)
                    <option value="{{ $opt['value'] }}">{{ $opt['value'] }}</option>
                  @endforeach
                @endif
              </select>

            {{-- Radio select --}}
            @elseif($q->type === 'radio')
              <div id="input_{{ $q->question_key }}">
                @if(is_array($q->options))
                  @foreach($q->options as $oIndex => $opt)
                    <label class="option-label">
                      <input type="radio" name="q_{{ $q->question_key }}" value="{{ $opt['value'] }}">
                      <span>{{ $opt['value'] }}</span>
                    </label>
                  @endforeach
                @endif
              </div>
            @endif

            <div class="btn-group">
              <button type="button" class="btn btn-secondary" onclick="prevStep('{{ $q->question_key }}')">← Back</button>
              <button type="button" class="btn btn-primary" onclick="nextStep('{{ $q->question_key }}')">Next Step →</button>
            </div>
          </div>
        @endforeach

        {{-- Final Submit Card --}}
        <div class="question-card" id="card_submit">
          <div class="question-label" style="text-align:center;font-size:18px">🎉 ধন্যবাদ!</div>
          <p style="text-align:center;color:#475569;font-size:14px;margin-bottom:24px">আপনি সার্ভের শেষ ধাপে পৌঁছেছেন। আপনার উত্তর জমা দিতে সাবমিট বাটনে ক্লিক করুন।</p>
          <div class="btn-group">
            <button type="button" class="btn btn-secondary" onclick="prevStep('submit')">← Back</button>
            <button type="submit" class="btn btn-primary">Submit Survey</button>
          </div>
        </div>

      </form>
    @endif
  </div>
</div>

<script>
// Load questions branching map
const questions = @json($survey->questions->keyBy('question_key'));
const startKey = "{{ ($survey->questions->firstWhere('is_start', true) ?: $survey->questions->first())->question_key ?? '' }}";
const isLoggedIn = {{ auth()->check() ? 'true' : 'false' }};

const pathHistory = [];

function getActiveCard() {
    return document.querySelector('.question-card.active');
}

function updateProgressBar() {
    const total = Object.keys(questions).length + (isLoggedIn ? 1 : 2); // approximate length
    const current = pathHistory.length + 1;
    const pct = Math.min(100, Math.round((current / total) * 100));
    document.getElementById('progressFill').style.width = pct + '%';
}

function nextStep(currentKey) {
    const currentCard = document.getElementById('card_' + currentKey);
    
    // Validation
    if (currentKey === 'respondent') {
        const name = document.getElementById('resp_name').value.trim();
        const email = document.getElementById('resp_email').value.trim();
        if (!name || !email) {
            alert('অনুগ্রহ করে নাম ও ইমেইল পূরণ করুন।');
            return;
        }
    } else {
        const isRequired = currentCard.dataset.required === '1';
        const type = currentCard.dataset.type;
        let answered = false;
        let value = '';

        if (type === 'radio') {
            const selected = currentCard.querySelector('input[type=radio]:checked');
            if (selected) {
                answered = true;
                value = selected.value;
            }
        } else {
            const input = document.getElementById('input_' + currentKey);
            if (input && input.value.trim() !== '') {
                answered = true;
                value = input.value.trim();
            }
        }

        if (isRequired && !answered) {
            alert('এই প্রশ্নের উত্তর দেওয়া আবশ্যক।');
            return;
        }
    }

    // Determine next step
    let nextKey = 'end';
    if (currentKey === 'respondent') {
        nextKey = startKey;
    } else {
        const q = questions[currentKey];
        if (q) {
            let val = '';
            if (q.type === 'radio') {
                const checked = currentCard.querySelector('input[type=radio]:checked');
                if (checked) val = checked.value;
            } else {
                const el = document.getElementById('input_' + currentKey);
                if (el) val = el.value;
            }

            // check options mapping
            let mapped = false;
            if (['select', 'radio'].includes(q.type) && val && Array.isArray(q.options)) {
                for (let opt of q.options) {
                    if (opt.value.trim() === val.trim() && opt.next_question_key) {
                        nextKey = opt.next_question_key;
                        mapped = true;
                        break;
                    }
                }
            }

            if (!mapped) {
                nextKey = q.default_next_question_key || 'end';
            }
        }
    }

    // Move to next step
    pathHistory.push(currentKey);
    currentCard.classList.remove('active');

    if (nextKey === 'end' || !questions[nextKey]) {
        document.getElementById('card_submit').classList.add('active');
    } else {
        document.getElementById('card_' + nextKey).classList.add('active');
    }

    updateProgressBar();
}

function prevStep(currentKey) {
    if (pathHistory.length === 0) return;
    
    const prevKey = pathHistory.pop();
    const currentCard = document.getElementById('card_' + currentKey);
    currentCard.classList.remove('active');

    const prevCard = document.getElementById('card_' + prevKey);
    prevCard.classList.add('active');

    updateProgressBar();
}

document.addEventListener('DOMContentLoaded', function() {
    updateProgressBar();
});
</script>
</body>
</html>
