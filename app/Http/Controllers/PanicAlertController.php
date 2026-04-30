<?php

namespace App\Http\Controllers;

use App\Models\PanicAlert;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PanicAlertController extends Controller
{
    // Activar el botón de pánico
    public function trigger(Request $request)
    {
        $request->validate([
            'triggered_by' => 'nullable|in:button,shake,triple_tap',
        ]);

        // Verificar si ya hay una alerta activa
        $activeAlert = $request->user()
            ->panicAlerts()
            ->where('status', 'active')
            ->first();

        if ($activeAlert) {
            return response()->json([
                'message' => 'Ya tienes una alerta activa',
                'alert'   => $activeAlert,
            ], 422);
        }

        // Crear la alerta
        $alert = $request->user()->panicAlerts()->create([
            'triggered_by' => $request->triggered_by ?? 'button',
            'status'       => 'active',
            'started_at'   => Carbon::now(),
            'expires_at'   => Carbon::now()->addHours(8),
        ]);

        // Obtener contactos de emergencia
        $contacts = $request->user()->contacts()->where('is_active', true)->get();

        return response()->json([
            'message'  => 'Alerta de pánico activada',
            'alert'    => $alert,
            'contacts' => $contacts,
        ], 201);
    }

    // Cancelar la alerta activa
    public function cancel(Request $request)
    {
        $alert = $request->user()
            ->panicAlerts()
            ->where('status', 'active')
            ->first();

        if (!$alert) {
            return response()->json([
                'message' => 'No tienes ninguna alerta activa',
            ], 404);
        }

        $alert->update([
            'status'       => 'cancelled',
            'cancelled_at' => Carbon::now(),
        ]);

        return response()->json([
            'message' => 'Alerta cancelada correctamente',
            'alert'   => $alert,
        ]);
    }

    // Ver el estado de la alerta actual
    public function status(Request $request)
    {
        $alert = $request->user()
            ->panicAlerts()
            ->where('status', 'active')
            ->first();

        if (!$alert) {
            return response()->json([
                'message' => 'Sin alertas activas',
                'active'  => false,
            ]);
        }

        return response()->json([
            'active' => true,
            'alert'  => $alert,
        ]);
    }
}