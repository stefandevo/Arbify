@extends('layout')

@section('content')
    <div class="container">
        <div class="d-flex mb-4 justify-content-between align-items-center">
            <h2>Languages</h2>
            @can('create', Arbify\Models\Language::class)
                <a href="{{ route('languages.create') }}" class="btn btn-primary">New language</a>
            @endcan
        </div>

        @if (session('success'))
            <div class="alert alert-success mb-4">
                {!! session('success') !!}
            </div>
        @endif

        <div class="table-responsive-sm">
            <table class="table table-bordered table-striped bg-white mb-4">
                <colgroup>
                    <col style="width: 60px">
                    <col style="width: 100px">
                    <col>
                    <col style="width: 200px">
                </colgroup>
                <thead>
                <tr>
                    <th>Flag</th>
                    <th>Code</th>
                    <th>Language</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($languages as $language)
                    <tr>
                        <td class="p-0 text-center align-middle">
                            @if(!is_null($language->flag))
                                <img src="{{ asset("storage/flags/$language->flag.svg") }}" alt="" class="country-flag">
                            @endif
                        </td>
                        <td class="align-middle">{{ $language->code }}</td>
                        <td>
                            {{ $language->name }}<br>

                            @if(count($language->plural_forms) > 0)
                                <small>
                                    <b>Plural forms:</b>
                                    @foreach($language->plural_forms as $form)
                                        <span class="badge badge-light">{{ strtoupper($form) }}</span>
                                    @endforeach
                                </small>
                            @endif
                        </td>
                        <td class="py-0 align-middle">
                            @can('update', $language)
                                <a href="{{ route('languages.edit', $language) }}">Edit</a>
                            @endcan
                            @can('delete', $language)
                                <form method="post" action="{{ route('languages.destroy', $language) }}" class="d-inline ml-2 delete-modal-show"
                                      data-delete-modal-title="Deleting language" data-delete-modal-body="<b>{{ $language->code }}</b>">
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-link btn-sm text-danger">Delete</button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">The list is empty.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center">
            {{ $languages->links() }}
        </div>
    </div>
@endsection
