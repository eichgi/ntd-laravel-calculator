# Steps for setup

1. Move to the project's root
2. Copy .env.example into .env
3. Uncomment and define DB_* vars inside .env
4. Install composer deps `composer install`
5. Run migrations with
`php artisan migrate`
6. Run seeders
`php artisan db:seed`
7. Run the project
`php artisan serve`
8. Install npm deps
`npm install`
9. Run vite locally
`npm run dev`


### Tests
There are integration tests and unit tests for the calculator API
1. For unit tests run:
`./vendor/bin/phpunit tests/Unit/CalculatorTest.php`
2. For integration tests run:
`./vendor/bin/phpunit tests/Unit/CalculatorTest.php`

### Credentials
email: test@example.com

password: password

This user has 100 records if seeded.

---

#Features:
- Database includes migration, factories, seeders and indexes.
- Backend includes authentication, requests validations, controllers, services, repositories and models.
- Integration and unit testing
- Frontend is built with react and typescript. 
