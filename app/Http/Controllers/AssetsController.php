<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
<<<<<<< HEAD
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Asset;
=======
>>>>>>> 666be08a4b014c268fe5f6cc17e3d71fb9da67d7

class AssetsController extends Controller
{
    public function index()
    {
<<<<<<< HEAD
        $users = User::select('id', 'name')->get();
        $assets = Asset::latest()->get();

        return view('assets.asset', compact('assets', 'users'));
    }


    public function store(Request $request)
    {
        // dd($request->all());
        $data = $request->validate([
            'name'            => 'required',
            'asset_id'        => 'nullable',
            'purchase_date'   => 'nullable',
            'purchase_from'   => 'nullable',
            'manufacturer'    => 'nullable',
            'model'           => 'nullable',
            'serial_number'   => 'nullable',
            'supplier'        => 'nullable',
            'condition'       => 'nullable',
            'warranty_months' => 'nullable',
            'value'           => 'nullable',
            'asset_user_id'   => 'nullable',
            'description'     => 'nullable',
            'status'          => 'nullable',
        ]);


        if (!empty($data['purchase_date'])) {
            $data['purchase_date'] = date('Y-m-d', strtotime($data['purchase_date']));
        }
        $lastAsset = Asset::orderBy('id', 'DESC')->first();

        if ($lastAsset) {
            $lastNumber = intval(substr($lastAsset->asset_id, 3));
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        $data['asset_id'] = 'KH-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        Asset::create($data);

        return redirect()->route('assets/page')
            ->with('success', 'Asset created successfully.');
    }


    public function getAsset($id)
    {

        return Asset::findOrFail($id);
    }



    // (Optional) Add update and destroy methods here as needed


    public function update(Request $request)
    {
        $data = $request->validate([
            'id'              => 'required|exists:assets,id',
            'name'            => 'required|string|max:255',
            'asset_id'        => "nullable|string|max:100|unique:assets,asset_id,{$request->id}",
            'purchase_date'   => 'nullable|date',
            'purchase_from'   => 'nullable|string|max:255',
            'manufacturer'    => 'nullable|string|max:255',
            'model'           => 'nullable|string|max:255',
            'serial_number'   => 'nullable|string|max:255',
            'supplier'        => 'nullable|string|max:255',
            'condition'       => 'nullable|string|max:255',
            'warranty_months' => 'nullable|integer|min:0',
            'value'           => 'nullable|string|max:50',
            'asset_user_id'   => 'nullable|exists:users,id',
            'description'     => 'nullable|string',
            'status'          => 'nullable|string|in:Pending,Approved,Deployed,Damaged',
        ]);

        $asset = Asset::findOrFail($request->id);
        $asset->update($data);

        return redirect()->route('assets.page')
            ->with('success', 'Asset updated successfully.');
    }

    // <-- THIS MUST EX
    public function destroy(Asset $asset)
    {
        $asset->delete();
        return redirect()->route('assets.page')->with('success', 'Asset deleted.');
=======
        return view('assets.asset');
>>>>>>> 666be08a4b014c268fe5f6cc17e3d71fb9da67d7
    }
}
