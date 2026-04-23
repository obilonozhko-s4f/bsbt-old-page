<?php
/**
 * -----------------------------------------------------------------------------
 * BSBT Cookie Banner
 * BSBT баннер cookies
 * -----------------------------------------------------------------------------
 * RU:
 * - Современный inline-баннер без зависимости от site_css/combine.php
 * - Поддержка категорий: Essential / Analytics / Maps
 * - Floating cookie button после закрытия баннера
 * - GA и Google Maps загружаются только после согласия
 *
 * EN:
 * - Modern inline banner without dependency on site_css/combine.php
 * - Category support: Essential / Analytics / Maps
 * - Floating cookie button after closing the banner
 * - GA and Google Maps load only after consent
 * -----------------------------------------------------------------------------
 */

$cookieLang = $this->lang->lang();

$texts = array(
  'de' => array(
    'title'            => 'Cookie-Einstellungen',
    'text'             => 'Wir verwenden Cookies und externe Dienste, um die Website sicher zu betreiben, Karten anzuzeigen und die Nutzung zu analysieren. Details finden Sie in unserer <a href="https://bs-travelling.com/terms/datenschutzerklrung" target="_blank">Datenschutzerklärung</a>.',
    'settings'         => 'Einstellungen',
    'accept_all'       => 'Alle akzeptieren',
    'reject_all'       => 'Alle ablehnen',
    'save'             => 'Auswahl speichern',
    'manage'           => 'Cookie-Einstellungen öffnen',
    'essential_title'  => 'Notwendig',
    'essential_text'   => 'Erforderlich für die Grundfunktionen der Website und die Speicherung Ihrer Datenschutzeinstellungen.',
    'analytics_title'  => 'Analytics',
    'analytics_text'   => 'Hilft uns zu verstehen, wie Besucher die Website nutzen (Google Analytics).',
    'maps_title'       => 'Google Maps',
    'maps_text'        => 'Erlaubt das Laden eingebetteter Karten und externer Google-Inhalte.',
  ),
  'en' => array(
    'title'            => 'Cookie settings',
    'text'             => 'We use cookies and external services to operate the website securely, display maps and analyze usage. For details, please see our <a href="https://bs-travelling.com/terms/datenschutzerklrung" target="_blank">Privacy Policy</a>.',
    'settings'         => 'Settings',
    'accept_all'       => 'Accept all',
    'reject_all'       => 'Reject all',
    'save'             => 'Save selection',
    'manage'           => 'Open cookie settings',
    'essential_title'  => 'Essential',
    'essential_text'   => 'Required for the core functionality of the website and storing your privacy preferences.',
    'analytics_title'  => 'Analytics',
    'analytics_text'   => 'Helps us understand how visitors use the website (Google Analytics).',
    'maps_title'       => 'Google Maps',
    'maps_text'        => 'Allows loading embedded maps and external Google content.',
  ),
  'ru' => array(
    'title'            => 'Настройки cookie',
    'text'             => 'Мы используем cookie и внешние сервисы для безопасной работы сайта, отображения карт и анализа посещаемости. Подробнее — в нашей <a href="https://bs-travelling.com/terms/datenschutzerklrung" target="_blank">Политике конфиденциальности</a>.',
    'settings'         => 'Настройки',
    'accept_all'       => 'Принять все',
    'reject_all'       => 'Отклонить все',
    'save'             => 'Сохранить выбор',
    'manage'           => 'Открыть настройки cookie',
    'essential_title'  => 'Необходимые',
    'essential_text'   => 'Нужны для базовой работы сайта и сохранения ваших настроек конфиденциальности.',
    'analytics_title'  => 'Аналитика',
    'analytics_text'   => 'Помогает понять, как посетители используют сайт (Google Analytics).',
    'maps_title'       => 'Google Maps',
    'maps_text'        => 'Разрешает загрузку встроенных карт и внешнего контента Google.',
  ),
);

if (!isset($texts[$cookieLang])) $cookieLang = 'en';
$t = $texts[$cookieLang];
?>

<style type="text/css">
/* -----------------------------------------------------------------------------
   RU: Базовый контейнер баннера
   EN: Main banner container
----------------------------------------------------------------------------- */
#bsbt-cookie-banner{
  position:fixed;
  right:24px;
  bottom:24px;
  width:100%;
  max-width:430px;
  z-index:99999;
  display:none;
  font-family:-apple-system,BlinkMacSystemFont,"SF Pro Text","Helvetica Neue",Arial,sans-serif;
}
#bsbt-cookie-banner.visible{
  display:block;
}

