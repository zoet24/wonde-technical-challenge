# Wonde Technical Challenge

A technical challenge from Wonde to fulfil the following user story: "As a Teacher I want to be able to see which students are in my class each day
of the week so that I can be suitably prepared." The frontend was built using React, TypeScript and Tailwind, and backend was built using PHP.

## Deployment

- Clone the git repo locally
- `cd` into the backend directory, add a .env file with your API_ACCESS_TOKEN and run `composer install`
- `cd` into the root directory and run `php -S localhost:8000 -t backend/api` to start the PHP backend
- `cd` into the frontend directory, run `npm install` and then `npm start` to start the React frontend
- Open "http://localhost:3000/" in your browser to view the site

## Future work

- Add in link to student profiles
- Add in smoother animation transitions

## Dev commands

- `npm start` to start React frontend
- `php -S localhost:8000 -t backend/api` to start PHP backend
