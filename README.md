# Reader Hub API

## API aimed at managing readers and books

### Main features

API has an authentication system based on Bearer Token, in addition to the cruds of readers, books, book genres and publishers, in addition to sending an email on the reader's birthday with the total number of books read in the year and the total number of pages read since the start your registration on the platform.

### Authentication 

Authentication based on the Bearer Token, when you /check-in or /login, you will receive json in return with a field called "token", you must send this token for each request using the authenticated routes.

### Installation Requirements

- PHP 8.2 
- Laravel 11.0 
- Composer 
- Sanctum 4.0 
- Predis 2.0

If you don't have Redis, I recommend downloading it from this link: [Redis download](https://github.com/tporadowski/redis/releases) In a Windows environment it is necessary to make some modifications, I will leave the link to a tutorial below. [Tutorial link](https://www.youtube.com/watch?v=DLKzd3bvgt8) After completing the installation and the standard ping test waiting for PONG, this step ends.
Ambient configuration

Clone the repository: git clone https://github.com/ThiagoZamprognioBahia/ReaderHub-API

install the dependencies: `composer install`


### Server Requirements:

Create a SQL database at your location and configure its .env with your data. I will leave the .env.exemple in the DB_ part the same as my local .env. After that, access the downloaded repository on your machine via CMD, or your IDE terminal (if you have one) and run the `php artisan make:migration` command

### Email sending configuration:

Firstly, in the .env in the MAIL_ part, the SMTP email server settings must be entered. I will leave below the platform that I always test sending emails and the link on how to configure it.

