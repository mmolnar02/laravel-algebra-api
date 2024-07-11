<?php

namespace App\Http\Controllers;

use App\Helpers\Image;
use App\Models\Client;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $clients = Client::with('user')->get();

        if(Gate::denies('viewAny', Client::class)){
            $clients = $clients->where('user_id', auth()->id())->values();
        }

        return response()->json($clients);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('clients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClientRequest $request)
    {
        $validateData = $request->validated();
        $validateData['user_id'] = auth()->id();

        if ($request->hasFile('image')){
            $validateData['image'] = Image::upload($request->file('image'), 'clients');
        }

        Client::create($validateData);

        return redirect()
            ->route('clients.index')
            ->with('success', 'Client created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        Gate::authorize('view', $client);

        return view('clients.show', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        Gate::authorize('update', $client);

        return view('clients.edit', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClientRequest $request, Client $client)
    {
        Gate::authorize('update', $client);

        $validateData = $request->validated();

        if ($request->hasFile('image')){
            $validateData['image'] = Image::upload($request->file('image'), 'clients', $client->image);
        }

        $client->update($validateData);

        return redirect()
            ->route('clients.show', $client->id)
            ->with('success', 'Client updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        Gate::authorize('delete', $client);

        $client->delete();

        return redirect()
            ->route('clients.index')
            ->with('success', 'Client deleted successfully.');
    }
}
