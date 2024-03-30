
Reader Hub API
API aimed at managing readers and books


Main features

API has an authentication system based on Bearer Token, in addition to the cruds of readers, books, book genres and publishers, in addition to sending an email on the reader's birthday with the total number of books read in the year and the total number of pages read since the start your registration on the platform.


Feature 1: Reader Registration.

Auth: No
Endpoint: /registrar-se
HTTP Methods: POST
Description: Function for creating reader registration (Store).


Feature 2: Reader Login.

Auth: No
Endpoint: /login
HTTP Methods: POST
Description: Reader login function.


Feature 3: Other reader functions.

Auth: Yes
Endpoint: /leitores
HTTP methods: GET, PATCH, DELETE
Description: Basic functions related to the reader (Index, Show, Update, Destroy)

Feature 4: Collects Redis cache from reader.

Auth: Yes
Endpoint: /cache
HTTP Methods: GET
Description: Function that returns the total number of pages read and the total number of books read.

Feature 5: Publisher functions.

Auth: Yes
Endpoint: /editoras
HTTP methods: POST, GET, PATCH, DELETE
Description: Standard function related to publishers. (Index, Store, Show, Update, Destroy)

Feature 6: Functions for book genres

Auth: Yes
Endpoint: /generos
HTTP methods: POST, GET, PATCH, DELETE
Description: Standard functions, related to genders. (Index, Store, Show, Update, Destroy)

Resource 7: Functions of books.

Auth: Yes
Endpoint: /livros
HTTP methods: POST, GET, PATCH, DELETE
Description: Standard function, related to books. (Index, Store, Show, Update, Destroy)

Resource 8: Book and reader relationship functions.

Auth: Yes
Endpoint: /livros-leitores
HTTP methods: POST, GET, DELETE
Description: Functions to control the relationship between books and readers. (Index, Store, Destroy)


Authentication
Authentication based on the Bearer Token, when you /registrar-se or /login, you will receive json in return with a field called "token", you must send this token for each request using the authenticated routes.

Installation Requirements

PHP 8.2
Laravel 11.0
Composer
Sanctum 4.0
Predis 2.0

* If you don't have Redis, I recommend downloading it from this link:
Redis download: https://github.com/tporadowski/redis/releases
In a Windows environment it is necessary to make some modifications, I will leave the link to a tutorial below.
Tutorial link: https://www.youtube.com/watch?v=DLKzd3bvgt8
After completing the installation and the standard ping test waiting for PONG, this step ends.


Ambient configuration

Clone the repository
git clone https://github.com/ThiagoZamprognioBahia/ReaderHub-API

install the dependencies:
'''bash composer install


Server Requirements:

Create a SQL database at your location and configure its .env with your data.
I will leave the .env.exemple in the DB_ part the same as my local .env.
After that, access the downloaded repository on your machine via CMD, or your IDE terminal (if you have one) and run the 
'''bash php artisan make:migration' command


Email sending configuration:

Firstly, in the .env in the MAIL_ part, the SMTP email server settings must be entered.
I will leave below the platform that I always test sending emails and the link on how to configure it.

For those thinking about using Gmail, here is a link:
https://support.google.com/accounts/answer/6010255?hl=pt-BR&sjid=8074746018313122746-SA 

Email testing: https://mailtrap.io/


After creating an account, just go to the Email testing->inboxes tab, create your email inbox.
Click on the Integrations select button and select the LARAVEL 9+ option
The MAIL_ tags will appear below, just copy and paste into your .env.
As for the SMTP username and password, just click on "Show Credentials", copy and paste them into the .env

The command responsible for sending the email is called send-email-birthday which is configured to be run daily(),
This command will send the congratulations email to the reader who is having a birthday.
It is necessary to start your schedule. You can do this with this command '''bash php artisan schedule:work

API initialization:

'''bash php artisan serve
