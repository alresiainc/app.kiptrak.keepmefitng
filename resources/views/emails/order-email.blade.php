<html>

<head>
    <style>
        header {
            text-align: center;
            font-size: 24px;
            color: rgb(219, 18, 18);
        }



        footer {
            display: flex;
            justify-content: space-between;
            align-content: center;
        }
    </style>

    <title>{{ $subject ?? '' }}</title>
</head>

<body>
    <header>{{ $subject ?? '' }}</header>
    <hr>
    <main>
        {!! $content !!}
    </main>

    <hr>
    <footer>
        <p>Thanks for your patronage</p>
        <p>&copy; <span class="copyright-date"></span> <strong><span class="project-name">KIPTRAK</span></strong>. All
            rights reserved</p>
    </footer>



    <h1>Mail from kiptrak</h1>

</body>

</html>
