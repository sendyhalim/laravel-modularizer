Feature: Creators

	Scenario: Creating a module
		When I create a module with name 'Admin'
		Then I should see 'Admin has been created in tests/tmp/modules/Module'