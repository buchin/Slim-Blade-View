<?php

namespace Slim\Views;

use Illuminate\Events\Dispatcher;
use Psr\Http\Message\ResponseInterface;

class Blade
{
    /**
     * @var array
     */
    protected $viewPaths;

    /**
     * @var string
     */
    protected $cachePath;

    /**
     * @var Dispatcher|null
     */
    protected $events;

    /**
     * @var array
     */
    protected $attributes;

    protected $renderer;

    /**
     * Blade constructor.
     * @param array $viewPaths
     * @param $cachePath
     * @param Dispatcher|null $events
     */
    public function __construct($viewPaths = [], $cachePath = '', Dispatcher $events = null, $attributes = [])
    {
        if (is_string($viewPaths)) {
            $viewPaths = [$viewPaths];
        }
        $this->viewPaths = $viewPaths;
        $this->cachePath = $cachePath;
        $this->events = $events;
        $this->attributes = $attributes;

        $this->renderer = new \Philo\Blade\Blade($this->viewPaths, $this->cachePath, $this->events);
    }

    public function getRenderer()
    {
        return $this->renderer;
    }

    /**
     * Render a template
     *
     * $data cannot contain template as a key
     *
     * throws RuntimeException if $templatePath . $template does not exist
     *
     * @param ResponseInterface $response
     * @param string $template
     * @param array $data
     *
     * @return ResponseInterface
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function render(ResponseInterface $response, $template, array $data = [])
    {
        $output = $this->fetch($template, $data);
        $response->getBody()->write($output);

        return $response;
    }

    /**
     * Get the attributes for the renderer
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Set the attributes for the renderer
     *
     * @param array $attributes
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * Set the attribute for the renderer
     *
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    /**
     * Retrieve an attribute
     *
     * @param $key
     * @return mixed
     */
    public function get($key)
    {
        if (!isset($this->attributes[$key])) {
            return false;
        }

        return $this->attributes[$key];
    }

    /**
     * @return array
     */
    public function getViewPaths()
    {
        return $this->viewPaths;
    }

    /**
     * @param array $viewPaths
     */
    public function setViewPaths($viewPaths)
    {
        $this->viewPaths = $viewPaths;
    }

    /**
     * @return string
     */
    public function getCachePath()
    {
        return $this->cachePath;
    }

    /**
     * @param string $cachePath
     */
    public function setCachePath($cachePath)
    {
        $this->cachePath = $cachePath;
    }

    /**
     * Renders a template and returns the result as a string
     *
     * cannot contain template as a key
     *
     * throws RuntimeException if $templatePath . $template does not exist
     *
     * @param $template
     * @param array $data
     *
     * @return mixed
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function fetch($template, array $data = [])
    {
        if (isset($data['template'])) {
            throw new \InvalidArgumentException("Duplicate template key found");
        }

        $data = array_merge($this->attributes, $data);

        return $this->renderer->view()->make($template, $data)->render();
    }
}