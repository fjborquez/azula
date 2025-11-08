<!DOCTYPE html>
<html>
    <title>Urgent: Use Your Expiring Pantry Items</title>
    <body>
        <h1>Dear {{ $person['name'] }} {{ $person['lastname'] }},</h1>
        <p>We hope this message finds you well. We wanted to inform you that some of the items in your pantry in <b>{{ $houseData['description'] }}</b> are approaching their expiration dates within the next days.</p>
        <p>To help you manage your pantry effectively and reduce waste, we recommend checking the following items:</p>
        <ul>
            @foreach ($inventories as $item)
                <li>{{ $item->catalog_description }} {{ $item->brand_name}} - {{ $item->quantity }} {{ $item->uom_abbreviation }} - Expiration Date: {{ Carbon\Carbon::parse($item->expiration_date)->format('d/m/Y') }}</li>
            @endforeach
        </ul>
        <p>Please consider using these items soon to ensure their freshness and quality. If you have any questions or need assistance, feel free to reach out to us.</p>
        <p>Thank you for being a valued member of our community!</p>
        <p>Best regards,<br>Avatar Team</p>
</html>
