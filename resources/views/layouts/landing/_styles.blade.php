{{-- Gaya CSS Kustom Layout Landing --}}
<style>
    body { 
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: {{ $is_frontend ? '#0a0a0a' : '#ffffff' }};
        color: {{ $is_frontend ? '#ffffff' : '#1a1a1a' }};
    }
    /* Gaya Preloader */
    #preloader {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: {{ $is_frontend ? '#0a0a0a' : '#ffffff' }};
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        transition: opacity 0.5s ease, visibility 0.5s ease;
    }
    .loader {
        width: 48px;
        height: 48px;
        border: 5px solid {{ $is_frontend ? '#1f2937' : '#fff' }};
        border-bottom-color: {{ $is_frontend ? '#f2994a' : '#ea580c' }};
        border-radius: 50%;
        display: inline-block;
        box-sizing: border-box;
        animation: rotation 1s linear infinite;
    }
    @keyframes rotation {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    .text-gradient {
        background: linear-gradient(135deg, {{ $is_frontend ? '#f2994a 0%, #e28a44 100%' : '#ea580c 0%, #9a3412 100%' }});
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .btn-premium {
        background: linear-gradient(135deg, {{ $is_frontend ? '#e28a44 0%, #f2994a 100%' : '#ea580c 0%, #c2410c 100%' }});
        box-shadow: 0 10px 20px -5px rgba(234, 88, 12, 0.3);
    }
    .soft-card {
        background: {{ $is_frontend ? '#121212' : '#ffffff' }};
        border: 1px solid {{ $is_frontend ? '#1f2937' : '#f3f4f6' }};
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.03);
        border-radius: 24px;
    }
    .nav-link-active {
        color: {{ $is_frontend ? '#f2994a' : '#ea580c' }};
        font-weight: 800;
    }
</style>
