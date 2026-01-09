{{-- ================================================
FILE: resources/views/home.blade.php
FUNGSI: Halaman utama website
================================================ --}}

@extends('layouts.app')

@section('title', 'Beranda')

@section('content')

{{-- Animasi Background --}}
<div class="stars" id="stars"></div>
<div class="asteroids" id="asteroids"></div>

<style>
    /* Background bintang dan asteroid */
    body {
        position: relative;
        overflow-x: hidden;
        background: radial-gradient(#000014, #0d0d2b);
        color: #fff;
    }

    .stars, .asteroids {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: 0;
    }

    .star {
        position: absolute;
        background: radial-gradient(white, rgba(255,255,255,0));
        border-radius: 50%;
        width: 2px;
        height: 2px;
        opacity: 0.8;
        animation: twinkle 5s infinite;
    }

    @keyframes twinkle {
        0%, 100% { opacity: 0.2; }
        50% { opacity: 1; }
    }

    .asteroid {
        position: absolute;
        width: 20px;
        height: 20px;
        background: gray;
        border-radius: 50%;
        box-shadow: 0 0 10px rgba(255,255,255,0.2);
        animation: asteroidMove linear infinite;
    }

    @keyframes asteroidMove {
        0% { transform: translateY(-50px) translateX(0) rotate(0deg); opacity: 0; }
        50% { opacity: 1; }
        100% { transform: translateY(120vh) translateX(200px) rotate(360deg); opacity: 0; }
    }

    /* Hero Section Modern */
    section.bg-primary {
        position: relative;
        z-index: 1;
        background: linear-gradient(135deg, #1f1f47, #0f0f23);
    }

    section.bg-primary h1 {
        text-shadow: 0 0 20px #00f6ff, 0 0 40px #00f6ff;
    }

    section.bg-primary p {
        font-size: 1.2rem;
        color: #c0c0ff;
    }

    .btn-light {
        background: #00f6ff;
        color: #0a0a0a;
        font-weight: 600;
        transition: 0.3s;
    }

    .btn-light:hover {
        background: #00d4ff;
        color: #fff;
    }

    /* Cards Modern */
    .card {
        border-radius: 15px;
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .card:hover {
        transform: translateY(-10px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    }
</style>

{{-- Hero Section --}}
<section class="bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-3">
                    Anugerah jaya Elektronik
                </h1>
                <p class="lead mb-4">
                    Temukan berbagai produk berkualitas dengan harga terbaik.
                    Gratis ongkir untuk pembelian pertama!
                </p>
                <a href="{{ route('catalog.index') }}" class="btn btn-light btn-lg">
                    <i class="bi bi-bag me-2"></i>Mulai Belanja
                </a>
            </div>
            <div class="col-lg-6 d-none d-lg-block text-center">
                <img src="{{ asset('images/fototoko.jpg.png') }}" alt="Shopping" class="img-fluid"
                    style="max-height: 400px;">
            </div>
        </div>
    </div>
</section>

{{-- Kategori --}}
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-4">Kategori Populer</h2>
        <div class="row g-4">
            @foreach($categories as $category)
            <div class="col-3 col-md-4 col-lg-2">
                <a href="{{ route('catalog.index', ['category' => $category->slug]) }}" class="text-decoration-none">
                    <div class="card border-0 shadow-sm text-center h-100">
                        <div class="card-body">
                            <img src="{{ $category->image_url }}" alt="{{ $category->name }}"
                                class="rounded-circle mb-4 ml-" width="80" height="80" style="margin-left: 25px; object-fit: cover;">
                            <h6 class="card-title mb-0">{{ $category->name }}</h6>
                            <small class="text-muted">{{ $category->products_count }} produk</small>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Produk Unggulan --}}
<section class="py-5 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Produk Unggulan</h2>
            <a href="{{ route('catalog.index') }}" class="btn btn-outline-primary">
                Lihat Semua <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        <div class="row g-4">
            @foreach($featuredProducts as $product)
            <div class="col-6 col-md-4 col-lg-3">
                @include('profile.partials.product-card', ['product' => $product])
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Promo Banner --}}
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card bg-warning text-dark border-0" style="min-height: 200px;">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <h3>Flash Sale!</h3>
                        <p>Diskon hingga 50% untuk produk pilihan</p>
                        <a href="#" class="btn btn-dark" style="width: fit-content;">
                            Lihat Promo
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-info text-white border-0" style="min-height: 200px;">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <h3>Member Baru?</h3>
                        <p>Dapatkan voucher Rp 50.000 untuk pembelian pertama</p>
                        <a href="{{ route('register') }}" class="btn btn-light" style="width: fit-content;">
                            Daftar Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Produk Terbaru --}}
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-4">Produk Terbaru</h2>
        <div class="row g-4">
            @foreach($latestProducts as $product)
            <div class="col-6 col-md-4 col-lg-3">
                @include('profile.partials.product-card', ['product' => $product])
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- JavaScript Animasi Bintang dan Asteroid --}}
<script>
    // Membuat bintang
    const starsContainer = document.getElementById('stars');
    for(let i = 0; i < 150; i++) {
        const star = document.createElement('div');
        star.classList.add('star');
        star.style.top = Math.random() * 100 + 'vh';
        star.style.left = Math.random() * 100 + 'vw';
        star.style.width = star.style.height = Math.random() * 2 + 1 + 'px';
        starsContainer.appendChild(star);
    }

    // Membuat asteroid
    const asteroidsContainer = document.getElementById('asteroids');
    for(let i = 0; i < 10; i++) {
        const asteroid = document.createElement('div');
        asteroid.classList.add('asteroid');
        asteroid.style.top = Math.random() * -100 + 'px';
        asteroid.style.left = Math.random() * 100 + 'vw';
        asteroid.style.width = asteroid.style.height = (Math.random() * 20 + 10) + 'px';
        asteroid.style.animationDuration = (5 + Math.random() * 10) + 's';
        asteroidsContainer.appendChild(asteroid);
    }
</script>

@endsection
