<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'BillSplitter') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        
        <!-- Font Awesome -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

        <!-- Custom Styles -->
        <style>
            :root {
                --primary-color: #4A5CFF;
                --primary-dark: #3544CC;
                --secondary-color: #6C757D;
                --success-color: #28A745;
                --danger-color: #DC3545;
                --warning-color: #FFC107;
                --info-color: #17A2B8;
                --light-color: #F8F9FA;
                --dark-color: #343A40;
            }

            body {
                font-family: 'Inter', sans-serif;
                background-color: #F5F7FF;
                color: #2D3748;
            }

            .card {
                border: none;
                border-radius: 15px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
                transition: transform 0.2s, box-shadow 0.2s;
            }

            .card:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            }

            .btn {
                padding: 0.5rem 1.5rem;
                border-radius: 8px;
                font-weight: 500;
                transition: all 0.2s;
            }

            .btn-primary {
                background-color: var(--primary-color);
                border-color: var(--primary-color);
            }

            .btn-primary:hover {
                background-color: var(--primary-dark);
                border-color: var(--primary-dark);
            }

            .form-control {
                border-radius: 8px;
                padding: 0.75rem 1rem;
                border: 1px solid #E2E8F0;
            }

            .form-control:focus {
                border-color: var(--primary-color);
                box-shadow: 0 0 0 0.2rem rgba(74, 92, 255, 0.25);
            }

            .navbar {
                background-color: white !important;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
            }

            .table {
                border-radius: 15px;
                overflow: hidden;
            }

            .badge {
                padding: 0.5em 1em;
                border-radius: 6px;
            }

            .alert {
                border-radius: 10px;
                border: none;
            }

            /* Responsive Utilities */
            @media (max-width: 768px) {
                .container {
                    padding: 1rem;
                }
                
                .card {
                    margin-bottom: 1rem;
                }
                
                .btn {
                    width: 100%;
                    margin-bottom: 0.5rem;
                }
                
                .d-flex {
                    flex-direction: column;
                }
                
                .table-responsive {
                    border-radius: 15px;
                    overflow: hidden;
                }
            }

            /* Custom Animation Classes */
            .fade-in {
                animation: fadeIn 0.3s ease-in;
            }

            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(10px); }
                to { opacity: 1; transform: translateY(0); }
            }
        </style>
    </head>
    <body>
        @include('layouts.navigation')

        <main class="py-4">
            <div class="container">
                @if(session('success'))
                    <div class="alert alert-success fade-in mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger fade-in mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
