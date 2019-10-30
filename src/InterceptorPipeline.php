<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */
declare(strict_types=1);

namespace Spiral\Core;

/**
 * Provides the ability to modify the call to the domain core on it's way to the action.
 */
final class InterceptorPipeline implements CoreInterface
{
    /** @var CoreInterface */
    private $core;

    /** @var CoreInterceptorInterface[] */
    private $interceptors = [];

    /** @var int */
    private $position = 0;

    /**
     * @param CoreInterceptorInterface $interceptor
     */
    public function addInterceptor(CoreInterceptorInterface $interceptor)
    {
        $this->interceptors[] = $interceptor;
    }

    /**
     * @param CoreInterface $core
     * @return $this
     */
    public function withCore(CoreInterface $core): self
    {
        $pipeline = clone $this;
        $pipeline->core = $core;

        return $pipeline;
    }

    /**
     * @inheritDoc
     */
    public function callAction(string $controller, ?string $action, array $parameters = [], array $scope = [])
    {
        $position = $this->position++;
        if (isset($this->interceptors[$position])) {
            return $this->interceptors[$position]->callAction($controller, $action, $parameters, $scope, $this);
        }

        return $this->core->callAction($controller, $action, $parameters, $scope);
    }
}