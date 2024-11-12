<!DOCTYPE html>
<html>
<head>
    <title>{{ $subject }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .content {
            background-color: #f4f4f4;
            padding: 20px;
            border-radius: 8px;
        }
        .table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        .table th, .table td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .table th {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $subject }}</h1>
        </div>
        <div class="content">
            <p>Olá,</p>
            <p>Segue abaixo os detalhes da simulação de crédito:</p>

            <table class="table">
                <tr>
                    <th>Valor da Parcela</th>
                    <td>{{ $valorParcela }}</td>
                </tr>
                <tr>
                    <th>Valor Total</th>
                    <td>{{ $valorTotal }}</td>
                </tr>
                <tr>
                    <th>Total de Juros</th>
                    <td>{{ $totalJuros }}</td>
                </tr>
            </table>

            <p>Obrigado por utilizar nossos serviços!</p>
        </div>
    </div>
</body>
</html>
