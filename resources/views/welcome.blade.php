<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="refresh" content="0;url={{ route('file.login.form') }}">
        <title>TEAL</title>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
        <style>
            body {
                font-family: 'Roboto', sans-serif;
                background-color: #282828;
                color: #ebdbb2;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
            }
            
            .container {
                text-align: center;
            }
            
            .logo {
                font-size: 4rem;
                font-weight: 700;
                color: #8ec07c;
                margin-bottom: 2rem;
            }
            
            p {
                font-size: 1.2rem;
                margin-bottom: 2rem;
            }
            
            .spinner {
                border: 4px solid rgba(0, 0, 0, 0.1);
                width: 36px;
                height: 36px;
                border-radius: 50%;
                border-left-color: #8ec07c;
                animation: spin 1s linear infinite;
                margin: 0 auto;
            }
            
            @keyframes spin {
                0% {
                    transform: rotate(0deg);
                }
                100% {
                    transform: rotate(360deg);
                }
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="logo">TEAL</div>
            <p>Redirecting to login...</p>
            <div class="spinner"></div>
        </div>
    </body>
</html>