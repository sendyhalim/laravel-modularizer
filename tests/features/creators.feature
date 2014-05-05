Feature: Creators

	Scenario: Creating a module (success)
		When I create a module with name 'Admin'
		Then I should see 'Admin has been created in tests/tmp/modules/Modules'

	Scenario: Creating a module but folder exists
		When I create a module with name 'Admin'
		Then I should see 'Admin has been created in tests/tmp/modules/Modules'
		When I create a module with name 'Admin'
		Then I should see 'Could not create Admin in tests/tmp/modules/Modules'
		Then I should see 'Admin exists'