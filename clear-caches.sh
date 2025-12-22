#!/bin/bash
# Clear all Laravel caches on dash.chatvoo.com
# Run this script on your server after deploying code changes

echo "Clearing Laravel caches..."

cd /path/to/your/kiki/folder  # UPDATE THIS PATH!

php artisan route:clear
echo "✓ Route cache cleared"

php artisan config:clear
echo "✓ Config cache cleared"

php artisan cache:clear
echo "✓ Application cache cleared"

php artisan view:clear
echo "✓ View cache cleared"

echo ""
echo "All caches cleared! Routes should now work."
echo ""
echo "Test the route:"
echo "https://dash.chatvoo.com/payment/alfa/return?test=1"
