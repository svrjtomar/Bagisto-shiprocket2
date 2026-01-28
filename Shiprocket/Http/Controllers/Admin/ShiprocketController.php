<?php

namespace Webkul\Shiprocket\Http\Controllers\Admin;


use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Webkul\Shiprocket\Services\ShiprocketService;
use Webkul\Core\Models\CoreConfig;

class ShiprocketController extends Controller

{

    public function config()
    {
        return view('shiprocket::admin.config');
    }

    

   public function save(Request $request)
    {
        // Basic validation
        $request->validate([
            'email' => 'required|email',
        ]);

        // Always use default scope
        $channelCode = 'default';
        $localeCode  = 'en';

        /*
        |--------------------------------------------------------------------------
        | Save Email (always overwrite)
        |--------------------------------------------------------------------------
        */
        CoreConfig::updateOrCreate(
            [
                'code'         => 'shiprocket.email',
                'channel_code' => $channelCode,
                'locale_code'  => $localeCode,
            ],
            [
                'value' => trim($request->input('email')),
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Save Password (only if provided)
        |--------------------------------------------------------------------------
        */
        if ($request->filled('password')) {
            CoreConfig::updateOrCreate(
                [
                    'code'         => 'shiprocket.password',
                    'channel_code' => $channelCode,
                    'locale_code'  => $localeCode,
                ],
                [
                    'value' => $request->input('password'),
                ]
            );
        }

        return redirect()
            ->back()
            ->with('success', 'Shiprocket API credentials saved successfully.');
    }

   
    
 public function testApi(ShiprocketService $shiprocket)
{
    try {
        if ($shiprocket->authenticate()) {
            return response()->json([
                'success' => true,
                'message' => 'Shiprocket authenticated successfully. Token cached.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Authentication failed. Check email & API password.',
        ], 400);

    } catch (\Throwable $e) {
        \Log::error('Shiprocket Test API Error', [
            'error' => $e->getMessage(),
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Server error while authenticating Shiprocket.',
        ], 500);
    }
}

public function testChannel(ShiprocketService $shiprocket)
{
    return response()->json(
        $shiprocket->testChannelConnection()
    );
}

public function fetch()
{
    return response()->json([
        'email'      => \Webkul\Core\Models\CoreConfig::where('code', 'shiprocket.email')
            ->where('channel_code', 'default')
            ->where('locale_code', 'en')
            ->value('value'),

        'password'   => \Webkul\Core\Models\CoreConfig::where('code', 'shiprocket.password')
            ->where('channel_code', 'default')
            ->where('locale_code', 'en')
            ->value('value'),

        'channel_id' => \Webkul\Core\Models\CoreConfig::where('code', 'shiprocket.channel_id')
            ->where('channel_code', 'default')
            ->where('locale_code', 'en')
            ->value('value'),
    ]);
}



public function pickupLocations(ShiprocketService $service)

{
  
    if (! $service->token()) {
        return response()->json([
            'success' => false,
            'message' => 'Please authenticate Shiprocket first (Test API)',
        ], 401);
    }
    

    return response()->json([
        'success' => true,
        'data' => $service->fetchPickupLocations(),
    ]);
}


}
