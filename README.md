#Probation task using Yii2 framework

##Notes

Code is a mess because of two reasons:
 * I don't have any experience with Yii2;
 * Yii2 is a messy and limiting framework. It is breaking SOLID whenever it can. To make clearer architecture 
 I must ditch most of the capabilities of Yii2 and rewrite or write completely new components and libraries, maybe install
 3rd party libraries and make them work in Yii2. Each approach would take a ton of time, which I don't have at the moment.
 So I finished the task using Yii2 structure as it is documented.

Validation messages are general, no way to tell which input is the problem, because I at least for now can't figure out how to
put array of error messages into HttpException and force Yii2 to output it as intended.
 
##Goals:
 * Create simple rest-api which will manage personal phonebook for each user
 * User should be able to create, change, remove contact information and add phones to contacts
 * Add registration and authorization system

##To run project:
 
 * Pull docker-setup repository
 * Move to docker-setup folder
 * Pull this project into folder named `code`, which must be located in docker-setup folder
 * Run `docker-compose up --build`  from docker-setup folder
 
_**Postman collection for api is in file apidoc.json**_
