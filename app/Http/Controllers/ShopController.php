<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    //
    function slectShop($shopId)
    {
        if (Auth::check() && Auth::user()->isManager()) {
            $shop = Shop::find($shopId);
            Auth::user()->update([
                'shop_id' => $shopId,
                'shop_name' => $shop->shop_name,
            ]);
            return redirect('/shop');
        } else if (Auth::check() && Auth::user()->isAdmin()) {
            $shop = Shop::find($shopId);
            Auth::user()->update([
                'shop_id' => $shopId,
                'shop_name' => $shop->shop_name,
            ]);
            return redirect('/shop');
        }
        return redirect('/');
    }
}
