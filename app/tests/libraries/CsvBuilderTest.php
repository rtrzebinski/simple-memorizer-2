<?php

class CsvBuilderTest extends TestCase {

	/**
	 * @test
	 */
	public function shouldBuildCsvFileFromCollectionOfArrays()
	{
		$data = [
			[
				'user_name' => uniqid(),
				'user_email' => $this->randomEmailAddress(),
			],
			[
				'user_name' => uniqid(),
				'user_email' => $this->randomEmailAddress(),
			]
		];

		$path = App::make('CsvBuilder')->
			setHeaderField('name', 'user_name')->
			setHeaderField('email', 'user_email')->
			setData($data)->
			build()->
			getPath();

		$handler = fopen($path, 'r');
		$line1 = fgetcsv($handler);
		$this->assertEquals('name', $line1[0]);
		$this->assertEquals('email', $line1[1]);
		$line2 = fgetcsv($handler);
		$this->assertEquals($data[0]['user_name'], $line2[0]);
		$this->assertEquals($data[0]['user_email'], $line2[1]);
		$line3 = fgetcsv($handler);
		$this->assertEquals($data[1]['user_name'], $line3[0]);
		$this->assertEquals($data[1]['user_email'], $line3[1]);
	}

	/**
	 * @test
	 */
	public function shouldBuildCsvFileFromCollectionOfObjects()
	{
		$data = [];
		$object1 = new stdClass();
		$object1->user_name = uniqid();
		$object1->user_email = $this->randomEmailAddress();
		$data[] = $object1;
		$object2 = new stdClass();
		$object2->user_name = uniqid();
		$object2->user_email = $this->randomEmailAddress();
		$data[] = $object2;

		$path = App::make('CsvBuilder')->
			setHeaderField('name', 'user_name')->
			setHeaderField('email', 'user_email')->
			setData($data)->
			build()->
			getPath();

		$handler = fopen($path, 'r');
		$line1 = fgetcsv($handler);
		$this->assertEquals('name', $line1[0]);
		$this->assertEquals('email', $line1[1]);
		$line2 = fgetcsv($handler);
		$this->assertEquals($data[0]->user_name, $line2[0]);
		$this->assertEquals($data[0]->user_email, $line2[1]);
		$line3 = fgetcsv($handler);
		$this->assertEquals($data[1]->user_name, $line3[0]);
		$this->assertEquals($data[1]->user_email, $line3[1]);
	}

	/**
	 * @test
	 * @expectedException Exception
	 */
	public function shouldFailIfCsvHeaderIsNotDefined()
	{
		$data = [
			[
				'user_name' => uniqid(),
				'user_email' => $this->randomEmailAddress(),
			]
		];

		App::make('CsvBuilder')->
			setData($data)->
			build();
	}

	/**
	 * @test
	 * @expectedException Exception
	 */
	public function shouldFailIfCsvDataIsNotDefined()
	{
		App::make('CsvBuilder')->
			setHeaderField('name', 'user_name')->
			build();
	}

	/**
	 * @test
	 * @expectedException Exception
	 */
	public function shouldFailIfCollectionFieldsAreNotAccessible()
	{
		App::make('CsvBuilder')->
			setHeaderField('name', 'user_name')->
			setData([])->
			build();
	}

}
