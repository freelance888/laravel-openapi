<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/swagger-ui/{{ $openapi_ui_version }}/swagger-ui.css" />
    <link rel="icon" href="/favicon.ico" />
    <style>
        html
        {
            box-sizing: border-box;
            overflow: -moz-scrollbars-vertical;
            overflow-y: scroll;
        }

        *,
        *:before,
        *:after
        {
            box-sizing: inherit;
        }

        body
        {
            margin:0;
            background: #fafafa;
        }
    </style>
</head>

<body>
<div id="swagger-ui"></div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/swagger-ui/{{ $openapi_ui_version }}/swagger-ui-bundle.js" charset="UTF-8"> </script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/swagger-ui/{{ $openapi_ui_version }}/swagger-ui-standalone-preset.js" charset="UTF-8"> </script>
<script>
    window.onload = function() {
        // Begin Swagger UI call region
        const ui = SwaggerUIBundle({
            spec: @json($openapi_data),
            dom_id: '#swagger-ui',
            deepLinking: true,
            presets: [
                SwaggerUIBundle.presets.apis,
                SwaggerUIStandalonePreset
            ],
            plugins: [
                SwaggerUIBundle.plugins.DownloadUrl
            ],
            layout: "StandaloneLayout"
        });
        // End Swagger UI call region

        window.ui = ui;
    };
</script>
</body>
</html>
