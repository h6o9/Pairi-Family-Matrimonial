(function () {
    'use strict';

    if (window.PiyariPasswordToggle) {
        window.PiyariPasswordToggle.enhance(document);
        return;
    }

    function createToggleButton() {
        var btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'password-toggle-btn';
        btn.setAttribute('aria-label', 'Show password');
        btn.setAttribute('tabindex', '0');
        btn.innerHTML = '<i class="fas fa-eye-slash" aria-hidden="true"></i>';
        return btn;
    }

    function removeLegacyToggles(scope) {
        (scope || document).querySelectorAll('.toggle-password, .password-toggle-wrapper > .password-toggle-btn').forEach(function (el) {
            // Remove only legacy buttons that sit outside our wrap
            if (!el.closest('.password-input-wrap')) {
                el.remove();
            }
        });

        (scope || document).querySelectorAll('.password-toggle-wrapper').forEach(function (legacyWrap) {
            var input = legacyWrap.querySelector('input');
            if (!input) {
                return;
            }
            // Flatten legacy wrapper so only one system remains
            while (legacyWrap.firstChild) {
                legacyWrap.parentNode.insertBefore(legacyWrap.firstChild, legacyWrap);
            }
            legacyWrap.remove();
        });
    }

    function enhancePasswordInputs(root) {
        var scope = root || document;
        removeLegacyToggles(scope);

        var inputs = scope.querySelectorAll('input[type="password"]');
        var enhanced = 0;

        inputs.forEach(function (input) {
            if (input.dataset.passwordToggleReady === '1' && input.closest('.password-input-wrap')) {
                var wrapReady = input.closest('.password-input-wrap');
                // Ensure exactly one toggle button
                var buttons = wrapReady.querySelectorAll('.password-toggle-btn');
                if (buttons.length === 0) {
                    wrapReady.appendChild(createToggleButton());
                } else if (buttons.length > 1) {
                    for (var i = 1; i < buttons.length; i++) {
                        buttons[i].remove();
                    }
                }
                return;
            }

            var existingWrap = input.closest('.password-input-wrap');
            if (existingWrap) {
                input.dataset.passwordToggleReady = '1';
                if (!existingWrap.querySelector('.password-toggle-btn')) {
                    existingWrap.appendChild(createToggleButton());
                }
                enhanced++;
                return;
            }

            var wrap = document.createElement('div');
            wrap.className = 'password-input-wrap';
            input.parentNode.insertBefore(wrap, input);
            wrap.appendChild(input);
            wrap.appendChild(createToggleButton());
            input.dataset.passwordToggleReady = '1';
            enhanced++;
        });

        // Remove any orphan toggle buttons outside wraps
        scope.querySelectorAll('.password-toggle-btn').forEach(function (btn) {
            if (!btn.closest('.password-input-wrap')) {
                btn.remove();
            }
        });

        return enhanced;
    }

    document.addEventListener('click', function (e) {
        var btn = e.target.closest('.password-toggle-btn');
        if (!btn) {
            return;
        }

        e.preventDefault();
        var wrap = btn.closest('.password-input-wrap');
        var input = wrap ? wrap.querySelector('input') : null;
        if (!input) {
            return;
        }

        var icon = btn.querySelector('i');
        var showing = input.type === 'password';
        input.type = showing ? 'text' : 'password';

        if (icon) {
            icon.classList.toggle('fa-eye-slash', !showing);
            icon.classList.toggle('fa-eye', showing);
        }

        btn.setAttribute('aria-label', showing ? 'Hide password' : 'Show password');

        // #region agent log
        fetch('http://127.0.0.1:7559/ingest/b21f2b75-4e25-47af-b7e2-c54d21d8b8b5', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-Debug-Session-Id': '64ce68' },
            body: JSON.stringify({
                sessionId: '64ce68',
                runId: 'post-fix',
                hypothesisId: 'P2',
                location: 'password-toggle.js:click',
                message: 'Password visibility toggled',
                data: { inputName: input.name || null, visible: showing },
                timestamp: Date.now()
            })
        }).catch(function () {});
        // #endregion
    });

    function logLayout(path) {
        var wraps = document.querySelectorAll('.password-input-wrap');
        var report = [];
        wraps.forEach(function (wrap, idx) {
            var btn = wrap.querySelector('.password-toggle-btn');
            var style = btn ? window.getComputedStyle(btn) : null;
            report.push({
                index: idx,
                buttonsInWrap: wrap.querySelectorAll('.password-toggle-btn').length,
                position: style ? style.position : null,
                top: style ? style.top : null,
                right: style ? style.right : null
            });
        });

        // #region agent log
        fetch('http://127.0.0.1:7559/ingest/b21f2b75-4e25-47af-b7e2-c54d21d8b8b5', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-Debug-Session-Id': '64ce68' },
            body: JSON.stringify({
                sessionId: '64ce68',
                runId: 'post-fix',
                hypothesisId: 'P2',
                location: 'password-toggle.js:init',
                message: 'Password toggle layout report',
                data: {
                    path: path,
                    wraps: wraps.length,
                    totalToggleBtns: document.querySelectorAll('.password-toggle-btn').length,
                    legacyBtns: document.querySelectorAll('.toggle-password').length,
                    report: report
                },
                timestamp: Date.now()
            })
        }).catch(function () {});
        // #endregion
    }

    function init() {
        enhancePasswordInputs(document);
        logLayout(window.location.pathname);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    window.PiyariPasswordToggle = { enhance: enhancePasswordInputs };
})();
