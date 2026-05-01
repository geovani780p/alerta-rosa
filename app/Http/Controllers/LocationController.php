<?php

namespace App\Http\Controllers;

use App\Models\LocationHistory;
use App\Models\PanicAlert;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    // Guardar ubicacion durante una alerta activa
    public function store(Request $request)
    {
        $request->validate([
            'latitude'        => 'required|numeric',
            'longitude'       => 'required|numeric',
            'accuracy_meters' => 'nullable|numeric',
            'speed_kmh'       => 'nullable|numeric',
            'battery_level'   => 'nullable|integer|min:0|max:100',
            'has_gps'         => 'nullable|boolean',
        ]);

        // Verificar que hay una alerta activa
        $alert = $request->user()
            ->panicAlerts()
            ->where('status', 'active')
            ->first();

        if (!$alert) {
            return response()->json([
                'message' => 'No tienes ninguna alerta activa',
            ], 404);
        }

        // Guardar ubicacion cifrada
        $location = LocationHistory::create([
            'alert_id'        => $alert->id,
            'lat_encrypted'   => $request->latitude,
            'lng_encrypted'   => $request->longitude,
            'accuracy_meters' => $request->accuracy_meters,
            'speed_kmh'       => $request->speed_kmh,
            'battery_level'   => $request->battery_level,
            'has_gps'         => $request->has_gps ?? true,
        ]);

        return response()->json([
            'message'  => 'Ubicacion guardada correctamente',
            'location' => [
                'id'              => $location->id,
                'battery_level'   => $location->battery_level,
                'has_gps'         => $location->has_gps,
                'created_at'      => $location->created_at,
            ],
        ], 201);
    }

    // Ver historial de ubicaciones de una alerta
    public function history(Request $request, $alertId)
    {
        $alert = $request->user()
            ->panicAlerts()
            ->find($alertId);

        if (!$alert) {
            return response()->json([
                'message' => 'Alerta no encontrada',
            ], 404);
        }

        $locations = $alert->locations()
            ->orderBy('created_at')
            ->get()
            ->map(function ($location) {
                return [
                    'id'              => $location->id,
                    'latitude'        => $location->lat_encrypted,
                    'longitude'       => $location->lng_encrypted,
                    'accuracy_meters' => $location->accuracy_meters,
                    'speed_kmh'       => $location->speed_kmh,
                    'battery_level'   => $location->battery_level,
                    'has_gps'         => $location->has_gps,
                    'timestamp'       => $location->created_at,
                ];
            });

        return response()->json([
            'alert_id'  => $alertId,
            'locations' => $locations,
        ]);
    }
}