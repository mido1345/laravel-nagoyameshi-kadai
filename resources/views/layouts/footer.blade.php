<footer class="bg-light">
    <div class="d-flex justify-content-center nagoyameshi-footer-logo">
        <a class="navbar-brand nagoyameshi-app-name" href="{{ url('/') }}">
            <div class="d-flex align-items-center">
                <img class="nagoyameshi-logo me-1" src ="{{ asset('/images/logo.svg') }}" alt="nagoyameshi">
                NAGOYAMESHI
            </div>
        </a>
    </div>
    <div class="d-flex justify-content-center nagoyameshi-footer-link">
        @if (Auth::guard('admin')->check())
            <a href="{{ route('admin.company.index') }}" class="link-secondary me-3">会社概要</a>
            <a href="{{ route('admin.terms.index') }}" class="link-secondary">利用規約</a>
        @else
            <a href="{{ route('company.index') }}" class="link-secondary me-3">会社概要</a>
            <a href="{{ route('terms.index') }}" class="link-secondary">利用規約</a>
        @endif
    </div>
    <p class="text-center text-muted small mb-0">&copy; NAGOYAMESHI All rights reserved.</p>
</footer>