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
	*drop([ 'id' => dataId ]) to delete data from table, 
	*drop(string name) to drop table using the name

_methods in every child model class u want to run migration on

Migrations can be added to the corresponding model's migrate method in the form:

```php
public function migrate(Schema $schema){
	###
	return [
		'id' => $schema->integer()->max_length(10)->is_null(true)->primary() || [ ->key('') || ->index('') ] || $schema->integer($max_length=10, $null = false, $key='primary'),
		'name' => $schema->string()->max_length(125)->is_null(false) || $schema->string($max_length=125, $null=true),
		'account_balance' => $schema->float()->is_null(false) || $schema->string($max_length=125, $null=true),
		'is_verified' => $schema->boolean()->default() || $schema->boolean($default=),
		'created_at' => $schema->time()->auto_generate(true) || $schema->time($auto_generate=true)
	];
	###
}
```
Note: Id is auto-generated and would be ignored

Populating a table with data can be done by adding array of arrays to the corresponding model's populate method 
```php
return [ 
	['name' => , 'account_balance' => , 'is_verified' => ], 
	[...], 
	[...] 
];
```

```md

*type can only be one of [ 'int', 'double', 'string', 'time']

*length: each type has maximum length [int => 10, double(no fixed length),]
	#Int Type
	*length <= 3 => Tinyint
	*3 < length < 6 => SmallInt 
	*6 < length <= 10 => Int
	*length > 10 => BigInt

	#String Type
	*if length provided when using string is less than 9, fixed length char will be used,
	*if length provided is greater than 63500, text is used
	*length between 9 and 63500 would be varchar

	#Time
	*length is ignored
	*DEFAULT of CURRENT_TIMESTAMP is used

*nullable can be true or false
	#if true, NULL is used as DEFAULT
	#if false, NOT NULL

*indexer is only available to SQL databases and if it is set, can only be one of [ 'primary', 'index', 'unique', 'fulltext' ]
	#all primary indexer have auto_increment set

*comment is just for you to know what d column holds and as such it's not necessary


```


//migration parser  
parser => validation:
	*type can only be one of [ 'int', 'double', 'text', 'string', 'enum', 'time']
	*length: each type has maximum length [int => 10, double(no fixed length), string => 63500 characters, text => in millions]
		*if length provided when using string is less than 9, fixed length char will be used
	*nullable means can be true or false
	*indexer is only available to SQL databases and can only be one of [primary, index, unique, fulltext, '' ] all primary indexer have auto_increment set
	*default is used for enum
	*comment is just for you to know what d column holds and as such it's not necessary


# Single Migration Steps

=> After creating migrations

=>then run "php Engineer app::migrate modelName" in the terminal

# Multi-Migration Generation Steps

=> After creating migrations

=> Add Name of Models to be migrated to the MIGRATION array in the configuration app.php file in the config folder

```php
'MIGRATION' => [
	App\\Users::class, App\\Posts::class, App\\Comments::class
]
#Always remember to remove already migrated models from the array
```

=>then run "php Engineer app::migrate *" in the terminal




=>transpiler

CREATE TABLE `apply_hire` (
  `id` int(11) NOT NULL,
  `job_id` int(9) NOT NULL,
  `writer_id` int(9) NOT NULL,
  `extras` varchar(950) NOT NULL,
  `accept` set('yes','no') NOT NULL,
  `hire` set('yes','no') NOT NULL DEFAULT 'no',
  `job_done` set('true','false') NOT NULL DEFAULT 'false',
  `approve` set('true','false') NOT NULL DEFAULT 'false'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;