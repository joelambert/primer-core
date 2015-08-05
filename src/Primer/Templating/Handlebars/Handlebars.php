<?php namespace Rareloop\Primer\Templating\Handlebars;

use Rareloop\Primer\Primer;
use Rareloop\Primer\FileSystem;
use Rareloop\Primer\Templating\Handlebars\Helpers\Inc;
use Rareloop\Primer\Events\Event;


use Handlebars\String;

class PartialsHandlebarsLoader implements \Handlebars\Loader
{
    protected $extensions;

    public function __construct($extensions)
    {
        $this->extensions = $extensions;
    }

    public function load($name)
    {
        $template = false;

        // Try and resolve the template to a real path
        foreach ($this->extensions as $ext) {
            // If we've not already found the template then keep looking
            if(!$template) {
                $path = Primer::$PATTERN_PATH . '/' . $name . '/template.' . $ext;

                if(is_file($path)) {
                    $template = file_get_contents($path);
                }
            }
        }

        if(!$template) {
            throw new \InvalidArgumentException('Template ' . $name . ' not found.');
        }

        return new String($template);
    }
}

class Handlebars extends \Handlebars\Handlebars
{

    private static $_instance;

    /**
     * Handlebars engine constructor
     * $options array can contain :
     * helpers        => Helpers object
     * escape         => a callable function to escape values
     * escapeArgs     => array to pass as extra parameter to escape function
     * loader         => Loader object
     * partials_loader => Loader object
     * cache          => Cache object
     *
     * @param array $options array of options to set
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(array $options = array())
    {
        $loader = new \Rareloop\Primer\Templating\Handlebars\PartialsHandlebarsLoader($options['extensions']);

        parent::__construct(array_merge(array(
            'partials_loader' => $loader,
        ), $options));

        // Register a helper to include sub patterns
        $this->getHelpers()->add('inc', new Inc());
    }

    public static function instance($options)
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new Handlebars($options);

            Event::fire('handlebars.new', self::$_instance);
        }

        return self::$_instance;
    }
}
