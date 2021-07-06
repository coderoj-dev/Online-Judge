@php

$sidebar = [
    'overview' => [
        'icon' => 'fa fa-dashboard',
        'name' => 'Overview',
        'count' => "", 
        'url' => route('administration.contest.overview', ['contest_id' => request()->contest_id]),
    ],
    'edit' => [
        'icon' => 'fa fa-pencil',
        'name' => 'Edit',
        'count' => "", 
        'url' => route('administration.contest.edit', ['contest_id' => request()->contest_id]),
    ],
    'problems' => [
        'icon' => 'fas fa-th-list',
        'name' => 'Problems',
        'count' => $contest->problems->count(), 
        'url' => route('administration.contest.problems', ['contest_id' => request()->contest_id]),
    ],
    'moderators' => [
        'icon' => 'fas fa-users',
        'name' => 'Moderators',
        'count' => $contest->moderator->count(), 
        'url' => route('administration.contest.moderators', ['contest_id' => request()->contest_id]),
    ],
    'registrations' => [
        'icon' => 'fas fa-list-alt',
        'name' => 'Registrations',
        'count' => $contest->registrations->count(), 
        'url' => route('administration.contest.registrations', ['contest_id' => request()->contest_id]),
    ],
    'submissions' => [
        'icon' => 'fas fa-hammer',
        'name' => 'Submissions',
        'count' => $contest->submissions->count(), 
        'url' => route('administration.contest.submissions', ['contest_id' => request()->contest_id]),
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
                    <span class="badge badge-primary pull-right">{{ $value['count'] }}</span>
                </button>
            </a>
        @endforeach
    </div>
</div>
