<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Holiday;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HolidayController extends Controller
{

    public function holiday(Request $request)
    {
        $query = Holiday::query();


        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name_holiday', 'like', "%{$search}%")
                    ->orWhere('date_holiday', 'like', "%{$search}%");
            });
        }


        $holidays = $query->orderBy('date_holiday', 'asc')->paginate(10);


        $holidays->appends($request->all());

        return view('employees.holidays', compact('holidays'));
    }

    /** Save Record */
    public function saveRecord(Request $request)
    {
        $request->validate([
            'nameHoliday' => 'required|string|max:255',
            'holidayDate' => 'required|date',
        ]);

        DB::beginTransaction();
        try {
            Holiday::create([
                'name_holiday' => $request->nameHoliday,
                'date_holiday' => $request->holidayDate,
            ]);

            DB::commit();
            flash()->success('Created new holiday successfully :)');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            flash()->error('Failed to add holiday :)');
            return redirect()->back();
        }
    }

    /** Update Record */
    public function updateRecord(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:holidays,id',
            'holidayName' => 'required|string|max:255',
            'holidayDate' => 'required|date',
        ]);

        DB::beginTransaction();
        try {
            Holiday::where('id', $request->id)->update([
                'name_holiday' => $request->holidayName,
                'date_holiday' => $request->holidayDate,
            ]);

            DB::commit();
            flash()->success('Holiday updated successfully :)');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            flash()->error('Failed to update holiday :)');
            return redirect()->back();
        }
    }

    /** Delete Record */
    public function deleteRecord(Request $request)
    {
        $request->validate(['id' => 'required|integer']);

        try {
            Holiday::destroy($request->id);
            flash()->success('Holiday deleted successfully :)');
            return redirect()->back();
        } catch (\Exception $e) {
            \Log::error($e);
            flash()->error('Failed to delete holiday :)');
            return redirect()->back();
        }
    }
}
