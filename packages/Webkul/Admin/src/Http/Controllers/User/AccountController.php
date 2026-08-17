<?php

namespace Webkul\Admin\Http\Controllers\User;

use Hash;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Core\Helpers\MediaFileName;

class AccountController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function edit()
    {
        $user = auth()->guard('admin')->user();

        return view('admin::account.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return Response
     */
    public function update()
    {
        $user = auth()->guard('admin')->user();

        $this->validate(request(), [
            'name' => 'required',
            'email' => 'email|unique:admins,email,'.$user->id,
            'password' => 'nullable|min:6|confirmed',
            'current_password' => 'required|min:6',
            'image.*' => 'nullable|mimes:bmp,jpeg,jpg,png,webp',
            'image_meta.*.file_name' => ['nullable', 'string', 'max:'.MediaFileName::MAX_LENGTH],
        ]);

        $data = request()->only([
            'name',
            'email',
            'password',
            'password_confirmation',
            'current_password',
            'image',
            'image_meta',
        ]);

        if (! Hash::check($data['current_password'], $user->password)) {
            session()->flash('warning', trans('admin::app.account.edit.invalid-password'));

            return redirect()->back();
        }

        $isPasswordChanged = false;

        if (! $data['password']) {
            unset($data['password']);
        } else {
            $isPasswordChanged = true;

            $data['password'] = bcrypt($data['password']);
        }

        $mediaFileName = app(MediaFileName::class);

        $requestedFileName = collect($data['image_meta'] ?? [])->first()['file_name'] ?? null;

        unset($data['image_meta']);

        if (request()->hasFile('image')) {
            if ($user->image) {
                Storage::delete($user->image);
            }

            $file = current(request()->file('image'));

            $data['image'] = $mediaFileName->resolve(
                'admins/'.$user->id,
                $requestedFileName,
                $file->getClientOriginalExtension()
            );

            Storage::put($data['image'], $file->get());
        } elseif (! isset($data['image'])) {
            if ($user->image) {
                Storage::delete($user->image);
            }

            $data['image'] = null;
        } else {
            $data['image'] = $mediaFileName->rename($user->image, $requestedFileName);
        }

        $user->update($data);

        if ($isPasswordChanged) {
            Event::dispatch('admin.password.update.after', $user);
        }

        session()->flash('success', trans('admin::app.account.edit.update-success'));

        return back();
    }
}
