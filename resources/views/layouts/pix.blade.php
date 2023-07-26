<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LINK SERVICOS - PIX</title>
    <style type="text/css">
        #pix .document {
            width: 210mm ;
        }

        @media print {
            html#pix, body#pix {
                width: 210mm ;
                height: 270mm ;
            }
        }

        @page {
            size: A4 ;
        }

        #pix .content-top {
            padding-top: 10px;
        }

        .item1 {
            grid-area: icon;
            padding-top: 10px;
            padding-bottom: 10px;
        }

        .item2 {
            grid-area: text;
        }

        .grid-container {
            border: 0.1rem solid #ffb822;
            border-radius: 1rem;
            display: grid;
            grid-template-areas: 'icon icon text text text';
        }

        .grid-container > div {
            text-align: center;
        }

        #products {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        #products td, #products th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        #products tr:nth-child(even){background-color: #f2f2f2;}

        #products tr:hover {background-color: #ddd;}

        #products th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
        }
    </style>
</head>
<body id="pix">
<div class="document" style="font-family: Roboto, Helvetica, sans-serif;">
    <div class="content-top">
        <p style="text-align: center;">
            <img alt="Krypton Pay" src="data:image/png;base64,{{ $logoBase64 }}"/>
        </p>
        <p style="text-align: center;">
                    <span>
                        Faça um <strong>Pix</strong> para
                        <strong>{{ $data->nome_fantasia }}</strong>
                    </span>
        </p>

        <p style="text-align: center;">
            <span style="color: #222960; font-size: 30px; ">
                <strong>
                    <span>
                        Pedido #{{ $data->id }}
                    </span>
                </strong>
                </span>
        </p>
        <p style="text-align: center;">
            <span style="color: #222960; font-size: 30px; ">
                <strong>
                    <span>
                        R$ <span
                            style="color: #a7bf3b;">{{ number_format($data->valor, 2, ',', '.') }}</span>
                    </span>
                </strong>
            </span>
        </p>
        <p style="text-align: center;">
            <img alt="Krypton Pay" src="{{ $logoQrCode }}" />
        </p>
        <p style="text-align: center;">
                    <span style="color: #a7bf3b;">
                       Chave Pix Copia e Cola:
                    </span><br/>
            <a>{{ $pixCopiaECola }}</a>
        </p>

        <table id="products">
            <thead>
            <tr>
                <th colspan="3" style="text-align: center">Produtos</th>
            </tr>
            <tr>
                <th>Quantidade</th>
                <th>Descrição</th>
                <th>Valor</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data->items as $product)
                <tr>
                    <td style="width: 50px;">{{ $product->quantidade }}</td>
                    <td>{{ $product->nome }}</td>
                    <td style="width: 120px;">R$ {{ number_format($product->valor, 2, ',', '.') }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="grid-container" style="margin-top: 5px;">
        <div class="item1">
            <img alt="Krypton Pay" src="data:image/png;base64,{{ $alertIcon }}" width="80px"/>
        </div>
        <div class="item2 " style="color: #f8b823;">
            <p></p>
            <p>Antes de pagar no app do seu banco,</p>
            <p>lembre-se de conferir os dados de quem vai receber.</p>
            <p><strong>NÃO</strong> realize o pagamento para a chave CNPJ, pois seu pagamento <strong>NÃO</strong> será compensado.</p>
        </div>
    </div>

</div>
</body>
</html>
