<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Welcome - SysTrain</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333;
        }

        p {
            color: #555;
        }

        .signature {
            margin-top: 20px;
            font-style: italic;
            color: #777;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Olá {{ $name }}!</h1>
        <p>Seja bem-vindo ao nosso sistema SysTrain. Seu plano {{$type_plan}} já está ativo.</p>
        <p>Agora você pode cadastrar até {{$limit_student}} alunos para utilizar nosso sistema.</p>
        <p>Agradecemos a preferência.</p>
        <p>Atenciosamente,<br>SysTrain</p>
        <div class="signature">
            <p>Esta é uma mensagem automática. Por favor, não responda a este e-mail.</p>
        </div>
    </div>
</body>

</html>
