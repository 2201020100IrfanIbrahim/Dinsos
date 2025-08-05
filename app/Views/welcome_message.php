<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MAU-KUEP - Sistem Informasi Manajemen Data Sosial</title>
    <meta name="description" content="Platform terpadu untuk monitoring dan pendataan bantuan sosial, penyandang disabilitas, dan UMKM">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="/favicon.ico">

    <style>
        * {
            transition: all 300ms ease;
            box-sizing: border-box;
        }
        
        *:focus {
            background-color: rgba(221, 72, 20, .2);
            outline: none;
        }
        
        html, body {
            color: rgba(33, 37, 41, 1);
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji";
            font-size: 16px;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            text-rendering: optimizeLegibility;
            line-height: 1.6;
        }

        /* Header Styles */
        header {
            background: linear-gradient(135deg, rgba(247, 248, 249, 0.95), rgba(255, 255, 255, 0.95));
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .menu {
            padding: 0.75rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1100px;
            margin: 0 auto;
            position: relative;
        }

        .logo-container {
            display: flex;
            align-items: center;
            height: 45px;
        }

        .logo-container a {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: inherit;
            transition: transform 300ms ease;
        }

        .logo-container a:hover {
            transform: scale(1.05);
        }

        .logo-container h1 {
            font-size: 1.6rem;
            margin: 0;
            color: rgba(33, 37, 41, 1);
            white-space: nowrap;
            font-weight: 700;
            background: linear-gradient(135deg, #dd4814, #ff6b35);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-items {
            list-style-type: none;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #dd4814;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 8px;
            transition: all 300ms ease;
        }

        .menu-toggle:hover {
            background: rgba(221, 72, 20, 0.1);
            transform: scale(1.1);
        }

        .menu-item {
            display: flex;
        }

        .menu-item a {
            padding: 0.6rem 1rem;
            border-radius: 8px;
            color: rgba(0, 0, 0, .7);
            text-decoration: none;
            font-weight: 500;
            transition: all 300ms ease;
            position: relative;
            overflow: hidden;
            white-space: nowrap;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .menu-item a::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(221, 72, 20, 0.1), transparent);
            transition: left 300ms ease;
        }

        .menu-item a:hover::before {
            left: 100%;
        }

        .menu-item a:hover {
            background: linear-gradient(135deg, rgba(221, 72, 20, .1), rgba(255, 107, 53, .1));
            color: rgba(221, 72, 20, 1);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(221, 72, 20, 0.2);
        }

        .user-icon a {
            padding: 0.4rem !important;
            margin-left: 10px;
        }

        .user-avatar {
            width: 30px;
            height: 30px;
            background: linear-gradient(135deg, #dd4814, #ff6b35);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 0.8rem;
            transition: all 300ms ease;
            border: 2px solid transparent;
        }

        .user-avatar:hover {
            border-color: rgba(221, 72, 20, 0.5);
            transform: scale(1.1);
        }

        /* Enhanced Hero Section with Advanced Animations */
        .hero-section {
            position: relative;
            height: 70vh;
            min-height: 500px;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 25%, #4a90e2 50%, #6bb6ff 75%, #87ceeb 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            overflow: hidden;
        }

        /* Hero Background Image with Animated Overlay */
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url('images/tup2.jpeg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            animation: backgroundZoom 20s ease-in-out infinite;
        }

        @keyframes backgroundZoom {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        .hero-section::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, 
                rgba(30, 60, 114, 0.85) 0%, 
                rgba(42, 82, 152, 0.8) 25%, 
                rgba(74, 144, 226, 0.75) 50%, 
                rgba(107, 182, 255, 0.7) 75%, 
                rgba(135, 206, 235, 0.65) 100%);
            animation: gradientShift 8s ease-in-out infinite;
        }

        @keyframes gradientShift {
            0%, 100% {
                background: linear-gradient(135deg, 
                    rgba(30, 60, 114, 0.85) 0%, 
                    rgba(42, 82, 152, 0.8) 25%, 
                    rgba(35, 95, 163, 0.75) 50%, 
                    rgba(107, 182, 255, 0.7) 75%, 
                    rgba(135, 206, 235, 0.65) 100%);
            }
        }

        /* Enhanced Floating Particles */
        .particle-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            overflow: hidden;
        }

        .particle {
            position: absolute;
            border-radius: 50%;
            animation: particleFloat 15s infinite linear;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
        }

        .particle-small {
            width: 3px;
            height: 3px;
            background: rgba(255, 255, 255, 0.8);
            animation-duration: 12s;
        }

        .particle-medium {
            width: 6px;
            height: 6px;
            background: rgba(255, 255, 255, 0.6);
            animation-duration: 18s;
        }

        .particle-large {
            width: 10px;
            height: 10px;
            background: rgba(255, 255, 255, 0.4);
            animation-duration: 25s;
        }

        @keyframes particleFloat {
            0% {
                transform: translateY(100vh) translateX(0) rotate(0deg) scale(0);
                opacity: 0;
            }
            10% {
                opacity: 1;
                transform: translateY(90vh) translateX(20px) rotate(45deg) scale(1);
            }
            50% {
                transform: translateY(50vh) translateX(-30px) rotate(180deg) scale(1.2);
            }
            90% {
                opacity: 1;
                transform: translateY(10vh) translateX(10px) rotate(315deg) scale(1);
            }
            100% {
                transform: translateY(-10vh) translateX(0) rotate(360deg) scale(0);
                opacity: 0;
            }
        }

        .hero-content {
            max-width: 800px;
            padding: 2rem;
            position: relative;
            z-index: 2;
        }

        .hero-content h1 {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            animation: titleFloat 3s ease-out;
        }

        @keyframes titleFloat {
            0% {
                opacity: 0;
                transform: translateY(50px) scale(0.9);
            }
            60% {
                transform: translateY(-10px) scale(1.05);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .hero-content p {
            font-size: 1.3rem;
            margin-bottom: 2rem;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
            animation: subtitleSlide 2s ease-out 0.5s both;
            line-height: 1.8;
        }

        @keyframes subtitleSlide {
            0% {
                opacity: 0;
                transform: translateX(-50px);
            }
            100% {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .cta-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            animation: buttonsPopIn 2s ease-out 1s both;
        }

        @keyframes buttonsPopIn {
            0% {
                opacity: 0;
                transform: translateY(30px) scale(0.8);
            }
            70% {
                transform: translateY(-5px) scale(1.05);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .btn {
            padding: 1rem 2rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 300ms ease;
            position: relative;
            overflow: hidden;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 500ms ease;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-primary {
            background: linear-gradient(135deg, #ffffff, #f8f9fa);
            color: #dd4814;
            box-shadow: 0 4px 15px rgba(255,255,255,0.3);
        }

        .btn-secondary {
            background: transparent;
            color: white;
            border: 2px solid white;
        }

        .btn:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            box-shadow: 0 8px 25px rgba(255,255,255,0.4);
        }

        .btn-secondary:hover {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(5px);
        }

        /* Stats Section */
        .stats-section {
            background: linear-gradient(135deg, #f8f9fa, #ffffff);
            padding: 3rem 1.5rem;
        }

        .stats-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.5rem;
        }

        .stat-card {
            background: white;
            padding: 1.8rem 1.5rem;
            border-radius: 16px;
            text-align: center;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
            transition: all 300ms ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(135deg, #dd4814, #ff6b35);
        }

        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.12);
        }

        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            display: block;
        }

        .stat-number {
            font-size: 2.2rem;
            font-weight: 800;
            color: #dd4814;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 1rem;
            color: #6c757d;
            font-weight: 500;
        }

        /* Features Section */
        .features-section {
            padding: 4rem 1.5rem;
            background: white;
        }

        .features-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-title {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .section-title h2 {
            font-size: 2.2rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 1rem;
        }

        .section-title p {
            font-size: 1.1rem;
            color: #6c757d;
            max-width: 600px;
            margin: 0 auto;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 1.5rem;
            margin-top: 2.5rem;
        }

        .feature-card {
            background: linear-gradient(135deg, #ffffff, #f8f9fa);
            padding: 2rem;
            border-radius: 16px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.06);
            transition: all 300ms ease;
            border: 1px solid rgba(221, 72, 20, 0.1);
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            border-color: rgba(221, 72, 20, 0.3);
        }

        .feature-icon {
            width: 55px;
            height: 55px;
            background: linear-gradient(135deg, #dd4814, #ff6b35);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.2rem;
            font-size: 1.4rem;
            color: white;
        }

        .feature-card h3 {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 0.8rem;
            color: #333;
        }

        .feature-card p {
            color: #6c757d;
            line-height: 1.6;
            font-size: 0.95rem;
        }

        /* News Section */
        .news-section {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            padding: 4rem 1.5rem;
        }

        .news-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .news-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 1.5rem;
            margin-top: 2.5rem;
        }

        .news-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
            transition: all 300ms ease;
            cursor: pointer;
        }

        .news-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.12);
        }

        .news-image {
            height: 160px;
            background: linear-gradient(135deg, #dd4814, #ff6b35);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2.5rem;
            position: relative;
            overflow: hidden;
        }

        .news-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 300ms ease;
        }

        .news-card:hover .news-image img {
            transform: scale(1.05);
        }

        .news-content {
            padding: 1.5rem;
        }

        .news-category {
            display: inline-block;
            background: linear-gradient(135deg, #dd4814, #ff6b35);
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-bottom: 0.8rem;
        }

        .news-date {
            color: #dd4814;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .news-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 0.8rem;
            color: #333;
            line-height: 1.4;
        }

        .news-excerpt {
            color: #6c757d;
            line-height: 1.6;
            margin-bottom: 1.2rem;
            font-size: 0.9rem;
        }

        .news-link {
            color: #dd4814;
            text-decoration: none;
            font-weight: 600;
            transition: color 300ms ease;
            font-size: 0.9rem;
        }

        .news-link:hover {
            color: #ff6b35;
        }

        .news-author {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #888;
            font-size: 0.85rem;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #eee;
        }

        .author-avatar {
            width: 24px;
            height: 24px;
            background: linear-gradient(135deg, #dd4814, #ff6b35);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.7rem;
            font-weight: bold;
        }

        /* Modal for News Detail */
        .modal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.8);
            backdrop-filter: blur(5px);
        }

        .modal-content {
            background-color: white;
            margin: 2% auto;
            padding: 0;
            border-radius: 16px;
            width: 90%;
            max-width: 800px;
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
        }

        .modal-header {
            position: relative;
            height: 300px;
            overflow: hidden;
            border-radius: 16px 16px 0 0;
        }

        .modal-header img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .modal-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 50%;
            background: linear-gradient(transparent, rgba(0,0,0,0.7));
        }

        .modal-title {
            position: absolute;
            bottom: 2rem;
            left: 2rem;
            right: 2rem;
            color: white;
            z-index: 10;
        }

        .modal-title h2 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }

        .modal-meta {
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .close {
            position: absolute;
            top: 1rem;
            right: 1.5rem;
            color: white;
            font-size: 2rem;
            font-weight: bold;
            cursor: pointer;
            z-index: 20;
            background: rgba(0,0,0,0.5);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.3s ease;
        }

        .close:hover {
            background: rgba(0,0,0,0.8);
        }

        .modal-body {
            padding: 2rem;
        }

        .modal-body p {
            margin-bottom: 1.5rem;
            line-height: 1.8;
            color: #333;
            font-size: 1.05rem;
        }

        /* Footer */
        footer {
            background: linear-gradient(135deg, #333, #222);
            color: white;
            text-align: center;
            padding: 3rem 2rem 1rem;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
        }

        .footer-info {
            margin-bottom: 2rem;
        }

        .footer-info h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: #ff6b35;
        }

        .footer-info p {
            color: #ccc;
            max-width: 600px;
            margin: 0 auto;
        }

        .footer-bottom {
            border-top: 1px solid #444;
            padding-top: 1rem;
            margin-top: 2rem;
            color: #999;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeInUp 1s ease-out;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-content h1 {
                font-size: 2.2rem;
            }
            
            .hero-content p {
                font-size: 1rem;
                padding: 0 1rem;
            }
            
            .cta-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .menu {
                padding: 0.6rem 1rem;
            }
            
            .logo-container h1 {
                font-size: 1.3rem;
            }
            
            .features-grid,
            .news-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .stats-container {
                grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
                gap: 1rem;
            }

            .stat-card {
                padding: 1.2rem 1rem;
            }

            .stat-icon {
                font-size: 2rem;
                margin-bottom: 0.8rem;
            }

            .stat-number {
                font-size: 1.8rem;
            }

            .stat-label {
                font-size: 0.9rem;
            }

            .section-title h2 {
                font-size: 1.8rem;
            }

            .section-title p {
                font-size: 1rem;
                padding: 0 1rem;
            }

            .features-section,
            .news-section,
            .stats-section {
                padding: 2.5rem 1rem;
            }

            .feature-card {
                padding: 1.5rem;
            }

            .feature-icon {
                width: 45px;
                height: 45px;
                font-size: 1.2rem;
            }

            .feature-card h3 {
                font-size: 1.1rem;
            }

            .feature-card p {
                font-size: 0.9rem;
            }

            .news-image {
                height: 140px;
                font-size: 2rem;
            }

            .news-content {
                padding: 1.2rem;
            }

            .news-title {
                font-size: 1.1rem;
            }

            .news-excerpt {
                font-size: 0.85rem;
            }

            /* Mobile Menu Styles */
            .menu-toggle {
                display: block;
                order: 2;
            }

            .nav-items {
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: white;
                flex-direction: column;
                box-shadow: 0 5px 15px rgba(0,0,0,0.1);
                border-radius: 0 0 15px 15px;
                padding: 1rem;
                gap: 0;
                transform: translateY(-10px);
                opacity: 0;
                visibility: hidden;
                transition: all 300ms ease;
            }

            .nav-items.active {
                transform: translateY(0);
                opacity: 1;
                visibility: visible;
            }

            .menu-item {
                width: 100%;
                margin-bottom: 5px;
            }

            .menu-item a {
                width: 100%;
                padding: 0.8rem;
                border-radius: 10px;
                justify-content: flex-start;
                background: rgba(247, 248, 249, 0.5);
                border: 1px solid rgba(221, 72, 20, 0.1);
                font-size: 0.9rem;
            }

            .menu-item a:hover {
                background: linear-gradient(135deg, rgba(221, 72, 20, .15), rgba(255, 107, 53, .15));
                transform: translateX(5px);
                box-shadow: 0 2px 10px rgba(221, 72, 20, 0.2);
            }

            .user-icon a {
                justify-content: center;
                background: linear-gradient(135deg, rgba(221, 72, 20, .1), rgba(255, 107, 53, .1));
            }

            .modal-content {
                width: 95%;
                margin: 5% auto;
            }

            .modal-header {
                height: 200px;
            }

            .modal-title h2 {
                font-size: 1.5rem;
            }

            .modal-body {
                padding: 1.5rem;
            }
        }

        @media (max-width: 480px) {
            .logo-container h1 {
                font-size: 1.1rem;
            }
            
            .menu {
                padding: 0.5rem 0.8rem;
            }

            .hero-content h1 {
                font-size: 1.8rem;
            }

            .stats-container {
                grid-template-columns: 1fr 1fr;
                gap: 0.8rem;
            }

            .stat-card {
                padding: 1rem 0.8rem;
            }

            .stat-number {
                font-size: 1.6rem;
            }

            .features-grid,
            .news-grid {
                gap: 0.8rem;
            }

            .section-title h2 {
                font-size: 1.6rem;
            }
        }
    </style>
