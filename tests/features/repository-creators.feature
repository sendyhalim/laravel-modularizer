Feature: RepositoryCreators

	Scenario Outline: Creating repositories for a module(success)
		When I create a module with name '<module>'
		Then I should see '<module> has been created in tests/tmp/modules/Modules'
		When I create a repository for model with arguments '<model>' '<module>' and options '<path>' '<baseNamespace>' '<baseDirectory>'
		Then I should see '<model> Repositories(Read and Write) has been created for module <module>.'

	    Examples:
		|	model 	| 	module 		| path                              | baseNamespace | baseDirectory 	|
		|	User  	|	Admin		| tests/tmp/modules/MyModules       | Core          | MyModules		|
		|   Role    |   Blog        | tests/tmp/modules/Modules         | Haha		    | Modules 		|