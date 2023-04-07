<!doctype html>
<html lang="en">

<head {{ language_attributes() }}>
  <meta charset="{{ bloginfo('charset') }}">
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  {{ wp_head() }}

  @stack('scripts')
</head>

<body {{ body_class() }}>
  {{ wp_body_open() }}
  @yield('content')
</body>

{{ wp_footer() }}
</html>
