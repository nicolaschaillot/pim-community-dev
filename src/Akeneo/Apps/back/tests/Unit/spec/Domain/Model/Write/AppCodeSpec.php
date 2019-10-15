<?php

declare(strict_types=1);

namespace spec\Akeneo\Apps\Domain\Model\Write;

use Akeneo\Apps\Domain\Model\Write\AppCode;
use PhpSpec\ObjectBehavior;

/**
 * @author Romain Monceau <romain@akeneo.com>
 * @copyright 2019 Akeneo SAS (http://www.akeneo.com)
 * @license http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */
class AppCodeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedThrough('create', ['magento']);
        $this->shouldBeAnInstanceOf(AppCode::class);
    }

    function it_cannot_contains_an_empty_string()
    {
        $this->beConstructedThrough('create', ['']);
        $this->shouldThrow(new \InvalidArgumentException('Code is required'))->duringInstantiation();
    }

    function it_cannot_contains_a_string_longer_than_100_characters()
    {
        $this->beConstructedThrough('create', [str_repeat('a', 101)]);
        $this->shouldThrow(
            new \InvalidArgumentException('Code cannot be longer than 100 characters')
        )->duringInstantiation();
    }

    function it_contains_only_alphanumeric_characters()
    {
        $this->beConstructedThrough('create', ['foo-bar']);
        $this->shouldThrow(
            new \InvalidArgumentException('Code can only contain alphanumeric characters and underscore')
        )->duringInstantiation();
    }

    function it_returns_the_app_code_as_a_string()
    {
        $this->beConstructedThrough('create', ['foo_bar']);
        $this->__toString()->shouldReturn('foo_bar');
    }
}
