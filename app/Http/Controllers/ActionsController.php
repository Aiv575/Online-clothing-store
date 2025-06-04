<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ActionsController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'user.name' => 'required',
            'user.email' => 'required|email|unique:users,email',
            'user.password' => 'required|min:8|alpha_dash|confirmed',
        ], [
            'user.name.required' => 'Поле "Имя" обязательно для заполнения',
            'user.email.required' => 'Поле "Электронная почта" обязательно для заполнения',
            'user.email.email'=> 'Поле "Электронная почта" должно быть предоставлено в виде валидного адреса электронной почты',
            'user.password.required'=> 'Поле "Пароль" обязательно для заполнения',
            'user.password.min'=> 'Поле "Пароль" должно быть не менее, чем 8 символов',
            'user.password.alpha_dash'=> 'Поле "Пароль" должно содержать только строчные и прописные символы латиницы, цифры, а также символы "-" и "_"',
            'user.password.confirmed'=> 'Поле "Пароль" и "Повторите пароль" не совпадает',
        ]);

        $data = $request->input('user');
        $data['password'] = bcrypt($data['password']);

        $user = User::create($data);
        Auth::login($user);
        return redirect('/');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }

    public function login(Request $request)
    {
        $request->validate([
            'user.email'=> 'required|email',
            'user.password'=> 'required|min:8|',
        ], [
            'user.email.required' => 'Поле "Электронная почта" обязательно для заполнения',
            'user.email.email'=> 'Поле "Электронная почта" должно быть предоставлено в виде валидного адреса электронной почты',
            'user.password.required'=> 'Поле "Пароль" обязательно для заполнения',
            'user.password.min'=> 'Поле "Пароль" должно быть не менее, чем 8 символов',
            'user.password.alpha_dash'=> 'Поле "Пароль" должно содержать только строчные и прописные символы латиницы, цифры, а также символы "-" и "_"',
        ]);
        if(Auth::attempt($request -> input('user'))) {
            return redirect('/');
        } else {
            return back() -> withErrors([
                'user.email' => 'Почта или пароль не подходят'
            ]);
        }
    }

    public function profile_update(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'address' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|max:2048',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->address = $request->address;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('avatar')) {
            if ($user->avatar && $user->avatar !== 'default.jpg') {
                Storage::disk('public')->delete($user->avatar);
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->save();

        return redirect()->route('profile')->with('success', 'Профиль обновлен');
    }

    public function create_review(Request $request, $id)
    {
        $validated = $request->validate([
            'thing_id' => 'required|exists:things,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $path = $request->file('image')?->store('reviews', 'public');

        Review::create([
            'user_id' => auth()->id(),
            'thing_id' => $validated['thing_id'],
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'image' => $path,
        ]);

        return back()->with('success', 'Отзыв отправлен!');
    }

    public function like(Review $review)
    {
        $user = auth()->user();

        if ($review->isLikedBy($user)) {
            $review->likes()->where('user_id', $user->id)->delete();
        } else {
            $review->likes()->create(['user_id' => $user->id]);
        }

        return back();
    }
}
