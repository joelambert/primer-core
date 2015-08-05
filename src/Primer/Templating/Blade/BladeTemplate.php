<?php namespace Rareloop\Primer\Templating\Blade;

use Rareloop\Primer\Templating\Template;
use Rareloop\Primer\Primer;

use Philo\Blade\Blade;




class BladeTemplate extends Template
{
    /**
     * Array of file extensions
     *
     * @var array
     */
    protected $extensions = array('blade.php');

    public function load($directory, $filename)
    {
        parent::load($directory, $filename);
    }

    public function render($data)
    {
        $views = Primer::$BASE_PATH;
        $cache = Primer::$BASE_PATH . '/cache';

        // We need to work out the template relative to the base path
        $templatePath = str_replace($views, '', $this->directory);

        $blade = new Blade($views, $cache);

        $blade->getCompiler()->extend(function($view, $compiler)
        {
            $pattern = $compiler->createMatcher('datetime');

            return preg_replace($pattern, '$1<?php echo $2->format(\'m/d/Y H:i\'); ?>', $view);
        });

        return $blade->view()->make($templatePath . '/' . $this->filename)->with($data->toArray())->render();
    }
}
