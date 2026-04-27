<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    // Obtener todos los contactos de la usuaria
    public function index(Request $request)
    {
        $contacts = $request->user()->contacts()->orderBy('priority')->get();

        return response()->json([
            'contacts' => $contacts,
        ]);
    }

    // Agregar un nuevo contacto
    public function store(Request $request)
    {
        // Validar que no tenga mas de 10 contactos
        if ($request->user()->contacts()->count() >= 10) {
            return response()->json([
                'message' => 'Has alcanzado el limite de 10 contactos de emergencia',
            ], 422);
        }

        $request->validate([
            'name'     => 'required|string|max:100',
            'phone'    => 'required|string|max:20',
            'email'    => 'nullable|email',
            'priority' => 'nullable|integer|min:1|max:10',
        ]);

        $contact = $request->user()->contacts()->create([
            'name'     => $request->name,
            'phone'    => $request->phone,
            'email'    => $request->email,
            'priority' => $request->priority ?? 1,
        ]);

        return response()->json([
            'message' => 'Contacto agregado exitosamente',
            'contact' => $contact,
        ], 201);
    }

    // Eliminar un contacto
    public function destroy(Request $request, $id)
    {
        $contact = $request->user()->contacts()->find($id);

        if (!$contact) {
            return response()->json([
                'message' => 'Contacto no encontrado',
            ], 404);
        }

        $contact->delete();

        return response()->json([
            'message' => 'Contacto eliminado exitosamente',
        ]);
    }
}