<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Gifter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container">
        <div class="gifter-logo text-center mt-5">
            <img src="https://gifter-test.trztechnologies.com/assets/images/gift_poke_logo.png" alt="logo-image" style="height:57px; width:180px">
        </div>
        <div class="content-body m-5 bg-gray" style="line-height: 1.2;">
            <div class="row">
                <div class="col col-1"></div>
                <div class="col col-1">
                    <div class="font-weight-bold p-2">Name:</div>
                    <div class="font-weight-bold p-2">Email:</div>
                    <div class="font-weight-bold p-2">Subject:</div>
                    <div class="font-weight-bold p-2">Message:</div>

                </div>
                <div class="col col-9">
                    <div class="p-2">{{isset($name) ? $name : ''}}</div>
                    <div class="p-2">{{isset($email) ? $email : ''}}</div>
                    <div class="p-2">{{isset($subject) ? $subject : ''}}</div>
                    <div class="p-2">{{isset($message_query) ? $message_query : ''}}</div>
                </div>
                <div class="col col-1"></div>
            </div>
        </div>
    </div>
</body>
</html>
