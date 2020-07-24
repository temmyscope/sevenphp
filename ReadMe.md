# SevenPHP

Built majorly with the Seven Library & Packages as a microservice restful framework with the intent to make it easily re-workable for any project (including SwoolePHP). The major focus for the development of the framework is to make most microservice development task as easy as possible while highly performant.


# WorkSpace
All code you'd both write and edit are all in the root's app folder.
Configuration settings and keys are kept in the config/app.php file and are all accessible using the app helper
```php
#For example to get your app's url, you can do the following:
app()->get('APP_URL')

```


# Creating Migrations
This section shows you how to create :- 
	*migrate, 
	*populate, 
	*drop table(s) using the name



Migrations can be added to the App\Providers\MigrationEngine migrate method in the form:

```php

###
return [
	'apps' => [ //table ids are automatically generated as primary keys
		'token' => $this->foreign_key($table='sessions_table', $column='session'), //the referenced table must already exists the name must be exact to avoid errors
		'name' => $this->string($max_length=125, $null=true), //$key can be one of [ 'primary', 'unique','fulltext', ''  ]
		'pos' => $this->integer($max_length=10),
		'account_balance' => $this->double() || $this->float($max_length=16), //in other to specify a maximum length, float should be used instead of a double
		'is_verified' => $this->oneOf($options=['true', 'false'], $default='false' ),
		'created_at' => $this->datetime()
	],
];
//constraints: a table can only have one primary key and it will be autogenerated
###
```
Note: Id is auto-generated and would be ignored

Populating a table with data can be done by adding array of arrays to the App\Providers\MigrationEngine populate method's return array
```php
###

return [
	'table name' => [ 'name' => , 'account_balance' => , 'is_verified' => ],
	'table name' => [...],
];

#always remove the arrays after migration
###

```

Dropping table(s) can be done by adding table names to the App\Providers\MigrationEngine drop method's return array
```php

return [ 'user', 'user_sessions' ]; //drop tables

```

# Migration Generation Steps

=> After creating migrations

=> Add Name of tables to be migrated to the MIGRATION array in the configuration app.php file in the config folder

```php
'MIGRATION' => [
	'users', 'user_session', 'contact_us'
]
#Always remember to remove already migrated models from the array
```

=>then run "php Engineer app::migrate" in the terminal
