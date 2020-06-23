#Probation task using Yii2 framework

Problem:
    - Create simple rest-api which will manage personal phonebook for each user
    - User should be able to create, change, remove contact information and add phones to contacts
    - Add registration and authorization system

##To run project:
 
 * Pull docker-setup repository
 * Move to docker-setup folder
 * Pull this project into folder named `code`, which must be located in docker-setup folder
 * Run `docker-compose up --build`  from docker-setup folder
 
_Postman collection for api is in file apidoc.json_

##Notes

* Yii2 framework is very limiting. Yii2 breaks SOLID whatever it can, so to fix that I would require more time.
That's why I couldn't do clear architecture for this project. I used only tools and approaches provided by framework.
Something better requires more time.
* I couldn't make validation more clear. Messages just return error message without specifying what is wrong with input.
I need to rewrite or extend frameworks response system to force better handling of HttpExceptions. 