</head>
<body>

<header>
    <div class="menu">
        <div class="logo-container">
            <a href="/">
                <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #2781f0ff, #397df4ff); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; margin-right: 10px; font-size: 0.9rem;">MK</div>
                <h1>MAU-KUEP</h1>
            </a>
        </div>
        
        <button class="menu-toggle" id="menuToggle">☰</button>
        
        <ul class="nav-items" id="navItems">
            <li class="menu-item user-icon">
                <a href="/login" title="Login">
                    <div class="user-avatar">👤</div>
                </a>
            </li>
        </ul>
    </div>
</header>

<section class="hero-section">
    <!-- Enhanced Particle System -->
    <div class="particle-container" id="particleContainer"></div>
    
    <div class="hero-content">
        <h1>MAU-KUEP</h1>
        <p>Platform Terpadu untuk Monitoring dan Pendataan Bantuan Sosial, Penyandang Disabilitas, dan UMKM di Kepulauan Riau</p>
        <div class="cta-buttons">
            <a href="#features" class="btn btn-primary">
                🚀 Mulai Sekarang
            </a>
            <a href="#news" class="btn btn-secondary">
                📰 Lihat Berita
            </a>
        </div>
    </div>
</section>

<section class="stats-section">
    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-icon">📊</div>
            <div class="stat-number">15,247</div>
            <div class="stat-label">Data Penerima Bansos</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">♿</div>
            <div class="stat-number">3,892</div>
            <div class="stat-label">Data Penyandang Disabilitas</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">🏪</div>
            <div class="stat-number">8,156</div>
            <div class="stat-label">Data UMKM Terdaftar</div>
        </div>
    </div>
