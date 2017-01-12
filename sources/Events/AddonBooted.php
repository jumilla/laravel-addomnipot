<?php

namespace Jumilla\Addomnipot\Laravel\Events;

use Jumilla\Addomnipot\Laravel\Environment;

class AddonBooted
{
	public $environment;

	public function __construct(Environment $environment)
	{
		$this->environment = $environment;
	}
}
