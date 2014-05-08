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
            'name' => $moduleName,
            '--path' => 'tests/tmp/modules',
        ]);
    }
   /**
     * @When /^I prepare modularizer with options \'([^\']*)\' and \'([^\']*)\'$/
     */
    public function iPrepareModularizer($path, $baseDirectory)
    {
        $preparatorCommand = App::make('Sendy\Modularizer\Commands\PreparatorCommand');
        $this->tester = new CommandTester($preparatorCommand);
        $path = empty($path) ? 'tests/tmp/modules' : $path;
        $baseDirectory = empty($baseDirectory) ? 'Modules' : $baseDirectory;

        $this->tester->execute([
            '--path'          => $path,
            '--basedirectory' => $baseDirectory,
        ]);

        $this->shouldMatchMyStub($path, $baseDirectory, $preparatorCommand->getCreator());
    }

    /**
     * @When /^I create a repository for model with arguments \'([^\']*)\' \'([^\']*)\' and options \'([^\']*)\' \'([^\']*)\'$/
     */
    public function iCreateARepositoryForModelWithArgumentsAndOptions($model, $module, $path, $baseNamespace)
    {
        $repositoryCreatorCommand = App::make('Sendy\Modularizer\Commands\RepositoryCreatorCommand');
        $this->tester = new CommandTester($repositoryCreatorCommand);

        $this->tester->execute([
            'model'           => $model,
            'module'          => $module,
            '--path'          => $path,
            '--basenamespace' => $baseNamespace,
        ]);

        //$this->shouldMatchMyStub($path, $baseNamespace, $repositoryCreatorCommand->getCreator());
    }

    /**
     * @Then /^I should see \'([^\']*)\'$/
     */
    public function iShouldSee($output)
    {
        assertContains($output, $this->tester->getDisplay());
    }

    /**
     * @Given /^"([^"]*)" should match my stub$/
     */
    public function shouldMatchMyStub($path, $baseDirectory, $creator)
    {
        $path = $path . '/' . $baseDirectory;

        foreach ($creator->getFiles() as $file)
        {
            $pathToFile = "{$path}/{$file}";

            // We'll use the name of the generated file as
            // the basic for our stub lookup.
            $stubName = pathinfo($pathToFile)['filename'];

            $expected = file_get_contents(__DIR__."/../../stubs/{$baseDirectory}/{$stubName}.txt");
            $actual = file_get_contents($pathToFile);

            // Let's compare the stub against what was actually generated.
            assertEquals($expected, $actual);
        }


    }
}
