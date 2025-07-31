<?php

namespace App\View\Components\Dashboard;

use Illuminate\View\Component;

class StatCard extends Component
{
    public function __construct(
        public string  $title,
        public ?string $value = "0",
        public string  $id,
        public string  $icon,
        public string  $bgColor = 'bg-primary',
        public string  $footerText = '',
        public ?string $link = null,
    )
    {
    }

    public function render()
    {
        return view('components.dashboard.stat-card');
    }
}
