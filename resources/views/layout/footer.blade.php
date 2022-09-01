<!-- Layout Footer Start -->
<footer>
    <div class="footer-content">
        <div class="container">
            <div class="row">
                <div class="col-12 col-sm-6">
                    <p class="mb-0 text-muted text-medium">Mantiz Technology 2018</p>
                </div>
                <div class="col-sm-6 d-none d-sm-block">
                    <ul class="breadcrumb pt-0 pe-0 mb-0 float-end">
                        <li class="breadcrumb-item mb-0 text-medium">
                            <a href="https://www.youtube.com/channel/UC-eq0v9pdpjWCrOJ8SFgIkg" target="_blank" class="btn-link">
                                <i data-acorn-icon="youtube"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- Layout Footer End -->

@if (NULL !== Auth::user()->school_year_id)
<span class="badge logro-badge-right-bottom bg-pink position-fixed e-0 b-0 z-index-1">{{ Auth::user()->current_year->name }}</span>
@endif
