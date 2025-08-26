<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SPBS-D - Sistem Informasi Manajemen Data Sosial</title>
    <meta name="description" content="Platform terpadu untuk monitoring dan pendataan bantuan sosial, penyandang disabilitas, dan UMKM">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="/favicon.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        /* === BASIC SETUP === */
        :root {
            --primary-color: #dd4814;
            --secondary-color: #ff6b35;
            --dark-grey: #333;
            --medium-grey: #6c757d;
            --light-grey: #f8f9fa;
            --text-color: rgba(33, 37, 41, 1);
        }

        * {
            transition: all 300ms ease;
            box-sizing: border-box;
        }

        *:focus {
            outline: 2px solid rgba(221, 72, 20, .3);
            outline-offset: 2px;
        }

        /* Remove focus outline for buttons and interactive elements */
        button:focus,
        .menu-toggle:focus,
        .nav-items a:focus,
        .btn:focus {
            outline: none !important;
            box-shadow: none !important;
        }

        /* Custom focus styles for better UX */
        .menu-toggle:focus {
            background: rgba(221, 72, 20, 0.1);
        }

        .nav-items a:focus {
            background: var(--light-grey);
            color: var(--primary-color);
        }

        .btn:focus {
            transform: scale(0.98);
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            color: var(--text-color);
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji";
            font-size: 16px;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            text-rendering: optimizeLegibility;
            line-height: 1.6;
        }

        /* === PRELOADER === */
        #preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #fff;
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 1;
            visibility: visible;
            transition: opacity 0.5s ease-out, visibility 0.5s ease-out;
        }
        #preloader.loaded {
            opacity: 0;
            visibility: hidden;
        }
        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid rgba(0, 0, 0, 0.1);
            border-top-color: var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* === HEADER === */
        header {
            background: linear-gradient(135deg, rgba(247, 248, 249, 0.95), rgba(255, 255, 255, 0.95));
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .menu {
            padding: 0.5rem 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
        }
        .logo-container a {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: inherit;
            outline: none;
            -webkit-tap-highlight-color: transparent;
        }
        .logo-container a:focus {
            outline: none;
        }
        .main-logo {
            width: 30px;
            height: 30px;
            border-radius: 6px;
            object-fit: contain;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-right: 8px;
            background: white;
            padding: 2px;
            outline: none;
        }
        .logo-container h1 {
            font-size: 1.1rem;
            margin: 0;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            outline: none;
        }

        /* Navigasi */
        .nav-items { 
            list-style-type: none; 
            margin: 0; 
            padding: 0; 
            display: flex; 
            align-items: center; 
            gap: 0.5rem; 
        }
        .nav-items .menu-item a {
            padding: 0.6rem 1rem;
            text-decoration: none;
            color: var(--dark-grey);
            border-radius: 8px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
            outline: none;
            -webkit-tap-highlight-color: transparent;
        }
        .nav-items .menu-item a:hover {
            background: var(--light-grey);
            color: var(--primary-color);
        }
        .nav-items .menu-item a:active {
            transform: scale(0.98);
        }
        .user-icon a { 
            padding: 0.5rem !important; 
        }
        .user-avatar { 
            width: 32px; 
            height: 32px; 
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); 
            border-radius: 50%; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            color: white; 
            font-weight: bold; 
            font-size: 0.9rem; 
        }
        .menu-toggle { 
            display: none; 
            background: none; 
            border: none; 
            font-size: 1.3rem; 
            color: var(--primary-color); 
            cursor: pointer; 
            padding: 0.5rem; 
            border-radius: 8px;
            outline: none;
            -webkit-tap-highlight-color: transparent;
        }
        .menu-toggle:hover {
            background: rgba(221, 72, 20, 0.1);
        }
        .menu-toggle:active {
            transform: scale(0.95);
        }

        /* === HERO SECTION - ADJUSTED HEIGHT === */
        .hero-section {
            position: relative;
            height: 90vh; /* Reduced from 100vh */
            min-height: 650px; /* Reduced from 700px */
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            overflow: hidden;
        }

        /* Background with subtle animation */
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image: url('images/tup2.jpeg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            animation: subtleZoom 20s ease-in-out infinite;
        }
        @keyframes subtleZoom { 
            0%, 100% { transform: scale(1); } 
            50% { transform: scale(1.05); }
        }

        /* Modern gradient overlay */
        .hero-section::after {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(135deg, 
                rgba(15, 30, 57, 0.9) 0%,
                rgba(20, 49, 128, 0.85) 30%,
                rgba(30, 60, 114, 0.8) 60%,
                rgba(41, 98, 255, 0.75) 100%);
        }
        
        /* Animated wave background */
        .hero-waves {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            overflow: hidden;
            z-index: 1;
            height: 120px; /* Adjusted wave height */
        }

        .wave {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 200%;
            height: 100px;
            background: rgba(255, 255, 255, 0.1);
            clip-path: polygon(0 30px, 100% 0px, 100% 120px, 0 120px);
        }

        .wave:nth-child(1) { animation: wave-animation 12s ease-in-out infinite; opacity: 0.4; height: 100px; }
        .wave:nth-child(2) { animation: wave-animation 15s ease-in-out infinite reverse; opacity: 0.3; height: 80px; }
        .wave:nth-child(3) { animation: wave-animation 10s ease-in-out infinite; opacity: 0.2; height: 60px; }
        .wave:nth-child(4) { animation: wave-animation 18s ease-in-out infinite reverse; opacity: 0.15; height: 40px; }

        @keyframes wave-animation {
            0% { transform: translateX(0); }
            50% { transform: translateX(-25%); }
            100% { transform: translateX(0); }
        }

        /* Floating particles */
        .hero-particles {
            position: absolute; top: 0; left: 0; right: 0; bottom: 0; z-index: 1; pointer-events: none;
        }

        .particle {
            position: absolute; background: rgba(255, 255, 255, 0.8); border-radius: 50%; animation: particleFloat 20s linear infinite;
        }
        .particle:nth-child(1) { width: 4px; height: 4px; left: 10%; animation-delay: 0s; animation-duration: 15s; }
        .particle:nth-child(2) { width: 6px; height: 6px; left: 20%; animation-delay: 3s; animation-duration: 18s; }
        .particle:nth-child(3) { width: 3px; height: 3px; left: 30%; animation-delay: 6s; animation-duration: 12s; }
        .particle:nth-child(4) { width: 5px; height: 5px; left: 40%; animation-delay: 9s; animation-duration: 20s; }
        .particle:nth-child(5) { width: 4px; height: 4px; left: 50%; animation-delay: 12s; animation-duration: 14s; }
        .particle:nth-child(6) { width: 7px; height: 7px; left: 60%; animation-delay: 4s; animation-duration: 16s; }
        .particle:nth-child(7) { width: 3px; height: 3px; left: 70%; animation-delay: 8s; animation-duration: 19s; }
        .particle:nth-child(8) { width: 5px; height: 5px; left: 80%; animation-delay: 11s; animation-duration: 13s; }
        .particle:nth-child(9) { width: 4px; height: 4px; left: 90%; animation-delay: 2s; animation-duration: 21s; }
        .particle:nth-child(10) { width: 6px; height: 6px; left: 15%; animation-delay: 7s; animation-duration: 17s; }
        .particle:nth-child(11) { width: 3px; height: 3px; left: 75%; animation-delay: 10s; animation-duration: 11s; }
        .particle:nth-child(12) { width: 5px; height: 5px; left: 85%; animation-delay: 5s; animation-duration: 22s; }

        @keyframes particleFloat {
            0% { top: 100%; opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { top: -10%; opacity: 0; }
        }
        
        /* Geometric shapes */
        .hero-shapes {
            position: absolute; top: 0; left: 0; right: 0; bottom: 0; z-index: 1; pointer-events: none;
        }

        .geometric-shape { position: absolute; opacity: 0.12; will-change: transform; }
        .shape-1 { width: 150px; height: 150px; background: rgba(255, 255, 255, 0.15); border-radius: 50%; top: 10%; left: 8%; animation: floatSmooth 15s ease-in-out infinite; }
        .shape-2 { width: 100px; height: 100px; background: linear-gradient(45deg, rgba(255, 255, 255, 0.1), transparent); transform: rotate(45deg); top: 15%; right: 10%; animation: rotateFloat 18s linear infinite; }
        .shape-3 { width: 120px; height: 120px; border: 3px solid rgba(255, 255, 255, 0.2); border-radius: 50%; bottom: 30%; left: 12%; animation: pulseScale 12s ease-in-out infinite; }
        .shape-4 { width: 80px; height: 80px; background: rgba(255, 255, 255, 0.1); border-radius: 15px; bottom: 35%; right: 15%; animation: floatSmooth 10s ease-in-out infinite 2s; }
        .shape-5 { width: 50px; height: 50px; background: rgba(255, 255, 255, 0.15); border-radius: 50%; top: 35%; left: 45%; animation: gentleBob 8s ease-in-out infinite 1s; }
        .shape-hex-1 { width: 70px; height: 70px; top: 20%; right: 20%; clip-path: polygon(30% 0%, 70% 0%, 100% 50%, 70% 100%, 30% 100%, 0% 50%); background: rgba(255, 255, 255, 0.1); animation: rotateHex 25s linear infinite; }
        .shape-hex-2 { width: 50px; height: 50px; bottom: 45%; left: 70%; clip-path: polygon(30% 0%, 70% 0%, 100% 50%, 70% 100%, 30% 100%, 0% 50%); background: rgba(255, 255, 255, 0.08); animation: rotateHex 30s linear infinite reverse; }
        .shape-hex-3 { width: 40px; height: 40px; top: 60%; right: 35%; clip-path: polygon(30% 0%, 70% 0%, 100% 50%, 70% 100%, 30% 100%, 0% 50%); background: rgba(255, 255, 255, 0.1); animation: rotateHex 20s linear infinite; }
        
        @keyframes floatSmooth {
            0%, 100% { transform: translateY(0) translateX(0) rotate(0deg); } 25% { transform: translateY(-20px) translateX(15px) rotate(5deg); } 50% { transform: translateY(10px) translateX(-10px) rotate(-3deg); } 75% { transform: translateY(-15px) translateX(8px) rotate(2deg); }
        }
        @keyframes rotateFloat {
            0% { transform: rotate(0deg) translateY(0) scale(1); } 25% { transform: rotate(90deg) translateY(-15px) scale(1.1); } 50% { transform: rotate(180deg) translateY(-25px) scale(0.9); } 75% { transform: rotate(270deg) translateY(-10px) scale(1.05); } 100% { transform: rotate(360deg) translateY(0) scale(1); }
        }
        @keyframes pulseScale {
            0%, 100% { transform: scale(1) rotate(0deg); opacity: 0.1; } 33% { transform: scale(1.2) rotate(120deg); opacity: 0.2; } 66% { transform: scale(0.8) rotate(240deg); opacity: 0.15; }
        }
        @keyframes gentleBob {
            0%, 100% { transform: translateY(0) scale(1); } 50% { transform: translateY(-20px) scale(1.1); }
        }
        @keyframes rotateHex {
            0% { transform: rotate(0deg) scale(1); } 50% { transform: rotate(180deg) scale(1.1); } 100% { transform: rotate(360deg) scale(1); }
        }

        /* Glowing orbs */
        .hero-orbs {
            position: absolute; top: 0; left: 0; right: 0; bottom: 0; z-index: 1; pointer-events: none;
        }

        .orb {
            position: absolute; border-radius: 50%; background: radial-gradient(circle, rgba(255,255,255,0.3) 0%, rgba(255,255,255,0.1) 40%, transparent 70%); animation: orbFloat 22s ease-in-out infinite;
        }
        .orb-1 { width: 180px; height: 180px; top: 8%; right: 8%; animation-delay: 0s; }
        .orb-2 { width: 130px; height: 130px; bottom: 20%; left: 5%; animation-delay: 7s; }
        .orb-3 { width: 100px; height: 100px; top: 55%; right: 25%; animation-delay: 14s; }
        .orb-4 { width: 80px; height: 80px; top: 25%; left: 75%; animation-delay: 3s; }

        @keyframes orbFloat {
            0%, 100% { transform: translateY(0) scale(1); opacity: 0.4; } 25% { transform: translateY(-30px) scale(1.15); opacity: 0.6; } 50% { transform: translateY(-15px) scale(0.85); opacity: 0.3; } 75% { transform: translateY(-40px) scale(1.1); opacity: 0.5; }
        }

        /* Content styling - adjusted for better scale */
        .hero-content { 
            max-width: 750px; /* Reduced from 800px */
            padding: 2rem; 
            position: relative; 
            z-index: 2; 
        }
        .hero-content h1 { 
            font-size: 3rem; /* Reduced from 3.5rem */
            font-weight: 800; 
            margin-bottom: 1.2rem; /* Reduced spacing */
            text-shadow: 2px 2px 8px rgba(0,0,0,0.4); 
            animation: slideInUp 1.2s ease-out;
            line-height: 1.1;
        }
        .hero-content p { 
            font-size: 1.15rem; /* Reduced from 1.3rem */
            margin-bottom: 2rem; /* Reduced spacing */
            text-shadow: 1px 1px 4px rgba(0,0,0,0.4);
            animation: slideInUp 1.2s ease-out 0.3s both;
            opacity: 0.95;
            line-height: 1.5;
        }
        .cta-buttons { 
            display: flex; 
            gap: 1rem; /* Reduced gap */
            justify-content: center; 
            flex-wrap: wrap;
            animation: slideInUp 1.2s ease-out 0.6s both;
        }
        
        @keyframes slideInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .btn { 
            padding: 0.8rem 2rem; /* Reduced padding */
            border-radius: 50px; 
            text-decoration: none; 
            font-weight: 600; 
            display: inline-flex; 
            align-items: center; 
            gap: 8px; /* Reduced gap */
            font-size: 0.95rem; /* Reduced font size */
            outline: none;
            -webkit-tap-highlight-color: transparent;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease; /* Faster transition */
        }
        .btn i { font-size: 1.1em; }
        .btn-primary, .btn-secondary { 
            background: rgba(255, 255, 255, 0.15); 
            color: white; 
            border: 2px solid rgba(255, 255, 255, 0.9); 
            backdrop-filter: blur(10px); /* Adjusted blur */
        }
        .btn-primary:hover, .btn-secondary:hover { 
            background: white; 
            color: var(--primary-color); 
            border-color: white; 
            transform: translateY(-3px) scale(1.03); /* Adjusted hover effect */
            box-shadow: 0 10px 30px rgba(255,255,255,0.3); /* Adjusted shadow */
        }

        /* === SECTION STYLING === */
        .section {
            padding: 3.5rem 1rem; /* Reduced from 4rem */
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .section-title {
            text-align: center;
            margin-bottom: 2.5rem; /* Reduced spacing */
        }
        .section-title h2 {
            font-size: 1.8rem; /* Reduced from 2rem */
            font-weight: 700;
            color: var(--dark-grey);
            margin-bottom: 0.8rem;
            position: relative;
            display: inline-block;
            padding-bottom: 0.8rem;
        }
        .section-title h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 50px; /* Reduced from 60px */
            height: 3px; /* Reduced from 4px */
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 2px;
        }
        .section-title p {
            font-size: 1rem; /* Reduced from 1.1rem */
            color: var(--medium-grey);
            max-width: 650px; /* Reduced from 700px */
            margin: 0.8rem auto 0;
        }

        /* === STATS SECTION === */
        .stats-section { background: var(--light-grey); }
        .stats-container { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(230px, 1fr)); /* Reduced from 250px */
            gap: 1.25rem; /* Reduced gap */
        }
        .stat-card { 
            background: white; 
            padding: 1.5rem 1.25rem; /* Reduced padding */
            border-radius: 12px; /* Reduced radius */
            text-align: center; 
            box-shadow: 0 6px 20px rgba(0,0,0,0.08); /* Adjusted shadow */
            border-top: 3px solid var(--primary-color); /* Thinner border */
        }
        .stat-card:hover { 
            transform: translateY(-6px); /* Adjusted hover */
            box-shadow: 0 12px 30px rgba(0,0,0,0.12); 
        }
        .stat-icon { 
            font-size: 2.2rem; /* Reduced from 2.5rem */
            margin-bottom: 0.8rem; 
            color: var(--primary-color); 
        }
        .stat-number { 
            font-size: 2rem; /* Reduced from 2.2rem */
            font-weight: 700; 
            color: var(--dark-grey); 
            margin-bottom: 0.5rem; 
        }
        .stat-label { 
            font-size: 0.95rem; /* Reduced from 1rem */
            color: var(--medium-grey); 
            font-weight: 500; 
        }

