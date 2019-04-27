# Laravel - VueJS Exam


## Software prerequisites
 - Depending on your operating system, install docker: https://docs.docker.com/install/.
 - Also if still needed, install "docker-compose": https://docs.docker.com/compose/install/
 - Install composer: https://getcomposer.org/download/
 - Install NodeJS (with NPM) https://nodejs.org/en/download/
 - Install git https://git-scm.com/book/en/v2/Getting-Started-Installing-Git

## Steps to run the app
 - Clone this repository
    ```
    git clone <repository-url>
    ```
 - Go into the root directory of the repo:
    ```
    cd laravel-vuejs-exam
    ```
 - Go into the web app directory.
    ```
    cd web-app
    ```
 - Copy the sample env (sample env contains already the local environment settings)
    ```
    cp .env.example .env
    ```
 - Install the backend dependencies
    ```
    composer install
    ```
 - Install NPM dependencies
    ```
    npm install
    ```
 - Compile the frontend (VueJS)
    ```
    npm run production
    ```
 - Go back to the repo root directory
    ```
    cd ..
    ```
 - Start the docker cluster
    ```
    docker-compose up
    ```
 - Once the docker cluster is up and running, open a new terminal window and run the following command to migrate the database:
    ```
    docker exec -it laravel_vuejs_exam_web_app php artisan migrate
    ```
 - Then run the following command to seed the database with the default data.
    ```
    docker exec -it laravel_vuejs_exam_web_app php artisan db:seed
    ```
 - The application should be accessible at `http://localhost:8000`.


## Cluster URL's
 - `http://localhost:8000` - for the web app.
 - `http://localhost:4444` - for the phpmyadmin

## Sample Account
 - Email: melvin.empleo.test@gmail.com
 - Password: admin123
 - User type: admin

## Cluster Components
 - Ubuntu-based image for the web app
 - PHPMyAdmin
 - Redis 5.0
 - MySQL 5.7


### Notes:
 - The cluster's MySQL might not work on the first few runs, just restart the cluster until it runs properly.

