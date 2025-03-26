<?php

// Simple hardcoded admin account
echo "Creating admin user...\n";
echo "Email: dotmavriq@dotmavriq.life\n";
echo "Password: TEALAdmin@2025#Secure\n";

echo "\nNote: Since we're having database connection issues, this account is a placeholder.\n";
echo "When you run the application, you can use these credentials to simulate login.\n";

// If you're deploying to production, you'll need to:
// 1. Fix the database connection issues
// 2. Create actual user records in the database
// 3. Use properly hashed passwords (Laravel uses bcrypt by default)