/* -----------------------------------------------------------------------------
   RU: Floating cookie button после закрытия баннера
   EN: Floating cookie button after banner is closed
----------------------------------------------------------------------------- */
#bsbt-cookie-fab{
  position:fixed;
  right:24px;
  bottom:24px;
  width:56px;
  height:56px;
  border:none;
  border-radius:50%;
  z-index:99998;
  display:none;
  cursor:pointer;
  background:rgba(18,18,18,.88);
  color:#fff;
  box-shadow:0 12px 30px rgba(0,0,0,.22);
  backdrop-filter:blur(20px);
  -webkit-backdrop-filter:blur(20px);
  transition:transform .2s ease, box-shadow .2s ease, opacity .2s ease;
}
#bsbt-cookie-fab.visible{
  display:block;
}
#bsbt-cookie-fab:hover{
  transform:translateY(-1px) scale(1.02);
  box-shadow:0 16px 36px rgba(0,0,0,.28);
}
#bsbt-cookie-fab span{
  display:block;
  width:100%;
  text-align:center;
  font-size:24px;
  line-height:56px;
}

/* -----------------------------------------------------------------------------
   RU: Основная карточка
   EN: Main card
----------------------------------------------------------------------------- */
#bsbt-cookie-card{
  background:rgba(255,255,255,.82);
  color:#111827;
  border:1px solid rgba(255,255,255,.55);
  border-radius:24px;
  overflow:hidden;
  box-shadow:
    0 20px 60px rgba(0,0,0,.16),
    0 8px 24px rgba(0,0,0,.08);
  backdrop-filter:blur(24px);
  -webkit-backdrop-filter:blur(24px);
}
#bsbt-cookie-head{
  padding:22px 22px 10px 22px;
}
#bsbt-cookie-title{
  margin:0;
  font-size:28px;
  line-height:1.1;
  letter-spacing:-0.02em;
  font-weight:700;
  color:#111827;
}
#bsbt-cookie-subtitle{
  margin:14px 0 0 0;
  font-size:15px;
  line-height:1.75;
  color:#4b5563;
}
#bsbt-cookie-subtitle a{
  color:#2563eb;
  text-decoration:none;
}
#bsbt-cookie-subtitle a:hover{
  text-decoration:underline;
}
#bsbt-cookie-body{
  padding:0 22px 22px 22px;
}

/* -----------------------------------------------------------------------------
   RU: Ряд верхних кнопок
   EN: Top action buttons row
----------------------------------------------------------------------------- */
#bsbt-cookie-actions-row{
  display:flex;
  align-items:center;
  gap:14px;
  margin-top:24px;
  flex-wrap:nowrap;
}
#bsbt-cookie-settings-toggle{
  appearance:none;
  border:none;
  background:rgba(15,23,42,.06);
  color:#111827;
  border-radius:18px;
  padding:16px 22px;
  font-size:14px;
  line-height:1;
  font-weight:600;
  cursor:pointer;
  transition:all .2s ease;
  white-space:nowrap;
  flex:0 0 auto;
}
#bsbt-cookie-settings-toggle:hover{
  background:rgba(15,23,42,.10);
}
.bsbt-cookie-btn{
  appearance:none;
  border:none;
  border-radius:18px;
  padding:16px 22px;
  font-size:14px;
  line-height:1;
  font-weight:600;
  cursor:pointer;
  transition:all .2s ease;
  white-space:nowrap;
  flex:0 0 auto;
}
.bsbt-cookie-btn:hover{
  transform:translateY(-1px);
}
.bsbt-cookie-btn-primary{
  background:#ACCF68;
  color:#111827;
  box-shadow:0 10px 24px rgba(172,207,104,.30);
}
.bsbt-cookie-btn-primary:hover{
  background:#9fc95a;
}
.bsbt-cookie-btn-secondary{
  background:rgba(15,23,42,.08);
  color:#111827;
}
.bsbt-cookie-btn-secondary:hover{
  background:rgba(15,23,42,.12);
}
.bsbt-cookie-btn-full{
  width:100%;
  justify-content:center;
}

/* -----------------------------------------------------------------------------
   RU: Контейнер расширенных настроек
   EN: Expanded settings container
----------------------------------------------------------------------------- */
#bsbt-cookie-settings{
  display:none;
  margin-top:18px;
  padding-top:18px;
  border-top:1px solid rgba(15,23,42,.08);
}
#bsbt-cookie-settings.visible{
  display:block;
}

