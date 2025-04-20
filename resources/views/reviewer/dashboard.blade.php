<!-- resources/views/admin/dashboard.blade.php -->
@extends('layouts.app')

@section('content')

      <!-- Main Content -->
      <main class="content">
        <h1>Welcome, {{ Auth::user()->name }} - Your role: {{ Auth::user()->role }}</h1>
        <p>This is a simple example of a responsive sidebar with scroll functionality. Resize the browser to see the responsive behavior.</p>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec sit amet congue ligula, id facilisis lacus. Integer non quam id arcu convallis bibendum.</p>
        <p>Scroll the sidebar for more menu options!</p>
      </main>
  

@endsection