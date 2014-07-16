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
        $moduleCreatorCommand = App::make('Sendy\Modularizer\Commands\ModuleCreatorCommand');
        $this->tester = new CommandTester($moduleCreatorCommand);

        $baseDirectory = 'Module';
        $path = 'tests/tmp/modules';
        $this->tester->execute([
            'name' => $moduleName,
            '--path' => $path,
            '--basedirectory' => $baseDirectory,
        ]);

        $files = $moduleCreatorCommand->getCreator()->getFiles();
        $data['MODULE'] = $moduleName;
        // replace placeholder
        foreach ($files as &$file)
        {
            $file = $this->makeTemplate($file, $data);
        }

        $this->shouldMatchMyStub($path, "{$baseDirectory}/{$moduleName}", $files);
    }
   /**
     * @When /^I prepare modularizer with options \'([^\']*)\' and \'([^\']*)\'$/
     */
    public function iPrepareModularizer($path, $baseDirectory)
    {
        $preparatorCommand = App::make('Sendy\Modularizer\Commands\PreparatorCommand');
        $this->tester = new CommandTester($preparatorCommand);
        $path = empty($path) ? 'tests/tmp/module' : $path;
        $baseDirectory = empty($baseDirectory) ? 'Module' : $baseDirectory;

        $this->tester->execute([
            '--path'          => $path,
            '--basedirectory' => $baseDirectory,
        ]);

        // get path of destination files
        $files = $preparatorCommand->getCreator()->getFiles();

        $this->shouldMatchMyStub($path, $baseDirectory, $files);
    }

    /**
     * @When /^I create a repository for model with arguments \'([^\']*)\' \'([^\']*)\' and options \'([^\']*)\' \'([^\']*)\' \'([^\']*)\'$/
     */
    public function iCreateARepositoryForModelWithArgumentsAndOptions($model, $module, $path, $baseNamespace, $baseDirectory)
    {
        $repositoryCreatorCommand = App::make('Sendy\Modularizer\Commands\RepositoryCreatorCommand');
        $this->tester = new CommandTester($repositoryCreatorCommand);

        $this->tester->execute([
            'model'           => $model,
            'module'          => $module,
            '--path'          => $path,
            '--basenamespace' => $baseNamespace,
            '--basedirectory' => $baseDirectory,
        ]);

        // get path of destination files
        $files = $repositoryCreatorCommand->getCreator()->getFiles();
        $data['MODEL'] = $model;
        // replace to model name
        foreach ($files as &$file)
        {
            $file = $this->makeTemplate($file, $data);
        }

        // use dirname for path, because stubs will look for double base directory if we dont use dirname for path
        $this->shouldMatchMyStub(dirname($path), "{$baseDirectory}/{$module}", $files);
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
    public function shouldMatchMyStub($path, $baseDirectory, $files)
    {
        $path = $path . '/' . $baseDirectory;

        foreach ($files as $file)
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

    private function makeTemplate($templateFile, $data)
    {
        foreach ($data as $key => $value)
        {
            $templateFile = preg_replace("/\{\{$key\}\}/i", $value, $templateFile);
        }

        return $templateFile;
    }
}
