<!DOCTYPE html>
<html>
    <title>Tienes productos que caducarán pronto en {{ $houseData['description'] ?? 'tu casa' }}</title>
    <body>
        <h1>Hola {{ $person['name'] }} {{ $person['lastname'] }},</h1>
        <p>Queremos informarte que algunos productos en <b>{{ $houseData['description'] }}</b> están pronto a caducar en los próximos días</p>
        <p>Para ayudarte a gestionar efectivamente tu despensa y evitar perder esa comida, te recomendamos comprobar los siguientes productos:</p>
        <ul>
            @foreach ($inventories as $item)
                <li>{{ $item->catalog_description }} {{ $item->brand_name}} - {{ $item->quantity }} {{ $item->uom_abbreviation }} - Caduca el: {{ Carbon\Carbon::parse($item->expiration_date)->format('d/m/Y') }}</li>
            @endforeach
        </ul>
        <p>Considera usar estos productos pronto para mantener su frescura y calidad. No dudes en contactarnos en caso de cualquier duda.</p>
        <p>Muchas gracias por ser un miembro valioso de nuestra comunidad.</p>
        <p>Atentamente,<br>Avatar Team</p>
</html>
