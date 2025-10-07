
## Project and Task Listing Assignment

### How to Run

### 1. Install [Xamp 8.2](https://www.apachefriends.org/download.html) and run apache and mysql

### 2. set php in environment file

username: root
password:

### 3. git pull git@github.com:mahfuzdiu/project-management-system.git
### 4. composer install
### 5. php artisan key:generate
### 6. php artisan migrate
### 7. php artisan db:seed
### 8. php artisan serve
### 9. go to http://localhost:8000
### 10. To test email sending and job/queue
I have used mailtrap tesing environment. 
Update the env with smtp config.

RUN: ```php artisan queue:work```

mail will be working up sending request on ```tasks/{id} (manager/assigned user only)``` this api  