For those thinking about using Gmail, here is a link: [Gmail Configuration](https://support.google.com/accounts/answer/6010255?hl=pt-BR&sjid=8074746018313122746-SA)

Email testing: [Mailtrap](https://mailtrap.io/)

After creating an account, just go to the Email testing->inboxes tab, create your email inbox. Click on the Integrations select button and select the LARAVEL 9+ option The MAIL_ tags will appear below, just copy and paste into your .env. As for the SMTP username and password, just click on "Show Credentials", copy and paste them into the .env

The command responsible for sending the email is called send-email-birthday which is configured to be run daily(), This command will send the congratulations email to the reader who is having a birthday. It is necessary to start your schedule. You can do this with this command `php artisan schedule:work`

### API initialization:

`php artisan serve`

### Route details.

Below I will leave a list of parameters and filters that can be used for each possible route.

### Endpoint: /check-in 

- **Auth:** No 
- **HTTP Methods:** POST 
- **Description:** Function for creating reader registration (store).
- **Request Parameters:**
    - **name**: (required|string).
    - **last_name**: (required|string).
    - **email**: (required|string|unique).
    - **telephone**: (required|string).
    - **birthday**: (required|date_format:Y-m-d).
    - **neighborhood**: (required|string).
    - **city**: (required|string).
    - **zipcode**: (required|string).
    - **street**: (required|string).
    - **number**: (required|string).
    - **complement**: (nullable|string).
    - **password**: (required|string|min:8).

### Endpoint: /login 

- **Auth:** No 
- **HTTP Methods:** POST 
- **Description:** Reader login function (auth).
- **Request Parameters:**
    - **email**: (required|string|unique).
    - **password**: (required|string).
      
### Endpoint: /readers 

- **Auth:** Yes 
- **HTTP methods:** GET
- **Description:** Function that returns list of readers (index).
- **Filters:**
    - **name**: (nullable|string).
    - **last_name**: (nullable|string).
    - **email**: (nullable|string|unique).
    - **telephone**: (nullable|string).
    - **birthday**: (nullable|date_format:Y-m-d).
    - **page**: (nullable|int).
    - **per_page**: (nullable|int).
- **Request Parameters:**
    - **name**: (nullable|string).
    - **last_name**: (nullable|string).
    - **email**: (nullable|string|unique).
    - **telephone**: (nullable|string).
    - **birthday**: (nullable|date_format:Y-m-d).
    - **page**: (nullable|int).
    - **per_page**: (nullable|int).

### Endpoint: /readers/{ID} 

- **Auth:** Yes 
- **HTTP Methods:** GET 
- **Description:** Function that returns data from a single reader (show).

### Endpoint: /readers/{ID} 

- **Auth:** Yes 
- **HTTP Methods:** PATCH 
- **Description:** Function that updates a single reader (update).
- **Request Parameters:**
    - **name**: (nullable|string).
    - **last_name**: (nullable|string).
    - **email**: (nullable|string|unique).
    - **telephone**: (nullable|string).
    - **birthday**: (nullable|date_format:Y-m-d).
    - **neighborhood**: (nullable|string).
    - **city**: (nullable|string).
    - **zipcode**: (nullable|string).
    - **street**: (nullable|string).
    - **number**: (nullable|string).
    - **complement**: (nullable|string).
    - **password**: (required|string|min:8).

### Endpoint: /readers/{ID} 

- **Auth:** Yes 
- **HTTP Methods:** DELETE 
- **Description:** Function that deletes a reader (destroy).
- **Request Parameters:**
    - **password**: (required|string|min:8).

### Endpoint: /cache/{ID}

- **Auth:** Yes 
- **HTTP Methods:** GET 
- **Description:** Function that returns the total number of pages read and the total number of books read (getTotalBooksAndPagesFromCache).

### Endpoint: /books 

- **Auth:** Yes 
- **HTTP methods:** GET
- **Description:** Function that returns list of books (index).
- **Filters:**
    - **name**: (nullable|string).
    - **genre**: (nullable|string).
    - **author**: (nullable|string).
    - **publisher_name**: (nullable|string).
    - **isbn**: (nullable|string).
    - **page**: (nullable|int).
    - **per_page**: (nullable|int).
- **Request Parameters:**
    - **name**: (nullable|string).
    - **genre**: (nullable|string).
    - **author**: (nullable|string).
    - **publisher_name**: (nullable|string).
    - **isbn**: (nullable|string).
    - **page**: (nullable|int).
    - **per_page**: (nullable|int).

### Endpoint: /books 

- **Auth:** Yes 
- **HTTP methods:** POST
- **Description:** Function that creates a book (store), if there is no genre with that name in the genre_name field, it creates and links to that book, if a publisher name is passed it does the same.
- **Request Parameters:**
    - **name**: (required|string).
    - **genre_id**: (nullable|integer|required_without:genre_name).
    - **genre_name**: (nullable|string|required_without:genre_id).
    - **author**: (required|string).
    - **year**: (required|integer).
    - **pages**: (required|integer).
    - **language**: (required|string).
    - **edition**: (required|string).
    - **publisher_id**: (nullable|integer).
    - **publisher_name**: (nullable|string).
    - **publisher_code**: (nullable|string).
    - **publisher_telephone**: (nullable|string).
    - **isbn**: (nullable|string).

### Endpoint: /books/{ID} 

- **Auth:** Yes 
- **HTTP Methods:** GET 
- **Description:** Function that returns data from a single book (show).

### Endpoint: /books/{ID} 

- **Auth:** Yes 
- **HTTP Methods:** PATCH 
- **Description:** Function that updates a single book (update), if there is no genre with that name in the genre_name field, it creates and links it to that book, if a publisher name is passed it does the same.
- **Request Parameters:**
    - **name**: (nullable|string).
    - **genre_id**: (nullable|integer).
    - **genre_name**: (nullable|string).
    - **author**: (nullable|string).
    - **year**: (nullable|integer).
    - **pages**: (nullable|integer).
    - **language**: (nullable|string).
    - **edition**: (nullable|string).
    - **publisher_id**: (nullable|integer).
    - **publisher_name**: (nullable|string).
    - **publisher_code**: (nullable|string).
    - **publisher_telephone**: (nullable|string).
    - **isbn**: (nullable|string).

### Endpoint: /books/{ID} 

- **Auth:** Yes 
- **HTTP Methods:** DELETE 
- **Description:** Function that deletes a book (destroy) with SoftDeletes.

### Endpoint: /publishers 

- **Auth:** Yes 
- **HTTP methods:** GET
- **Description:** Function that returns list of publishers (index).
- **Filters:**
    - **page**: (nullable|int).
    - **per_page**: (nullable|int).

### Endpoint: /publishers 

- **Auth:** Yes 
- **HTTP methods:** POST
- **Description:** Function that creates a publisher (store).
- **Request Parameters:**
    - **name**: (required|string).
    - **code**: (nullable|string).
    - **telephone**: (nullable|string).

### Endpoint: /publishers/{ID} 

- **Auth:** Yes 
- **HTTP Methods:** GET 
- **Description:** Function that returns data from a single publisher (show).

### Endpoint: /publishers/{ID} 

- **Auth:** Yes 
- **HTTP Methods:** PATCH 
- **Description:** Function that updates a single publisher.
- **Request Parameters:**
    - **name**: (nullable|string).
    - **code**: (nullable|string).
    - **telephone**: (nullable|string).

### Endpoint: /publishers/{ID} 

- **Auth:** Yes 
- **HTTP Methods:** DELETE 
- **Description:** Function that deletes a publisher (destroy) if it is not linked to a book, otherwise it will be necessary to edit the book's publishers.

### Endpoint: /genres 

- **Auth:** Yes 
- **HTTP methods:** GET
- **Description:** Function that returns list of genres (index).

### Endpoint: /genres 

- **Auth:** Yes 
- **HTTP methods:** POST
- **Description:** Function that creates a genre (store).
- **Request Parameters:**
    - **name**: (required|string).

### Endpoint: /genres/{ID} 

- **Auth:** Yes 
- **HTTP Methods:** GET 
- **Description:** Function that returns data from a single genre (show).

### Endpoint: /genres/{ID} 

- **Auth:** Yes 
- **HTTP Methods:** PATCH 
- **Description:** Function that updates a single genre.
- **Request Parameters:**
    - **name**: (nullable|string).

### Endpoint: /genres/{ID} 

- **Auth:** Yes 
- **HTTP Methods:** DELETE 
- **Description:** Function that deletes a genre (destroy) if it is not linked to a book, otherwise it will be necessary to edit the book's genre.

### Endpoint: /books-readers

- **Auth:** Yes 
- **HTTP methods:** GET
- **Description:** Function that returns a list of relationships between books and readers (index).
- **Filters:**
    - **page**: (nullable|int).
    - **per_page**: (nullable|int).

### Endpoint: /books-readers 

- **Auth:** Yes 
- **HTTP methods:** POST
- **Description:** Function that creates relationships between books and readers (store).
- **Request Parameters:**
    - **reader_id**: (required|string).
    - **book_id**: (required|string).

### Endpoint: /books-readers/{ID} 

- **Auth:** Yes 
- **HTTP Methods:** DELETE 
- **Description:** Function that deletes relationships between books and readers (destroy).
    


