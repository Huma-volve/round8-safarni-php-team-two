<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Flight Ticket</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            direction: ltr;
            text-align: left;
        }

        .ticket {
            border: 1px solid #000;
            padding: 15px;
        }

        .header {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }

        td {
            padding: 4px 0;
            vertical-align: top;
        }

        .label {
            font-weight: bold;
            width: 120px;
        }

        .qr {
            text-align: center;
            margin-top: 15px;
        }

        .footer {
            text-align: center;
            font-size: 10px;
            margin-top: 15px;
            color: #555;
        }
    </style>
</head>
<body>

<div class="ticket">

    <div class="header">🎫 Flight Ticket</div>

    <table>
        <tr>
            <td class="label">Passenger Name:</td>
            <td>{{ $ticket->user->name }}</td>
            <td class="label">Ticket Number:</td>
            <td>{{ $ticket->ticket_number }}</td>
        </tr>

        <tr>
            <td class="label">Flight Number:</td>
            <td>{{ $ticket->flightSeat->flight->flight_number }}</td>
            <td class="label">Airline:</td>
            <td>{{ $ticket->flightSeat->flight->carrier }}</td>
        </tr>

        <tr>
            <td class="label">From:</td>
            <td>{{ $ticket->flightSeat->flight->departure_airport }}</td>
            <td class="label">To:</td>
            <td>{{ $ticket->flightSeat->flight->arrival_airport }}</td>
        </tr>

        <tr>
            <td class="label">Departure Date:</td>
            <td>{{ $ticket->flightSeat->flight->departure_date }}</td>
            <td class="label">Departure Time:</td>
            <td>{{ $ticket->flightSeat->flight->departure_time }}</td>
        </tr>

        <tr>
            <td class="label">Seat:</td>
            <td>{{ $ticket->flightSeat->seat_number }}</td>
            <td class="label">Class:</td>
            <td>{{ $ticket->flightSeat->class }}</td>
        </tr>

        <tr>
            <td class="label">Price:</td>
            <td>{{ $ticket->price }} EGP</td>
            <td class="label">Status:</td>
            <td>{{ $ticket->status }}</td>
        </tr>
    </table>

    <div class="qr">
        <img src="data:image/svg+xml;base64,{{ base64_encode($qrSvg) }}" width="150">
    </div>

    <div class="footer">
        Please keep this ticket and present it during boarding
    </div>

</div>

</body>
</html>
