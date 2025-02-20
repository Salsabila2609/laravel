#!/bin/sh

# Stop jika ada error
set -e  

echo "⚙️  Running Composer Install..."
composer install --no-dev --prefer-dist --optimize-autoloader

echo "📦 Installing additional packages..."
composer require ezyang/htmlpurifier:^4.18 --no-interaction --ansi

echo "📂 Linking storage..."
php artisan storage:link

echo "🔄 Running Database Migration..."
php artisan migrate 

echo "📦 Installing Node Modules..."
npm install
npm install dompurify
npm run build

echo "✅ Container is ready!"

# Menjalankan perintah default container (misalnya PHP server atau supervisor)
exec "$@"
