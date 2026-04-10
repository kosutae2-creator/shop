<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Arial', sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 10px; }
        .header { text-align: center; padding-bottom: 20px; border-bottom: 2px solid #6366f1; }
        .order-id { font-size: 24px; font-weight: bold; color: #6366f1; }
        .footer { font-size: 12px; color: #999; text-align: center; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Hvala na porudžbini!</h1>
            <p class="order-id">#{{ $order->id }}</p>
        </div>
        <p>Zdravo, <strong>{{ $order->customer_name }}</strong>,</p>
        <p>Uspešno smo primili tvoju porudžbinu i naši operateri će je obraditi u najkraćem mogućem roku.</p>
        
        <h3>Detalji isporuke:</h3>
        <p>
            <strong>Adresa:</strong> {{ $order->address }}<br>
            <strong>Telefon:</strong> {{ $order->phone }}
        </p>

        <h3>Ukupno za plaćanje: {{ number_format($order->total_amount, 0, ',', '.') }} RSD</h3>
        <p>Plaćanje se vrši kuriru prilikom preuzimanja paketa.</p>

        <div class="footer">
            Ovaj email je automatski generisan. Molimo ne odgovarajte na njega.
        </div>
    </div>
</body>
</html>