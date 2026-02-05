@props([
    'hasHeader'  => true,
    'hasFeature' => true,
    'hasFooter'  => false,
])

<!DOCTYPE html>

<html
    lang="{{ app()->getLocale() }}"
    dir="{{ core()->getCurrentLocale()->direction }}"
>
    <head>

        {!! view_render_event('bagisto.shop.layout.head.before') !!}

        <title>{{ $title ?? '' }}</title>

        <meta charset="UTF-8">

        <meta
            http-equiv="X-UA-Compatible"
            content="IE=edge"
        >
        <meta
            http-equiv="content-language"
            content="{{ app()->getLocale() }}"
        >

        <meta
            name="viewport"
            content="width=device-width, initial-scale=1"
        >
        <meta
            name="base-url"
            content="{{ url()->to('/') }}"
        >
        <meta
            name="currency"
            content="{{ core()->getCurrentCurrency()->toJson() }}"
        >

        @stack('meta')

        {{-- Critical CSS: renders instantly before external stylesheets load --}}
        <style>
            *,*::before,*::after{box-sizing:border-box}
            html{margin-top:0!important;scroll-behavior:smooth}
            
            /* Luxury Color Palette Variables */
            :root{--gold-primary:#D4AF37;--gold-secondary:#B8941F;--gold-accent:#F4E4C1;--cream-primary:#FAF6EF;--cream-secondary:#F5EFE7;--charcoal:#0a0a0a;--charcoal-light:#2a2a2a;--rose-gold:#E8B4B8;--platinum:#E5E4E2}
            
            body{margin:0;padding:0;background:linear-gradient(135deg,var(--cream-primary) 0%,var(--cream-secondary) 50%,#E8DFD4 100%);color:var(--charcoal);font-family:'Poppins',sans-serif;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}
            #app{opacity:0;transition:opacity .4s ease}
            #app.app-loaded{opacity:1}
            .skip-to-main-content-link{position:absolute;left:-9999px;z-index:999;padding:1em;background:var(--charcoal);color:#fff;opacity:0}

            /* Luxury Loading Overlay */
            #luxury-loader{position:fixed;inset:0;z-index:9999;display:flex;flex-direction:column;align-items:center;justify-content:center;background:linear-gradient(135deg,var(--charcoal) 0%,var(--charcoal-light) 50%,#2a2a2a 100%);transition:opacity .6s cubic-bezier(.25,.8,.25,1),visibility .6s}
            #luxury-loader.loader-hidden{opacity:0;visibility:hidden;pointer-events:none}
            #luxury-loader .loader-logo{font-family:'Playfair Display',serif;font-size:2rem;font-weight:600;color:var(--gold-primary);letter-spacing:.15em;text-transform:uppercase;margin-bottom:2rem;opacity:0;animation:loaderFadeIn .8s ease .2s forwards}
            #luxury-loader .loader-bar{width:120px;height:1.5px;background:rgba(212,175,55,.15);border-radius:2px;overflow:hidden;position:relative}
            #luxury-loader .loader-bar::after{content:'';position:absolute;top:0;left:-100%;width:100%;height:100%;background:linear-gradient(90deg,transparent,var(--gold-primary),transparent);animation:loaderSlide 1.2s ease-in-out infinite}
            #luxury-loader .loader-tagline{margin-top:1.5rem;font-family:'Cormorant Garamond',serif;font-size:.9rem;color:rgba(212,175,55,.5);letter-spacing:.3em;text-transform:uppercase;opacity:0;animation:loaderFadeIn .8s ease .5s forwards}
            @keyframes loaderSlide{0%{left:-100%}100%{left:100%}}
            @keyframes loaderFadeIn{from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:translateY(0)}}

            /* Elegant Background Pattern */
            body::before{content:'';position:fixed;top:0;left:0;width:100%;height:100%;background-image:radial-gradient(circle at 25% 25%,rgba(212,175,55,.08) 0%,transparent 50%),radial-gradient(circle at 75% 75%,rgba(184,134,11,.05) 0%,transparent 50%);pointer-events:none;z-index:-1}
        </style>

        {{-- Preconnect to Google Fonts for faster loading --}}
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

        <link
            rel="icon"
            sizes="16x16"
            href="{{ core()->getCurrentChannel()->favicon_url ?? bagisto_asset('images/favicon.ico') }}"
        />

        @bagistoVite(['src/Resources/assets/css/app.css', 'src/Resources/assets/js/app.js'])

        {{-- Load all Google Fonts in a single request for efficiency --}}
        <link
            rel="stylesheet"
            href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400&family=DM+Serif+Display&display=swap"
        >

            @stack('styles')

        <style>
            /* Elegant Luxury Styles for Jewelry Boutique */
            .bg-luxury-cream{background:linear-gradient(135deg,var(--cream-primary) 0%,var(--cream-secondary) 50%,#E8DFD4 100%)}
            
            /* Elegant Typography */
            .font-luxury{font-family:'Playfair Display',serif}
            .font-elegant{font-family:'Cormorant Garamond',serif}
            
            /* Luxury Cards with Glass Effect */
            .luxury-card{background:rgba(255,255,255,.7);backdrop-filter:blur(10px);border:1px solid rgba(212,175,55,.2);box-shadow:0 8px 32px rgba(0,0,0,.1);transition:all .3s cubic-bezier(.25,.8,.25,1)}
            .luxury-card:hover{transform:translateY(-4px);box-shadow:0 12px 40px rgba(212,175,55,.2),0 8px 32px rgba(0,0,0,.15)}
            
            /* Gold Accents */
            .gold-accent{color:var(--gold-primary)}
            .gold-border{border-color:var(--gold-primary)}
            .gold-bg{background:linear-gradient(135deg,var(--gold-primary),var(--gold-secondary))}
            
            /* Elegant Buttons */
            .luxury-button{background:linear-gradient(135deg,var(--gold-primary),var(--gold-secondary));color:white;border:none;padding:12px 24px;border-radius:8px;font-weight:500;letter-spacing:.5px;text-transform:uppercase;transition:all .3s ease;box-shadow:0 4px 15px rgba(212,175,55,.3)}
            .luxury-button:hover{transform:translateY(-2px);box-shadow:0 6px 20px rgba(212,175,55,.4)}
            
            /* Elegant Animations */
            @keyframes shimmer{0%{background-position:-1000px 0}100%{background-position:1000px 0}}
            .shimmer-luxury{background:linear-gradient(90deg,transparent,rgba(212,175,55,.3),transparent);background-size:1000px 100%;animation:shimmer 2s infinite}
            
            /* Product Hover Effects */
            .product-luxury{transition:all .4s cubic-bezier(.25,.8,.25,1)}
            .product-luxury:hover{transform:scale(1.02) translateY(-8px)}
            
            /* Elegant Loading States */
            .luxury-shimmer{background:linear-gradient(90deg,var(--cream-primary) 25%,var(--cream-secondary) 50%,var(--cream-primary) 75%);background-size:200% 100%;animation:shimmer 1.5s infinite}
            
            /* Additional Luxury Styles */
            .font-playfair{font-family:'Playfair Display',serif}
            .text-charcoal{color:var(--charcoal)}
            
            {!! core()->getConfigData('general.content.custom_scripts.custom_css') !!}
        </style>

        @if(core()->getConfigData('general.content.speculation_rules.enabled'))
            <script type="speculationrules">
                @json(core()->getSpeculationRules(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
            </script>
        @endif

        {!! view_render_event('bagisto.shop.layout.head.after') !!}

    </head>

    <body>
        {{-- Luxury Loading Overlay --}}
        <div id="luxury-loader" aria-hidden="true">
            <div class="loader-logo">{{ config('app.name') }}</div>
            <div class="loader-bar"></div>
            <div class="loader-tagline">Boutique & Joyeria</div>
        </div>

        {!! view_render_event('bagisto.shop.layout.body.before') !!}

        <a
            href="#main"
            class="skip-to-main-content-link"
        >
            Skip to main content
        </a>

        <div id="app">
            <!-- Flash Message Blade Component -->
            <x-shop::flash-group />

            <!-- Confirm Modal Blade Component -->
            <x-shop::modal.confirm />

            <!-- Page Header Blade Component -->
            @if ($hasHeader)
                <x-shop::layouts.header />
            @endif

            @if(
                core()->getConfigData('general.gdpr.settings.enabled')
                && core()->getConfigData('general.gdpr.cookie.enabled')
            )
                <x-shop::layouts.cookie />
            @endif

            {!! view_render_event('bagisto.shop.layout.content.before') !!}

            <!-- Page Content Blade Component -->
            <main id="main" class="bg-gradient-to-br from-cream-primary via-cream-secondary to-cream-primary">
                {{ $slot }}
            </main>

            {!! view_render_event('bagisto.shop.layout.content.after') !!}


            <!-- Page Services Blade Component -->
            @if ($hasFeature)
                <x-shop::layouts.services />
            @endif

            <!-- Page Footer Blade Component -->
            @if ($hasFooter)
                <x-shop::layouts.footer />
            @endif
        </div>

        {!! view_render_event('bagisto.shop.layout.body.after') !!}

        @stack('scripts')

        {!! view_render_event('bagisto.shop.layout.vue-app-mount.before') !!}
        <script>
            /**
             * Load event, the purpose of using the event is to mount the application
             * after all of our `Vue` components which is present in blade file have
             * been registered in the app. No matter what `app.mount()` should be
             * called in the last.
             */
            window.addEventListener("load", function (event) {
                app.mount("#app");

                // Show the app and hide the luxury loader
                var appEl = document.getElementById('app');
                var loader = document.getElementById('luxury-loader');

                if (appEl) appEl.classList.add('app-loaded');
                if (loader) {
                    loader.classList.add('loader-hidden');
                    setTimeout(function() { loader.remove(); }, 700);
                }
            });

            // Fallback: if load takes too long (>2.5s), show content anyway
            setTimeout(function() {
                var appEl = document.getElementById('app');
                var loader = document.getElementById('luxury-loader');
                if (appEl && !appEl.classList.contains('app-loaded')) {
                    appEl.classList.add('app-loaded');
                    if (loader) {
                        loader.classList.add('loader-hidden');
                        setTimeout(function() { loader.remove(); }, 700);
                    }
                }
            }, 2500);
        </script>

        {!! view_render_event('bagisto.shop.layout.vue-app-mount.after') !!}

        <script type="text/javascript">
            {!! core()->getConfigData('general.content.custom_scripts.custom_javascript') !!}
        </script>
    </body>
</html>
