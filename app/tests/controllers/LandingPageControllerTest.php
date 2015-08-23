<?php

class LandingPageControllerTest extends TestCase {

	/**
	 * @test
	 */
	public function shouldDisplayLandingPage()
	{
		View::shouldReceive('make')->with('landing_page')->once();
		$this->route('GET', 'landing_page');
		
		$this->assertResponseOk();
	}

}
