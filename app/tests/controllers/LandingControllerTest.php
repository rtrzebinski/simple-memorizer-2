<?php

class LandingControllerTest extends TestCase {

	/**
	 * @test
	 */
	public function shouldDisplayLandingPage()
	{
		View::shouldReceive('make')->with('landing')->once();
		$this->route('GET', 'landing');
		
		$this->assertResponseOk();
	}

}
