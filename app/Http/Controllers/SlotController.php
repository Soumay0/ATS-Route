<?php

namespace App\Http\Controllers;

use App\Models\Slot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SlotController extends Controller
{
    public function index()
    {
        $slots = Slot::orderBy('created_at', 'desc')->get();
        return response()->json($slots);
    }

    public function generateLiveSlots()
    {
        // This simulates a "rationalization algorithm" by fetching live flights
        // and inserting them into the slots database for review.
        $response = Http::timeout(10)->get('https://opensky-network.org/api/states/all', [
            'lamin' => 0.0,
            'lomin' => -20.0,
            'lamax' => 60.0,
            'lomax' => 90.0,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $states = $data['states'] ?? [];
            
            $added = 0;
            foreach (array_slice($states, 0, 15) as $index => $state) {
                $callsign = trim($state[1]);
                if (empty($callsign)) continue;

                $slotId = 'SLT-' . rand(1000, 9999);
                
                // Ensure unique slot id just in case
                while (Slot::where('slot_id', $slotId)->exists()) {
                    $slotId = 'SLT-' . rand(1000, 9999);
                }

                Slot::create([
                    'slot_id' => $slotId,
                    'airline' => $callsign,
                    'flight' => substr($callsign, 0, 6),
                    'time' => gmdate('H:i') . ' UTC',
                    'block_time' => rand(1, 9) . 'h ' . rand(0, 59) . 'm',
                    'status' => 'pending'
                ]);
                $added++;
            }
            
            return response()->json(['message' => "Rationalization complete. $added new slot requests generated."]);
        }

        return response()->json(['message' => "Failed to fetch live flights for slot generation."], 500);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected,pending'
        ]);

        $slot = Slot::findOrFail($id);
        $slot->update(['status' => $request->status]);

        return response()->json(['message' => 'Status updated successfully', 'slot' => $slot]);
    }
}