/* -----------------------------------------------------------------------------
   RU: Строка одной категории
   EN: Single category row
----------------------------------------------------------------------------- */
.bsbt-cookie-row{
  display:flex;
  align-items:flex-start;
  justify-content:space-between;
  gap:16px;
  padding:16px 0;
  border-bottom:1px solid rgba(15,23,42,.06);
}
.bsbt-cookie-row:last-child{
  border-bottom:none;
  padding-bottom:4px;
}
.bsbt-cookie-row-text{
  flex:1 1 auto;
}
.bsbt-cookie-row-title{
  display:block;
  margin:0 0 6px 0;
  font-size:15px;
  font-weight:600;
  color:#111827;
}
.bsbt-cookie-row-desc{
  margin:0;
  font-size:13px;
  line-height:1.65;
  color:#6b7280;
}

/* -----------------------------------------------------------------------------
   RU: Тумблер Apple-like
   EN: Apple-like switch
----------------------------------------------------------------------------- */
.bsbt-cookie-switch{
  position:relative;
  width:52px;
  min-width:52px;
  height:32px;
  margin-top:2px;
}
.bsbt-cookie-switch input{
  display:none;
}
.bsbt-cookie-slider{
  position:absolute;
  inset:0;
  background:#d1d5db;
  border-radius:999px;
  transition:all .2s ease;
}
.bsbt-cookie-slider:before{
  content:"";
  position:absolute;
  width:28px;
  height:28px;
  left:2px;
  top:2px;
  background:#fff;
  border-radius:50%;
  box-shadow:0 2px 6px rgba(0,0,0,.18);
  transition:all .2s ease;
}
.bsbt-cookie-switch input:checked + .bsbt-cookie-slider{
  background:#34c759;
}
.bsbt-cookie-switch input:checked + .bsbt-cookie-slider:before{
  transform:translateX(20px);
}
.bsbt-cookie-switch.is-disabled{
  opacity:.7;
  pointer-events:none;
}

/* -----------------------------------------------------------------------------
   RU: Нижняя панель сохранения
   EN: Save footer
----------------------------------------------------------------------------- */
#bsbt-cookie-footer{
  margin-top:18px;
}

/* -----------------------------------------------------------------------------
   RU: Мобильная адаптация
   EN: Mobile adjustments
----------------------------------------------------------------------------- */
@media (max-width: 640px){
  #bsbt-cookie-banner{
    right:12px;
    left:12px;
    bottom:12px;
    max-width:none;
    width:auto;
  }
  #bsbt-cookie-fab{
    right:12px;
    bottom:12px;
  }
  #bsbt-cookie-title{
    font-size:24px;
  }
  #bsbt-cookie-actions-row{
    flex-direction:column;
    align-items:stretch;
    gap:10px;
  }
  #bsbt-cookie-settings-toggle,
  .bsbt-cookie-btn{
    width:100%;
  }
}
</style>

<div id="bsbt-cookie-banner" role="dialog" aria-label="Cookie Notice">
  <div id="bsbt-cookie-card">
    <div id="bsbt-cookie-head">
      <h3 id="bsbt-cookie-title"><?= $t['title']; ?></h3>
      <p id="bsbt-cookie-subtitle"><?= $t['text']; ?></p>
    </div>

    <div id="bsbt-cookie-body">
      <div id="bsbt-cookie-actions-row">
        <button type="button" id="bsbt-cookie-settings-toggle"><?= $t['settings']; ?></button>
        <button class="bsbt-cookie-btn bsbt-cookie-btn-primary" id="bsbt-cookie-accept-all"><?= $t['accept_all']; ?></button>
        <button class="bsbt-cookie-btn bsbt-cookie-btn-secondary" id="bsbt-cookie-reject-all"><?= $t['reject_all']; ?></button>
      </div>

      <div id="bsbt-cookie-settings">
        <div class="bsbt-cookie-row">
          <div class="bsbt-cookie-row-text">
            <span class="bsbt-cookie-row-title"><?= $t['essential_title']; ?></span>
            <p class="bsbt-cookie-row-desc"><?= $t['essential_text']; ?></p>
          </div>
          <label class="bsbt-cookie-switch is-disabled">
            <input type="checkbox" checked="checked" disabled="disabled" />
            <span class="bsbt-cookie-slider"></span>
          </label>
        </div>

        <div class="bsbt-cookie-row">
          <div class="bsbt-cookie-row-text">
            <span class="bsbt-cookie-row-title"><?= $t['analytics_title']; ?></span>
            <p class="bsbt-cookie-row-desc"><?= $t['analytics_text']; ?></p>
          </div>
          <label class="bsbt-cookie-switch">
            <input type="checkbox" id="bsbt-cookie-analytics" />
            <span class="bsbt-cookie-slider"></span>
          </label>
        </div>

        <div class="bsbt-cookie-row">
          <div class="bsbt-cookie-row-text">
            <span class="bsbt-cookie-row-title"><?= $t['maps_title']; ?></span>
            <p class="bsbt-cookie-row-desc"><?= $t['maps_text']; ?></p>
          </div>
          <label class="bsbt-cookie-switch">
            <input type="checkbox" id="bsbt-cookie-maps" />
            <span class="bsbt-cookie-slider"></span>
          </label>
        </div>

        <div id="bsbt-cookie-footer">
          <button class="bsbt-cookie-btn bsbt-cookie-btn-primary bsbt-cookie-btn-full" id="bsbt-cookie-save"><?= $t['save']; ?></button>
        </div>
      </div>
    </div>
  </div>
