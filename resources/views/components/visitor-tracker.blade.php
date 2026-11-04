<!-- Client-Side Human Visitor Analytics (Eliminates Bots, Headless Crawlers & Zero Page Latency) -->
<script>
  (function() {
    // 1. Immediate rejection of automated bot test runners & headless crawlers
    if (navigator.webdriver) return;
    if (window.outerWidth === 0 && window.outerHeight === 0) return;
    if (!navigator.languages || navigator.languages.length === 0) return;

    try {
      var path = window.location.pathname.toLowerCase();
      var search = window.location.search.toLowerCase();
      var isService = path.indexOf('service') !== -1;
      var isAmc = path.indexOf('amc') !== -1 || search.indexOf('plan') !== -1 || search.indexOf('amc_plan') !== -1;

      // 2. Identify New vs Returning User via localStorage (persists across sessions)
      var isNewUser = false;
      var userId = localStorage.getItem('fix_vst_uid');
      if (!userId) {
        isNewUser = true;
        userId = 'usr_' + Math.random().toString(36).substring(2) + Date.now().toString(36);
      }

      // 3. Visit Session tracking via sessionStorage (cleared on browser/tab close)
      // 15-minute inactivity window rule
      var now = Date.now();
      var lastActive = parseInt(sessionStorage.getItem('fix_vst_last_active') || '0', 10);
      var isSessionExpired = !lastActive || (now - lastActive) > 15 * 60 * 1000;
      var hasActiveSession = sessionStorage.getItem('fix_vst_session_id');

      var isNewVisit = false;
      var sessionId = hasActiveSession;
      if (!hasActiveSession || isSessionExpired) {
        isNewVisit = true;
        sessionId = 'sess_' + Math.random().toString(36).substring(2) + now.toString(36);
      }

      // 4. Section engagement tracking (at most once per visit session)
      var shouldTrackService = isService && !sessionStorage.getItem('fix_seen_srv');
      var shouldTrackAmc = isAmc && !sessionStorage.getItem('fix_seen_amc');

      // 5. Exit early if there is nothing new to record
      if (!isNewVisit && !shouldTrackService && !shouldTrackAmc) {
        sessionStorage.setItem('fix_vst_last_active', now.toString());
        return;
      }

      // 6. Human Engagement Filter:
      // Real humans scroll, move the mouse, touch the screen, or spend > 3 seconds on the page.
      // Automated scrapers & bots exit immediately within < 500ms without touching the DOM.
      var engaged = false;
      var dwellTimer = null;

      function cleanupListeners() {
        if (dwellTimer) clearTimeout(dwellTimer);
        window.removeEventListener('scroll', onHumanEngaged, { passive: true });
        window.removeEventListener('mousemove', onHumanEngaged, { passive: true });
        window.removeEventListener('touchstart', onHumanEngaged, { passive: true });
        window.removeEventListener('click', onHumanEngaged, { passive: true });
        window.removeEventListener('keydown', onHumanEngaged, { passive: true });
      }

      function onHumanEngaged() {
        if (engaged) return;
        engaged = true;
        cleanupListeners();

        // Commit storage tokens only upon verified human engagement
        if (isNewUser) {
          localStorage.setItem('fix_vst_uid', userId);
        }
        if (isNewVisit) {
          sessionStorage.setItem('fix_vst_session_id', sessionId);
          sessionStorage.removeItem('fix_seen_srv');
          sessionStorage.removeItem('fix_seen_amc');
        }
        sessionStorage.setItem('fix_vst_last_active', Date.now().toString());

        if (shouldTrackService) {
          sessionStorage.setItem('fix_seen_srv', '1');
        }
        if (shouldTrackAmc) {
          sessionStorage.setItem('fix_seen_amc', '1');
        }

        // Dispatch lightweight asynchronous ping
        var payload = JSON.stringify({
          is_new_visit: isNewVisit,
          is_new_user: isNewVisit ? isNewUser : false,
          is_service: shouldTrackService,
          is_amc: shouldTrackAmc
        });

        if (navigator.sendBeacon) {
          var blob = new Blob([payload], { type: 'application/json' });
          navigator.sendBeacon('/analytics/ping', blob);
        } else {
          fetch('/analytics/ping', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            body: payload,
            keepalive: true
          }).catch(function() {});
        }
      }

      // Listen for genuine human interaction events
      window.addEventListener('scroll', onHumanEngaged, { passive: true, once: true });
      window.addEventListener('mousemove', onHumanEngaged, { passive: true, once: true });
      window.addEventListener('touchstart', onHumanEngaged, { passive: true, once: true });
      window.addEventListener('click', onHumanEngaged, { passive: true, once: true });
      window.addEventListener('keydown', onHumanEngaged, { passive: true, once: true });

      // Fallback: 3-second active dwell time (for reading without mouse/touch interaction)
      dwellTimer = setTimeout(function() {
        if (document.visibilityState === 'visible') {
          onHumanEngaged();
        } else {
          document.addEventListener('visibilitychange', function onVisible() {
            if (document.visibilityState === 'visible') {
              document.removeEventListener('visibilitychange', onVisible);
              onHumanEngaged();
            }
          });
        }
      }, 3000);

    } catch (e) {
      // Graceful silent fail
    }
  })();
</script>

