<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleSelectionController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return redirect($this->getRedirectUrl(Auth::user()->role));
        }
        
        return view('role-selection');
    }

    public function selectRole(Request $request)
    {
        $validated = $request->validate([
            'role' => 'required|in:admin,user,chauffeur'
        ]);

        // Store the selected role in session
        session(['selected_role' => $validated['role']]);

        return response()->json([
            'success' => true,
            'redirect_url' => $this->getRedirectUrl($validated['role'])
        ]);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'role' => 'required|in:admin,user,chauffeur'
        ]);

        try {
            \Log::info('Login attempt', ['email' => $validated['email'], 'role' => $validated['role']]);
            
            $user = \App\Models\User::where('email', $validated['email'])->first();
            
            if (!$user) {
                \Log::warning('User not found', ['email' => $validated['email']]);
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid email or password.'
                ], 422);
            }
            
            if (!$user->is_active) {
                \Log::warning('Inactive user', ['email' => $validated['email']]);
                return response()->json([
                    'success' => false,
                    'message' => 'Your account is inactive. Please contact administrator.'
                ], 422);
            }
            
            \Log::info('Attempting authentication', ['email' => $validated['email'], 'role' => $validated['role']]);
            
            // Attempt authentication with remember token
            $authAttempt = Auth::attempt(['email' => $validated['email'], 'password' => $validated['password']], true);
            
            if (!$authAttempt) {
                \Log::warning('Authentication failed', [
                    'email' => $validated['email'],
                    'role' => $validated['role'],
                    'user_exists' => $user ? 'yes' : 'no',
                    'user_active' => $user ? ($user->is_active ? 'yes' : 'no') : 'N/A'
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid email or password.'
                ], 422);
            }
            
            // Regenerate session after successful authentication
            $request->session()->regenerate();
            
            // Get authenticated user
            $authenticatedUser = Auth::user();
            
            \Log::info('Authentication successful', [
                'user_id' => $authenticatedUser->id,
                'user_email' => $authenticatedUser->email,
                'user_role' => $authenticatedUser->role,
                'selected_role' => $validated['role']
            ]);
            
            if ($authenticatedUser->role !== $validated['role']) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                \Log::warning('Role mismatch', [
                    'user_role' => $authenticatedUser->role,
                    'selected_role' => $validated['role']
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Your account role (' . ucfirst($authenticatedUser->role) . ') does not match the selected role (' . ucfirst($validated['role']) . ').'
                ], 422);
            }
            
            // Store selected role in session
            session(['selected_role' => $validated['role']]);
            
            // Get redirect URL
            $redirectUrl = $this->getRedirectUrl($validated['role']);
            
            \Log::info('Login successful - redirecting', [
                'user_id' => $authenticatedUser->id,
                'user_role' => $authenticatedUser->role,
                'redirect_url' => $redirectUrl,
                'session_id' => $request->session()->getId()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Login successful! Redirecting...',
                'redirect_url' => $redirectUrl,
                'user' => [
                    'id' => $authenticatedUser->id,
                    'name' => $authenticatedUser->name,
                    'email' => $authenticatedUser->email,
                    'role' => $authenticatedUser->role
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Login error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Login failed. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : 'An unexpected error occurred'
            ], 500);
        }
    }

    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'role' => 'required|in:admin,user,chauffeur',
                'phone' => 'nullable|string|max:20',
                'date_of_birth' => 'nullable|date|before:today',
                'address' => 'nullable|string|max:500',
                'city' => 'nullable|string|max:100',
                'country' => 'nullable|string|max:100',
                'emergency_contact' => 'nullable|string|max:255',
                'emergency_phone' => 'nullable|string|max:20',
                'license_number' => 'nullable|string|max:50',
                'license_expiry' => 'nullable|date|after:today',
                'notes' => 'nullable|string|max:1000',
            ]);

            $user = \App\Models\User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => $validated['password'],
                'role' => $validated['role'],
                'phone' => $validated['phone'] ?? null,
                'date_of_birth' => $validated['date_of_birth'] ?? null,
                'address' => $validated['address'] ?? null,
                'city' => $validated['city'] ?? null,
                'country' => $validated['country'] ?? null,
                'emergency_contact' => $validated['emergency_contact'] ?? null,
                'emergency_phone' => $validated['emergency_phone'] ?? null,
                'license_number' => $validated['license_number'] ?? null,
                'license_expiry' => $validated['license_expiry'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            Auth::login($user, true);
            session(['selected_role' => $validated['role']]);

            return response()->json([
                'success' => true,
                'message' => 'Registration successful! Welcome to the system.',
                'redirect_url' => $this->getRedirectUrl($validated['role']),
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Please check your input: ' . implode(', ', $e->validator->errors()->all()),
                'errors' => $e->validator->errors()
            ], 422);
            
        } catch (\Illuminate\Database\QueryException $e) {
            \Log::error('Database error during registration', [
                'error' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Database error occurred. Please try again.'
            ], 500);
            
        } catch (\Exception $e) {
            \Log::error('Unexpected error during registration', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'input' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : 'An unexpected error occurred'
            ], 500);
        }
    }

    private function getRedirectUrl($role)
    {
        $routes = [
            'admin' => 'admin.dashboard',
            'user' => 'client.dashboard',
            'chauffeur' => 'chauffeur.dashboard'
        ];
        
        $fallbacks = [
            'admin' => '/admin',
            'user' => '/client/dashboard',
            'chauffeur' => '/chauffeur/dashboard'
        ];
        
        try {
            return route($routes[$role] ?? 'welcome');
        } catch (\Exception $e) {
            \Log::error('Route error for role: ' . $role, ['error' => $e->getMessage()]);
            return $fallbacks[$role] ?? '/';
        }
    }
}
