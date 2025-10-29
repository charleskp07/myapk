<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Code de confirmation</title>
</head>

<body>
    <br /><br /><br />

    <div
        style="text-align: center; font-size: 20px; max-width: 400px; margin: auto; background-color: rgba(0, 0, 0, 0.05); padding: 30px 50px;">
        <h3 style="margin-bottom: 5px;">Bonjour {{ $name }},</h3>

        <div>Utilisez le code ci-dessous pour confirmer votre e-mail.</div>
        <h1>{{ $code }}</h1>
        <p style="font-size: 12px; color: #333; text-align: justify;">
            Pour garantir la sécurité et l'intégrité de nos utilisateurs, nous avons mis un place un système de double
            authentification qui nous permettre de confirmer la validité et l'intégrité de votre compte. En cas de problème, <a
                href=""><u>Contactez-nous</u></a>. Nous disposons d'une équipe qui vous aidera
            à résoudre le problème dans le plus bref des délais.
            <br /><br /><br /><br />
        </p>
        <div style="font-size: 12px;">
            <small>
                <b>Powered BY <a href="">Dayal Enterprises</a></b><br />
                Copyright &copy; 2025 | All rights reserved.
            </small>
        </div>
    </div>

    <br /><br /><br />
</body>

</html>
