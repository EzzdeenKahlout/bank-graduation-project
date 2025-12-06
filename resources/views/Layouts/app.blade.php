<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title',  __('messages.dashboard'))</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .navbar {
            background: white;
            padding: 1rem 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar h2 {
            color: #667eea;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .nav-links {
            display: flex;
            gap: 1rem;
            align-items: center;
        }
        .nav-links a {
            text-decoration: none;
            color: #333;
            padding: 0.5rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            border-radius: 8px;
        }
        .nav-links a:hover {
            background: #f0f0f0;
            color: #667eea;
        }

        /* Language Dropdown */
        .language-dropdown {
            position: relative;
            display: inline-block;
        }
        .language-btn {
            background: transparent;
            border: none;
            cursor: pointer;
            padding: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.3rem;
            color: #333;
            font-size: 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .language-btn:hover {
            background: #f0f0f0;
        }
        .language-dropdown-content {
            display: none;
            position: absolute;
            {{ app()->getLocale() == 'ar' ? 'left' : 'right' }}: 0;
            background-color: white;
            min-width: 180px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            border-radius: 8px;
            z-index: 1000;
            margin-top: 0.5rem;
            overflow: hidden;
        }
        .language-dropdown-content a {
            color: #333;
            padding: 0.75rem 1rem;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
        }
        .language-dropdown-content a:hover {
            background-color: #f0f0f0;
            color: #667eea;
        }
        .language-dropdown-content a.active {
            background-color: #667eea;
            color: white;
        }
        .language-dropdown.active .language-dropdown-content {
            display: block;
        }

        .container { max-width: 1200px; margin: 2rem auto; padding: 0 2rem; }
        .card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        .btn {
            padding: 0.7rem 1.5rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        .form-control {
            width: 100%;
            padding: 0.8rem;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
        .form-group { margin-bottom: 1.5rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-weight: 600; }
        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .alert-success { background: #d4edda; color: #155724; }
        .alert-error { background: #f8d7da; color: #721c24; }

        /* Icon styles */
        .icon {
            width: 20px;
            height: 20px;
            display: inline-block;
        }
        .icon-lg {
            width: 24px;
            height: 24px;
        }
    
/* --- Center Popup for payment success / failure --- */
.popup-overlay{position:fixed;inset:0;background:rgba(0,0,0,.35);display:none;align-items:center;justify-content:center;z-index:9999}
.popup-card{background:#fff;border-radius:16px;min-width:280px;max-width:420px;padding:20px 18px;box-shadow:0 20px 60px rgba(0,0,0,.25);text-align:center;direction:rtl}
.popup-card .icon{width:46px;height:46px;margin:0 auto 10px;border-radius:50%;display:flex;align-items:center;justify-content:center}
.popup-card.success .icon{background:#e6f7ec;color:#16a34a}
.popup-card.error .icon{background:#fdecea;color:#dc2626}
.popup-card h4{margin:6px 0 8px;font-size:18px}
.popup-card p{margin:0 0 12px;color:#555;font-size:14px}
.popup-actions{display:flex;gap:8px;justify-content:center;margin-top:6px}
.popup-btn{padding:8px 14px;border-radius:999px;border:0;cursor:pointer;box-shadow:0 2px 6px rgba(0,0,0,.12)}
.popup-btn.primary{background:#6366f1;color:#fff}
.popup-btn.ghost{background:#f3f4f6;color:#111827}
@media (max-width:480px){.popup-card{margin:0 16px}}
</style>
</head>
<body>
<div id="globalPopupOverlay" class="popup-overlay" role="dialog" aria-modal="true" aria-hidden="true">
  <div id="globalPopupCard" class="popup-card" tabindex="-1">
    <div class="icon"></div>
    <h4 id="popupTitle"></h4>
    <p id="popupMsg"></p>
    <div class="popup-actions">
      <button id="popupOk" class="popup-btn primary">حسنًا</button>
    </div>
  </div>
</div>
    <nav class="navbar">
        <h2>
            <svg class="icon-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            {{ __('messages.Digital_Bank') }}
        </h2>
        <div class="nav-links">
            <!-- Language Dropdown -->
            <div class="language-dropdown" id="languageDropdown">
                <button class="language-btn" onclick="toggleLanguageDropdown()">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                    </svg>
                    <svg class="icon" style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="language-dropdown-content">
                    <a href="{{ route('language.switch', 'ar') }}" class="{{ app()->getLocale() == 'ar' ? 'active' : '' }}">
                         {{ __('messages.arabic') }}
                    </a>
                    <a href="{{ route('language.switch', 'en') }}" class="{{ app()->getLocale() == 'en' ? 'active' : '' }}">
                        {{ __('messages.english') }}
                    </a>
                </div>
            </div>

            @auth
                <a href="{{ route('dashboard') }}">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    {{ __('messages.dashboard') }}
                </a>

                @permission('view_admin_dashboard')
                    <a href="{{ route('admin.dashboard') }}">
                        <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        {{ __('messages.admin_panel') }}
                    </a>
                @endpermission

                <a href="{{ route('cards.index') }}">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                    {{ __('messages.my_cards') }}
                </a>

                <a href="{{ route('settings.index') }}">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    {{ __('messages.settings') }}
                </a>

                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        {{ __('messages.logout') }}
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    {{ __('messages.login') }}
                </a>
                <a href="{{ route('register') }}" class="btn btn-primary">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                    {{ __('messages.register') }}
                </a>
            @endauth
        </div>
    </nav>

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">
                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-error">
                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        @endif
        @yield('content')
    </div>

    <script>
        function toggleLanguageDropdown() {
            document.getElementById('languageDropdown').classList.toggle('active');
        }

        // Close dropdown when clicking outside
        window.onclick = function(event) {
            if (!event.target.matches('.language-btn') && !event.target.closest('.language-btn')) {
                var dropdowns = document.getElementsByClassName("language-dropdown");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('active')) {
                        openDropdown.classList.remove('active');
                    }
                }
            }
        }
    </script>

<script>
(function(){
  function showPopup(type, title, msg){
    var overlay = document.getElementById('globalPopupOverlay');
    var card = document.getElementById('globalPopupCard');
    var icon = card.querySelector('.icon');
    var t = document.getElementById('popupTitle');
    var m = document.getElementById('popupMsg');
    card.classList.remove('success','error');
    card.classList.add(type || 'success');
    icon.innerHTML = type==='error'
      ? '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24"><path d="M12 2a10 10 0 1010 10A10.012 10.012 0 0012 2zm1 14h-2v-2h2zm0-4h-2V7h2z"/></svg>'
      : '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24"><path d="M12 2a10 10 0 1010 10A10.012 10.012 0 0012 2zm-1 15l-4-4 1.41-1.41L11 13.17l5.59-5.59L18 9z"/></svg>';
    t.textContent = title || (type==='error' ? 'فشلت العملية' : 'تمت العملية بنجاح');
    m.textContent = msg || '';
    overlay.style.display = 'flex';
    overlay.setAttribute('aria-hidden','false');
    card.focus();
    // close helpers
    function close(){ overlay.style.display='none'; overlay.setAttribute('aria-hidden','true'); }
    document.getElementById('popupOk').onclick = close;
    overlay.onclick = function(e){ if(e.target===overlay) close(); };
    document.addEventListener('keydown', function esc(e){ if(e.key==='Escape'){ close(); document.removeEventListener('keydown', esc);} });
    setTimeout(close, 3500); // auto close
  }

  // Pull flash messages from Laravel
  var popupType = null, popupMsg = null;
  try {
    popupType = {!! session('success') ? "'success'" : (session('error') || $errors->any() ? "'error'" : "null") !!};
    popupMsg = {!! json_encode(session('success') ?? session('error') ?? ($errors->first() ?? null), JSON_UNESCAPED_UNICODE) !!};
  } catch(e){}

  if (popupType && popupMsg) {
    window.addEventListener('DOMContentLoaded', function(){
      showPopup(popupType, null, popupMsg);
    });
  }

  // expose for manual use
  window.showPopup = showPopup;
})();
</script>

@stack('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
