@use(function Skeleton\Support\vite)
{{ vite()->asset('admin/assets/ts/views/admin-menu/application.ts') }}

<div id="admin-menu">
    @yield('content')
</div>
