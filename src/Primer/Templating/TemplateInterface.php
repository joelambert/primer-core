<?php

namespace Rareloop\Primer\Templating;

/**
 * Interface defining common templating functions
 */
interface TemplateInterface
{
    /**
     * Loads a template from the filesystem
     *
     * @param String $directory The full path to the templates parent folder
     * @param String $filename The name of the file (without extension)
     * @return Template Chainable interface
     */
    public function load($directory, $filename);

    /**
     * Render the current object
     *
     * @param  ViewData $data An associative array to pass to the template
     * @return String              HTML text
     */
    public function render($data);

    /**
     * Return the raw template without any processing having been done
     *
     * @return String The raw template file
     */
    public function raw();
}
