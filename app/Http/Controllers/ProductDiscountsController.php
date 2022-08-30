<?php

namespace App\Http\Controllers;

use App\Product;
use App\ProductDiscount;
use Illuminate\Http\Request;

class ProductDiscountsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $discounts = ProductDiscount::with(['products', 'campaign'])->get();

            return $discounts;
        } catch (\Exception $e) {
            return $e;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            if (!$request->campaign_id || !$request->product_id) {
                throw new \Exception('Dados insuficientes.');
            }

            $discount = new ProductDiscount;
            $discount->campaign_id = $request->campaign_id;
            $discount->product_id = $request->product_id;

            
            $discount->value = $request->value ?: null;
            $discount->percentage = $request->percentage ?: null;

            $discount->save();

             return $discount;
        } catch (\Exception $e) {
            return $e;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $discount = ProductDiscount::with(['products', 'campaign'])->find($id);

            return $discount;
        } catch (\Exception $e) {
            return $e;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {          
            $discount = ProductDiscount::find($id);

            if ($request->campaign_id)
                $discount->campaign_id = $request->campaign_id;
            if ($request->product_id)
                $discount->product_id = $request->product_id;
            if ($request->value)
                $discount->value = $request->value;
            if ($request->percentage)
                $discount->percentage = $request->percentage;

            $discount->save();

            return response('Oferta atualizada com sucesso.', 200);
        } catch (\Exception $e) {
            return $e;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $discount = ProductDiscount::find($id);

            $discount->delete();

            return response('Oferta deletada com sucesso.', 200);
        } catch (\Exception $e) {
            return $e;
        }
    }
}
