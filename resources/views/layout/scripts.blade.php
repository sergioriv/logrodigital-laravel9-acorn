<!-- Vendor Scripts Start -->
<script src="/js/vendor/jquery-3.5.1.min.js"></script>
<script src="/js/vendor/bootstrap.bundle.min.js"></script>
<script src="/js/vendor/OverlayScrollbars.min.js"></script>
<script src="/js/vendor/autoComplete.min.js"></script>
<script src="/js/vendor/clamp.min.js"></script>
<script src="/js/vendor/bootstrap-notify.min.js"></script>
<script src="/icon/acorn-icons.js"></script>
<script src="/icon/acorn-icons-interface.js"></script>
@yield('js_vendor')
<!-- Vendor Scripts End -->
<!-- Template Base Scripts Start -->
<script src="/js/base/helpers.js"></script>
<script src="/js/base/globals.js"></script>
<script src="/js/base/nav.js"></script>
<script src="/js/base/settings.js"></script>
<!-- Template Base Scripts End -->
<!-- Page Specific Scripts Start -->
<script src="/js/app.js?d=1666621931302"></script>
@auth
<x-notify-errors />
@endauth
@yield('js_page')
<script src="/js/common.js?d=1678306933740"></script>
<script src="/js/scripts.js?d=1678306933740"></script>
<!-- Page Specific Scripts End -->
