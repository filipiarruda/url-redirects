<!DOCTYPE html>
<html lang="pt-BR" class="dark">
<head>
    <meta charset="UTF-8">
    <title>Redirecionando...</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-900 text-white flex items-center justify-center min-h-screen">

    <div class="text-center space-y-4">
        @if($active)
            <h1 class="text-3xl font-bold">Aguarde...</h1>
            <p class="text-lg">Você será redirecionado em instantes.</p>
            <p class="text-sm text-gray-400">
                Se não for redirecionado automaticamente, <a href="{{ $url }}" class="text-blue-400 underline">clique aqui</a>.
            </p>
            <script>
                setTimeout(() => {
                    window.location.href = @js($url);
                }, 3000);
            </script>
        @else
            <div class="flex flex-col items-center space-y-2">
                <h1 class="text-2xl font-bold text-red-400" style="color: #000; font-weight: 700;">Link indisponível</h1>
                <!-- Ícone de indisponível (círculo com X) -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-red-500 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none"/>
                    <line x1="9" y1="9" x2="15" y2="15" stroke="currentColor" stroke-width="2"/>
                    <line x1="15" y1="9" x2="9" y2="15" stroke="currentColor" stroke-width="2"/>
                </svg>
                
                <p class="text-gray-400" style="color: #000;">Este link está desativado.</p>
            </div>
        @endif
    </div>

</body>
</html>
