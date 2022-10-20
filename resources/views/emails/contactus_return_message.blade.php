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
        <div class="gifter-logo text-center mt-4">
            <img src="https://gifter-test.trztechnologies.com/assets/images/gift_poke_logo.png" alt="logo-image" style="height:57px; width:180px">
        </div>
        <div class="content-body m-3 bg-gray" style="line-height: 1.2;">
            <div class="row">
                <div class="col col-2"></div>
                <div class="col col-8">
                    <div class="p-1">Dear {{isset($name) ? $name : ''}},</div>
                    <div class="p-1">Thank you for contacting us.</div>
                    <div class="p-1">Our team has received your inquiry about {{isset($subject) ? $subject : ''}} and will get in touch with you shortly.</div>
                    <div class="p-1 mb-4">If you have any other questions in the mean time, feel free to contact us.</div>
                    <div>Best Regards,</div>
                    <div>Gift Poke Team</div>
                </div>
                <div class="col col-2"></div>
            </div>
        </div>
    </div>
</body>
</html>
