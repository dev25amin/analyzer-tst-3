<svg viewBox="0 0 316 316" xmlns="http://www.w3.org/2000/svg" {{ $attributes }}>
    <defs>
        <linearGradient id="lightning" x1="0%" y1="0%" x2="100%" y2="0%">
            <stop offset="0%" stop-color="yellow">
                <animate attributeName="offset" values="0;1;0" dur="3s" repeatCount="indefinite"/>
            </stop>
            <stop offset="50%" stop-color="white">
                <animate attributeName="offset" values="0.5;1.5;0.5" dur="3s" repeatCount="indefinite"/>
            </stop>
            <stop offset="100%" stop-color="yellow">
                <animate attributeName="offset" values="1;2;1" dur="3s" repeatCount="indefinite"/>
            </stop>
        </linearGradient>
    </defs>

    <path d="M50 60h216c8.837 0 16 7.163 16 16v164c0 8.837-7.163 16-16 16H50c-8.837 0-16-7.163-16-16V76c0-8.837 7.163-16 16-16zm0 20v164h216V80H50z" stroke="url(#lightning)" stroke-width="3" fill="none"/>

    <path d="M70 110h80c5.523 0 10-4.477 10-10s-4.477-10-10-10H70c-5.523 0-10 4.477-10 10s4.477 10 10 10z"/>
    <path d="M70 140h120c5.523 0 10-4.477 10-10s-4.477-10-10-10H70c-5.523 0-10 4.477-10 10s4.477 10 10 10z"/>
    <path d="M70 170h100c5.523 0 10-4.477 10-10s-4.477-10-10-10H70c-5.523 0-10 4.477-10 10s4.477 10 10 10z"/>
    <path d="M70 200h90c5.523 0 10-4.477 10-10s-4.477-10-10-10H70c-5.523 0-10 4.477-10 10s4.477 10 10 10z"/>
    <path d="M70 230h110c5.523 0 10-4.477 10-10s-4.477-10-10-10H70c-5.523 0-10 4.477-10 10s4.477 10 10 10z"/>

    <circle cx="230" cy="130" r="25" fill="none" stroke="currentColor" stroke-width="6"/>
    <path d="M248 148l18 18" stroke="currentColor" stroke-width="6" stroke-linecap="round"/>
    <path d="M210 115h40M210 135h30M210 145h35" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
</svg>
