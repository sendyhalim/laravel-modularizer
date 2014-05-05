Feature: Preparations

	Scenario: Preparing modularizer(success)
		When I prepare modularizer with options "" and "MyModules"
		Then I should see "Preparation has been completed in tests/tmp/modules/MyModules"

	Scenario: Preparing modularizer(fail)
		When I prepare modularizer with options "" and "MyModules"
		Then I should see "Preparation has been completed in tests/tmp/modules/MyModules"
		When I prepare modularizer with options "" and "MyModules"
		Then I should see "Could not make preparation in tests/tmp/modules/MyModules"
		Then I should see "Core exists"