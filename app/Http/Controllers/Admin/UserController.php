<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request): Response
    {
        $q = $request->string('q')->toString();
        $verified = $request->input('verified'); // true|false|null
        $sort = $request->string('sort')->toString() ?: 'created_at';
        $direction = $request->string('direction')->toString() ?: 'desc';
        $perPage = max(10, min(200, (int) $request->input('perPage', 50)));

        $query = User::query();

        if ($q) {
            $query->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                  ->orWhere('email', 'like', "%{$q}%");
            });
        }

        if ($verified === 'true') {
            $query->whereNotNull('email_verified_at');
        } elseif ($verified === 'false') {
            $query->whereNull('email_verified_at');
        }

        if (! in_array($sort, ['id','name','email','created_at','email_verified_at'], true)) {
            $sort = 'created_at';
        }
        if (! in_array(strtolower($direction), ['asc','desc'], true)) {
            $direction = 'desc';
        }

        $users = $query
            ->orderBy($sort, $direction)
            ->paginate($perPage)
            ->withQueryString()
            ->through(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'email_verified_at' => $user->email_verified_at,
                    'created_at' => $user->created_at,
                ];
            });

        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
            'filters' => [
                'q' => $q,
                'verified' => $verified,
                'sort' => $sort,
                'direction' => $direction,
                'perPage' => $perPage,
            ],
        ]);
    }
}
