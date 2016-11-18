<?php

namespace Fhm\ErrorBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class FhmErrorBundle extends Bundle
{
	public function getParent()
	{
		return 'TwigBundle';
	}
}
