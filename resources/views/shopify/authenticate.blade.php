<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Duy from FireApps</title>

    <link rel="stylesheet" href="{{ mix('/css/app.css') }}" />
</head>

<body>
    <form class="mx-auto mt-5 p-2 text-center rounded border" id="authenticate-box">
        <h2>Welcome</h2>
        <p>Please enter your Shopify URL</p>
        <div class="input-group mb-2">
            <input type="text" class="form-control" placeholder="Store name">
            <div class="input-group-append">
                <span class="input-group-text" id="basic-addon2">.myshopify.com</span>
            </div>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Login</button>
    </form>
</body>

</html>