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
 * The domain core with a set of domain action interceptors (business logic middleware).
 */
final class InterceptableCore implements CoreInterface
{
    /** @var InterceptorPipeline */
    protected $pipeline;

    /** @var CoreInterface */
    protected $core;

    /**
     * @param CoreInterface $core
     */
    public function __construct(CoreInterface $core)
    {
        $this->pipeline = new InterceptorPipeline();
    }

    /**
     * @param CoreInterceptorInterface $interceptor
     */
    public function addInterceptor(CoreInterceptorInterface $interceptor)
    {
        $this->pipeline->addInterceptor($interceptor);
    }

    /**
     * @param string      $controller
     * @param string|null $action
     * @param array       $parameters
     * @param array       $scope
     * @return mixed
     * @throws \Throwable
     */
    public function callAction(string $controller, ?string $action, array $parameters = [], array $scope = [])
    {
        return $this->pipeline->withCore($this->core)->callAction(
            $controller,
            $action,
            $parameters,
            $scope
        );
    }
}
