<!-- Cookie Consent Banner -->
<div class="cookie-consent" id="cookieConsent">
    <div class="cookie-consent-box">
        <p class="cookie-consent-text">Allowing cookies will improve your experience on this website.</p>
        <div class="cookie-consent-actions">
            <button class="cookie-btn cookie-btn-outline" id="cookieReject">Reject</button>
            <button class="cookie-btn cookie-btn-outline" id="cookieCustomize">Customize preferences</button>
        </div>
        <button class="cookie-btn cookie-btn-accept" id="cookieAccept">Accept cookies</button>
    </div>
</div>

<!-- Cookie Preferences Modal -->
<div class="cookie-prefs-overlay" id="cookiePrefsOverlay">
    <div class="cookie-prefs-modal">
        <div class="cookie-prefs-header">
            <h4>Cookie Preferences</h4>
            <button class="cookie-prefs-close" id="cookiePrefsClose">&times;</button>
        </div>
        <div class="cookie-prefs-body">
            <div class="cookie-pref-item">
                <div class="cookie-pref-info">
                    <strong>Essential Cookies</strong>
                    <p>Required for the website to function. Cannot be disabled.</p>
                </div>
                <label class="cookie-toggle">
                    <input type="checkbox" checked disabled>
                    <span class="cookie-toggle-slider"></span>
                </label>
            </div>
            <div class="cookie-pref-item">
                <div class="cookie-pref-info">
                    <strong>Analytics Cookies</strong>
                    <p>Help us understand how visitors use our website.</p>
                </div>
                <label class="cookie-toggle">
                    <input type="checkbox" id="cookiePrefAnalytics" checked>
                    <span class="cookie-toggle-slider"></span>
                </label>
            </div>
            <div class="cookie-pref-item">
                <div class="cookie-pref-info">
                    <strong>Marketing Cookies</strong>
                    <p>Used to deliver relevant advertisements.</p>
                </div>
                <label class="cookie-toggle">
                    <input type="checkbox" id="cookiePrefMarketing" checked>
                    <span class="cookie-toggle-slider"></span>
                </label>
            </div>
        </div>
        <div class="cookie-prefs-footer">
            <button class="cookie-btn cookie-btn-accept" id="cookiePrefsSave">Save preferences</button>
        </div>
    </div>
</div>

<style>
/* Cookie Consent Banner */
.cookie-consent {
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100%;
    z-index: 99999;
    display: none;
    justify-content: center;
    padding: 16px;
    pointer-events: none;
}
.cookie-consent.active {
    display: flex;
}
.cookie-consent-box {
    background: #1a1a1a;
    border-radius: 14px;
    padding: 24px;
    max-width: 520px;
    width: 100%;
    pointer-events: all;
    box-shadow: 0 -4px 30px rgba(0, 0, 0, 0.3);
    animation: cookieSlideUp 0.4s ease;
}
.cookie-consent-text {
    color: #fff;
    font-size: 15px;
    font-weight: 600;
    margin-bottom: 16px;
    line-height: 1.4;
}
.cookie-consent-actions {
    display: flex;
    gap: 10px;
    margin-bottom: 10px;
}
.cookie-btn {
    border: none;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    border-radius: 8px;
    padding: 12px 20px;
    transition: all 0.2s ease;
    font-family: inherit;
}
.cookie-btn-outline {
    background: transparent;
    border: 1.5px solid #fff;
    color: #fff;
    flex: 1;
}
.cookie-btn-outline:hover {
    background: rgba(255, 255, 255, 0.1);
}
.cookie-btn-accept {
    background: #fff;
    color: #1a1a1a;
    width: 100%;
    font-weight: 600;
}
.cookie-btn-accept:hover {
    background: #e0e0e0;
}

