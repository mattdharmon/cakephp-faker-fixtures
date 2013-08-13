<?php

App::uses('Populator', 'CakeFaker.Lib/Faker/ORM/CakePHP');

class FakerTestFixture extends CakeTestFixture
{
	public function insert($db)
	{
		$generator = \Faker\Factory::create();
		$generator->seed(65876587);
		if ( ! ClassRegistry::isKeySet('faker')) {
			ClassRegistry::addObject('faker', new Populator($generator));
		}
		$populator = ClassRegistry::getObject('faker');

		$populator->addEntity($this->model_name, $this->num_records, $this->alterFields($generator));
		// $insertedPKs = $populator->execute();
	}

	protected function alterFields($generator)
	{
		return array();
	}
}