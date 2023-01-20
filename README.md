# haydengarage_backend

Tech requirements - Docker, PHP 8.1.0

This repository creates the backend for the application. I did begin using Inertia to handle the front end but aborted this due to an issue with dynamic content, the backend and frontend is now seporated.

The backend should be initiated first, the front end can then be loaded here: https://github.com/mrleon83/haydengarage_frontend

To run

install dependancies - composer install

run project locally using sail - ./vendor/bin/sail up

migrate the database - ./vendor/bin/sail artisan migrate
