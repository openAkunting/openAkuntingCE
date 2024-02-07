<!-- HTML for static distribution bundle build -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Swagger UI</title>
    <link rel="stylesheet" type="text/css" href="./swagger-ui/swagger-ui.css" />
    <link rel="stylesheet" type="text/css" href="./swagger-ui/index.css" />
    <link rel="icon" type="image/png" href="./swagger-ui/favicon-32x32.png" sizes="32x32" />
    <link rel="icon" type="image/png" href="./swagger-ui/favicon-16x16.png" sizes="16x16" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-2 ">
                <div class="sticky-top">
                    <strong>Collection</strong>
                    <div>
                        <ul>
                            <li> <a href="?collection=auth">Auth</a></li>
                            <li> <a href="?collection=masterData">Master Data</a></li>
                            
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-10">
                <div id="swagger-ui"></div>
            </div>
        </div>
    </div>

    <script src="./swagger-ui/swagger-ui-bundle.js" charset="UTF-8"> </script>
    <script src="./swagger-ui/swagger-ui-standalone-preset.js" charset="UTF-8"> </script>
    <script>
        window.onload = function () {
            //<editor-fold desc="Changeable Configuration Block"> 
            // the following lines will be replaced by docker/configurator, when it runs in a docker-container
            window.ui = SwaggerUIBundle({
                url: "./json/?collection=<?php echo $_GET['collection']?>",
                dom_id: '#swagger-ui',
                deepLinking: true,
                presets: [
                    SwaggerUIBundle.presets.apis,
                    // SwaggerUIStandalonePreset.slice(1) // here
                    SwaggerUIStandalonePreset
                ],
                plugins: [
                    SwaggerUIBundle.plugins.DownloadUrl
                ],
                layout: "StandaloneLayout",
            });
        };


    </script>
</body>

</html>