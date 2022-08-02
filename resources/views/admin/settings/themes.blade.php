@extends('admin.layouts.master')
@section('title', 'Theme Settings')

@section('page_content')

    <!-- Main content -->
    <div class="row">
        <div class="col-md-3 settings_bar_gap">
            @include('admin.common.settings_bar')
        </div>
        <div class="row">
            @foreach($themes as $theme)
            <div class="col-md-4">
                <div class="box box-info">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <img src="{{ url('resources/views/Themes/' . $theme['name'] . '/assets/thumbnail.png' ) }}" alt="..." class="img-thumbnail">
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <dl class="row">
                            <dt class="col-sm-3"><i>Description:</i></dt>
                            <dd class="col-sm-9">{{ $theme['description'] }}</dd>
                            <dt class="col-sm-3 margin-theme"><i>Author:</i></dt>
                            <dd class="col-sm-9 margin-theme">{{ $theme['author'] }}</dd>
                            <dt class="col-sm-3"><i>Version:</i></dt>
                            <dd class="col-sm-9">{{ $theme['version'] }}</dd>
                            <dt class="col-sm-3"><i>Parent:</i></dt>
                            <dd class="col-sm-9">{{ ucfirst($theme['parent']) }}</dd>
                            </dl>
                        <div class="row">
                            <div class="col-md-10">
                                <div class="top-bar-title padding-bottom pull-left active"> {{ ucfirst($theme['name']) }} Theme</div>
                            </div>
                            <div class="col-md-2">
                                <a href="{{ url(\Config::get('adminPrefix').'/settings/theme-set', $theme['name']) }}" class="btn btn-theme pull-right {{ ($theme['name'] == env('THEME') ? 'disabled' : '') }}">{{ ($theme['name'] == env('THEME') ? 'Activated' : 'Activate') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

@endsection

@push('extra_body_scripts')

@endpush
