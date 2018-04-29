# Installation
1. ``` composer install ```;
2. Setup .env file;
3. Create database for project;
4. ``` php artisan migrate```
# API:
1. POST /api/user - create new user. Request example:
```json
{
    "name": "Test name",
    "country": "Russia",
    "city": "Novosibirsk",
    "currency": "RUB"
}
```
Response example:
```json
{
    "success": true,
    "data": {
        "id": 1
    }
}
```
2. POST /user/{user}/add-money - add money to user wallet, {user} - user id.
Request example:
```json
{
	"money": 78000,
	"currency": "RUB"
}
```
Response example:
```json
{
    "success": true,
    "data": {
        "currency": "RUB",
        "user_money": 78000
    }
}
```
3. POST /user/{user}/transfer-money - transfer money from one user to other, {user} - user id.
Request example:
```json
{
	"to_user_id": 2,
	"money": 15000
}
```
Response example:
```json
{
    "success": true,
    "data": {
        "user_id": 2,
        "currency": "RUB",
        "user_money": 93000
    }
}
```
4. POST /exchange-rate - create new exchange rate. 
Request example:
```json
{
	"currency": "EUR",
	"rate": 0.87,
	"date": "2018-04-30"
}
```
Response example:
```json
{
    "success": true,
    "data": {
        "id": 2
    }
}
```