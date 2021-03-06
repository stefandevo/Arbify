@extends('layout')

@section('content')
    <div class="container">
        <div class="d-flex mb-4 justify-content-between align-items-center">
            <h2>Projects</h2>
            @can('create', Arbify\Models\Project::class)
                <a href="{{ route('projects.create') }}" class="btn btn-primary">New project</a>
            @endcan
        </div>

        @if (session('success'))
            <div class="alert alert-success mb-4">
                {!! session('success') !!}
            </div>
        @endif

        <div class="row row-cols-1 row-cols-md-2 mb-4">
            @foreach($projects as $project)
                <div class="col mb-4">
                    <article class="card project-card">
                        <div class="card-body">
                            <header class="d-flex align-items-start mb-2">
                                <h3 class="h5 card-title mb-0">{{ $project->name }}</h3>
                                @if($project->isPrivate())
                                    <span class="badge badge-dark ml-2 align-self-center">Private</span>
                                @endif
                                @php
                                    $member = Auth::user()->projectMemberships->where('project_id', $project->id)->first();
                                @endphp
                                @if(!is_null($member))
                                    @if($member->isLead())
                                        <span class="badge badge-danger ml-auto">Lead</span>
                                    @elseif($member->isMember())
                                        <span class="badge badge-info ml-auto">Member</span>
                                    @elseif($member->isTranslator())
                                        <span class="badge badge-light ml-auto">Translator</span>
                                    @endif
                                @endif
                            </header>

                            <div class="progress project-card-progress">
                                @php $progress = $statistics[$project->id]['percent']; @endphp
                                <div class="progress-bar {{ $progress == 100 ? 'bg-success' : 'bg-info' }}"
                                     style="width: {{ $progress }}%"></div>
                            </div>

                            <div class="small text-muted mb-3">
                                @php
                                    $messages = $project->messages->count();
                                    $languages = $project->languages->count();
                                    $members = $project->projectMembers->count();
                                @endphp
                                {{ $messages . ' ' . Str::plural('message', $messages) }}
                                • {{ $languages . ' ' . Str::plural('language', $languages) }}
                                • {{ $members . ' ' . Str::plural('member', $members) }}
                            </div>

                            <ul class="flags-horizontal-list">
                                @foreach($project->languages as $language)
                                    @if(!is_null($language->flag))
                                        <li>
                                            <img src="{{ asset("storage/flags/$language->flag.svg") }}"
                                                 alt="{{ $language->getDisplayName() }}">
                                        </li>
                                    @endif
                                @endforeach
                            </ul>

                            <a class="stretched-link" href="{{ route('projects.show', $project) }}"></a>
                        </div>
                        <div class="card-footer d-flex flex-wrap px-3">
                            <a class="btn btn-sm btn-link mr-2"
                               href="{{ route('projects.show', $project) }}">Overview</a>
                            <a class="btn btn-sm btn-outline-primary mr-2"
                               href="{{ route('messages.index', $project) }}">Messages</a>
                            <a class="btn btn-sm btn-link mr-2" href="{{ route('project-languages.index', $project) }}">Languages</a>
                            @can('view-any', [Arbify\Models\ProjectMember::class, $project])
                                <a class="btn btn-sm btn-link" href="{{ route('project-members.index', $project) }}">Members</a>
                            @endcan
                            @canany(['update', 'delete'], $project)
                                <button class="btn btn-sm btn-link dropdown-toggle ml-auto" data-toggle="dropdown">
                                    Actions
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    @can('update', $project)
                                        <a class="dropdown-item" href="{{ route('projects.edit', $project) }}">Edit</a>
                                    @endcan
                                    @can('delete', $project)
                                        <form method="post" action="{{ route('projects.destroy', $project) }}"
                                              class="d-inline delete delete-modal-show"
                                              data-delete-modal-title="Deleting project"
                                              data-delete-modal-body="<b>{{ $project->name }}</b>">
                                            @csrf
                                            @method('DELETE')

                                            <button class="dropdown-item">Delete</button>
                                        </form>
                                    @endcan
                                </div>
                            @endcanany
                        </div>
                    </article>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center">
            {{ $projects->links() }}
        </div>
    </div>
@endsection