/* === FEATURES SECTION === */
.features-section { background: white; }
.feature-item {
    display: flex;
    align-items: center;
    gap: 1.5rem; /* Adjusted gap */
    margin-bottom: 3rem; /* Adjusted spacing */
}
.feature-item:last-child { margin-bottom: 0; }
.feature-item .feature-image {
    flex-basis: 35%; /* Reduced from 45% */
    max-width: 200px; /* Added max-width */
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}
.feature-item .feature-image img {
    width: 100%;
    display: block;
    aspect-ratio: 4/3;
    object-fit: cover;
    transition: transform 0.4s ease;
}
.feature-item .feature-image:hover img {
    transform: scale(1.05);
}
.feature-item .feature-content { flex-basis: 65%; /* Adjusted from 55% */ }
.feature-item.reverse { flex-direction: row-reverse; }
.feature-icon-title {
    display: flex;
    align-items: center;
    gap: 0.8rem;
    margin-bottom: 0.8rem;
}
.feature-icon {
    font-size: 1.6rem;
    color: var(--primary-color);
    width: 50px;
    height: 50px;
    background: var(--light-grey);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.feature-content h3 {
    font-size: 1.4rem;
    font-weight: 600;
    margin: 0;
    color: var(--dark-grey);
}
.feature-content p {
    color: var(--medium-grey);
    line-height: 1.7;
    font-size: 0.95rem;
}
        /* === NEWS SECTION === */
        .news-section { background: var(--light-grey); }
        .news-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); /* Reduced from 320px */
            gap: 1.5rem;
        }
        .news-card { 
            background: white; 
            border-radius: 12px; /* Reduced radius */
            overflow: hidden; 
            box-shadow: 0 6px 20px rgba(0,0,0,0.08); /* Adjusted shadow */
            cursor: pointer; 
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .news-card:hover { 
            transform: translateY(-6px); /* Adjusted hover */
            box-shadow: 0 12px 30px rgba(0,0,0,0.12); 
        }
        .news-image { 
            height: 180px; /* Reduced from 200px */
            overflow: hidden; 
        }
        .news-image img { 
            width: 100%; 
            height: 100%; 
            object-fit: cover; 
            transition: transform 0.4s ease;
        }
        .news-card:hover .news-image img {
            transform: scale(1.05);
        }
        .news-content { 
            padding: 1.25rem; /* Reduced padding */
        }
        .news-category { 
            display: inline-block; 
            background: var(--secondary-color); 
            color: white; 
            padding: 0.25rem 0.75rem; /* Reduced padding */
            border-radius: 15px;
            font-size: 0.75rem; /* Reduced font size */
            font-weight: 600; 
            margin-bottom: 0.8rem; 
        }
        .news-title { 
            font-size: 1.05rem; /* Reduced from 1.1rem */
            font-weight: 600; 
            margin-bottom: 0.5rem; 
            color: var(--dark-grey); 
            line-height: 1.4; 
        }
        .news-excerpt { 
            color: var(--medium-grey); 
            font-size: 0.9rem;
            line-height: 1.6; 
        }

        /* === CTA SECTION === */
        .cta-section {
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            color: white;
            padding: 3.5rem 1rem; /* Reduced padding */
            text-align: center;
        }
        .cta-section h2 {
            font-size: 1.6rem; /* Reduced from 1.8rem */
            margin-bottom: 1rem;
        }
        .cta-section p {
            max-width: 550px; /* Reduced from 600px */
            margin: 0 auto 1.8rem auto; /* Reduced bottom margin */
            opacity: 0.9;
            font-size: 1rem;
        }

        /* === FOOTER === */
        footer {
            background: #222;
            color: #ccc;
            padding-top: 3.5rem; /* Reduced padding */
        }
        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); /* Reduced from 250px */
            gap: 1.8rem; /* Reduced gap */
            padding: 0 1rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        .footer-col h4 {
            font-size: 1.1rem;
            color: white;
            margin-bottom: 1rem; 
            position: relative;
            padding-bottom: 0.6rem;
        }
        .footer-col h4::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 30px;
            height: 3px;
            background: var(--primary-color);
        }
        .footer-col p, .footer-col ul {
            font-size: 0.95rem; /* Reduced from 1rem */
            color: #999;
            margin: 0;
            padding: 0;
            list-style: none;
        }
        .footer-col ul li {
            margin-bottom: 0.6rem;
        }
        .footer-col a {
            color: #999;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        .footer-col a:hover {
            color: white;
        }
        .social-icons a {
            display: inline-flex;
            width: 38px; /* Reduced from 40px */
            height: 38px;
            background: #333;
            color: white;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin-right: 0.5rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        .social-icons a:hover {
            background: var(--primary-color);
            transform: scale(1.1); /* Adjusted hover */
        }
        .footer-bottom {
            border-top: 1px solid #333;
            padding: 1.5rem;
            margin-top: 2rem; /* Reduced margin */
            text-align: center;
            color: #777;
            font-size: 0.9rem;
        }
        
        /* === BACK TO TOP BUTTON === */
        #backToTopBtn {
            position: fixed;
            bottom: 20px; /* Adjusted position */
            right: 20px;
            width: 40px; /* Reduced size */
            height: 40px;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem; /* Reduced font size */
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(0,0,0,0.25); /* Adjusted shadow */
            opacity: 0;
            visibility: hidden;
            z-index: 100;
            transform: translateY(100px);
            transition: all 0.4s ease;
        }
        #backToTopBtn.visible {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        #backToTopBtn:hover {
            background: var(--secondary-color);
            transform: translateY(-2px) scale(1.05); /* Adjusted hover */
        }

        /* Modal styles */
        .modal { 
            display: none; position: fixed; z-index: 2000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.8); backdrop-filter: blur(5px); 
        }
        .modal-content { 
            background-color: white; margin: 5% auto; padding: 0; border-radius: 12px; width: 90%; max-width: 700px; max-height: 90vh; overflow-y: auto; position: relative; box-shadow: 0 15px 50px rgba(0,0,0,0.25);
        }
        .modal-header { 
            position: relative; height: 220px; overflow: hidden; border-radius: 12px 12px 0 0; 
        }
        .modal-header img { 
            width: 100%; height: 100%; object-fit: cover; 
        }
        .modal-body {
            padding: 1.8rem; /* Adjusted padding */
        }
        .close { 
            position: absolute; top: 0.8rem; right: 1.2rem; color: white; font-size: 1.8rem; font-weight: bold; cursor: pointer; z-index: 20; text-shadow: 0 2px 4px black; transition: transform 0.3s ease;
        }
        .close:hover {
            transform: scale(1.2);
        }

        /* === RESPONSIVE STYLES === */
        @media (max-width: 768px) {
            .menu { padding: 0.4rem 1rem; }
            .main-logo { width: 26px; height: 26px; }
            .logo-container h1 { font-size: 1rem; }
            
            .hero-section {
                height: 80vh; /* Adjusted for mobile */
                min-height: 480px; /* Adjusted for mobile */
                background-attachment: scroll; /* Disable parallax on mobile for performance */
            }
            .hero-content {
                padding: 1.5rem;
            }
            .hero-content h1 { 
                font-size: 2.2rem; /* Adjusted for mobile */
                line-height: 1.2;
            }
            .hero-content p { 
                font-size: 1rem; /* Adjusted for mobile */
            }
            .cta-buttons { 
                flex-direction: column; 
                align-items: center;
                gap: 1rem;
            }
            .btn {
                width: 200px; /* Adjusted for mobile */
                justify-content: center;
                padding: 0.8rem 1.5rem;
            }
            
            /* Hide some shapes and particles on mobile for cleaner look */
            .shape-2, .shape-4, .shape-hex-2, .shape-hex-3 { display: none; }
            .hero-particles .particle:nth-child(n+7) { display: none; }
            .hero-orbs .orb-4 { display: none; }
            .hero-waves .wave:nth-child(3), .hero-waves .wave:nth-child(4) { display: none; }
            
            .section {
                padding: 2.5rem 1rem; /* Adjusted for mobile */
            }
            .section-title h2 { 
                font-size: 1.6rem; /* Adjusted for mobile */
            }
            
            .stats-container {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
    .feature-item, .feature-item.reverse { 
        flex-direction: column; 
        text-align: center;
        gap: 1.8rem;
    }
    .feature-content p {
        text-align: justify; /* Membuat teks rata kanan-kiri */
    }
    
    .feature-icon-title { 
        justify-content: center; 
    }
    .feature-item .feature-image,
    .feature-item .feature-content {
        flex-basis: 100%;
        max-width: none; /* Remove max-width on mobile */
    }
    .feature-item .feature-image {
        max-width: 250px; /* Adjust max-width for mobile if needed */
        margin: 0 auto; /* Center image on mobile */
    }

            .news-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .cta-section {
                padding: 2.5rem 1rem; /* Adjusted for mobile */
            }
            .cta-section h2 {
                font-size: 1.4rem; /* Adjusted for mobile */
            }
            
            .footer-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            /* Mobile Menu */
            .menu-toggle { display: block; order: 2; }
            .nav-items { 
                position: absolute; top: 100%; left: 0; right: 0; 
                background: white; 
                flex-direction: column; 
                box-shadow: 0 8px 20px rgba(0,0,0,0.1);
                padding: 0.8rem;
                transform: translateY(-10px); 
                opacity: 0; 
                visibility: hidden;
                border-radius: 0 0 12px 12px;
            }
            .nav-items.active { transform: translateY(0); opacity: 1; visibility: visible; }
            .menu-item { width: 100%; margin-bottom: 0.4rem; }
            .menu-item a { width: 100%; padding: 0.8rem; border-radius: 8px; justify-content: flex-start; }
            
            .modal-content {
                margin: 5% auto; width: 95%;
            }
            .modal-header {
                height: 160px; /* Adjusted for mobile */
            }
            .modal-body {
                padding: 1.5rem;
            }
        }

        @media (max-width: 480px) {
            .hero-section {
                height: 75vh;
                min-height: 420px;
            }
            .hero-content h1 {
                font-size: 1.9rem; /* Further reduced */
            }
            .hero-content p {
                font-size: 0.95rem; /* Further reduced */
            }
            .section-title h2 {
                font-size: 1.5rem; /* Further reduced */
            }
            .stat-number {
                font-size: 1.7rem; /* Further reduced */
            }
            .btn {
                padding: 0.8rem 1.5rem;
                font-size: 0.9rem;
            }
            
            .shape-1, .shape-3 { width: 80px; height: 80px; }
            .shape-5 { width: 40px; height: 40px; }
            
            .hero-waves .wave { height: 60px; }
            
            .orb-1 { width: 120px; height: 120px; }
            .orb-2 { width: 100px; height: 100px; }
            .orb-3 { width: 80px; height: 80px; }
        }

        @media (min-width: 1400px) {
            .hero-section { min-height: 750px; }
            .hero-content h1 { font-size: 3.5rem; }
            .hero-content p { font-size: 1.25rem; }
            .container { max-width: 1300px; }
            .shape-1 { width: 180px; height: 180px; }
            .shape-2 { width: 120px; height: 120px; }
            .shape-3 { width: 150px; height: 150px; }
            .orb-1 { width: 220px; height: 220px; }
            .orb-2 { width: 180px; height: 180px; }
            .orb-3 { width: 130px; height: 130px; }
        }
    </style>
</head>
<body>

<div id="preloader">
    <div class="spinner"></div>
</div>

<header>
    <div class="menu">
        <div class="logo-container">
            <a href="/">
                <img src="images/logo.png" alt="Logo SPBS-D KEPRI" class="main-logo">
                <h1>SPBS-D KEPRI</h1>
            </a>
        </div>
        
        <button class="menu-toggle" id="menuToggle"><i class="fa-solid fa-bars"></i></button>
        
        <ul class="nav-items" id="navItems">
            <li class="menu-item">
                <a href="#features"><i class="fa-solid fa-star"></i> Fitur</a>
            </li>
            <li class="menu-item">
                <a href="#news"><i class="fa-solid fa-newspaper"></i> Berita</a>
            </li>
            <li class="menu-item user-icon">
                <a href="/login" title="Login">
                    <div class="user-avatar"><i class="fa-solid fa-user"></i></div>
                </a>
            </li>
        </ul>
    </div>
</header>

<section class="hero-section">
    <div class="hero-waves">
        <div class="wave"></div> <div class="wave"></div> <div class="wave"></div> <div class="wave"></div>
    </div>
    <div class="hero-particles">
        <div class="particle"></div> <div class="particle"></div> <div class="particle"></div> <div class="particle"></div> <div class="particle"></div> <div class="particle"></div> <div class="particle"></div> <div class="particle"></div> <div class="particle"></div> <div class="particle"></div> <div class="particle"></div> <div class="particle"></div>
    </div>
    <div class="hero-orbs">
        <div class="orb orb-1"></div> <div class="orb orb-2"></div> <div class="orb orb-3"></div> <div class="orb orb-4"></div>
    </div>
    <div class="hero-shapes">
        <div class="geometric-shape shape-1"></div> <div class="geometric-shape shape-2"></div> <div class="geometric-shape shape-3"></div> <div class="geometric-shape shape-4"></div> <div class="geometric-shape shape-5"></div> <div class="geometric-shape shape-hex-1"></div> <div class="geometric-shape shape-hex-2"></div> <div class="geometric-shape shape-hex-3"></div>
    </div>
    
    <div class="hero-content">
        <h1>SPBS-D KEPRI</h1>
        <p>Platform Terpadu untuk Monitoring dan Pendataan Bantuan Sosial, Penyandang Disabilitas, dan UMKM di Kepulauan Riau</p>
        <div class="cta-buttons">
            <a href="#features" class="btn btn-primary">
                <i class="fa-solid fa-rocket"></i> Mulai Sekarang
            </a>
            <a href="#news" class="btn btn-secondary">
                <i class="fa-solid fa-newspaper"></i> Lihat Berita
            </a>
        </div>
    </div>
</section>

<section class="section stats-section">
    <div class="container stats-container">
        <div class="stat-card">
            <div class="stat-icon"><i class="fa-solid fa-chart-pie"></i></div>
            <div class="stat-number">
                <!-- Pastikan nama variabelnya benar -->
                <?= number_format($jumlah_bansos ?? 0, 0, ',', '.') ?>
            </div>
            <div class="stat-label">Data Penerima Bansos</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fa-solid fa-wheelchair"></i></div>
            <div class="stat-number">
                <?= number_format($jumlah_disabilitas ?? 0, 0, ',', '.') ?>
            </div>
            <div class="stat-label">Data Penyandang Disabilitas</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fa-solid fa-store"></i></div>
            <div class="stat-number">
                <?= number_format($jumlah_umkm ?? 0, 0, ',', '.') ?>
            </div>
            <div class="stat-label">Data UMKM Terdaftar</div>
        </div>
    </div>
</section>

<section class="section features-section" id="features">
    <div class="container">
        <div class="section-title">
            <h2>Fitur Unggulan</h2>
            <p>Sistem informasi terintegrasi untuk mendukung program sosial yang efektif dan tepat sasaran.</p>
        </div>
        
        <div class="features-wrapper">
            <div class="feature-item">
                <div class="feature-image">
                    <img src="images/bankel.jpeg" alt="Bantuan Sosial">
                </div>
                <div class="feature-content">
                    <div class="feature-icon-title">
                        <div class="feature-icon"><i class="fa-solid fa-file-invoice-dollar"></i></div>
                        <h3>SIM-Bankel (Bantuan Sosial)</h3>
                    </div>
                    <p>SIM-Bankel adalah sistem informasi yang dirancang untuk mempermudah proses pendataan dan pengelolaan penerima Bantuan Keluarga (Bankel) di seluruh wilayah Kepulauan Riau. SIM-Bankel hadir sebagai solusi digital yang mendukung pemerataan bantuan, meningkatkan efisiensi, serta memperkuat akuntabilitas dalam penyaluran bantuan keluarga di seluruh Kepulauan Riau.</p>
                </div>
            </div>

            <div class="feature-item reverse">
                <div class="feature-image">
                    <img src="images/fabel.jpeg" alt="Dukungan Disabilitas">
                </div>
                <div class="feature-content">
                    <div class="feature-icon-title">
                        <div class="feature-icon"><i class="fa-solid fa-universal-access"></i></div>
                        <h3>SIM-Difabel (Disabilitas)</h3>
                    </div>
                    <p>SIM-Difabel hadir sebagai inovasi digital untuk mewujudkan pelayanan yang adil, merata, dan inklusif bagi penyandang disabilitas di Kepulauan Riau. SIM-Difabel adalah sistem informasi yang dikembangkan untuk mendata, mengelola, dan memantau informasi terkait penyandang disabilitas di seluruh wilayah Kepulauan Riau.</p>
                </div>
            </div>

            <div class="feature-item">
                <div class="feature-image">
                    <img src="images/kuep.jpeg" alt="Monitoring UMKM">
                </div>
                <div class="feature-content">
                    <div class="feature-icon-title">
                        <div class="feature-icon"><i class="fa-solid fa-chart-line"></i></div>
                        <h3>SIM-Monevkuep (Monitoring UMKM)</h3>
                    </div>
                    <p>SIM-MonevKUEP hadir sebagai solusi digital untuk memperkuat pengawasan, meningkatkan efektivitas program, serta mendorong pertumbuhan ekonomi masyarakat secara berkelanjutan. SIM-MonevKUEP adalah sistem informasi yang dirancang untuk memantau, mengevaluasi, dan mengelola perkembangan Usaha Ekonomi Produktif (UEP) di seluruh Kepulauan Riau.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section news-section" id="news">
    <div class="container">
        <div class="section-title">
            <h2>Berita & Informasi</h2>
            <p>Update terbaru mengenai program bantuan sosial dan pemberdayaan masyarakat di Kepulauan Riau.</p>
        </div>
        
        <div class="news-grid" id="newsGrid">
        </div>
    </div>
</section>

<div id="newsModal" class="modal">
</div>

<footer>
    <div class="footer-grid">
        <div class="footer-col">
            <h4>SPBS-D KEPRI</h4>
            <p>Platform digital terintegrasi untuk manajemen data sosial di Provinsi Kepulauan Riau. Mendukung transparansi dan akuntabilitas.</p>
        </div>
        <div class="footer-col">
            <h4>Navigasi</h4>
            <ul>
                <li><a href="#">Beranda</a></li>
                <li><a href="#features">Fitur</a></li>
                <li><a href="#news">Berita</a></li>
                <li><a href="/login">Login</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h4>Hubungi Kami</h4>
            <ul>
                <li><i class="fa-solid fa-map-marker-alt"></i> Pusat Pemerintahan Provinsi Kepulauan Riau Istana Kota Piring Gedung Sultan Mahmud Riayat Syah, Dompak, Bukit Bestari, 29124 Tanjungpinang.</li>
                <li><i class="fa-solid fa-envelope"></i> email@kepriprov.go.id</li>
                <li><i class="fa-solid fa-phone"></i> 0771-4575000</li>
            </ul>
        </div>
        <div class="footer-col">
            <h4>Ikuti Kami</h4>
            <div class="social-icons">
                <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="#" title="Twitter"><i class="fab fa-twitter"></i></a>
                <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; 2025 Dinas Sosial Kepulauan Riau. Semua hak dilindungi undang-undang.</p>
    </div>
</footer>

<a href="#" id="backToTopBtn" title="Kembali ke atas"><i class="fa-solid fa-arrow-up"></i></a>


<script>
    // === PRELOADER & BACK-TO-TOP SCRIPT ===
    const preloader = document.getElementById('preloader');
    const backToTopBtn = document.getElementById('backToTopBtn');

    window.addEventListener('load', () => {
        preloader.classList.add('loaded');
    });

    window.onscroll = () => {
        if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
            backToTopBtn.classList.add('visible');
        } else {
            backToTopBtn.classList.remove('visible');
        }
    };

    backToTopBtn.addEventListener('click', (e) => {
        e.preventDefault();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    // === NEWS DATA AND MODAL ===
    const newsData = [
        {
            id: 1,
            title: "Penyerahan Bantuan Kesejahteraan Sosial Bahan Pokok Beras, Bantuan Pengembangan Usaha Ekonimi Produktif(UEP) dan Pemberian Insentif Koordinator dan Pendamping PKH di Kabupaten Lingga",
            category: "Program Bansos",
            excerpt: "Bidang Penanganan Fakir Miskin Dinas Sosial Provinsi Kepulauan Riau mengadakan Penyerahan Bantuan  Kesejahteraan Sosial Berupa Beras untuk Masyarakat di Kabupaten Lingga.",
            image: "images/bankel.jpeg",
            content: `Pada hari Selasa Tanggal 16 Juli 2024, Bidang Penanganan Fakir Miskin Dinas Sosial Provinsi Kepulauan Riau mengadakan Penyerahan Bantuan  Kesejahteraan Sosial Berupa Beras untuk Masyarakat di Kabupaten Lingga. 
Kegiatan ini dilaksanakan bersamaan dengan Kunjungan Kerja Gubernur Kepulauan Riau ke Kabupaten Lingga, dimana hari pertama pada Selasa, 16 Juli 2024. Adapun total bantuan yg d berikan adalah :
Penyerahan Bantuan Beras dari Pemerintah Provinsi Kepulauan Riau di Benan ialah 31 penerima.
Penyerahan Bantuan Beras dari Pemerintah Provinsi Kepulauan Riau di Mensanak ialah 37 penerima.
Penyerahan Bantuan Beras dari Pemerintah Provinsi Kepulauan Riau di Pulau Duyung ialah 26 penerima.
Penyerahan Bantuan Beras dari Pemerintah Provinsi Kepulauan Riau di Rejai ialah 55 penerima.
Penyerahan Bantuan Beras dari Pemerintah Provinsi Kepulauan Riau di Senayang ialah 305 penerima.

Kegiatan selanjutnya Pada hari Rabu Tanggal 17 Juli 2024, Bidang Penanganan Fakir Miskin Dinas Sosial Provinsi Kepulauan Riau mengadakan Penyerahan Bantuan  Kesejahteraan Sosial Berupa Beras untuk Masyarakat yang disejalankan dengam Kegiatan Penyerahan Simbolis Penerima Bantuan Usaha Ekonomi Produktif (UEP) di Kabupaten Lingga. yang juga masih bersamaan dengan Kunjungan Kerja Gubernur Kepulauan Riau di Kabupaten Lingga. Adapun total bantuan yang di berikan adalah :
Penyerahan Bantuan Beras dari Pemerintah Provinsi Kepulauan Riau di Daik ialah 69 penerima.
Penyerahan Bantuan Beras dari Pemerintah Provinsi Kepulauan Riau di Kerandin ialah 34 penerima.
Penyerahan Bantuan Beras dari Pemerintah Provinsi Kepulauan Riau di Pancur ialah 53 penerima.
Penyerahan Bantuan Beras dari Pemerintah Provinsi Kepulauan Riau di Penaah ialah 140 penerima.

Kegiatan selanjutnya Pada hari Kamis Tanggal 18 Juli 2024, Bidang Penanganan Fakir Miskin Dinas Sosial Provinsi Kepulauan Riau mengadakan Penyerahan Bantuan Kesejahteraan Sosial Berupa Beras untuk Masyarakat yg di sejalankan dengan Kegiatan Gerakan Cepat Atasi Stunting di Dabo, Kabupaten Lingga. Yang juga Masih bersamaan dengan Kunjungan Kerja Gubernur Kepulauan Riau. Adapun total bantuan yang di berikan adalah :
Penyerahan Bantuan Beras dari Pemerintah Provinsi Kepulauan Riau di Dabo ialah 135 penerima.
Penyerahan Bantuan Beras dari Pemerintah Provinsi Kepulauan Riau di Cempa ialah 45 penerima.
Penyerahan Bantuan Beras dari Pemerintah Provinsi Kepulauan Riau di Batu Belobang ialah 37 penerima.`,
            date: "16 Juli 2024"
        },
        {
            id: 2,
            title: "Kunjungan Peningkatan Wawasan dan Pengetahuan Anak Panti Asuhan/ Lembaga Kesejahteraan Sosial Anak (LKSA) Kota Tanjungpinang",
            category: "Kesejahteraan Sosial Anak",
            excerpt: "Bidang Rehabilitasi Sosial Dinas Sosial Provinsi Kepulauan Riau mengadakan Kegiatan  Peningkatan Wawasan dan Pengetahuan Anak Panti Asuhan/ Lembaga Kesejahteraan Sosial Anak (LKSA) Kota Tanjungpinang ke Kota Palembang Provinsi Sumatra Selatan Tahun 2024.",
            image: "images/fabel.jpeg",
            content: `Pada hari Rabu 24 Juli 2024, Bidang Rehabilitasi Sosial Dinas Sosial Provinsi Kepulauan Riau mengadakan Kegiatan  Peningkatan Wawasan dan Pengetahuan Anak Panti Asuhan/ Lembaga Kesejahteraan Sosial Anak (LKSA) Kota Tanjungpinang ke Kota Palembang Provinsi Sumatra Selatan Tahun 2024  .
                    Kegiatan ini dilaksanakan di Kota Palembang , Sumatera Selatan. Kegiatan ini berlangsung dari tanggal 24 s.d. 27 Juli 2024. Kegiatan ini diikuti oleh 33  Anak dan pendamping dari Panti Asuhan/Lembaga Kesejahteraan Sosial (LKSA) di Kota Tanjungpinang. Adapun dengan rincian : 
                    LKSA Miftahul ulum 5 orang
                    LKSA Umi Al Fitrah 6 orang
                    LKSA Hidayahtullah 6 orang
                    LKSA Muhamadiyah 6 orang
                    RPRSA Ceria 4 orang
                    LKSA Anugerah 6 orang
                    Kegiatan ini juga sekaligus mengunjungi Sentra Budi Perkasa milik Kemensos RI, kegiatan ini juga dihadiri oleh Bapak Rudi Chua selaku Anggota Komisi II DPRD Provinsi Kepulauan Riau dan Bapak Uba Ingan Sigalingging, S.Sn. Sentra ini merupakan sentra multi layanan yang menangani semua Pemerlu Pelayanan Kesejahteraan Sosial (PPKS). Di Sentra Budi Perkasa ini, anak dan pendamping dari Panti Asuhan/Lembaga Kesejahteraan Sosial (LKSA) di Kota Tanjungpinang melakukan visitasi keliling sentra. Diantaranya ke ruang keterampilan menjahit, elektro, peternakan, perikanan dan juga tempat pembuatan kaki palsu. 
                    Melalui kegiatan Peningkatan Wawasan dan Pengetahuan Anak Panti Asuhan/ Lembaga Kesejahteraan Sosial Anak (LKSA) Kota Tanjungpinang ke Kota Palembang Provinsi Sumatra Selatan ini, diharapkan anak panti asuhan/LKSA mampu memberikan motivasi agar kedepannya mampu meningkatkan prestasi dan memanfaatkan di lingkungan sekitar panti agar dapat menambahkan sisi ekonomi terkhusus bagi anak di dalam panti.`,
            date: "24 Juli 2024"
        },
        {
            id: 3,
            title: "Penyerahan Bantuan Kesejahteraan Sosial Berupa Bahan Pokok Beras dan Bantuan Usaha Ekonomi Produktif (UEP) di Kabupaten Karimun",
            category: "UMKM & Bansos",
            excerpt: "Dinas Sosial Provinsi Kepri mengadakan Penyerahan Bantuan Kesejahteraan Sosial Berupa Bahan Pokok Beras dan Bantuan UEP di Kabupaten Karimun.",
            image: "images/kuep.jpeg",
            content: `Pada hari Rabu Tanggal 4 September 2024, Bidang Penanganan Fakir Miskin Dinas Sosial Provinsi Kepulauan Riau mengadakan Penyerahan Bantuan Kesejahteraan Sosial Berupa Bahan Pokok Beras untuk Masyarakat di Kabupaten Karimun. Kegiatan ini dilaksanakan bersamaan dengan Kunjungan Kerja Gubernur Kepulauan Riau ke Kabupaten Karimun.
Hari pertama pada hari Rabu, Tanggal 4 September Tahun 2024, dilaksanakan Penyerahan Bantuan dari Pemerintah Provinsi Kepulauan Riau di 3 lokasi. Lokasi pertama pada Pukul 09.30 WIB di Lapangan Bola Pamak Tebing, dengan peserta dari Kelurahan Tebing (92 KK), Kelurahan Pamak (88 KK), Kelurahan Kapling (81 KK) dan Kelurahan Teluk Uma (117 KK).
Kemudian dilanjutkan pada pukul 13.00 WIB di Gedung Nilam Sari Kantor Bupati Karimun. Peserta yang hadir berasal dari kelurahan Sungai Pasir (238 KK) dan kelurahan Sungai Raya (216 KK). Kegiatan selesai pada pukul 15.30 WIB di Gedung Nasional Kecamatan Karimun, dengan peserta dari Kelurahan Sungai Lakam Barat (278 KK), Kelurahan Sungai Lakam Timur (105 KK) dan Kelurahan Tanjung Balai (97 KK).
Kelurahan-kelurahan yang tidak dihadirkan di dalam 3 lokasi acara didistribusikan langsung kepada masyarakat. Adapun kelurahan yang dimaksud yaitu: Kelurahan Lubuk Semut (75 KK), Tanjung Balai Kota (64 KK), Teluk Air (82 KK), Baran Barat (105 KK), Baran Timur (125 KK), Meral Kota (60 KK), Parit Benut (97 KK), Darussalam (137 KK), Pangke (173 KK), Pangke Barat (114 KK), Pasir Panjang (312 KK), Harjosari (76 KK), dan Kelurahan Pongkar (86 KK).
Jumlah Total Penerima Bantuan Kesejahteraan Sosial Berupa Bahan Pokok Beras 10 Kg di Kabupaten Karimun berjumlah 2.788 KK. Selain itu, terdapat juga Bantuan Usaha Ekonomi Produktif (UEP) dengan dana sebesar Rp 2.500.000 per orang/pelaku usaha, dengan total penerima 60 orang di Kecamatan Tebing, Meral, dan Karimun.`,
            date: "4 September 2024"
        },
    ];

    function populateNews() {
        const newsGrid = document.getElementById('newsGrid');
        newsGrid.innerHTML = '';
        
        newsData.forEach(news => {
            const newsCard = document.createElement('div');
            newsCard.className = 'news-card';
            newsCard.onclick = () => openModal(news);
            
            // Perbaikan di sini: Menambahkan backtick (`) yang hilang
            newsCard.innerHTML = `
                <div class="news-image">
                    <img src="${news.image}" alt="${news.title}">
                </div>
                <div class="news-content">
                    <span class="news-category">${news.category}</span>
                    <h3 class="news-title">${news.title}</h3>
                    <p class="news-excerpt">${news.excerpt}</p>
                </div>
            `;
            
            newsGrid.appendChild(newsCard);
        });
    }

    function openModal(news) {
        const modal = document.getElementById('newsModal');
        // Perbaikan di sini: Menambahkan backtick (`) yang hilang
        modal.innerHTML = `
            <div class="modal-content">
                <span class="close" onclick="closeModal()">&times;</span>
                <div class="modal-header">
                    <img src="${news.image}" alt="${news.title}">
                </div>
                <div class="modal-body">
                    <span class="news-category">${news.category}</span>
                    <h2>${news.title}</h2>
                    <p style="color: #666; margin-bottom: 1rem; font-size: 0.9rem;">${news.date}</p>
                    <p style="line-height: 1.7;">${news.content.replace(/\n/g, '<br><br>')}</p>
                </div>
            </div>
        `;
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        const modal = document.getElementById('newsModal');
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    window.onclick = function(event) {
        const modal = document.getElementById('newsModal');
        if (event.target === modal) {
            closeModal();
        }
    };

    // Mobile Menu Toggle
    const menuToggle = document.getElementById("menuToggle");
    const navItems = document.getElementById("navItems");
    if (menuToggle) {
        menuToggle.addEventListener('click', function() {
            navItems.classList.toggle('active');
            const icon = menuToggle.querySelector('i');
            icon.className = navItems.classList.contains('active') ? 'fa-solid fa-times' : 'fa-solid fa-bars';
        });
    }

    document.querySelectorAll('.nav-items a').forEach(link => {
        link.addEventListener('click', () => {
            if (navItems.classList.contains('active')) {
                navItems.classList.remove('active');
                menuToggle.querySelector('i').className = 'fa-solid fa-bars';
            }
        });
    });

    window.addEventListener('load', () => {
        populateNews();
    });

    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
</script>

</body>
</html>