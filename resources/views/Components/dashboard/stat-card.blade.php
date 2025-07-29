<div class="col-md-3 mb-3">
    <div class="card {{ $bgColor }} text-white h-100">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <div>
                    <h5 class="card-title">{{ $title }}</h5>
                    <h2 class="mb-0" id="{{ $id }}">{{ $value ?? 0 }}</h2>
                </div>
                <div class="align-self-center">
                    <i class="{{ $icon }} fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
        <div class="card-footer {{ $bgColor }} border-0">
            @if($link)
                <a href="{{ $link }}" class="text-white text-decoration-none">
                    {{ $footerText }} <i class="fas fa-arrow-right"></i>
                </a>
            @else
                <span class="text-white">{{ $footerText }}</span>
            @endif
        </div>
    </div>
</div>
