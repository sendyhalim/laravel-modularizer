Feature: RepositoryCreators

	Scenario Outline: Creating repositories for a module(success)
		When I create a module with name 'Admin'
		Then I should see 'Admin has been created in tests/tmp/modules/Modules'
		When I create a repository for model with arguments '<model>' '<module>' and options '<path>' '<baseNamespace>'
		Then I should see 'Repositories(Read and Write) for has been created for <module>.'

	    Examples:
		|	model 	| 	module 	| path                    | baseNamespace |
		|	user  	|	admin	| tests/tmp/modules       | CORE          |