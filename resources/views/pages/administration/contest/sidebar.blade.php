@php

$sidebar = [
    'overview' => [
        'icon' => 'fa fa-dashboard',
        'name' => 'Overview',
        'url' => route('administration.contest.overview', ['contest_id' => request()->contest_id]),
    ],
    'edit' => [
        'icon' => 'fa fa-dashboard',
        'name' => 'Edit',
        'url' => route('administration.contest.edit', ['contest_id' => request()->contest_id]),
    ],
    'problems' => [
        'icon' => 'fa fa-dashboard',
        'name' => 'Problems',
        'url' => route('administration.contest.problems', ['contest_id' => request()->contest_id]),
    ],
    'moderators' => [
        'icon' => 'fa fa-dashboard',
        'name' => 'Moderators',
        'url' => route('administration.contest.moderators', ['contest_id' => request()->contest_id]),
    ],
    'registrations' => [
        'icon' => 'fa fa-dashboard',
        'name' => 'Registrations',
        'url' => route('administration.contest.registrations', ['contest_id' => request()->contest_id]),
    ],
    'submissions' => [
        'icon' => 'fa fa-dashboard',
        'name' => 'Submissions',
        'url' => route('administration.contest.submissions', ['contest_id' => request()->contest_id]),
    ],
    'ranklist' => [
        'icon' => 'fa fa-dashboard',
        'name' => 'Ranklist',
        'url' => route("contest.arena.standings",['contest_slug' => $contest->slug])
    ],    
];

@endphp

<div class="box">
    <div class="header">Contest Dashboard</div>
    <div class="body" style="min-height: 300px;">
        @foreach ($sidebar as $key => $value)
            <a href="{{ $value['url'] }}">
                <button
                    class="panel-sidebar-btn sidebar-btn {{ Request::segment(4) == $key ? 'sidebar-btn-active' : '' }}">
                    <i class="{{ $value['icon'] }}"></i> {{ $value['name'] }}
                </button>
            </a>
        @endforeach
    </div>
</div>
