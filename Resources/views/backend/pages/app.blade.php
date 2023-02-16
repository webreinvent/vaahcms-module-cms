@extends("vaahcms::backend.vaahone.layouts.backend")

@section('vaahcms_extend_backend_css')

@endsection


@section('vaahcms_extend_backend_js')


    @if(env('MODULE_CMS_ENV') == 'develop')
        <script type="module" src="http://localhost:8367/Vue/main.js"></script>
    @else
        <script type="module" src="{{vh_module_assets_url("Cms", "build/index.js")}}"></script>
    @endif

@endsection

@section('content')

    <div id="appCms" class="bulma">

        <section class="section has-padding-25">
            <router-view></router-view>

            <vue-progress-bar></vue-progress-bar>
        </section>

    </div>

@endsection
