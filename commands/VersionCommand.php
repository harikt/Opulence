<?php
/**
 * Copyright (C) 2015 David Young
 *
 * Defines the version command
 */
namespace Opulence\Console\Commands;
use Opulence\Console\Responses\IResponse;

class VersionCommand extends Command
{
    /** @var string The template for the output */
    private static $template = <<<EOF
<info>Opulence Console {{version}}</info>
EOF;
    /** @var string The version number of the application */
    private $applicationVersion = "Unknown";

    /**
     * @param string $applicationVersion The version number of the application
     */
    public function __construct($applicationVersion)
    {
        parent::__construct();

        $this->applicationVersion = $applicationVersion;
    }

    /**
     * @inheritdoc
     */
    protected function define()
    {
        $this->setName("version")
            ->setDescription("Displays the application version");
    }

    /**
     * @inheritdoc
     */
    protected function doExecute(IResponse $response)
    {
        // Compile the template
        $compiledTemplate = self::$template;
        $compiledTemplate = str_replace("{{version}}", $this->applicationVersion, $compiledTemplate);

        $response->writeln($compiledTemplate);
    }
}