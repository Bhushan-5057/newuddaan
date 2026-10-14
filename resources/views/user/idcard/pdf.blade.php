<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>ID Card - {{ $user->username }}</title>
    <style>
        @page { margin: 0; }
        body { margin: 0; }
    </style>
</head>
<body class="idc-pdf">
    @include('user.idcard._card')
</body>
</html>
