<?php

namespace App\Http\Controllers\Api;

use App\Models\Address;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddressRequest;
use App\Http\Resources\AddressResource;

class AddressController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request)
    {
        $addresses = Address::where(
            'user_id',
            $request->user()->id
        )
            ->latest()
            ->get();

        return $this->successResponse(
            'Address list fetched successfully',
            AddressResource::collection($addresses)
        );
    }

    public function store(AddressRequest $request)
    {

        if ($request->is_default) {

            Address::where(
                'user_id',
                $request->user()->id
            )->update([
                'is_default' => false
            ]);
        }

        $address = Address::create([

            'user_id' => $request->user()->id,

            'full_name' => $request->full_name,

            'phone' => $request->phone,

            'address_line_1' => $request->address_line_1,

            'address_line_2' => $request->address_line_2,

            'city' => $request->city,

            'state' => $request->state,

            'country' => $request->country,

            'postal_code' => $request->postal_code,

            'is_default' => $request->is_default,
        ]);

        return $this->successResponse(
            'Address created successfully',
            new AddressResource($address)
        );
    }

    public function show(Address $address)
    {
        return $this->successResponse(
            'Address fetched successfully',
            new AddressResource($address)
        );
    }

    public function update(
        AddressRequest $request,
        Address $address
    ) {

        if ($request->is_default) {

            Address::where(
                'user_id',
                $request->user()->id
            )
                ->where('id', '!=', $address->id)

                ->update([
                    'is_default' => false
                ]);
        }

        $address->update([

            'full_name' => $request->full_name,

            'phone' => $request->phone,

            'address_line_1' => $request->address_line_1,

            'address_line_2' => $request->address_line_2,

            'city' => $request->city,

            'state' => $request->state,

            'country' => $request->country,

            'postal_code' => $request->postal_code,

            'is_default' => $request->is_default,
        ]);

        return $this->successResponse(
            'Address updated successfully',
            new AddressResource($address)
        );
    }

    public function destroy(Address $address)
    {
        $address->delete();

        return $this->successResponse(
            'Address deleted successfully'
        );
    }
}
