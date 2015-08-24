<?php
/*
 * Copyright (c) Shingo OKAWA <shingo.okawa.n.a@gmail.com>
 */
namespace Illuminate\YetAnother\Cache\Configuration;

/**
 * [Yet Another Implementation]
 * Provides functionality to defines options.
 *
 * @author Shingo OKAWA
 */
class Options implements OptionsInterface
{
    /**
     * Holds user specified options.
     *
     * @var array
     */
    protected $input;

    /**
     * Holds options which is currently handled.
     *
     * @var array
     */
    protected $context;

    /**
     * Holds option handlers.
     *
     * @var OptionInterface
     */
    protected $handlers;

    /**
     * Constructor.
     *
     * @param array $options the 
     */
    public function __construct(array $options=[])
    {
        $this->input = $options;
        $this->context = [];
        $this->handlers = $this->getHandlers();
    }

    /**
     * Returns predefined options.
     *
     * @return array the predefined option handlers.
     */
    protected function getHandlers()
    {
        return [
            'codec'  => new CodecOption(),
            'client' => new ClientOption()
        ];
    }

    /**
     * Returns the default value for the given option.
     *
     * @param  string     $option the name of the option.
     * @return mixed|null the resulting default value.
     */
    public function getDefault($option)
    {
        if (isset($this->handlers[$option])) {
            $handler = $this->handlers[$option];
            return $handler->getDefault($this);
        }
    }

    /**
     * Checks if thespecified option has been set by the user upon
     * initialization.
     *
     * @param  string $option the name of the option.
     * @return bool   true if option of the specified name is defined.
     */
    public function defined($option)
    {
        return (
            array_key_exists($option, $this->context)
            || array_key_exists($option, $this->input)
        );
    }

    /**
     * Returns the value of the specified option.
     *
     * @param  string $option the name of the option.
     * @return bool   true if the specified option has been set.
     */
    public function __isset($option)
    {
        return (
            array_key_exists($option, $this->context)
            || array_key_exists($option, $this->input)
        ) && $this->__get($option) !== null;
    }

    /**
     * Returns the value of the given option.
     *
     * @param  string     $option the name of the option.
     * @return mixed|null the assigned value of the specified option.
     */
    public function __get($option)
    {
        if (isset($this->context[$option])
        || array_key_exists($option, $this->context)) {
            return $this->context[$option];
        }

        if (isset($this->input[$option]) || array_key_exists($option, $this->input)) {
            $value = $this->input[$option];
            unset($this->input[$option]);

            if (method_exists($value, '__invoke')) {
                $value = $value($this, $option);
            }

            if (isset($this->handlers[$option])) {
                $handler = $this->handlers[$option];
                $value = $handler->filter($this, $value);
            }

            return $this->context[$option] = $value;
        }

        if (isset($this->handlers[$option])) {
            return $this->context[$option] = $this->getDefault($option);
        }

        return null;
    }

    /**
     * Sets the value of the specified option.
     *
     * @param string $option the name of the option.
     * @param mixed  $value  the assigning value of the specified option.
     */
    public function __set($option, $value)
    {
        if (method_exists($value, '__invoke')) {
            $value = $value($this, $option);
        }

        if (isset($this->handlers[$option])) {
            $handler = $this->handlers[$option];
            $value = $handler->filter($this, $value);
        }

        $this->context[$option] = $value;
    }
}