Feature: Preparations

	Scenario Outline: Preparing modularizer(success)
		When I prepare modularizer with options '<path>' and '<baseDirectory>'
		Then I should see 'Preparation has been completed in <path>/<baseDirectory>'

	    Examples:
		| path                    | baseDirectory |
		| tests/tmp/modules       | MyModules     |
		| tests/tmp/random        | MyModules     |
		| tests/tmp/random        | Modules       |

	Scenario Outline: Preparing modularizer(fail)
		When I prepare modularizer with options '<path>' and '<baseDirectory>'
		Then I should see 'Preparation has been completed in <path>/<baseDirectory>'
		When I prepare modularizer with options '<path>' and '<baseDirectory>'
		Then I should see 'Could not make preparation in <path>/<baseDirectory>'
		Then I should see 'Core exists'

		Examples:
		| path                    | baseDirectory |
		| tests/tmp/modules       | MyModules     |
		| tests/tmp/random        | MyModules     |
		| tests/tmp/random        | Modules       |
