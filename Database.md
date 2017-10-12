# Database base info 
All the tables are setup via migrations so you need to run `php artisan migrate` and `php artisan db:seed`

## Tables

### 1. sport_events
sport_event just keep basic information for event, more fields can be added later


| 	Fields      | Type          | Purpose                               |
| ------------- |:-------------:| ------------------------------------: |
| id            | int(10)	      |                                       |
| event_start   | decimal(14,4)	| When Event will start                 |
| created_at    | timestamp     | creation time                         |
| updated_at    | timestamp     | last updating time                    |
| completed     | tinyint(1)    | is event completed or not             |


**There can be a question why i used decimal(14,4) for microtime.**
1. Because the server i have is using MySQL version below 5.6 
2. Although MySQL support micro date format now but to quickly do some calculations and to have too sorting result i find it to be best 

### 2. athletes
This is basic table to keep information of athletes. to run example there should be record in this table. 


| 	Fields      | Type          | Purpose                               |
| ------------- |:-------------:| ------------------------------------: |
| id            | int(10)	      |                                       |
| first_name    | varchar(100)	| first name of atlete                  |
| sur_name      | varchar(100)  | Family name of athlete                |
| created_at    | timestamp     | creation time                         |
| updated_at    | timestamp     | last updating time                    |


### 3. sport_event_athletes
The information that which Athlet is athached with which event, we are keep this table. the relation between 
events and athletes are *Many to Many* so you can many athelts doing many events 


| 	Fields        | Type          | Purpose                                                                               |
| -------------   |:-------------:| ------------------------------------:                                                 |
| id              | int(10)	      | Primary Key                                                                           |
| SE_id           | int(10)      	| Forign Key for sport_events table                                                     |
| A_id            | int(10)       | Forign key for athlet tables                                                          | 
| code_identifier | varchar(64)   | Must be Unique, During event Athlet will wear a device which will have that string    |
| start_number    | int(11)       | Position of Athlet to start the event                                                 | 
| created_at      | timestamp     | creation time                                                                         |
| updated_at      | timestamp     | last updating time                                                                    |



### 4. athlete_progress
This table will have all the information related to which athlete finishes the corridor or finish line when

| 	Fields        | Type                    | Purpose                                                                               |
| -------------   |:-------------:          | ------------------------------------:                                                 |
| id              | int(10)	                | Primary Key                                                                           |
| SEA_id          | int(10)      	          | Forign Key for sport_event_athletes table                                             |
| finish_type     | enum('corridor', 'line')| Identifier to cross or point which athlet crossed                                     | 
| clock_time      | decimal(14,4)           | Time when he crossed the point                                                        |
| step_history    | int(11)                 | Athlet become part of which step*(is explanied below)*                                | 
| created_at      | timestamp               | creation time                                                                         |
| updated_at      | timestamp               | last updating time                                                                    |

**Explantion why to use Steps**
1. steps are updated once any finish line crosses with space of 250 ms, next players crosses within 250 ms will become part of same
step else will moved to next
2. due to adding steps at crossing time, i can navigate the histoy, i can fetch according to histroy without making any calculation 


## Some other useful tables

### 5.  migrations 

To maintain laravel micrations, more detail [here](https://laravel.com/docs/5.4/migrations)

### 6. 7. jobs and failed_jobs
Laravel tables for Queues execution, more detail [here](https://laravel.com/docs/5.4/queues) 




