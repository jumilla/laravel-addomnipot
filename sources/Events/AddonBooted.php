<?php

namespace Jumilla\Addomnipot\Laravel\Events;

use Jumilla\Addomnipot\Laravel\Environment;

class AddonBooted
{
	public $world;

	public function __construct(Environment $world)
	{
		$this->world = $world;
	}
}