</section>

<section class="features-section" id="features">
    <div class="features-container">
        <div class="section-title">
            <h2>Fitur Unggulan</h2>
            <p>Sistem informasi terintegrasi untuk mendukung program sosial yang efektif dan tepat sasaran</p>
        </div>
        
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">📋</div>
                <h3>SIM-Bankel (Bantuan Sosial)</h3>
                <p>Sistem pendataan dan monitoring bantuan sosial yang akurat untuk memastikan bantuan tepat sasaran kepada masyarakat yang membutuhkan.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">♿</div>
                <h3>SIM-Difabel (Disabilitas)</h3>
                <p>Platform khusus untuk pendataan penyandang disabilitas dengan fitur komprehensif untuk mendukung program inklusi sosial.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">📈</div>
                <h3>SIM-Monevkuep (Monitoring UMKM)</h3>
                <p>Sistem monitoring dan evaluasi untuk usaha mikro, kecil, dan menengah guna mendukung pertumbuhan ekonomi lokal.</p>
            </div>
        </div>
    </div>
</section>

<section class="news-section" id="news">
    <div class="news-container">
        <div class="section-title">
            <h2>Berita & Informasi</h2>
            <p>Update terbaru mengenai program bantuan sosial dan pemberdayaan masyarakat di Kepulauan Riau</p>
        </div>
        
        <div class="news-grid" id="newsGrid">
            <!-- Berita akan diisi dari JavaScript -->
        </div>
    </div>
