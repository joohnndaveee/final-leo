<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact');
    }

    public function store(Request $request)
    {
        // Validate the form data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
        ]);

        // Save the message to database (guest = contact form)
        Message::create(array_merge($validated, ['source' => 'guest']));

        // Return success response for AJAX
        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully! We will get back to you soon.'
        ]);
    }
}
