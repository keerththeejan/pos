@extends('layouts.auth2')
@section('title', 'Welcome')
@inject('request', 'Illuminate\Http\Request')
@section('hide_auth2_topbar', true)
@section('no_top_padding', true)
@section('content')

    @php
        // Live data for header dropdowns
        $header_categories = \App\Category::where('category_type', 'product')
            ->where('parent_id', 0)
            ->orderBy('name', 'asc')
            ->get(['id','name','image_path']);
        $brands = \App\Brands::orderBy('name','asc')->get();
        // Dynamic countries: distinct from Contacts & Business Locations, fallback to Currencies
        $contact_countries = \App\Contact::whereNotNull('country')
            ->where('country','!=','')
            ->distinct()
            ->orderBy('country','asc')
            ->pluck('country')
            ->toArray();
        $location_countries = \App\BusinessLocation::whereNotNull('country')
            ->where('country','!=','')
            ->distinct()
            ->orderBy('country','asc')
            ->pluck('country')
            ->toArray();
        $currency_countries = \App\Currency::select('country')
            ->whereNotNull('country')
            ->where('country','!=','')
            ->distinct()
            ->orderBy('country','asc')
            ->pluck('country')
            ->toArray();

        $countries = array_values(array_unique(array_filter(array_merge($contact_countries, $location_countries, $currency_countries))));
        // sort alphabetically and show all
        sort($countries, SORT_FLAG_CASE | SORT_NATURAL);
        
        // Fetch ALL top-level product categories for live display (no limit)
        $categories = \App\Category::where('category_type', 'product')
            ->where('parent_id', 0)
            ->orderBy('name', 'asc')
            ->get(['id','name','image_path']);
        
        // Build hero slides from ACTIVE banners (no stock fallbacks)
        $hero_slides = [];
        $banners = \App\Banner::where('is_active', 1)
            ->orderByDesc('id')
            ->get(['id','title','image']);
        $bannerDir = \App\Banner::uploadDir();
        foreach ($banners as $b) {
            if (empty($b->image)) { continue; }
            $path = trim($bannerDir, '/').'/'.ltrim($b->image, '/');
            $url  = asset($path);
            // Ensure the public file exists to avoid broken images
            $ok = file_exists(public_path($path));
            if (!$ok) { continue; }
            $hero_slides[] = ['img' => $url, 'name' => $b->title ?? ''];
            if (count($hero_slides) >= 3) break;
        }
        
        // Get products to showcase (latest active, for sale)
        // Note: 'featured' column doesn't exist on products. Use latest active items instead.
        $featured_products = \App\Product::active()
            ->productForSales()
            ->with(['variations'])
            ->latest('id')
            ->limit(8)
            ->get();
            
        // Get brands for brands section
        $brands_section = \App\Brands::inRandomOrder()->limit(6)->get();

        // Helper to resolve category image URL (supports storage & legacy uploads path)
        $resolveCatImg = function ($raw) {
            if (empty($raw)) { return null; }
            $raw = str_replace('\\', '/', $raw);
            if (\Illuminate\Support\Str::startsWith($raw, ['http://', 'https://'])) {
                return $raw;
            }
            // Normalize '/storage/public/..' -> '/storage/..'
            if (\Illuminate\Support\Str::startsWith($raw, ['/storage/public/', 'storage/public/'])) {
                $raw = preg_replace('#^/?storage/public/#', '/storage/', $raw);
            }
            if (\Illuminate\Support\Str::startsWith($raw, ['/storage/', 'storage/'])) {
                $candidate = ltrim(\Illuminate\Support\Str::startsWith($raw, '/') ? $raw : '/' . $raw, '/');
                if (file_exists(public_path($candidate))) {
                    return \Illuminate\Support\Str::startsWith($raw, '/') ? $raw : '/' . $raw;
                }
                $legacy = 'uploads/public/category_images/' . basename($raw);
                if (file_exists(public_path($legacy))) { return '/' . $legacy; }
                return \Illuminate\Support\Str::startsWith($raw, '/') ? $raw : '/' . $raw;
            }
            if (\Illuminate\Support\Str::startsWith($raw, 'public/')) {
                // Map 'public/category_images/..' -> '/storage/category_images/..'
                $converted = preg_replace('/^public\\\//', 'storage/', $raw);
                return '/' . $converted;
            }
            if (\Storage::exists('public/' . ltrim($raw, '/'))) { return \Storage::url(ltrim($raw, '/')); }
            if (\Storage::exists(ltrim($raw, '/'))) { return \Storage::url(ltrim($raw, '/')); }
            $legacy = 'uploads/public/category_images/' . basename($raw);
            if (file_exists(public_path($legacy))) { return '/' . $legacy; }
            return '/' . ltrim($raw, '/');
        };
    @endphp

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f9fafb;
            color: #1f2937;
            line-height: 1.6;
        }
        
        /* Navbar Styles */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            width: 100%;
            background: white;
            z-index: 50;
            border-bottom: 1px solid #e5e7eb;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }
        
        .navbar-container {
            max-width: 80rem;
            margin-left: auto;
            margin-right: auto;
            padding-left: 1rem;
            padding-right: 1rem;
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.25rem;
        }
        
        .logo {
            font-size: 1.5rem;
            font-weight: 800;
            letter-spacing: -0.025em;
            color: #6b1b1b;
            text-decoration: none;
        }
        
        .nav-menus {
            display: flex;
            align-items: center;
            gap: 1.25rem;
            flex: 1 1 0%;
            justify-content: flex-start; /* keep left so right block can go flush right */
        }
        
        .dropdown {
            position: relative;
        }
        
        .dropdown-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            color: #1f2937;
            font-size: 0.875rem;
            line-height: 1.25rem;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0.5rem 0.75rem;
            border-radius: 0.375rem;
            transition: all 0.2s;
        }
        
        .dropdown-btn:hover {
            background-color: #f3f4f6;
        }
        
        .dropdown-content {
            position: absolute;
            display: none;
            z-index: 20;
            margin-top: 0.5rem;
            width: 16rem;
            max-height: 20rem;
            overflow: auto;
            background: white;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 0.5rem 0;
        }
        
        .dropdown:hover .dropdown-content {
            display: block;
        }
        
        .dropdown-item {
            display: flex; /* inline icon + text on one line */
            align-items: center; /* vertical center */
            gap: 0.5rem; /* space between image and text */
            padding: 0.5rem 1rem;
            color: #374151;
            text-decoration: none;
            transition: all 0.2s;
            white-space: nowrap; /* prevent wrapping under image */
        }
        
        .dropdown-item:hover {
            background-color: #f9fafb;
            color: #111827;
        }
        
        .badge {
            display: inline-flex;
            align-items: center;
            border-radius: 0.375rem;
            color: white;
            font-size: 0.75rem;
            line-height: 1rem;
            font-weight: 600;
            padding: 0.25rem 0.75rem;
        }
        
        .badge-new {
            background: #059669;
        }
        
        .badge-sale {
            background: #b45309;
        }
        
        .auth-buttons {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        /* Right side of the navbar (Login/Register/Cart) */
        .navbar-right {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 2.5rem;
            padding-left: 1rem;
            padding-right: 1rem;
            border-radius: 0.375rem;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s;
        }
        
        .btn-outline {
            border: 1px solid #d1d5db;
            color: #1f2937;
        }
        
        .btn-outline:hover {
            background-color: #f9fafb;
        }
        
        .btn-primary {
            background: #4f46e5;
            color: white;
        }
        
        .btn-primary:hover {
            background: #4338ca;
        }
        
        .btn-secondary {
            background: #6b7280;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #4b5563;
        }
        
        .nav-spacer {
            height: 72px;
        }
        
        /* Hero Section */
        .hero {
            position: relative;
            width: 100%;
            height: 500px;
            overflow: hidden;
            background-color: #f3f4f6;
        }
        
        .hero-img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: opacity 0.3s ease;
        }
        
        .hero-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,0.2);
        }
        
        .hero-caption {
            position: absolute;
            left: 50%;
            bottom: 1rem;
            transform: translateX(-50%);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: #fff;
            z-index: 5;
        }
        
        .caption-text {
            font-size: 0.875rem;
            line-height: 1.25rem;
            font-weight: 600;
        }
        
        .caption-line {
            width: 4rem;
            height: 2px;
            background: rgba(255,255,255,0.8);
        }
        
        .hero-indicator {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #fff;
            margin: 0 4px;
            transition: all 0.3s;
        }
        
        .hero-indicator.active {
            background: white;
            transform: scale(1.2);
        }
        
        /* Categories Section */
        .categories-section, .featured-section, .brands-section {
            width: 100%;
            padding-top: 2.5rem;
            padding-bottom: 2.5rem;
        }
        
        .categories-section {
            background: linear-gradient(to bottom right, white, #e0e7ff);
        }
        
        .featured-section {
            background: white;
        }
        
        .brands-section {
            background: #f9fafb;
        }
        
        .container {
            max-width: 80rem;
            margin-left: auto;
            margin-right: auto;
            padding-left: 1.25rem;
            padding-right: 1.25rem;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .section-title h2 {
            font-size: 1.875rem;
            line-height: 2.25rem;
            font-weight: 800;
            color: black;
        }
        
        .categories-scroller, .featured-scroller, .brands-grid {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            border: 1px solid #f3f4f6;
            padding: 1.5rem;
        }
        
        .categories-wrapper, .brands-wrapper {
            display: flex;
            gap: 1rem;
            overflow-x: auto;
            padding-bottom: 0.5rem;
            scroll-padding: 1rem;
            scroll-snap-type: x mandatory;
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        
        .categories-wrapper::-webkit-scrollbar, .brands-wrapper::-webkit-scrollbar {
            display: none;
        }
        
        .categories-container, .brands-container {
            display: flex;
            gap: 1rem;
        }
        
        .category-card, .brand-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            border: 1px solid #f3f4f6;
            transition: all 0.3s ease-in-out;
            width: 18rem;
            min-width: 18rem;
            height: 18rem;
            scroll-snap-align: start;
            text-decoration: none;
        }
        
        .category-card:hover, .brand-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        
        .category-icon, .brand-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 7rem;
            height: 7rem;
            margin-bottom: 1rem;
            border-radius: 9999px;
            background: #f9fafb;
            transition: background-color 0.3s;
        }
        
        .category-card:hover .category-icon, .brand-card:hover .brand-icon {
            background: #e0e7ff;
        }
        
        .category-img, .brand-img {
            object-fit: contain;
            object-position: center;
            width: 100%;
            height: 100%;
            border-radius: 50%;
        }
        
        .category-name, .brand-name {
            font-size: 1.25rem;
            line-height: 1.75rem;
            font-weight: 600;
            color: #111827;
            text-align: center;
            margin-bottom: 0.5rem;
        }
        
        .category-desc, .brand-desc {
            color: #6b7280;
            font-size: 0.875rem;
            line-height: 1.25rem;
            text-align: center;
        }
        
        /* Featured Products - single-row scroller */
        .featured-scroller {
            overflow-x: auto;
            overflow-y: hidden;
            padding-bottom: 0.5rem;
            margin-bottom: 0.5rem;
            scroll-snap-type: x mandatory;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none; /* Firefox */
            ms-overflow-style: none; /* IE 10+ */
            padding-left: 0.75rem; /* inset like categories */
            padding-right: 0.25rem;
        }
        .featured-scroller::-webkit-scrollbar { display: none; }
        .featured-track {
            display: flex;
            gap: 1rem;
        }
        .featured-track .product-card {
            /* Exactly 4 cards per viewport width on desktop (gap = 1rem -> 3 gaps total = 3rem) */
            flex: 0 0 calc((100% - 3rem) / 4);
            max-width: none;
            scroll-snap-align: start;
        }
        /* Exactly 4 per row on desktop */
        .categories-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
        }
        /* Single-row horizontal scroller for categories */
        .categories-scroller-wrap { position: relative; }
        .categories-scroller {
            overflow-x: auto;
            overflow-y: hidden;
            padding-bottom: 0.5rem;
            margin-bottom: 0.5rem;
            scroll-snap-type: x mandatory;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none; /* Firefox */
            ms-overflow-style: none; /* IE 10+ */
            /* add a little inset so first/last card don't stick to edges */
            padding-left: 0.75rem;
            padding-right: 0.25rem;
        }
        .categories-scroller::-webkit-scrollbar { display: none; }
        .categories-track {
            display: flex;
            gap: 1rem; /* was 1.5rem: slightly wider cards */
        }
        .categories-track .product-card {
            /* Exactly 4 cards per viewport width on desktop (gap = 1rem -> 3 gaps total = 3rem) */
            flex: 0 0 calc((100% - 3rem) / 4);
            max-width: none;
            scroll-snap-align: start;
        }
        /* Arrow controls */
        .cat-nav {
            position: absolute;
            right: 0.25rem;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            z-index: 2;
        }
        .cat-nav-btn {
            width: 38px;
            height: 38px;
            border-radius: 9999px;
            border: none;
            background: #2563eb; /* blue-600 */
            color: #fff;
            display: grid;
            place-items: center;
            box-shadow: 0 4px 10px rgba(0,0,0,.15);
            cursor: pointer;
        }
        .cat-nav-btn:disabled { opacity: .4; cursor: default; }
        
        .product-card {
            background: white;
            border-radius: 0.75rem;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .product-image {
            width: 100%;
            height: 240px; /* was 200px */
            object-fit: cover;
        }
        
        .product-info {
            padding: 1.25rem; /* was 1rem */
        }
        
        .product-name {
            font-size: 1.25rem; /* was 1.125rem */
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .product-price {
            font-size: 1.25rem;
            font-weight: 700;
            color: #059669;
            margin-bottom: 0.5rem;
        }
        
        .product-stock {
            font-size: 0.875rem;
            color: #6b7280;
            margin-bottom: 1rem;
        }
        
        .view-all {
            text-align: center;
            margin-top: 2rem;
        }
        
        /* Footer */
        .footer {
            background: #1f2937;
            color: white;
            padding: 3rem 0;
        }
        
        .footer-container {
            max-width: 80rem;
            margin: 0 auto;
            padding: 0 1.25rem;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 2rem;
        }
        
        .footer-section h3 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #f9fafb;
        }
        
        .footer-section p {
            margin-bottom: 1rem;
            color: #d1d5db;
        }
        
        .footer-links {
            list-style: none;
        }
        
        .footer-links li {
            margin-bottom: 0.5rem;
        }
        
        .footer-links a {
            color: #d1d5db;
            text-decoration: none;
            transition: color 0.2s;
        }
        
        .footer-links a:hover {
            color: white;
        }
        
        .footer-contact {
            color: #d1d5db;
            margin-bottom: 0.5rem;
        }
        
        .newsletter-form {
            display: flex;
            margin-top: 1rem;
        }
        
        .newsletter-input {
            flex: 1;
            padding: 0.5rem;
            border: none;
            border-radius: 0.25rem 0 0 0.25rem;
        }
        
        .newsletter-button {
            background: #4f46e5;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0 0.25rem 0.25rem 0;
            cursor: pointer;
        }
        
        .footer-bottom {
            text-align: center;
            padding: 1.5rem 0 0;
            margin-top: 2rem;
            border-top: 1px solid #374151;
            color: #9ca3af;
        }
        
        .footer-bottom-links {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            margin-top: 0.5rem;
        }
        
        .footer-bottom-links a {
            color: #9ca3af;
            text-decoration: none;
        }
        
        .footer-bottom-links a:hover {
            color: white;
        }
        
        /* Loading animation */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        /* Search form */
        .search-form {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .search-input {
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            padding-left: 0.75rem;
            padding-right: 0.75rem;
            height: 2.5rem;
            width: 18rem;
        }
        
        .search-button {
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            padding-left: 1rem;
            padding-right: 1rem;
            height: 2.5rem;
            background: white;
            cursor: pointer;
        }
        
        /* Responsive */
        @media (max-width: 1024px) {
            .footer-container {
                grid-template-columns: repeat(2, 1fr);
            }
            .categories-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 768px) {
            .footer-container {
                grid-template-columns: 1fr;
            }
            
            .nav-menus {
                display: none;
            }
            .categories-grid {
                grid-template-columns: 1fr;
            }
            /* Tablet: show 2 cards */
            .categories-track .product-card {
                flex-basis: calc((100% - 1rem) / 2); /* match reduced gap */
                max-width: none;
            }
            .featured-track .product-card {
                flex-basis: calc((100% - 1rem) / 2);
                max-width: none;
            }
        }
        /* Small screens */
        @media (max-width: 640px) {
            .navbar-container { padding-left: 0.75rem; padding-right: 0.75rem; gap: 0.75rem; }
            .logo { font-size: 1.25rem; }
            .navbar-right { gap: 0.5rem; }
            /* Keep search compact */
            .search-input { width: 10rem; }
            .btn, .search-button { padding-left: 0.75rem; padding-right: 0.75rem; height: 2.25rem; }

            .hero { height: 320px; }
            .caption-text { font-size: 0.75rem; }
            .hero-caption { gap: 0.5rem; }

            .container { padding-left: 0.75rem; padding-right: 0.75rem; }
            .categories-section, .featured-section, .brands-section { padding-top: 2rem; padding-bottom: 2rem; }

            /* Cards smaller on mobile scrollers */
            .category-card, .brand-card { width: 12rem; min-width: 12rem; height: 12rem; padding: 1rem; }
            .category-icon, .brand-icon { width: 5rem; height: 5rem; }
            .category-name, .brand-name { font-size: 1rem; }
            .category-desc, .brand-desc { font-size: 0.8125rem; }

            /* Featured: show 2 cards in viewport */
            .featured-track .product-card { flex-basis: calc((100% - 1rem) / 2); }
            .product-image { height: 180px; }
            .product-info { padding: 1rem; }
            .product-name, .product-price { font-size: 1.0625rem; }

            /* Dropdowns become full-width panels */
            .dropdown-content { position: static; width: 100%; max-height: 16rem; box-shadow: none; }
        }

        /* Extra small phones */
        @media (max-width: 480px) {
            .search-form { display: none; } /* hide search to save space */
            .hero { height: 260px; }
            .featured-track .product-card { flex-basis: 100%; }
            .footer-container { grid-template-columns: 1fr; }
            .badge { display: none; }
        }
    </style>

    <!-- Top navbar -->
    <div class="navbar">
        <div class="navbar-container">
            <!-- Left: Logo/Title -->
            <a href="{{ url('/') }}" class="logo">
                {{ config('app.name', 'Store') }}
            </a>

            <!-- Middle: Menus + badges + search -->
            <div class="nav-menus">
                <!-- All products dropdown (Categories) -->
                <div class="dropdown">
                    <button class="dropdown-btn">
                        <span>All products</span>
                        <svg style="width: 1rem; height: 1rem;" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M5.23 7.21a.75.75 0 011.06.02L10 11.188l3.71-3.957a.75.75 0 111.08 1.04l-4.24 4.52a.75.75 0 01-1.08 0l-4.24-4.52a.75.75 0 01.02-1.06z"/>
                        </svg>
                    </button>
                    <div class="dropdown-content">
                        <ul style="padding-top: 0.5rem; padding-bottom: 0.5rem;">
                            @forelse($header_categories as $c)
                                @php
                                  $cat_img = $resolveCatImg($c->image_path ?? null);
                                @endphp
                                <li>
                                  <a class="dropdown-item" href="{{ url('/products?category_id='.$c->id) }}">
                                    @if($cat_img)
                                      <img src="{{ $cat_img }}" alt="{{ $c->name }}" style="width: 24px; height: 24px; object-fit: cover; border-radius: 0.25rem;" onerror="this.style.display='none'">
                                    @else
                                      <span style="width: 24px; height: 24px; border-radius: 0.25rem; background:#f3f4f6; display:inline-flex; align-items:center; justify-content:center; color:#6b7280; font-size:12px;">{{ mb_substr($c->name,0,1) }}</span>
                                    @endif
                                    <span>{{ $c->name }}</span>
                                  </a>
                                </li>
                              @empty
                                <li class="dropdown-item">No categories</li>
                              @endforelse
                        </ul>
                    </div>
                </div>

                <!-- Country of origin dropdown (live distinct list) -->
                <div class="dropdown">
                    <button class="dropdown-btn">
                        <span>Country of origin</span>
                        <svg style="width: 1rem; height: 1rem;" viewBox="0 0 20 20" fill="CurrentColor">
                            <path d="M5.23 7.21a.75.75 0 011.06.02L10 11.188l3.71-3.957a.75.75 0 111.08 1.04l-4.24 4.52a.75.75 0 01-1.08 0l-4.24-4.52a.75.75 0 01.02-1.06z"/>
                        </svg>
                    </button>
                    <div class="dropdown-content">
                        <ul style="padding-top: 0.5rem; padding-bottom: 0.5rem;">
                            @php
                                // Minimal mapping for common countries to ISO2 (extend as needed)
                                $flag_codes = [
                                  'United States' => 'us', 'USA' => 'us', 'US' => 'us',
                                  'United Kingdom' => 'gb', 'UK' => 'gb', 'Great Britain' => 'gb',
                                  'Germany' => 'de', 'Japan' => 'jp', 'China' => 'cn', 'France' => 'fr',
                                  'Italy' => 'it', 'Canada' => 'ca', 'South Korea' => 'kr', 'Korea, Republic of' => 'kr',
                                  'India' => 'in', 'Sri Lanka' => 'lk', 'Spain' => 'es', 'Netherlands' => 'nl',
                                ];
                              @endphp
                              @forelse($countries as $country)
                                @php
                                  $code = $flag_codes[$country] ?? null;
                                  $flag_img = $code ? 'https://flagcdn.com/w40/'.strtolower($code).'.png' : null;
                                @endphp
                                <li>
                                  <a class="dropdown-item" href="{{ url('/products?country_of_origin='.urlencode($country)) }}">
                                    @if($flag_img)
                                      <img src="{{ $flag_img }}" alt="{{ $country }}" style="width: 24px; height: 18px; object-fit: cover; border-radius: 2px;" onerror="this.style.display='none'">
                                    @else
                                      <span style="width: 24px; height: 24px; border-radius: 0.25rem; background:#f3f4f6; display:inline-flex; align-items:center; justify-content:center; color:#6b7280; font-size:12px;">{{ mb_substr($country,0,1) }}</span>
                                    @endif
                                    <span>{{ $country }}</span>
                                  </a>
                                </li>
                              @empty
                                <li class="dropdown-item">No countries</li>
                              @endforelse
                        </ul>
                    </div>
                </div>

                <!-- All brands dropdown -->
                <div class="dropdown">
                    <button class="dropdown-btn">
                        <span>All brands</span>
                        <svg style="width: 1rem; height: 1rem;" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M5.23 7.21a.75.75 0 011.06.02L10 11.188l3.71-3.957a.75.75 0 111.08 1.04l-4.24 4.52a.75.75 0 01-1.08 0l-4.24-4.52a.75.75 0 01.02-1.06z"/>
                        </svg>
                    </button>
                    <div class="dropdown-content">
                        <ul style="padding-top: 0.5rem; padding-bottom: 0.5rem;">
                            @forelse($brands as $b)
                                @php
                                  // Try common columns for brand image
                                  $raw_b = data_get($b, 'logo') ?? data_get($b, 'image') ?? data_get($b, 'brand_image') ?? data_get($b, 'image_url');
                                  $b_img = null;
                                  if (!empty($raw_b)) {
                                      $b_img = \Illuminate\Support\Str::startsWith($raw_b, ['http://','https://']) ? $raw_b : \Storage::url($raw_b);
                                  }
                                @endphp
                                <li>
                                  <a class="dropdown-item" href="{{ url('/products?brand_id='.$b->id) }}">
                                    @if($b_img)
                                      <img src="{{ $b_img }}" alt="{{ $b->name }}" style="width: 24px; height: 24px; object-fit: cover; border-radius: 9999px;" onerror="this.style.display='none'">
                                    @else
                                      <span style="width: 24px; height: 24px; border-radius: 9999px; background:#f3f4f6; display:inline-flex; align-items:center; justify-content:center; color:#6b7280; font-size:12px;">{{ mb_substr($b->name,0,1) }}</span>
                                    @endif
                                    <span>{{ $b->name }}</span>
                                  </a>
                                </li>
                              @empty
                                <li class="dropdown-item">No brands</li>
                              @endforelse
                        </ul>
                    </div>
                </div>

                <!-- Badges (right next to All brands) -->
                <span class="badge badge-new">New</span>
                <span class="badge badge-sale">Sale</span>

                <!-- Search + Auth (Login/Register next to search) -->
                <form action="{{ url('/products') }}" method="GET" class="search-form">
                    <input type="text" name="search" placeholder="Search" class="search-input">
                    <button type="submit" class="search-button">Search</button>
                </form>
            </div>

            <!-- Right: Dashboard (auth only) + Cart -->
            <div class="navbar-right">
                @auth
                    <a href="{{ url('/home') }}" style="color: #1f2937;">Dashboard</a>
                    <a href="{{ url('/logout') }}" style="color: #1f2937;">Logout</a>
                @endauth
                @guest
                    <div class="auth-buttons">
                        <a href="{{ url('/login') }}" class="btn btn-outline">Login</a>
                        <a href="{{ url('/register') }}" class="btn btn-primary">Register</a>
                    </div>
                @endguest
                <a href="{{ url('/cart') }}" title="Cart" style="color:#1f2937; display:inline-flex; align-items:center;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 12.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Spacer to offset fixed navbar height -->
    <div class="nav-spacer"></div>

    @php
        $first_hero = (!empty($hero_slides) && isset($hero_slides[0]))
            ? $hero_slides[0]
            : ['img' => asset('img/default.png'), 'name' => config('app.name', 'Store')];
    @endphp
        @if(!empty($hero_slides) && count($hero_slides) > 1)
        <script>
            window.addEventListener('load', function(){
                try {
                    const slides = @json($hero_slides);
                    const img = document.getElementById('hero-img');
                    const cap = document.getElementById('hero-caption');
                    if (!img || !cap || !Array.isArray(slides) || slides.length < 2) return;
                    let i = 0;
                    function show(n){
                        const s = slides[n];
                        if(!s) return;
                        img.style.opacity = 0;
                        setTimeout(() => {
                            img.src = s.img;
                            img.alt = s.name || '';
                            cap.textContent = s.name || '';
                            img.style.opacity = 1;
                        }, 300);
                    }
                    setInterval(function(){
                        i = (i + 1) % slides.length;
                        show(i);
                    }, 4000);
                } catch (e) { /* no-op */ }
            });
        </script>
        @endif
        <!-- Hero banner -->
        <section class="hero">
            <img id="hero-img" class="hero-img" src="{{ $first_hero['img'] }}" alt="{{ $first_hero['name'] }}" loading="eager" onerror="this.onerror=null; this.src='{{ asset('img/default.png') }}';">
            <div class="hero-overlay"></div>
            <div class="hero-caption">
                <span id="hero-caption" class="caption-text">{{ $first_hero['name'] }}</span>
                <span class="caption-line"></span>
            </div>
        </section>

    @if($categories->count())
        <div class="categories-section">
            <div class="container">
                <div class="section-title">
                    <h2>CATEGORIES</h2>
                </div>
                
                <!-- Categories: single-row horizontal scroller with arrows -->
                <div class="categories-scroller-wrap">
                    <div class="categories-scroller" id="catScroller">
                        <div class="categories-track">
                        @foreach($categories as $cat)
                            @php
                                $img = $resolveCatImg($cat->image_path ?? null) ?? asset('img/default.png');
                            @endphp
                            <div class="product-card" style="text-decoration:none; color:inherit;">
                                <a href="{{ url('/products?category_id='.$cat->id) }}" style="display:block;">
                                    <img src="{{ $img }}" alt="{{ $cat->name }}" class="product-image" onerror="this.src='{{ asset('img/default.png') }}'">
                                </a>
                                <div class="product-info">
                                    <a href="{{ url('/products?category_id='.$cat->id) }}" style="text-decoration:none; color:inherit;">
                                        <h3 class="product-name">{{ $cat->name }}</h3>
                                    </a>
                                    <div class="product-stock" style="margin-bottom:0.75rem;">Explore products in this category</div>
                                    <a href="{{ url('/taxonomies?type=product') }}" class="btn btn-secondary" style="width:100%;">View Categories</a>
                                </div>
                            </div>
                        @endforeach
                        </div>
                    </div>
                    
                </div>
                <div class="view-all">
                    <a href="{{ url('/taxonomies?type=product') }}" class="btn btn-primary">View All Categories</a>
                </div>
            </div>
        </div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var scroller = document.getElementById('catScroller');
            var prev = document.getElementById('catPrev');
            var next = document.getElementById('catNext');
            if (!scroller || !prev || !next) return;

            var track = scroller.querySelector('.categories-track');

            function getStep() {
                var card = track ? track.querySelector('.product-card') : null;
                if (!card) return 0;
                var style = window.getComputedStyle(track);
                var gapRaw = style.columnGap || style.gap || '24px';
                var gap = parseFloat(gapRaw);
                if (isNaN(gap)) gap = 24; // fallback ~1.5rem
                return card.offsetWidth + gap;
            }

            function updateDisabled() {
                var max = scroller.scrollWidth - scroller.clientWidth - 1;
                prev.disabled = scroller.scrollLeft <= 0;
                next.disabled = scroller.scrollLeft >= max;
            }

            function scrollByStep(dir) {
                var step = getStep();
                if (!step) step = scroller.clientWidth * 0.25;
                scroller.scrollBy({ left: dir * step, behavior: 'smooth' });
                setTimeout(updateDisabled, 350);
            }

            prev.addEventListener('click', function(){ scrollByStep(-1); });
            next.addEventListener('click', function(){ scrollByStep(1); });
            scroller.addEventListener('scroll', updateDisabled, { passive: true });
            updateDisabled();
        });
    </script>

    <!-- Featured Products Section -->
    <div class="featured-section">
        <div class="container">
            <div class="section-title">
                <h2>FEATURED PRODUCTS</h2>
            </div>
            
            <div class="featured-scroller">
                <div class="featured-track">
                @forelse($featured_products as $product)
                    <div class="product-card">
                        @php
                            // Use accessor from App\Product: getImageUrlAttribute
                            $product_img = $product->image_url ?? 'https://via.placeholder.com/300x200?text=No+Image';
                            // Compute display price from variations
                            $price = 0;
                            $purchase = 0;
                            if ($product->relationLoaded('variations') && $product->variations->isNotEmpty()) {
                                $v = $product->variations
                                    ->sortBy(function($x){ return is_null($x->sell_price_inc_tax) ? INF : $x->sell_price_inc_tax; })
                                    ->first();
                                $price = ($v->sell_price_inc_tax ?? $v->default_sell_price ?? 0);
                                $purchase = ($v->dpp_inc_tax ?? $v->default_purchase_price ?? 0);
                            }
                        @endphp
                        <img src="{{ $product_img }}" alt="{{ $product->name }}" class="product-image" onerror="this.src='https://via.placeholder.com/300x200?text=Image+Error'">
                        <div class="product-info">
                            <h3 class="product-name">{{ $product->name }}</h3>
                            <div class="product-price">Selling: ${{ number_format($price, 2) }}</div>
                            <div class="product-price" style="color:#6b7280; font-weight:600;">Purchase: ${{ number_format($purchase, 2) }}</div>
                            {{-- Stock display omitted by default; enable when per-location stock is available --}}
                            @guest
                                <a href="{{ url('/login') }}" class="btn btn-secondary" style="width: 100%;">Login to Buy</a>
                            @else
                                <a href="{{ url('/products/'.$product->id) }}" class="btn btn-primary" style="width: 100%;">Buy Now</a>
                            @endguest
                        </div>
                    </div>
                @empty
                    <p>No featured products available</p>
                @endforelse
                </div>
            </div>
            
            <div class="view-all">
                <a href="{{ url('/products') }}" class="btn btn-primary">View All Products</a>
            </div>
        </div>
    </div>

    <!-- Brands Section -->
    <div class="brands-section">
        <div class="container">
            <div class="section-title">
                <h2>OUR BRANDS</h2>
            </div>
            
            <div class="brands-scroller">
                <div class="brands-wrapper">
                    <div class="brands-container">
                        @forelse($brands_section as $brand)
                            @php
                                $brand_img = null;
                                if (!empty($brand->logo)) {
                                    $raw = $brand->logo;
                                    $brand_img = \Illuminate\Support\Str::startsWith($raw, ['http://', 'https://']) ? $raw : \Storage::url($raw);
                                } else {
                                    // Use placeholder with brand initial
                                    $brand_initial = mb_substr($brand->name, 0, 1);
                                }
                            @endphp
                            <a href="{{ url('/products?brand_id='.$brand->id) }}" class="brand-card">
                                <div class="brand-icon">
                                    @if(isset($brand_img))
                                        <img src="{{ $brand_img }}" alt="{{ $brand->name }}" class="brand-img" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div style="display: none; color: #4f46e5; align-items: center; justify-content: center; font-size: 2rem; font-weight: bold;">
                                            {{ $brand_initial }}
                                        </div>
                                    @else
                                        <div style="color: #4f46e5; font-size: 2rem; font-weight: bold;">
                                            {{ $brand_initial }}
                                        </div>
                                    @endif
                                </div>
                                <h3 class="brand-name">{{ $brand->name }}</h3>
                            </a>
                        @empty
                            <p>No brands available</p>
                        @endforelse
                    </div>
                </div>
            </div>
            
            <div class="view-all">
                <a href="{{ url('/brands') }}" class="btn btn-primary">View All Brands</a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-section">
                <h3>About store</h3>
                <p>Subscribe to our newsletter to get exclusive updates about our latest products, special offers, and seasonal discounts.</p>
            </div>
            
            
            
            <div class="footer-section">
                <h3>Contact Info</h3>
                <p class="footer-contact">kilinochchi paranthan</p>
                <p class="footer-contact">0214563789</p>
                <p class="footer-contact">yathrhgurhu.com</p>
                <p class="footer-contact">monday  7am to 7pm-sunday</p>
            </div>
            
            <div class="footer-section">
                <h3>Newsletter</h3>
                <p>Subscribe to our newsletter to get exclusive updates about our latest products, special offers, and seasonal discounts.</p>
                <form class="newsletter-form">
                    <input type="email" placeholder="Your Email Address" class="newsletter-input" required>
                    <button type="submit" class="newsletter-button">Subscribe</button>
                </form>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>© 2025 E-Store. All rights reserved.</p>
            <div class="footer-bottom-links">
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
                <a href="#">FAQ</a>
            </div>
        </div>
    </footer>

    <script>
        // Hero image rotation
        @if(!empty($hero_slides) && count($hero_slides) > 1)
            (function(){
                const slides = @json($hero_slides);
                const img = document.getElementById('hero-img');
                const cap = document.getElementById('hero-caption');
                let i = 0;
                
                function show(n){
                    const s = slides[n];
                    if(!s) return;
                    img.style.opacity = 0;
                    
                    setTimeout(() => {
                        img.src = s.img;
                        img.alt = s.name || '';
                        cap.textContent = s.name || '';
                        img.style.opacity = 1;
                    }, 300);
                }
                
                setInterval(function(){ 
                    i = (i + 1) % slides.length; 
                    show(i); 
                }, 4000);
            })();
        @endif
    </script>

@endsection