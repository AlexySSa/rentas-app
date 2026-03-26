@extends('layouts.app')

@section('title', 'Iniciar sesión')

@section('content')
    <div class="auth-shell">
        <div class="card" style="width: min(460px, 100%);">
            <div class="page-header" style="margin-bottom: 24px;">
                <div>
                    <h2>Iniciar sesión</h2>
                    <p>Accede al sistema de gestión de alquiler de autos.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('login.attempt') }}" class="grid">
                @csrf

                <div class="field">
                    <label for="email">Correo electrónico</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
                </div>

                <div class="field">
                    <label for="password">Contraseña</label>
                    <input id="password" type="password" name="password" required>
                </div>

                <div class="field">
                    <label>
                        <input type="checkbox" name="remember" value="1" style="width: auto; margin-right: 8px;">
                        Recordarme
                    </label>
                </div>

                <button type="submit" class="btn-primary">Ingresar</button>
            </form>

            <div class="helper" style="margin-top: 18px;">
                Usuario de prueba: <strong>admin@carrental.test</strong> o <strong>empleado@carrental.test</strong> | Contraseña: <strong>password</strong>
            </div>
        </div>
    </div>
@endsection
