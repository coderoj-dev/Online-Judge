@php

$sidebar = [
    'overview' => [
        'icon' => 'fa fa-dashboard',
        'name' => 'Overview',
        'url' => route('administration.problem.overview', ['slug' => request()->slug]),
        'callback' => '',
    ],
    'preview_problem' => [
        'icon' => 'fas fa-eye',
        'name' => 'Preview Problem',
        'url' => route('administration.problem.preview_problem', ['slug' => request()->slug]),
        'callback' => '',
    ],
    'statement' => [
        'icon' => 'fa fa-pencil',
        'name' => 'Statement',
        'url' => route('administration.problem.statement', ['slug' => request()->slug]),
        'callback' => '',
    ],
    'test_case' => [
        'icon' => 'fas fa-th-list',
        'name' => 'Test Case',
        'url' => route('administration.problem.test_case', ['slug' => request()->slug]),
        'callback' => '',
    ],
    'settings' => [
        'icon' => 'fa fa-cog',
        'name' => 'Settings',
        'url' => route('administration.problem.settings', ['slug' => request()->slug]),
        'callback' => '',
    ],
    'languages' => [
        'icon' => 'fas fa-code',
        'name' => 'Languages',
        'url' => route('administration.problem.languages', ['slug' => request()->slug]),
        'callback' => '',
    ],
    'moderators' => [
        'icon' => 'fas fa-users',
        'name' => 'Moderators',
        'url' => route('administration.problem.moderators',['slug' => request()->slug]),
        'callback' => '',
    ],
    'checker' => [
        'icon' => 'fas fa-list-alt',
        'name' => 'Checker',
        'url' => route('administration.problem.checker', ['slug' => request()->slug]),
        'callback' => '',
    ],
    'test_submissions' => [
            'icon'  => 'fas fa-hammer',
            'name' => 'Test Submissions',
            'url' => route('administration.problem.test_submissions',['slug' => request()->slug]),
            'callback' => ''
     ]
];

@endphp

<div class="box">
    <div class="header">Problem Dashboard</div>
    <div class="body" style="min-height: 300px;">
        @foreach ($sidebar as $key => $value)
            <a href="{{ $value['url'] }}" callback="{{ $value['callback'] }}">
                <button
                    class="panel-sidebar-btn sidebar-btn {{ Request::segment(4) == $key ? 'sidebar-btn-active' : '' }}">
                    <i class="{{ $value['icon'] }}"></i> {{ $value['name'] }}
                </button>
            </a>
        @endforeach
    </div>
</div>
