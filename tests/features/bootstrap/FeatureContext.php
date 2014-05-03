<?php

use Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;
use Symfony\Component\Console\Tester\CommandTester;

require_once __DIR__.'/../../../vendor/phpunit/phpunit/PHPUnit/Framework/Assert/Functions.php';

/**
 * Features context.
 */
class FeatureContext extends BehatContext
{
    /**
     * The command that we're testing
     *
     * @var CommandTester
     */
    protected $tester;

    /**
     * @beforeSuite
     */
    public static function bootstrapLaravel()
    {
        require __DIR__.'/../../../../../../vendor/autoload.php';
        require __DIR__.'/../../../../../../bootstrap/start.php';
    }

    /**
     * @AfterScenario
     */
    public function tearDown()
    {
        // cleanups
        \Illuminate\Support\Facades\File::deleteDirectory(base_path('workbench/sendy/modularizer/tests/tmp'), true);

        $this->tester = null;
    }

    /**
     * @When /^I create a module with name \'([^\']*)\'$/
     */
    public function iCreateAModuleWithName($moduleName)
    {
        $this->tester = new CommandTester(App::make('Sendy\Modularizer\Commands\ModuleCreatorCommand'));

        $this->tester->execute([
            'path' => 'tests/tmp/modules',
            'name' => $moduleName,
        ]);
    }

    /**
     * @Then /^I should see \'([^\']*)\'$/
     */
    public function iShouldSee($output)
    {
        assertContains($output, $this->tester->getDisplay());
    }
}
