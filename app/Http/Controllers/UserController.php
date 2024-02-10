<?php

namespace App\Http\Controllers;

use App\Events\UserSaved;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index()
    {
        $users = User::paginate(15);
        return view('users.index' , compact('users'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Display a listing of the trashed users.
     */
    public function trashed()
    {
        $trashed_users = User::onlyTrashed()->paginate(15);
        return view('users.trashed' , compact('trashed_users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $data = $request->validated();

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            $path = $request->photo->store('public/photos');
            $data['photo'] = Storage::url($path);
        }

        $user =  User::create($data);

        event(new UserSaved($user));

        return redirect()->route('users.index')->with('success', 'User created successfully.');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);

//        dd($user->avatar);
//        dd($user->fullname);
//        dd($user->middleinitial);

        return view('users.show', compact('user'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user)
    {
        if (auth()->user()->type !== 'admin') {
            abort(403, 'Only admins can perform this action.');
        }

        $data = $request->validated();

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']); // Avoid updating the password if not provided
        }

        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            // Delete old photo if exists
            if ($user->photo && Storage::exists($user->photo)) {
                Storage::delete($user->photo);
            }

            $path = $request->photo->store('public/photos');
            $data['photo'] = Storage::url($path);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'User successfully updated.');


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return redirect()->route('users.index')->with('success', 'User successfully deleted.');
        } catch (Exception $e) {
            Log::error('Error deleting user with ID ' . $id . ': ' . $e->getMessage());
            return back()->with('error', 'Error deleting user: ' . $e->getMessage());
        }
    }

    public function restore($id)
    {
        try {
            $user = User::onlyTrashed()->findOrFail($id);
            $user->restore();
            return redirect()->route('users.trashed')->with('success', 'User successfully restored.');
        } catch (\Exception $e) {
            \Log::error('Error restoring user with ID ' . $id . ': ' . $e->getMessage());
            return back()->with('error', 'Error restoring user: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $user = User::withTrashed()->findOrFail($id);

            if ($user->trashed()) {
                $user->forceDelete();
                return redirect()->route('users.trashed')->with('success', 'User successfully deleted forever.');
            }

        } catch (Exception $e) {
            Log::error('Error deleting user with ID ' . $id . ': ' . $e->getMessage());
            return back()->with('error', 'Error deleting user: ' . $e->getMessage());
        }
    }

}
