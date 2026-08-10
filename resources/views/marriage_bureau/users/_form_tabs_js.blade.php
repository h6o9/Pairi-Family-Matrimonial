<script>
    function initMbUserFormTabs(initialTab) {
        var tabs = ['tab-basic', 'tab-education', 'tab-physical', 'tab-faith', 'tab-about'];
        var $saveNext = $('#btn-save-next');
        var $activeTab = $('#active_tab');
        var $saveAction = $('#save_action');

        function currentTabId() {
            var $active = $('.nav-tabs .nav-link.active');
            var href = $active.attr('href') || '';
            return href.replace('#', '') || 'tab-basic';
        }

        function syncButtons() {
            var tabId = currentTabId();
            $activeTab.val(tabId);
            var isLast = tabId === 'tab-about';
            $saveNext.toggle(!isLast);
        }

        function activateTab(tabId) {
            if (!tabId || tabs.indexOf(tabId) === -1) {
                tabId = 'tab-basic';
            }
            var trigger = document.querySelector('.nav-tabs a[href="#' + tabId + '"]');
            if (trigger) {
                if (typeof bootstrap !== 'undefined' && bootstrap.Tab) {
                    new bootstrap.Tab(trigger).show();
                } else {
                    $(trigger).tab('show');
                }
            }
            syncButtons();
        }

        // Tab header clicks stay native — only sync buttons
        $('a[data-bs-toggle="tab"], a[data-toggle="tab"]').on('shown.bs.tab', function () {
            syncButtons();
        });

        $('#btn-save, #btn-save-next').on('click', function () {
            $saveAction.val($(this).data('action'));
            $activeTab.val(currentTabId());
        });

        if (initialTab) {
            activateTab(initialTab);
        } else {
            syncButtons();
        }
    }
</script>