</section>

<!-- Modal untuk Detail Berita -->
<div id="newsModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span class="close">&times;</span>
            <img id="modalImage" src="" alt="">
            <div class="modal-title">
                <h2 id="modalTitle"></h2>
                <div class="modal-meta">
                    <span id="modalCategory" class="news-category"></span>
                    <span id="modalDate" class="news-date"></span>
                    <div class="news-author">
                        <div class="author-avatar" id="modalAuthorAvatar"></div>
                        <span id="modalAuthor"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-body">
            <div id="modalContent"></div>
        </div>
    </div>
</div>

<footer>
    <div class="footer-content">
        <div class="footer-info">
            <h3>MAU-KUEP</h3>
            <p>Platform digital yang mengintegrasikan sistem informasi manajemen untuk bantuan sosial, penyandang disabilitas, dan UMKM di Kepulauan Riau. Mendukung transparansi dan akuntabilitas program sosial pemerintah.</p>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 Dinas Sosial Kepulauan Riau. Semua hak dilindungi undang-undang.</p>
        </div>
    </div>
</footer>

<script>
    // ========================================
    // DATA BERITA - EDIT DI SINI UNTUK MENAMBAH/MENGEDIT BERITA
    // ========================================
    const newsData = [
        {
            id: 1,
            title: "Penyaluran Bantuan Sosial Tunai Mencapai 95% di Kepri",
            category: "Bantuan Sosial",
            excerpt: "Dinas Sosial Kepulauan Riau berhasil menyalurkan bantuan sosial tunai kepada 15.247 keluarga penerima manfaat dengan tingkat akurasi data mencapai 95%.",
            content: `
                <p>Dalam upaya memastikan tepat sasaran, Dinas Sosial Kepulauan Riau telah berhasil menyalurkan bantuan sosial tunai kepada 15.247 keluarga penerima manfaat di seluruh wilayah Kepri. Program ini merupakan bagian dari komitmen pemerintah daerah untuk mengentaskan kemiskinan dan meningkatkan kesejahteraan masyarakat.</p>
                
                <p>Kepala Dinas Sosial Kepri menyampaikan bahwa tingkat akurasi data mencapai 95% berkat dukungan sistem informasi MAU-KUEP yang terintegrasi. "Dengan sistem yang canggih ini, kami dapat memastikan bantuan tepat sasaran dan mengurangi potensi penyalahgunaan," ujarnya dalam keterangan pers.</p>
                
                <p>Program bantuan sosial ini mencakup bantuan langsung tunai, bantuan sembako, dan program pemberdayaan ekonomi untuk keluarga kurang mampu. Setiap penerima manfaat telah terverifikasi melalui sistem pendataan terpadu yang memadukan data dari berbagai instansi terkait.</p>
                
                <p>Dengan capaian ini, Kepulauan Riau menjadi salah satu provinsi dengan tingkat akurasi penyaluran bantuan sosial tertinggi di Indonesia. Program ini diharapkan dapat terus berkelanjutan untuk mendukung perekonomian masyarakat, terutama di masa pemulihan ekonomi pasca pandemi.</p>
            `,
            author: "Admin Dinsos Kepri",
            date: "28 Juli 2025",
            image: "https://images.unsplash.com/photo-1559027615-cd4628902d4a?w=800&h=400&fit=crop"
        },
        {
            id: 2,
            title: "Program Rehabilitasi Sosial untuk Penyandang Disabilitas Dimulai",
            category: "Disabilitas",
            excerpt: "Launching program rehabilitasi sosial komprehensif untuk 3.892 penyandang disabilitas di Kepulauan Riau dengan dukungan teknologi SIM-Difabel.",
            content: `
                <p>Pemerintah Kepulauan Riau resmi meluncurkan program rehabilitasi sosial komprehensif yang akan menjangkau 3.892 penyandang disabilitas di seluruh wilayah Kepri. Program ini merupakan implementasi dari komitmen daerah untuk mewujudkan inklusi sosial yang berkelanjutan dan memastikan hak-hak penyandang disabilitas terpenuhi.</p>
                
                <p>Program rehabilitasi sosial ini mencakup berbagai layanan seperti terapi fisik, terapi okupasi, konseling psikologi, pelatihan keterampilan hidup, dan program pemberdayaan ekonomi. Setiap penyandang disabilitas akan mendapat layanan yang disesuaikan dengan jenis disabilitas dan kebutuhan spesifik mereka.</p>
                
                <p>Dengan dukungan sistem SIM-Difabel yang terintegrasi dalam platform MAU-KUEP, proses pendataan dan monitoring program menjadi lebih efektif dan real-time. Sistem ini memungkinkan tracking progress setiap individu dan memastikan kualitas layanan yang diberikan sesuai dengan standar yang ditetapkan.</p>
                
                <p>Gubernur Kepulauan Riau dalam sambutannya menekankan pentingnya program ini sebagai wujud komitmen daerah terhadap pembangunan inklusif. "Tidak ada yang tertinggal dalam pembangunan Kepri, termasuk saudara-saudara kita penyandang disabilitas," ungkapnya.</p>
            `,
            author: "Tim Inklusi Sosial",
            date: "25 Juli 2025",
            image: "https://images.unsplash.com/photo-1576091160399-112ba8d25d1f?w=800&h=400&fit=crop"
        },
        {
            id: 3,
            title: "8.156 UMKM Terdaftar dalam Program Pemberdayaan Ekonomi",
            category: "UMKM",
            excerpt: "Melalui platform SIM-Monevkuep, tercatat 8.156 UMKM telah terdaftar dan mendapat akses ke program pemberdayaan ekonomi dari pemerintah daerah.",
            content: `
                <p>Dalam rangka mendorong pertumbuhan ekonomi lokal dan meningkatkan daya saing UMKM, Pemerintah Kepulauan Riau berhasil mendata dan memberdayakan 8.156 unit usaha mikro, kecil, dan menengah (UMKM) melalui platform SIM-Monevkuep yang terintegrasi dalam sistem MAU-KUEP.</p>
                
                <p>Program pemberdayaan UMKM ini meliputi berbagai bentuk bantuan seperti modal usaha, pelatihan manajemen bisnis, pendampingan teknis, fasilitasi akses pasar, dan program digitalisasi usaha. Setiap UMKM mendapat pendampingan yang disesuaikan dengan jenis usaha dan tingkat kebutuhan pengembangan.</p>
                
                <p>Platform SIM-Monevkuep memungkinkan monitoring real-time terhadap progress setiap UMKM binaan, mulai dari peningkatan omzet, jumlah tenaga kerja, ekspansi pasar, hingga adopsi teknologi digital. Data ini menjadi dasar untuk evaluasi program dan pengembangan strategi pemberdayaan ke depan.</p>
                
                <p>Kepala Dinas Koperasi dan UKM Kepri menyatakan bahwa dengan sistem digital ini, pelayanan kepada UMKM menjadi lebih efisien dan tepat sasaran. "Kami optimis UMKM Kepri akan semakin berkembang dan mampu bersaing di pasar yang lebih luas," kata Kepala Dinas dalam keterangan resminya.</p>
            `,
            author: "Diskop UKM Kepri",
            date: "22 Juli 2025",
            image: "https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=800&h=400&fit=crop"
        },
        {
            id: 4,
            title: "Pelatihan Digital Marketing untuk UMKM Kepri Sukses Digelar",
            category: "UMKM",
            excerpt: "Sebanyak 200 pelaku UMKM mengikuti pelatihan digital marketing untuk meningkatkan penjualan online dan memperluas jangkauan pasar.",
            content: `
                <p>Dinas Koperasi dan UKM Kepulauan Riau berhasil menyelenggarakan pelatihan digital marketing yang diikuti oleh 200 pelaku UMKM dari berbagai sektor usaha. Pelatihan ini merupakan bagian dari program pemberdayaan UMKM yang bertujuan untuk meningkatkan kemampuan pemasaran digital para pelaku usaha.</p>
                
                <p>Materi pelatihan mencakup strategi pemasaran media sosial, pembuatan konten kreatif, teknik fotografi produk, manajemen toko online, dan analisis performa digital marketing. Para peserta juga mendapat pendampingan langsung dalam membuat akun bisnis dan mengoptimalkan strategi pemasaran mereka.</p>
                
                <p>Hasil dari pelatihan ini sangat menggembirakan, dimana 85% peserta berhasil meningkatkan penjualan online mereka dalam kurun waktu 2 minggu setelah pelatihan. Beberapa UMKM bahkan melaporkan peningkatan omzet hingga 150% setelah menerapkan strategi digital marketing yang dipelajari.</p>
                
                <p>Program ini akan dilanjutkan dengan pelatihan lanjutan mengenai e-commerce dan ekspor digital untuk membantu UMKM Kepri merambah pasar internasional, khususnya negara-negara ASEAN yang merupakan pasar potensial bagi produk-produk unggulan Kepulauan Riau.</p>
            `,
            author: "Tim Pelatihan UMKM",
            date: "20 Juli 2025",
            image: "https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=800&h=400&fit=crop"
        },
        {
            id: 5,
            title: "Bantuan Alat Bantu untuk Penyandang Disabilitas Tersalurkan",
            category: "Disabilitas",
            excerpt: "Distribusi 500 unit alat bantu seperti kursi roda, tongkat putih, dan alat bantu dengar untuk penyandang disabilitas di Kepri.",
            content: `
                <p>Dinas Sosial Kepulauan Riau berhasil mendistribusikan 500 unit alat bantu kepada penyandang disabilitas di seluruh wilayah Kepri. Bantuan ini meliputi kursi roda, tongkat putih untuk tunanetra, alat bantu dengar, kruk, dan berbagai alat bantu mobilitas lainnya.</p>
                
                <p>Pendistribusian alat bantu ini merupakan hasil dari pendataan komprehensif melalui sistem SIM-Difabel yang berhasil mengidentifikasi kebutuhan spesifik setiap penyandang disabilitas. Data akurat ini memungkinkan penyaluran bantuan yang tepat sasaran dan sesuai dengan jenis disabilitas yang dialami.</p>
                
                <p>Para penerima bantuan juga mendapat pelatihan penggunaan alat bantu dan konseling untuk memaksimalkan manfaat dari alat bantu yang diterima. Tim medis dan terapis khusus memberikan pendampingan untuk memastikan alat bantu dapat digunakan dengan optimal.</p>
                
                <p>Kepala Dinas Sosial menyampaikan apresiasi kepada semua pihak yang terlibat dalam program ini. "Dengan alat bantu yang tepat, penyandang disabilitas dapat menjalani aktivitas sehari-hari dengan lebih mandiri dan bermartabat," ungkapnya dalam acara serah terima bantuan.</p>
            `,
            author: "Divisi Rehabilitasi Sosial",
            date: "18 Juli 2025",
            image: "https://images.unsplash.com/photo-1544027993-37dbfe43562a?w=800&h=400&fit=crop"
        },
        {
            id: 6,
            title: "Penyaluran Bantuan Sembako Ramadan Capai Target 100%",
            category: "Bantuan Sosial",
            excerpt: "Program bantuan sembako Ramadan berhasil menjangkau 20.000 keluarga kurang mampu di seluruh Kepulauan Riau tepat waktu.",
            content: `
                <p>Alhamdulillah, program penyaluran bantuan sembako Ramadan 1446 H berhasil mencapai target 100% dengan menjangkau 20.000 keluarga kurang mampu di seluruh Kepulauan Riau. Distribusi dilakukan secara serentak di 7 kabupaten/kota dengan memanfaatkan sistem pendataan MAU-KUEP untuk memastikan akurasi penerima.</p>
                
                <p>Setiap paket sembako berisi bahan pokok berkualitas seperti beras 10kg, minyak goreng 2 liter, gula pasir 1kg, tepung terigu 1kg, susu kental manis, mie instan, dan berbagai kebutuhan dapur lainnya yang cukup untuk kebutuhan keluarga selama bulan Ramadan.</p>
                
                <p>Gubernur Kepulauan Riau secara langsung memantau proses distribusi di beberapa titik penyaluran. "Ini adalah bentuk kepedulian pemerintah daerah terhadap masyarakat kurang mampu, khususnya di bulan suci Ramadan. Semoga bantuan ini dapat meringankan beban keluarga dan menambah berkah di bulan yang penuh rahmat ini," ungkap Gubernur.</p>
                
                <p>Proses distribusi berjalan lancar berkat koordinasi yang baik antara Dinas Sosial, TNI, Polri, dan relawan di tingkat kelurahan. Sistem MAU-KUEP juga membantu dalam monitoring real-time untuk memastikan tidak ada keluarga yang terlewat dari program bantuan ini.</p>
            `,
            author: "Panitia Bantuan Ramadan",
            date: "15 Juli 2025",
            image: "https://images.unsplash.com/photo-1586201375761-83865001e31c?w=800&h=400&fit=crop"
        }
    ];

    // ========================================
    // FUNGSI UNTUK MENAMPILKAN BERITA
    // ========================================
    function displayNews() {
        const newsGrid = document.getElementById('newsGrid');
        newsGrid.innerHTML = '';

        // Tampilkan maksimal 6 berita terbaru
        const displayedNews = newsData.slice(0, 6);

        displayedNews.forEach(news => {
            const newsCard = document.createElement('div');
            newsCard.className = 'news-card';
            newsCard.onclick = () => openModal(news);

            newsCard.innerHTML = `
                <div class="news-image">
                    <img src="${news.image}" alt="${news.title}" 
                         onerror="this.parentElement.innerHTML='<div style=\\'display: flex; align-items: center; justify-content: center; height: 100%; font-size: 2.5rem;\\'>📰</div>'">
                </div>
                <div class="news-content">
                    <span class="news-category">${news.category}</span>
                    <div class="news-date">📅 ${news.date}</div>
                    <h3 class="news-title">${news.title}</h3>
                    <p class="news-excerpt">${news.excerpt}</p>
                    <div class="news-author">
                        <div class="author-avatar">${news.author.charAt(0)}</div>
                        <span>${news.author}</span>
                    </div>
                </div>
            `;

            newsGrid.appendChild(newsCard);
        });
    }

    // ========================================
    // FUNGSI MODAL DETAIL BERITA
    // ========================================
    function openModal(news) {
        const modal = document.getElementById('newsModal');
        document.getElementById('modalImage').src = news.image;
        document.getElementById('modalTitle').textContent = news.title;
        document.getElementById('modalCategory').textContent = news.category;
        document.getElementById('modalDate').textContent = '📅 ' + news.date;
        document.getElementById('modalAuthor').textContent = news.author;
        document.getElementById('modalAuthorAvatar').textContent = news.author.charAt(0);
        document.getElementById('modalContent').innerHTML = news.content;
        
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        const modal = document.getElementById('newsModal');
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    // Event listeners untuk modal
    document.querySelector('.close').onclick = closeModal;
    window.onclick = function(event) {
        const modal = document.getElementById('newsModal');
        if (event.target === modal) {
            closeModal();
        }
    };

    // ========================================
    // ANIMASI PARTIKEL
    // ========================================
    function createParticles() {
        const container = document.getElementById('particleContainer');
        const particleCount = 50;
        
        for (let i = 0; i < particleCount; i++) {
            const particle = document.createElement('div');
            const size = Math.random() < 0.3 ? 'particle-small' : 
                         Math.random() < 0.6 ? 'particle-medium' : 'particle-large';
            
            particle.className = `particle ${size}`;
            particle.style.left = Math.random() * 100 + '%';
            particle.style.animationDelay = Math.random() * 15 + 's';
            particle.style.animationDuration = (12 + Math.random() * 8) + 's';
            
            container.appendChild(particle);
        }
    }

    // ========================================
    // MOBILE MENU
    // ========================================
    const menuToggle = document.getElementById("menuToggle");
    const navItems = document.getElementById("navItems");
    
    if (menuToggle) {
        menuToggle.addEventListener('click', function() {
            navItems.classList.toggle('active');
            
            if (navItems.classList.contains('active')) {
                menuToggle.innerHTML = '✕';
                menuToggle.style.transform = 'rotate(180deg)';
            } else {
                menuToggle.innerHTML = '☰';
                menuToggle.style.transform = 'rotate(0deg)';
            }
        });
    }

    // Close mobile menu when clicking outside
    document.addEventListener('click', function(event) {
        if (menuToggle && !menuToggle.contains(event.target) && !navItems.contains(event.target)) {
            navItems.classList.remove('active');
            menuToggle.innerHTML = '☰';
            menuToggle.style.transform = 'rotate(0deg)';
        }
    });

    // ========================================
    // SMOOTH SCROLLING
    // ========================================
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

    // ========================================
    // COUNTER ANIMATION UNTUK STATISTIK
    // ========================================
    function animateCounter(element, target) {
        let current = 0;
        const increment = target / 100;
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            element.textContent = Math.floor(current).toLocaleString();
        }, 20);
    }

    const statsObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const numbers = entry.target.querySelectorAll('.stat-number');
                numbers.forEach(number => {
                    const target = parseInt(number.textContent.replace(/,/g, ''));
                    animateCounter(number, target);
                });
                statsObserver.unobserve(entry.target);
            }
        });
    });

    // ========================================
    // INISIALISASI HALAMAN
    // ========================================
    document.addEventListener('DOMContentLoaded', function() {
        // Tampilkan berita
        displayNews();
        
        // Buat partikel animasi
        createParticles();
        
        // Observer untuk animasi counter
        const statsSection = document.querySelector('.stats-section');
        if (statsSection) {
            statsObserver.observe(statsSection);
        }
        
        // Animation observer untuk cards
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe semua cards untuk animasi
        document.querySelectorAll('.stat-card, .feature-card, .news-card').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = 'all 0.6s ease-out';
            observer.observe(card);
        });
    });

    // ========================================
    // PARALLAX EFFECT
    // ========================================
    window.addEventListener('scroll', function() {
        const scrolled = window.pageYOffset;
        const heroSection = document.querySelector('.hero-section');
        const rate = scrolled * -0.5;
        
        if (heroSection) {
            heroSection.style.transform = `translateY(${rate}px)`;
        }
    });
</script>

</body>
</html>