<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MedicationTime;
use Carbon\Carbon;
use App\Models\MarEntry;


class MarController extends Controller
{
//     public function addTime(Request $request)
//     {
//         $time = Carbon::parse($request->time);

//         MedicationTime::create([
//             'mar_entry_id' => $request->entry_id,
//             'date' => $request->date,
//             'shift' => $request->shift,
//             'time' => $time,
//         ]);

//         return response()->json([
//             'success' => true,
//             'time_formatted' => $time->format('h:i A')
//         ]);
//     }
// }


public function addTime(Request $request)
{
    $entry = MarEntry::find($request->entry_id);

    $data = $entry->administration_data ?? [];

    if (!isset($data[$request->date])) {
        $data[$request->date] = [
            '7-3' => [],
            '3-11' => [],
            '11-7' => [],
        ];
    }

    if (!isset($data[$request->date][$request->shift]) || !is_array($data[$request->date][$request->shift])) {
        $data[$request->date][$request->shift] = [];
    }

    $data[$request->date][$request->shift][] = $request->time;

    $entry->administration_data = $data;
    $entry->save();

    return response()->json(['success' => true]);
}