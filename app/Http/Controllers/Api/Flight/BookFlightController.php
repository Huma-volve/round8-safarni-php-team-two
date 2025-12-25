<?php

namespace App\Http\Controllers\Api\Flight;

use App\Http\Controllers\Controller;
use App\Http\Requests\Flight\BookRequest;
use App\Models\FlightSeat;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class BookFlightController extends Controller
{
    public function bookSeat(BookRequest $request, $flightId)
    {


        DB::beginTransaction();
        $user_id = auth()->id(); // يجب استبدال هذا بالقيمة الحقيقية لمعرف المستخدم المصادق عليه
        try {
            $seat = FlightSeat::where('id', $request->seat_id)->lockForUpdate()->first();
            if (!$seat || !$seat->is_available) {
                abort(409, 'Seat not available');
            }
            $seat->update([
                'is_available' => false,
                'user_id' => $user_id,
            ]);
            // إنشاء التذكرة
            $ticket = Ticket::create([
                'user_id' => $user_id,
                'flight_seat_id' => $seat->id,
                'ticket_number' => 'TICK-' . strtoupper(uniqid()),
                'price' => $seat->price,
                'status' => 'booked',
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
        $qrSvg = QrCode::size(200)
            ->format('svg')
            ->generate(json_encode([
                'ticket_id' => $ticket->id,
                'ticket_number' => $ticket->ticket_number,
                'user_id' => $ticket->user_id,
                'flight_id' => $flightId,
                'seat_number' => $seat->seat_number,
            ]));
        $ticket = Ticket::with([
            'user',
            'flightSeat.flight'
        ])->findOrFail($ticket->id);
        $pdf = Pdf::loadView('tickets.pdf', [
            'ticket' => $ticket,
            'qrSvg' => $qrSvg
        ]);
        $pdfPath = storage_path('app/public/tickets/tickets_' . $ticket->id . '.pdf');
        $pdf->save($pdfPath);
        return apiResponse(
            true,
            'Ticket booked successfully',
            [
                'pdf_url' => asset('storage/tickets/ticket_' . $ticket->id . '.pdf'),
                'qr_svg' => $qrSvg
            ],
            200
        );

    }

    public function cancelTicket( $ticketId)
    {
        DB::beginTransaction();
        // $user_id = auth()->id();
        $user_id = 2; // مؤقتًا للاختبار
        try {
            // استرجاع التذكرة مع المقعد والرحلة
            $ticket = Ticket::with('flightSeat')->findOrFail($ticketId);

            // تحقق من ملكية التذكرة للمستخدم
            if ($ticket->user_id !== $user_id) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized to cancel this ticket'
                ], 403);
            }
           
            // تحديث المقعد ليصبح متاح مرة أخرى
            $seat = $ticket->flightSeat;
            if ($seat) {
                $seat->update([
                    'is_available' => true,
                    'user_id' => null,
                ]);
            }

            // تحديث حالة التذكرة
            $ticket->update([
                'status' => 'cancelled',
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Ticket cancelled successfully',
                'ticket' => $ticket
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }





}
