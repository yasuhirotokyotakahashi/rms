<!DOCTYPE html>
<html>

<head>
    <title>Reservation Reminder</title>
</head>

<body>
    <h1>Reservation Reminder</h1>
    <p>Hello {{ $reservation->user->name }},</p>
    <p>This is a reminder for your reservation on {{ $reservation->reservation_date }}.</p>
    <p>Thank you for choosing our service. We look forward to seeing you!</p>
</body>

</html>