@keyframes cookieSlideUp {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Cookie Preferences Modal */
.cookie-prefs-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(4px);
    z-index: 100000;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 16px;
}
.cookie-prefs-overlay.active {
    display: flex;
}
.cookie-prefs-modal {
    background: #1a1a1a;
    border-radius: 14px;
    max-width: 460px;
    width: 100%;
    overflow: hidden;
    animation: cookieSlideUp 0.3s ease;
}
.cookie-prefs-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 24px;
    border-bottom: 1px solid #333;
}
.cookie-prefs-header h4 {
    color: #fff;
    margin: 0;
    font-size: 17px;
    font-weight: 600;
}
.cookie-prefs-close {
    background: none;
    border: none;
    color: #999;
    font-size: 24px;
    cursor: pointer;
    padding: 0;
    line-height: 1;
}
.cookie-prefs-close:hover {
    color: #fff;
}
.cookie-prefs-body {
    padding: 16px 24px;
}
.cookie-pref-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 0;
    border-bottom: 1px solid #2a2a2a;
}
.cookie-pref-item:last-child {
    border-bottom: none;
}
.cookie-pref-info {
    flex: 1;
    padding-right: 16px;
}
.cookie-pref-info strong {
    color: #fff;
    font-size: 14px;
    display: block;
    margin-bottom: 3px;
}
.cookie-pref-info p {
    color: #888;
    font-size: 12px;
    margin: 0;
    line-height: 1.4;
}
/* Toggle Switch */
.cookie-toggle {
    position: relative;
    display: inline-block;
    width: 44px;
    height: 24px;
    flex-shrink: 0;
}
.cookie-toggle input {
    opacity: 0;
    width: 0;
    height: 0;
}
.cookie-toggle-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: #444;
    border-radius: 24px;
    transition: 0.3s;
}
.cookie-toggle-slider:before {
    content: "";
    position: absolute;
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background: #fff;
    border-radius: 50%;
    transition: 0.3s;
}
.cookie-toggle input:checked + .cookie-toggle-slider {
    background: #10847E;
}
.cookie-toggle input:checked + .cookie-toggle-slider:before {
    transform: translateX(20px);
}
.cookie-toggle input:disabled + .cookie-toggle-slider {
    opacity: 0.6;
    cursor: not-allowed;
}
.cookie-prefs-footer {
    padding: 16px 24px;
    border-top: 1px solid #333;
}

/* Responsive */
@media (max-width: 576px) {
    .cookie-consent {
        padding: 10px;
    }
    .cookie-consent-box {
        padding: 18px;
        border-radius: 12px;
    }
    .cookie-consent-text {
        font-size: 14px;
        margin-bottom: 14px;
    }
    .cookie-btn {
        font-size: 13px;
        padding: 10px 14px;
    }
    .cookie-prefs-modal {
        max-width: 100%;
    }
}
</style>

<script>
(function() {
    var COOKIE_KEY = 'cookie_consent';
    var banner = document.getElementById('cookieConsent');
    var acceptBtn = document.getElementById('cookieAccept');
    var rejectBtn = document.getElementById('cookieReject');
    var customizeBtn = document.getElementById('cookieCustomize');
    var prefsOverlay = document.getElementById('cookiePrefsOverlay');
    var prefsCloseBtn = document.getElementById('cookiePrefsClose');
    var prefsSaveBtn = document.getElementById('cookiePrefsSave');

    function getConsent() {
        try { return JSON.parse(localStorage.getItem(COOKIE_KEY)); } catch(e) { return null; }
    }

    function setConsent(val) {
        localStorage.setItem(COOKIE_KEY, JSON.stringify(val));
        banner.classList.remove('active');
        prefsOverlay.classList.remove('active');
    }

    // Show banner if no consent stored
    if (!getConsent()) {
        setTimeout(function() { banner.classList.add('active'); }, 800);
    }

    acceptBtn.addEventListener('click', function() {
        setConsent({ essential: true, analytics: true, marketing: true, timestamp: Date.now() });
    });

    rejectBtn.addEventListener('click', function() {
        setConsent({ essential: true, analytics: false, marketing: false, timestamp: Date.now() });
    });

    customizeBtn.addEventListener('click', function() {
        prefsOverlay.classList.add('active');
    });

    prefsCloseBtn.addEventListener('click', function() {
        prefsOverlay.classList.remove('active');
    });

    prefsOverlay.addEventListener('click', function(e) {
        if (e.target === prefsOverlay) prefsOverlay.classList.remove('active');
    });

    prefsSaveBtn.addEventListener('click', function() {
        var analytics = document.getElementById('cookiePrefAnalytics').checked;
        var marketing = document.getElementById('cookiePrefMarketing').checked;
        setConsent({ essential: true, analytics: analytics, marketing: marketing, timestamp: Date.now() });
    });
})();
</script>
