
# ////////////////////////////////   tremnal  ////////////////////////////////// #


# تثبيت Laravel Breeze للمصادقة
composer require laravel/breeze --dev

# تثبيت Breeze مع Blade
php artisan breeze:install blade

# تشغيل NPM
npm install
npm run dev

# إنشاء المودلز
php artisan make:model Text -m
php artisan make:model Paragraph -m

# إنشاء الكونترولر
php artisan make:controller TextAnalyzerController

# تشغيل الهجرة
php artisan migrate


# إنشاء API Controller
php artisan make:controller Api/TextAnalyzerApiController

# إنشاء middleware للAPI
php artisan make:middleware ApiKeyMiddleware

# إنشاء Personal Access Token (إذا كنت تستخدم Sanctum)
php artisan install:api


# 1. تثبيت Sanctum
composer require laravel/sanctum

# 2. نشر ملفات Sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"

# 3. تشغيل الهجرة
php artisan migrate

# 4. إنشاء Auth Controller
php artisan make:controller Api/AuthController

# 5. تنظيف وإعادة تحميل
php artisan config:clear
php artisan cache:clear
php artisan route:clear

# 6. إنشاء توكن للمستخدم (في tinker)
php artisan tinker

# في tinker:
$user = User::create([
    'name' => 'Test User',
    'email' => 'test@example.com', 
    'password' => bcrypt('password123')
]);

$token = $user->createToken('API Token')->plainTextToken;
echo $token; # انسخ هذا التوكن واستخدمه في Postman

# أو إذا كان لديك مستخدم موجود:
$user = User::find(1);
$token = $user->createToken('API Token')->plainTextToken;
echo $token;

exit;


# ////////////////////////////////   Laravel  //////////////////////////////////#

# env.

GEMINI_API_KEY=
GEMINI_API_URL=

# bootstrap/app.php

api: __DIR__.'/../routes/api.php',


# config/app.php

'gemini_api_key' => env('GEMINI_API_KEY'),


# config/services.php

'gemini' => [
    'key' => env('GEMINI_API_KEY'),
],




