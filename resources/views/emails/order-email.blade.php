<html>

<head>
    <style>
        body {
            padding: 20px;
            font-family: "Rubik", Helvetica, Arial, serif;
            font-family: "Open Sans", sans-serif;
            background: #f6f9ff;
            color: #444444;
        }

        main {
            background: #ffffff;
            padding: 35px;
            border-radius: 8px;
        }

        header {
            font-size: 24px;
            padding: 10px;
            border-bottom: 3px solid #04512d;
            font-weight: 600;
            margin-bottom: 20px;
        }



        footer {
            padding: 35px;
        }
    </style>

    <title>{{ $subject ?? '' }}</title>
</head>

<body>
    <header>{{ $subject ?? '' }}</header>

    <main>
        {!! $content !!}
    </main>

    <footer>
        <hr>
        <div style="text-align: center">
            &copy; <span class="copyright-date"> {{ date('Y') }}</span>
            <strong>KIPTRAK</strong> <br>
            Allrights reserved
        </div>
    </footer>

</body>

</html>