</div>

<button type="button" id="bsbt-cookie-fab" aria-label="<?= htmlspecialchars($t['manage']); ?>" title="<?= htmlspecialchars($t['manage']); ?>">
  <span>🍪</span>
</button>

<script type="text/javascript">
(function() {
  /**
   * ---------------------------------------------------------------------------
   * RU: Основные константы
   * EN: Main constants
   * ---------------------------------------------------------------------------
   */
  var COOKIE_NAME = 'bsbt_cookie_consent';
  var COOKIE_DAYS = 365;

  /**
   * ---------------------------------------------------------------------------
   * RU: Чтение cookie
   * EN: Read cookie
   * ---------------------------------------------------------------------------
   */
  function getCookie(name) {
    var match = document.cookie.match(new RegExp('(^|; )' + name + '=([^;]*)'));
    return match ? decodeURIComponent(match[2]) : null;
  }

  /**
   * ---------------------------------------------------------------------------
   * RU: Сохранение cookie
   * EN: Write cookie
   * ---------------------------------------------------------------------------
   */
  function setCookie(name, value, days) {
    var expires = new Date();
    expires.setTime(expires.getTime() + days * 24 * 60 * 60 * 1000);
    document.cookie = name + '=' + encodeURIComponent(value) + '; expires=' + expires.toUTCString() + '; path=/; SameSite=Lax';
  }

  /**
   * ---------------------------------------------------------------------------
   * RU: Ленивая загрузка Google Analytics
   * EN: Lazy-load Google Analytics
   * ---------------------------------------------------------------------------
   */
  function loadGA() {
    if (window._ga_loaded) return;
    window._ga_loaded = true;

    window._gaq = window._gaq || [];
    window._gaq.push(['_setAccount', 'UA-35054470-1']);
    window._gaq.push(['_trackPageview']);

    var ga = document.createElement('script');
    ga.type = 'text/javascript';
    ga.async = true;
    ga.src = (document.location.protocol == 'https:' ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';

    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(ga, s);
  }

  /**
   * ---------------------------------------------------------------------------
   * RU: Применение согласия к сервисам
   * EN: Apply consent to services
   * ---------------------------------------------------------------------------
   */
  function applyConsent(consent) {
    if (consent.analytics) {
      loadGA();
    }
    if (window.bsbtApplyMapConsent) {
      window.bsbtApplyMapConsent(consent.maps ? 'granted' : 'denied');
    }
  }

  /**
   * ---------------------------------------------------------------------------
   * RU: Скрыть баннер и показать floating button
   * EN: Hide banner and show floating button
   * ---------------------------------------------------------------------------
   */
  function hideBanner() {
    banner.className = banner.className.replace(/\bvisible\b/g, '').replace(/\s+/g, ' ').replace(/^\s+|\s+$/g, '');
    banner.style.display = 'none';
    fab.className = 'visible';
  }

  /**
   * ---------------------------------------------------------------------------
   * RU: Показать баннер и скрыть floating button
   * EN: Show banner and hide floating button
   * ---------------------------------------------------------------------------
   */
  function showBanner() {
    banner.style.display = 'block';
    if (banner.className.indexOf('visible') === -1) {
      banner.className += ' visible';
    }
    fab.className = fab.className.replace(/\bvisible\b/g, '').replace(/\s+/g, ' ').replace(/^\s+|\s+$/g, '');
  }

  /**
   * ---------------------------------------------------------------------------
   * RU: Сохранить согласие
   * EN: Save consent
   * ---------------------------------------------------------------------------
   */
  function saveConsent(consent) {
    setCookie(COOKIE_NAME, JSON.stringify(consent), COOKIE_DAYS);
    applyConsent(consent);
    hideBanner();
  }

  /**
   * ---------------------------------------------------------------------------
   * RU: Прочитать согласие
   * EN: Read consent
   * ---------------------------------------------------------------------------
   */
  function readConsent() {
    var raw = getCookie(COOKIE_NAME);
    if (!raw) return null;
    try {
      return JSON.parse(raw);
    } catch(e) {
      if (raw === 'accepted') {
        return {essential:true, analytics:true, maps:true};
      }
      if (raw === 'declined') {
        return {essential:true, analytics:false, maps:false};
      }
      return null;
    }
  }

  /**
   * ---------------------------------------------------------------------------
   * RU: Открыть/закрыть блок настроек
   * EN: Open/close settings panel
   * ---------------------------------------------------------------------------
   */
  function toggleSettings(forceOpen) {
    var hasVisible = settings.className.indexOf('visible') !== -1;
    if (forceOpen === true || !hasVisible) {
      settings.className = 'visible';
      return;
    }
    settings.className = settings.className.replace(/\bvisible\b/g, '').replace(/\s+/g, ' ').replace(/^\s+|\s+$/g, '');
  }

  /**
   * ---------------------------------------------------------------------------
   * RU: DOM-элементы
   * EN: DOM elements
   * ---------------------------------------------------------------------------
   */
  var banner = document.getElementById('bsbt-cookie-banner');
  var fab = document.getElementById('bsbt-cookie-fab');
  var settings = document.getElementById('bsbt-cookie-settings');
  var settingsToggle = document.getElementById('bsbt-cookie-settings-toggle');
  var btnAcceptAll = document.getElementById('bsbt-cookie-accept-all');
  var btnRejectAll = document.getElementById('bsbt-cookie-reject-all');
  var btnSave = document.getElementById('bsbt-cookie-save');
  var analyticsInput = document.getElementById('bsbt-cookie-analytics');
  var mapsInput = document.getElementById('bsbt-cookie-maps');

  /**
   * ---------------------------------------------------------------------------
   * RU: Начальная инициализация
   * EN: Initial boot
   * ---------------------------------------------------------------------------
   */
  var consent = readConsent();

  if (!consent) {
    showBanner();
  } else {
    analyticsInput.checked = !!consent.analytics;
    mapsInput.checked = !!consent.maps;
    applyConsent(consent);
    fab.className = 'visible';
  }

  /**
   * ---------------------------------------------------------------------------
   * RU: Кнопка настроек внутри баннера
   * EN: Settings button inside banner
   * ---------------------------------------------------------------------------
   */
  settingsToggle.onclick = function() {
    toggleSettings();
  };

  /**
   * ---------------------------------------------------------------------------
   * RU: Floating cookie button
   * EN: Floating cookie button
   * ---------------------------------------------------------------------------
   */
  fab.onclick = function() {
    var currentConsent = readConsent();
    if (currentConsent) {
      analyticsInput.checked = !!currentConsent.analytics;
      mapsInput.checked = !!currentConsent.maps;
    }
    showBanner();
    toggleSettings(true);
  };

  /**
   * ---------------------------------------------------------------------------
   * RU: Принять все
   * EN: Accept all
   * ---------------------------------------------------------------------------
   */
  btnAcceptAll.onclick = function() {
    analyticsInput.checked = true;
    mapsInput.checked = true;
    saveConsent({essential:true, analytics:true, maps:true});
  };

  /**
   * ---------------------------------------------------------------------------
   * RU: Отклонить все необязательные
   * EN: Reject all optional
   * ---------------------------------------------------------------------------
   */
  btnRejectAll.onclick = function() {
    analyticsInput.checked = false;
    mapsInput.checked = false;
    saveConsent({essential:true, analytics:false, maps:false});
  };

  /**
   * ---------------------------------------------------------------------------
   * RU: Сохранить ручной выбор
   * EN: Save custom selection
   * ---------------------------------------------------------------------------
   */
  btnSave.onclick = function() {
    saveConsent({
      essential:true,
      analytics:!!analyticsInput.checked,
      maps:!!mapsInput.checked
    });
  };
})();
</script>