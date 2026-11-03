<!-- Client-Side Human Visitor Analytics (Eliminates Bots & Zero Page Load Latency) -->
<script>
  (function() {
    // 1. Immediately reject automated bot test runners & crawlers
    if (navigator.webdriver) return;

    try {
      var path = window.location.pathname.toLowerCase();
      var search = window.location.search.toLowerCase();
      var isService = path.indexOf('service') !== -1;
      var isAmc = path.indexOf('amc') !== -1 || search.indexOf('plan') !== -1 || search.indexOf('amc_plan') !== -1;

      // 2. Identify New vs Returning User via localStorage (persists across sessions)
      var isNewUser = false;
      if (!localStorage.getItem('fix_vst_uid')) {
        isNewUser = true;
        localStorage.setItem('fix_vst_uid', 'usr_' + Math.random().toString(36).substring(2) + Date.now().toString(36));
      }

      // 3. Visit Session tracking via sessionStorage (cleared on browser/tab close)
      // 15-minute inactivity window rule
      var now = Date.now();
      var lastActive = parseInt(sessionStorage.getItem('fix_vst_last_active') || '0', 10);
      var isSessionExpired = !lastActive || (now - lastActive) > 15 * 60 * 1000;
      var hasActiveSession = sessionStorage.getItem('fix_vst_session_id');

      var isNewVisit = false;
      if (!hasActiveSession || isSessionExpired) {
        isNewVisit = true;
        sessionStorage.setItem('fix_vst_session_id', 'sess_' + Math.random().toString(36).substring(2) + now.toString(36));
        sessionStorage.removeItem('fix_seen_srv');
        sessionStorage.removeItem('fix_seen_amc');
      }

      sessionStorage.setItem('fix_vst_last_active', now.toString());

      // 4. Section engagement tracking (at most once per visit session)
      var shouldTrackService = false;
      if (isService && !sessionStorage.getItem('fix_seen_srv')) {
        shouldTrackService = true;
        sessionStorage.setItem('fix_seen_srv', '1');
      }

      var shouldTrackAmc = false;
      if (isAmc && !sessionStorage.getItem('fix_seen_amc')) {
        shouldTrackAmc = true;
        sessionStorage.setItem('fix_seen_amc', '1');
      }

      // 5. Send ping ONLY when there is something to record
      if (isNewVisit || shouldTrackService || shouldTrackAmc) {
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
    } catch (e) {
      // Graceful silent fail
    }
  })();
</script>

