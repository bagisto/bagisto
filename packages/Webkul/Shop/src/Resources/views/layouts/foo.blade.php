<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Foo</title>
    <style>
        .container {
            box-sizing: border-box;
            max-width: 50%;
            border: 1px solid green;
            height: 95vh;
        }

        .last-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(47.5%, 1fr));
            grid-gap: 5%;
        }

        .block1 {
            display: block;
            box-sizing: border-box;
            background: pink;
            height: 95vh;
        }

        .block2 {
            display: block;
            box-sizing: border-box;
            /* background: red; */
            height: 95vh;
            display: grid;
            grid-template-rows: 47.5% 47.5%;
            grid-row-gap: 5%;
        }

        .sub-block1 {
            display: block;
            box-sizing: border-box;
            background: green;
        }

        .sub-block2 {
            display: block;
            box-sizing: border-box;
            background: paleturquoise;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="last-grid">
            <div class="block1"></div>
            <div class="block2">
                <div class="sub-block1"></div>
                <div class="sub-block2"></div>
            </div>
        </div>
    </div>
</body>

</html>
